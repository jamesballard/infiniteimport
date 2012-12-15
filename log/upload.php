<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'common.php';

# find a unique identifier
#$id = uniqid();
$id = 'x'; # a single table makes debugging easier

$db = database();

# create a staging table
$table = "import_moodle_$id";
$create_sql = <<<CREATE_SQL
create table $table (
  id bigint(10) unsigned NOT NULL,
  time bigint(10) unsigned NOT NULL,
  userid bigint(10) unsigned NOT NULL,
  ip varchar(45) NOT NULL DEFAULT '',
  course bigint(10) unsigned NOT NULL DEFAULT '0',
  module varchar(20) NOT NULL DEFAULT '',
  cmid bigint(10) unsigned NOT NULL DEFAULT '0',
  action varchar(40) NOT NULL DEFAULT '',
  url varchar(100) NOT NULL DEFAULT '',
  info varchar(255) NOT NULL DEFAULT ''
)
CREATE_SQL;
$stmt = $db->query("drop table if exists $table"); // just in case
$stmt->closeCursor();
$stmt = $db->query($create_sql);
$stmt->closeCursor();

# load the data
bulk_import_csv('php://input', $table, 'id,time,userid,ip,course,module,cmid,action,url,info');

# transform the data


# clean up
#$stmt = $db->query("drop table $table");
#$stmt->closeCursor();

?>
