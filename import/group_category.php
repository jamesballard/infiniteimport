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

$importer = new BulkImport($parser, 'group_categories');
$importer->setSystemSpecific(true);
$importer->setDated(true);
$importer->run();

?>
