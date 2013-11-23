ALTER TABLE users ADD COLUMN created DATETIME NULL;
ALTER TABLE users ADD COLUMN modified DATETIME NULL AFTER created;
ALTER TABLE users ADD COLUMN username varchar(255) DEFAULT NULL AFTER idnumber;
ALTER TABLE users ADD COLUMN name varchar(255) DEFAULT NULL AFTER username;
ALTER TABLE users ADD COLUMN dob date DEFAULT NULL AFTER name;
ALTER TABLE users ADD COLUMN gender enum('M','F') DEFAULT NULL AFTER dob;
ALTER TABLE users ADD CONSTRAINT FK_users_systems FOREIGN KEY (system_id) REFERENCES systems (id);
ALTER TABLE users ADD UNIQUE INDEX system_sysid_ix (system_id, sysid);

ALTER TABLE modules ADD COLUMN created DATETIME NULL;
ALTER TABLE modules ADD COLUMN modified DATETIME NULL AFTER created;

