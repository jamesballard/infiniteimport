<?php

/*
if not explicitly stated, the field is optional
a blank value is treated as unspecified
all ids are assigned by the source system

sysid: string, required, private id within source system
name: string, optional, what the module is known as

*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('sysid'));
$parser->setOptionalFields(array('name'));

$importer = new BulkImport($parser, 'modules');
$importer->setSystemSpecific(true);
$importer->setDated(true);
$importer->run();

?>
