<?php

/*
time: date and time of action in RFC3339 format (based on ISO8601), in UTC time only.
action: string, required, the action being performed, typically described by a verb (e.g. login)
user: string, required, initiator of the action
userIp: string, optional, IP address of initiator of the action
module: string, optional, affected module
group: string, optional, affected group
sysinfo: string, optional, system specific additional information
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('time', 'action', 'user'));
$parser->setOptionalFields(array('module', 'group', 'sysinfo'));

$translator = new CallbackMappingIterator($parser, function($key, $row) {
	return array(
		'time' => translate_date($row['time']),
		'name' => $row['action'],
		'user_id' => IdManager::fromApplication('users', $row['user']),
		'module_id' => IdManager::fromApplication('modules', @$row['module']),
		'group_id' => IdManager::fromApplication('groups', @$row['group']),
		'sysinfo' => @$row['sysinfo'],
		'userIp'=> @$row['userIp'],
	);
});

$importer = new BulkImport($translator, 'actions');
$importer->setFields(array('time', 'name', 'user_id', 'module_id', 'group_id', 'sysinfo'));
$importer->setUpdate(false); # disallow updates, and the extra sql depends on it
$importer->setSystemSpecific(true);
$conditionIpType = 2;
$conditionIpName = 'IP Address';
$importer->setExtraSqlPostRow("
	set @action_id = last_insert_id();
	insert ignore into conditions set type = $conditionIpType, name = '$conditionIpName', value = :userIp, created = now(), modified = now();
	set @condition_id = (select id from conditions where type = $conditionIpType and name = '$conditionIpName' and value = :userIp);
	insert ignore into action_conditions set action_id = @action_id, condition_id = @condition_id, created = now(), modified = now();
");
$importer->run();

?>
