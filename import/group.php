<?php

/*
sysid: string, required, private id within source system
type: string, required, type of the record, 1 for LMS course
name: string, optional, what the group is known as
idnumber: string, optional, institutional id for the group
category: string, optional, sysid of the group category
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('sysid', 'type'));
$parser->setOptionalFields(array('name', 'idnumber', 'category'));

$translator = new CallbackMappingIterator($parser, function($key, $row) {
	return array(
		'sysid' => @$row['sysid'],
		'type' => @$row['type'],
		'name' => @$row['name'],
		'idnumber' => @$row['idnumber'],
		'group_category_id' => IdManager::fromApplication('group_categories', @$row['category']) ?: 0
	);
});

$importer = new BulkImport($translator, 'groups');
$importer->setFields(array('sysid', 'type', 'name', 'idnumber', 'group_category_id'));
$importer->setSystemSpecific(true);
$importer->setDated(true);
$importer->run();

?>
