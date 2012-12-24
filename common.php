<?php

require_once 'database.php';

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

function bulk_import_csv($source, $table, $allowed_fields, $extra_sql = '') {
	bulk_import_csv_pdo($source, $table, $allowed_fields, $extra_sql);
}

function bulk_import_csv_pdo($source, $table, $allowed_fields, $extra_sql = '') {
	if (is_string($allowed_fields)) {
		$allowed_fields = explode(',', $allowed_fields);
	}

	$input = fopen($source, 'r');

	# read the csv header line
	$columns = stream_get_line($input, 1024, "\n");
	$columns_arr = explode(',', $columns);

	# validate the list of fields
	$disallowed_fields = array_diff($columns_arr, $allowed_fields);
	if (count($disallowed_fields) != 0) {
		header('HTTP/1.0 403 Forbidden');
		die("Disallowed fields: " . implode(',', $disallowed_fields));
	}

	# save the rest of the csv to a temporary file
	$tmpfile = tempnam("/tmp", "infiniterooms.$table.dataload.csv");
	$tmp = fopen($tmpfile, 'w');
	stream_copy_to_stream($input, $tmp);
	fclose($tmp);
	fclose($input);

	# setup for rapid import
	$db = database();
	$db->exec("set unique_checks = 0");
	$db->exec("set foreign_key_checks = 0");
	#$db->exec("set sql_log_bin = 0"); # only if super!
	$db->exec("lock tables $table write");

	# perform the import
	# TODO: this works, but causes us to lose the primary key value, and any other data. I believe this limitation means
	# we'll have to move away from rapid loading, into parsing it locally and doing batch insert/updates
	$import_sql = <<<IMPORT_SQL
load data local infile '$tmpfile'
replace
into table $table
fields terminated by ','
optionally enclosed by '"'
lines terminated by '\n'
($columns)
$extra_sql
IMPORT_SQL;
	$db->exec($import_sql);
	$db->exec("unlock tables");

	# clean up temporary files
	unlink($tmpfile);
}

# due to a bug in PHP, we cannot use "load data local" directly so shell out to mysqlimport instead
# http://stackoverflow.com/questions/13016797/load-data-local-infile-fails-from-php-to-mysql-on-amazon-rds
function bulk_import_csv_mysqlimport($source, $table, $allowed_fields) {
	$mysqlimport = '/usr/local/bin/mysqlimport';

	$dbcfg = database_config();
	$dbname = $dbcfg['database'];

	if (is_string($allowed_fields)) {
		$allowed_fields = explode(',', $allowed_fields);
	}

	$input = fopen($source, 'r');

	# read the csv header line
	$columns = stream_get_line($input, 1024, "\n");
	$columns_arr = explode(',', $columns);

	# validate the list of fields
	$disallowed_fields = array_diff($columns_arr, $allowed_fields);
	if (count($disallowed_fields) != 0) {
		header('HTTP/1.0 403 Forbidden');
		die("Disallowed fields: " . implode(',', $disallowed_fields));
	}

	# save the rest of the csv to a temporary file
	$tmpfile = tempnam("/tmp", "$table.dataload.csv");
	$tmp = fopen($tmpfile, 'w');
	stream_copy_to_stream($input, $tmp);
	fclose($tmp);
	fclose($input);

	# save the mysql connection details to a file
	$mycnf_data = database_config_file();
	$mycnf_data .= <<<MYCNF
	[mysqlimport]
	local=true
	columns=$columns
	fields-terminated-by=,
	fields-optionally-enclosed-by="
	ignore=true
	use-threads=1
	lock-tables=true
MYCNF;
	$mycnf_file = tempnam("/tmp", "$table.dataload.cnf");
	file_put_contents($mycnf_file, $mycnf_data);

	passthru("$mysqlimport --defaults-extra-file=$mycnf_file $dbname $tmpfile 2>&1");

	unlink($mycnf_file);
	unlink($tmpfile);

}

function database_config_file() {
        $dbcfg = database_config();
	$cnf = "[client]\n";
	$cnf .= 'host=' . $dbcfg['host'] . "\n";
	$cnf .= 'user=' . $dbcfg['login'] . "\n";
	$cnf .= 'pass=' . $dbcfg['password'] . "\n";
	return $cnf;
}

?>
