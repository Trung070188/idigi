CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `display_name` varchar(200) DEFAULT NULL,
  `class` varchar(200) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `display_name` (`class`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4