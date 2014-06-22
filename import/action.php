<?php

/*
time: date and time of action in RFC3339 format (based on ISO8601), in UTC time only.
action: string, required, the action being performed, typically described by a verb (e.g. login)
user: string, required, initiator of the action
user_ip: string, optional, IP address of initiator of the action
module: string, optional, affected module
artefact: string optional, affect artefact
group: string, optional, affected group
sysid: string, optional, system specific identifier
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$system_id = get_system_id();

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('time', 'action', 'user'));
$parser->setOptionalFields(array('sysid', 'module', 'group'));

$translator = new CallbackMappingIterator($parser, function($key, $row) {
	$name = $row['action'];
	if (!empty($row['artefact'])) $name = $row['artefact'] . ' ' . $row['action'];
	
	$artefact_id = IdManager::fromApplication('artefacts', $row['artefact'],
		array('field' => 'sysname'));

	$verb = IdManager::fromApplication('dimension_verb',
		array(@$row['action'], $artefact_id ?: 0),
		array('field' => array('sysname','artefact_id'),
			'create' => true, 'dated' => true));
	
	$module_id = null;
	$module = @$row['module'];
	if (!empty($module)) {
		$module_id = IdManager::fromApplication('modules',
			array($system_id, $module, $artefact_id ?: 0),
			array('field' => array('system_id', 'sysid', 'artefact_id')));
	}
	
	return array(
		'time' => translate_date($row['time']),
		'name' => $name,
		'user_id' => IdManager::fromApplication('users', $row['user']),
		'module_id' => $module_id,
		'group_id' => IdManager::fromApplication('groups', @$row['group']),
		'ip_id'=> IdManager::fromApplication('ips', @$row['user_ip'],
			array('field' => 'ip', 'create' => true, 'dated' => true)),
		'sysid' => @$row['sysid'],
		'dimension_verb_id' => $verb,
	);
});

$importer = new BulkImport($translator, 'actions');
$importer->setFields(array('time', 'name', 'user_id', 'module_id', 'group_id', 'ip_id', 'sysid', 'dimension_verb_id'));
$importer->setUpdate(false); # disallow updates, and the extra sql depends on it
$importer->setSystemSpecific(true);
$importer->setDated(true);
/*
$conditionIpType = 2;
$conditionIpName = 'IP Address';
$importer->setExtraSqlPostRow("
	set @action_id = last_insert_id();
	insert ignore into conditions set type = $conditionIpType, name = '$conditionIpName', value = :user_ip, created = now(), modified = now();
	set @condition_id = (select id from conditions where type = $conditionIpType and name = '$conditionIpName' and value = :user_ip);
	insert ignore into action_conditions set action_id = @action_id, condition_id = @condition_id, created = now(), modified = now();
");
*/
$importer->run();

sql_execute("
	update actions a
	inner join dimension_date d on d.date = date(a.time)
	inner join dimension_time t on t.hour = hour(a.time)
	set a.dimension_date_id = d.id,
		a.dimension_time_id = t.id
	where a.dimension_date_id is null
");

sql_execute("
	update dimension_verb
	set name = CONCAT(UCASE(SUBSTRING(sysname, 1, 1)), SUBSTRING(sysname, 2))
	where name is null
");

?>
