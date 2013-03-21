<?php

require_once 'database.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'DatabaseException.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'CsvIterator.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'BulkImport.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'CallbackMappingIterator.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'IdManager.php';

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

function translate_date($datetime_in) {
	return convert_date(DateTime::RFC3339, 'Y-m-d H:i:s', $datetime_in);
}

function translate_date_from_db($datetime_in) {
	return convert_date('Y-m-d H:i:s', DateTime::RFC3339, $datetime_in);
}

function convert_date($format_in, $format_out, $datetime_in) {
	$utc = new DateTimeZone('UTC');
	$datetime = DateTime::createFromFormat($format_in, $datetime_in, $utc);
	if (!$datetime) {
		$errors = DateTime::getLastErrors();
		header('HTTP/1. 400 Bad Request');
		die("Failed to parse date $datetime_in as " . $format_in . " due to " . $errors['errors'][0]);
	}
	$datetime->setTimezone($utc);
	return $datetime->format($format_out);
}

function query_value($query, $params = array()) {
	$db = database();
	$stmt = $db->prepare($query);
	$stmt->execute($params);
	$result = $stmt->fetchColumn();
	$stmt->closeCursor();
	return $result;
}

?>
