<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$mysqlimport = '/usr/local/bin/mysqlimport';

$input = fopen('php://input', 'r');

// read the csv header line
$columns = stream_get_line($input, 1024, "\n");

// due to a bug in PHP, we cannot use "load data local" directly here so shell out to mysqlimport instead
// http://stackoverflow.com/questions/13016797/load-data-local-infile-fails-from-php-to-mysql-on-amazon-rds

// save the rest of the csv to a temporary file
$tmpfile = tempnam("/tmp", "$table.php-dataload");
$tmp = fopen($tmpfile, 'w');
stream_copy_to_stream($input, $tmp);
fclose($tmp);
fclose($input);

// save the mysql connection details to a file
$mycnf_data = <<<MYCNF
[client]
host=$dbhost
user=$dbuser
pass=$dbpass

[mysqlimport]
local=true
columns=$columns
fields-terminated-by=,
fields-optionally-enclosed-by="
ignore=true
use-threads=1
lock-tables=true
MYCNF;
$mycnf_file = tempnam("/tmp", "php-dateload.cnf");
file_put_contents($mycnf_file, $mycnf_data);

passthru("$mysqlimport --defaults-extra-file=$mycnf_file $dbname $tmpfile 2>&1");

unlink($mycnf_file);
unlink($tmpfile);

?>
