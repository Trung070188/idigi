CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `hash` varchar(40) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `uploaded_by` varchar(200) DEFAULT NULL,
  `is_image` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `extension` varchar(50) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4