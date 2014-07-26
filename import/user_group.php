<?php

/*
sysid: string, required, private id within source system
user_id: string, required, sysid of the user within source system
group_id: string, required, sysid of the group within source system
role: string, required, nature of the user-group relationship, e.g. Student
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

$parser = new CsvIterator('php://input');
$parser->setRequiredFields(array('sysid', 'user_id', 'group_id', 'role'));

$importer = new BulkImport($parser, 'user_groups');
$importer->setSystemSpecific(true);
$importer->setDated(true);
$importer->run();

sql_execute("
	delete from user_groups
	where modified < date_sub(now(), interval 1 hour)
	and (
		user_id in (select id from users where system_id = :system_id)
		or group_id in (select id from groups where system_id = :system_id)
	)
", array(':system_id' => get_system_id()));

?>
