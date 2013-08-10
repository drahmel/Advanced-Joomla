DROP TABLE IF EXISTS "joomlasvn"."tinkerforms_forms";
CREATE TABLE  "joomlasvn"."tinkerforms_forms" (
  "id" int(10) unsigned NOT NULL auto_increment,
  "sql" text NOT NULL COMMENT 'SQL Statement for query',
  "json" text COMMENT 'JSON data describing display',
  "html" text COMMENT 'HTML output for override',
  "name" varchar(45) NOT NULL,
  "type" char(2) NOT NULL,
  PRIMARY KEY  ("id")
);
