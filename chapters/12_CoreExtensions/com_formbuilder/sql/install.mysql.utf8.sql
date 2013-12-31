CREATE TABLE `formbuilder_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) DEFAULT NULL,
  `json` text,
  `user_ip` varchar(45) DEFAULT NULL,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_form_id` (`form_id`),
  KEY `idx_user_ip` (`user_ip`),
  KEY `idx_datemod` (`date_modified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `formbuilder_forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sql` text,
  `json` text,
  `html` text,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `alias` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
