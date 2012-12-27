<?php

require_once 'database.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'CsvIterator.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'BulkImport.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'CallbackMappingIterator.php';

function database_config() {
	$dbcfg_holder = new DATABASE_CONFIG();
        return $dbcfg_holder->default;
}

function database() {
	global $db;
	if (is_null($db)) $db = new_database();
	return $db;
}

function new_database() {
	$cfg = database_config();
	$db_url = "mysql:host=" . $cfg['host'] . ";dbname=" . $cfg['database'];
	$db = new PDO($db_url, $cfg['login'], $cfg['password'], array(
		//PDO::ATTR_PERSISTENT => true, // have to worry about connection state
		PDO::MYSQL_ATTR_LOCAL_INFILE => 1 // Won't work on some versions of PHP, may need to custom compile
	));
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}

// use https client-side certificate to authenticate a client
function get_system_id() {
	$client_cert = $_SERVER['SSL_CLIENT_CERT'];

	if (empty($client_cert)) {
		header('HTTP/1.0 403 Forbidden');
		phpinfo();
		die('SSL client certificate required');
	}

	# look for a server id
	$db = database();
	$stmt = $db->prepare('select id from systems where certificate = :cert');
	$stmt->execute(array(':cert' => $client_cert));
	$server_id = $stmt->fetchColumn();

	if (empty($server_id)) {
		// create a new entry if one does not exist
		$cert_obj = openssl_x509_read($client_cert);
		$cert_data = openssl_x509_parse($cert_obj);
		$cert_cn = $cert_data['subject']['CN'];
		$cert_email = @$cert_data['subject']['emailAddress'];

		$stmt = $db->prepare('insert into systems (certificate, site_name, contact_email) values (:cert, :site, :email)');
		$stmt->execute(array(':cert' => $client_cert, ':site' => $cert_cn, ':email' => $cert_email));
		$server_id = $db->lastInsertId();
	}	

	return $server_id;
}

// import new or updated rows
function bulk_update_csv($source, $table, $allowed_fields, $extra_sql = '') {
	$parser = new CsvIterator($source);
	$parser->setOptionalFields($allowed_fields);

	$importer = new BulkImport($parser, $table);
	$importer->setUpdate(true);
	$importer->setExtraSql($extra_sql);
	$importer->run();
}

// import rows that are known to not exist
function bulk_import_csv($source, $table, $allowed_fields, $extra_sql = '') {
	$parser = new CsvIterator($source);
	$parser->setOptionalFields($allowed_fields);

	$importer = new BulkImport($parser);
	$importer->setUpdate(false);
	$importer->setExtraSql($extra_sql);
	$importer->run();
}

?>
