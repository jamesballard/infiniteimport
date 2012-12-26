<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';

header('Content-Type: text/plain');

$db = database();
$stmt = $db->prepare('select max(time) from moodle_log where system_id = ?');
$stmt->bindParam(1, get_system_id());
if ($stmt->execute()) {
	print $stmt->fetchColumn();
}

?>
