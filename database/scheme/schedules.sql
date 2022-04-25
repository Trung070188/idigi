CREATE TABLE `schedules` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL COMMENT 'Follower',
    `creator_id` int(11) NOT NULL,
    `date` date NOT NULL COMMENT 'Appointment date',
    `time_from` time NOT NULL COMMENT 'Time start',
    `time_to` time NOT NULL COMMENT 'Time end',
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` int(11) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
