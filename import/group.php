<?php

/*
sysid: string, required, private id within source system
name: string, optional, what the group is known as
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('sysid'));
$parser->setOptionalFields(array('name'));

$importer = new BulkImport($parser, 'groups');
$importer->setSystemSpecific(true);
$importer->setDated(true);
$importer->run();

?>
