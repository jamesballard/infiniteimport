<?php

/*
if not explicitly stated, the field is optional
a blank value is treated as unspecified

sysid: string, required, private id within source system
username: string, optional, public id within source system
name: string, optional, what the person is known as
gender: 'M' or 'F', optional
dob: yyyy-mm-dd, optional
*/

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

# load the data
$system_id = get_system_id();
bulk_import_csv('php://input',
	'users',
	'sysid,username,name,gender,dob',
	"set system_id = $system_id, modified = now()");

?>
