<?php

/*
time: date and time of action in RFC3339 format (based on ISO8601), in UTC time only.
action: string, required, the action being performed, typically described by a verb (e.g. login)
user: string, required, initiator of the action
module: string, optional, affected module
group: string, optional, affected group
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('time', 'action', 'user'));
$parser->setOptionalFields(array('module', 'group'));

$translator = new CallbackMappingIterator($parser, function($key, $row) {
	return array(
		'time' => translate_date($row['time']),
		'name' => $row['action'],
		'user_id' => IdManager::fromApplication('users', $row['user']),
		'module_id' => IdManager::fromApplication('modules', $row['module']),
		'group_id' => IdManager::fromApplication('groups', $row['group']),
	);
});

$importer = new BulkImport($translator, 'actions');
$importer->setFields(array('time', 'name', 'user_id', 'module_id', 'group_id'));
$importer->setUpdate(false); # disallow updates, for performance
$importer->setSystemSpecific(true);
$importer->run();

?>
