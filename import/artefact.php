<?php

/*
sysid: string, required, private id within source system
name: string, optional, what the group is known as
idnumber: string, optional, institutional id for the artefact
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('name'));

$importer = new BulkImport($parser, 'artefacts');
$importer->setDated(true);
$importer->run();

?>
