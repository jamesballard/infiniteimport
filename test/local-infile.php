<?php

$sql = "load data local infile 'csvfile.csv' into table csvtest";

$db = new PDO('mysql:host=localhost;dbname=test', 'tester', '', array(
	PDO::MYSQL_ATTR_LOCAL_INFILE => 1,
	PDO::ATTR_PERSISTENT => true
));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec($sql);

?>
