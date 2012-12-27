<?php

/*
if not explicitly stated, the field is optional
a blank value is treated as unspecified
all ids are assigned by the source system

time: date and time of action in RFC3339 format (based on ISO8601), in UTC time only.
action: string, required, the action being performed, typically described by a verb (e.g. login)
user: string, required, initiator of the action
module: string, optional, affected module
group: string, optional, affected group
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$system_id = get_system_id();
$source = 'php://input';

$parser = new CsvIterator($source);
$parser->setRequiredFields(array('time', 'action', 'user'));
$parser->setOptionalFields(array('module', 'group'));

$translator = new CallbackMappingIterator($parser, function($key, $row) {
	return array(
		'time' => translate_date($row['time']),
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
