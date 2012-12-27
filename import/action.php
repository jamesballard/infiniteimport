<?php

/*
if not explicitly stated, the field is optional
a blank value is treated as unspecified
all ids are assigned by the source system

when: date and time of action in RFC3339 format (based on ISO8601), in UTC time only.
action: string, required, the action being performed, typically described by a verb (e.g. login)
user: string, required, initiator of the action
module: string, optional, affected module
group: string, optional, affected group
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$system_id = get_system_id();
$source = 'php://input';

function translate_date($datetime_in) {
	$utc = new DateTimeZone('UTC');
	$datetime = DateTime::createFromFormat(DateTime::RFC3339, $datetime_in, $utc);
	if (!$datetime) {
		$errors = DateTime::getLastErrors();
		header('HTTP/1. 400 Bad Request');
		die("Failed to parse date $datetime_in as " . DateTime::RFC3339 . " due to " . $errors['errors'][0]);
	}
	$datetime->setTimezone($utc);
	return $datetime->format('Y-m-d H:i:s');
}

function query_value($query, $params = array()) {
	$stmt = $db->prepare($query);
	$stmt->execute($params);
	$result = $stmt->fetchColumn();
	$stmt->closeCursor();
	return $result;
}

function translate_id($type, $sysid) {
	$key = "ir_${type}_${sysid}";
	$id = apc_fetch($key);
	if (is_null($id)) {
		$id = query_value("select id from $type where sysid = ?", array($sysid));
		if (!is_null($id)) apc_add($key, $id);
	}
	return $id;
}

$parser = new CsvIterator($source);
$parser->setRequiredFields(array('when', 'action', 'user'));
$parser->setOptionalFields(array('module', 'group'));

$translator = new CallbackMappingIterator($parser, function($key, $row) {
	return array(
		'time' => translate_date($row['when']),
		'name' => $row['action'],
		'user_id' => translate_id('users', $row['user']),
		'module_id' => translate_id('modules', $row['module']),
		'group_id' => translate_id('groups', $row['group']),
	);
});

$importer = new BulkImport($translator, 'actions');
$importer->setFields(array('time', 'name', 'user_id', 'module_id', 'group_id'));
$importer->setUpdate(true);
$importer->setExtraSql("system_id = $system_id");
$importer->run();

?>
