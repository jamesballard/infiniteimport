<?php

/*
sysid: string, required, private id within source system
username: string, optional, public id within source system
idnumber: string, optional, institutional id for the person
name: string, optional, what the person is known as
gender: 'M' or 'F', optional
dob: yyyy-mm-dd, optional
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('sysid'));
$parser->setOptionalFields(array('username', 'idnumber', 'name', 'gender', 'dob'));

$importer = new BulkImport($parser, 'users');
$importer->setSystemSpecific(true);
$importer->setDated(true);
$importer->run();

?>
