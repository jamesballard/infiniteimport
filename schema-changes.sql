ALTER TABLE users ADD COLUMN created DATETIME NULL;
ALTER TABLE users ADD COLUMN modified DATETIME NULL AFTER created;
ALTER TABLE users ADD CONSTRAINT FK_users_systems FOREIGN KEY (system_id) REFERENCES systems (id);
ALTER TABLE users ADD UNIQUE INDEX system_sysid_ix (system_id, sysid);
