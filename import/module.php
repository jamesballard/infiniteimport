<?php

/*
sysid: string, required, private id within source system
name: string, optional, what the module is known as
artefact: string, optional, which artefact this module is part of
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('sysid'));
$parser->setOptionalFields(array('name', 'artefact'));

$translator = new CallbackMappingIterator($parser, function($key, $row) {
	return array(
		'sysid' => $row['sysid'],
		'name' => $row['name'],
		'artefact_id' => IdManager::fromApplication('artefacts', $row['artefact'], array('field' => 'idnumber', 'create' => true)),
	);
});

$importer = new BulkImport($translator, 'modules');
$importer->setFields(array('sysid', 'name', 'artefact_id'));
$importer->setSystemSpecific(true);
$importer->setDated(true);
$importer->run();

?>
