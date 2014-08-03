<?php

/*
sysid: string, required, private id within source system
name: string, required, category name
idnumber: string, optional, instituion id of the category
parent: string, optional
depth: string, optional
path: string, optional
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('sysid', 'name'));
$parser->setOptionalFields(array('idnumber', 'parent', 'depth', 'path'));

$translator = new CallbackMappingIterator($parser, function($key, $row) {
	return array(
		'sysid' => @$row['sysid'],
		'name' => @$row['name'],
		'idnumber' => @$row['idnumber'],
		'parent_id' => IdManager::fromApplication('group_category', @$row['parent']),
		'depth' => @$row['depth'],
		'path' => @$row['path']
	);
});

$importer = new BulkImport($translator, 'group_categories');
$importer->setSystemSpecific(true);
$importer->setDated(true);
$importer->run();

?>
