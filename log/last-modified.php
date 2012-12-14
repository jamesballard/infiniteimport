<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

header('Content-Type: text/plain');

$db = database();
$system_id = get_system_id();
if ($result = $db->query('select max(time) from moodle_log')) {
	print $result->fetchColumn();
}

?>
