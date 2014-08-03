ALTER TABLE users ADD COLUMN created DATETIME NULL;
ALTER TABLE users ADD COLUMN modified DATETIME NULL AFTER created;
ALTER TABLE users ADD COLUMN username varchar(255) DEFAULT NULL AFTER idnumber;
ALTER TABLE users ADD COLUMN name varchar(255) DEFAULT NULL AFTER username;
ALTER TABLE users ADD COLUMN dob date DEFAULT NULL AFTER name;
ALTER TABLE users ADD COLUMN gender enum('M','F') DEFAULT NULL AFTER dob;
ALTER TABLE users MODIFY COLUMN system_id int(11) DEFAULT NULL;
ALTER TABLE users ADD CONSTRAINT FK_users_systems FOREIGN KEY (system_id) REFERENCES systems (id);
ALTER TABLE users ADD UNIQUE INDEX system_sysid_ix (system_id, sysid);

ALTER TABLE modules ADD COLUMN created DATETIME NULL;
ALTER TABLE modules ADD COLUMN modified DATETIME NULL AFTER created;

CREATE TABLE customer_keys (
  id int(11) NOT NULL AUTO_INCREMENT,
  customer_id int(11) NOT NULL,
  accesskey varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (accesskey),
  INDEX customer_ix (customer_id),
  FOREIGN KEY (customer_id) REFERENCES customers (id)
    ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

alter table actions drop key action_unique_ix;
alter table actions add unique key action_unique_ix (system_id,time,user_id,group_id,module_id,dimension_verb_id,sysid);

alter table customer_artefacts add unique key customer_artefact_unique_ix (customer_id,artefact_id);

alter table user_groups change id sysid int(11) unsigned;
