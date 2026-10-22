-- =============================================================================
-- Fix Support chat (IT Support / Instructor / Customer Service)
-- Error in chat: "We could not process your request right now..."
--
-- cPanel → phpMyAdmin → select live database → Import or SQL tab
-- Safe to re-run
-- =============================================================================

CREATE TABLE IF NOT EXISTS `chat_escalations` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `phone` varchar(255) DEFAULT NULL,
    `email` varchar(255) NOT NULL,
    `course_name` varchar(255) DEFAULT NULL,
    `issue` text,
    `type` varchar(30) NOT NULL DEFAULT 'it',
    `token` varchar(80) DEFAULT NULL,
    `follow_up_token` varchar(255) DEFAULT NULL,
    `follow_up_sent_at` timestamp NULL DEFAULT NULL,
    `status` varchar(30) NOT NULL DEFAULT 'pending',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `chat_escalations_type_status_index` (`type`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If table already existed with OLD columns (course, follow_up_token only),
-- also import: live-db/professionalonli_live_patch.sql

-- Verify:
-- SHOW TABLES LIKE 'chat_escalations';
-- DESCRIBE chat_escalations;
