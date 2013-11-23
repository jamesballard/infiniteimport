<?php

/*
sysid: string, required, private id within source system
name: string, optional, what the group is known as
idnumber: string, optional, institutional id for the artefact
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('name'));

// save the list of artefact names so we can update the references after
$artefacts = array();
$interceptor = new CallbackMappingIterator($parser, function($key, $row) {
	$artefacts[] = $row['name'];
	return $row;
});

// import the artefacts
$importer = new BulkImport($interceptor, 'artefacts');
$importer->setDated(true);
$importer->run();

// update the artefact references
$customer_id = get_customer_id();
$now = time();

try {
	$db = database();
	$stmt = $db->prepare('
		insert into customer_artefacts
		set customer_id = :customer_id,
			artefact_id = (select id from artefacts where name = :artefact_name),
			created = :now,
			modified = :now
		on duplicate key update
			modified = :now
	');
} catch (PDOException $e) {
	throw new DatabaseException($e->getMessage(), $sql);
}

foreach($artefacts as $artefact_name) {
	try {
		$stmt->execute(array(
			':customer_id' => $customer_id,
			':artefact_name' => $artefact_name,
			':now' => $now
		));
	} catch (PDOException $e) {
		throw new DatabaseException($e->getMessage(), $sql, $row);
	}
	$stmt->closeCursor();
}

?>
