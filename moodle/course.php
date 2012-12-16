<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

# load the data
$system_id = get_system_id();
bulk_import_csv('php://input',
	'moodle_course',
	'id,fullname,shortname,idnumber,startdate,visible',
	"set system_id = $system_id");

?>
