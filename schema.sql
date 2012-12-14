CREATE TABLE `systems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` bigint(4) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `certificate` text,
  `site_name` varchar(200) DEFAULT NULL,
  `contact_email` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_ix` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
