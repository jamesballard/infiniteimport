<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';

header('Content-Type: text/plain');

$key = $_REQUEST['accesskey'];

$db = database();

$stmt = $db->prepare('select customer_id from customer_keys where accesskey = :key');
$stmt->execute(array(':key' => $key));
$customer_id = $stmt->fetchColumn();

if (empty($customer_id)) {
	header('HTTP/1.0 403 Forbidden');
	die('Invalid customer access key');
}

$system_id = get_system_id();
$stmt = $db->prepare('update systems set customer_id = :customer_id where id = :system_id');
$stmt->execute(array(':customer_id' => $customer_id, ':system_id' => $system_id));

header('HTTP/1.0 204 No Content');
