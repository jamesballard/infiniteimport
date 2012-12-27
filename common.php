<?php

require_once 'database.php';
require_once 'lib' . DIRECTORY_SEPARATOR . 'CsvIterator.php';

function database_config() {
	$dbcfg_holder = new DATABASE_CONFIG();
        return $dbcfg_holder->default;
}

function database() {
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

class BulkImport {

	private $parser;
	private $table;
	private $extra_sql;
	private $update = false;

	public function __construct($parser, $table) {
		$this->parser = $parser;
		$this->table = $table;
	}

	public function setExtraSql($extra_sql) {
		$this->extra_sql = $extra_sql;
	}

	public function setUpdate($update) {
		$this->update = $update;
	}

	protected function buildSql() {
		# build parameter list
		$param_sql = '';
		if (!empty($this->extra_sql)) $param_sql .= $this->extra_sql . ',';
		$fields = $this->parser->getFields();
		foreach($fields as $col) {
			$param_sql .= "$col = :$col,";
		}
		$param_sql = rtrim($param_sql, ',');

		# prepare for the import
		$sql = "insert into $this->table ";
		if (!$this->update) $sql .= 'ignore ';
		$sql .= 'set ';
		$sql .= $param_sql . ' ';
		if ($this->update) {
			$sql .= 'on duplicate key update ';
			$sql .= $param_sql . ' ';
		}
		return $sql;
	}

	public function run() {
		$sql = $this->buildSql();
		$db = database();
		$stmt = $db->prepare($sql);

		foreach($this->parser as $row) {
			$stmt->execute($row);
			$stmt->closeCursor();
		}
	}

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
