<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

header('Content-Type: text/plain');

$db = database();
$stmt = $db->prepare('select max(time) from actions where system_id = ?');
$stmt->bindParam(1, get_system_id());
if ($stmt->execute()) {
	$datetime = $stmt->fetchColumn();
	print translate_date_from_db($datetime);	
}

?>
