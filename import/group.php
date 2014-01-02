<?php

/*
sysid: string, required, private id within source system
name: string, optional, what the group is known as
idnumber: string, optional, institutional id for the group
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('sysid'));
$parser->setOptionalFields(array('name', 'idnumber'));

$importer = new BulkImport($parser, 'groups');
$importer->setSystemSpecific(true);
$importer->run();

?>
