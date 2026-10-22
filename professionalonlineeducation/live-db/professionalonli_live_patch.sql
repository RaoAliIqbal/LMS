-- =============================================================================
-- Professional Online Education — LIVE database patch
-- File: professionalonli_live_patch.sql
--
-- PURPOSE
--   Brings the live database schema in line with the current Laravel app WITHOUT
--   deleting or replacing your existing data (users, enrollments, courses, etc.).
--
-- WHEN TO USE
--   Import this in cPanel → phpMyAdmin → select database → Import.
--   Do NOT import the full local dump over live — that can overwrite production data.
--
-- SAFE TO RE-RUN
--   Uses information_schema checks; skips steps already applied.
--
-- AFTER IMPORT (SSH or cPanel Terminal, in your site folder):
--   php artisan config:clear
--   php artisan cache:clear
--   php artisan view:clear
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

DELIMITER $$

DROP PROCEDURE IF EXISTS `po_patch_live_schema`$$

CREATE PROCEDURE `po_patch_live_schema`()
BEGIN
    DECLARE db_name VARCHAR(64);
    SET db_name = DATABASE();

    -- -------------------------------------------------------------------------
    -- 1) chatbot_qas (missing on live; required for admin Support Chatbot Q&A)
    -- -------------------------------------------------------------------------
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'chatbot_qas'
    ) THEN
        CREATE TABLE `chatbot_qas` (
            `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
            `topic_key` varchar(40) NOT NULL,
            `label` varchar(255) DEFAULT NULL,
            `tip_html` text NOT NULL,
            `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `chatbot_qas_topic_key_is_active_sort_order_index` (`topic_key`,`is_active`,`sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    END IF;

    -- -------------------------------------------------------------------------
    -- 2) chat_escalations — required for website Support chat (IT / Instructor / CS)
    -- -------------------------------------------------------------------------
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'chat_escalations'
    ) THEN
        CREATE TABLE `chat_escalations` (
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
    ELSE
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'chat_escalations' AND column_name = 'course_name'
        ) THEN
            ALTER TABLE `chat_escalations`
                ADD COLUMN `course_name` varchar(255) NULL DEFAULT NULL AFTER `email`;
        END IF;

        IF EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'chat_escalations' AND column_name = 'course'
        ) THEN
            UPDATE `chat_escalations`
            SET `course_name` = `course`
            WHERE (`course_name` IS NULL OR `course_name` = '')
              AND `course` IS NOT NULL
              AND `course` <> '';
        END IF;

        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'chat_escalations' AND column_name = 'token'
        ) THEN
            ALTER TABLE `chat_escalations`
                ADD COLUMN `token` varchar(80) NULL DEFAULT NULL AFTER `type`;
        END IF;

        IF EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'chat_escalations' AND column_name = 'follow_up_token'
        ) THEN
            UPDATE `chat_escalations`
            SET `token` = `follow_up_token`
            WHERE (`token` IS NULL OR `token` = '')
              AND `follow_up_token` IS NOT NULL
              AND `follow_up_token` <> '';
        END IF;

        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'chat_escalations' AND column_name = 'follow_up_sent_at'
        ) THEN
            ALTER TABLE `chat_escalations`
                ADD COLUMN `follow_up_sent_at` timestamp NULL DEFAULT NULL;
        END IF;

        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'chat_escalations' AND column_name = 'status'
        ) THEN
            ALTER TABLE `chat_escalations`
                ADD COLUMN `status` varchar(30) NOT NULL DEFAULT 'pending';
        END IF;
    END IF;

    -- -------------------------------------------------------------------------
    -- 3) approvals (required for /admin/approvals)
    -- -------------------------------------------------------------------------
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'approvals'
    ) THEN
        CREATE TABLE `approvals` (
            `id` int NOT NULL AUTO_INCREMENT,
            `approval` varchar(120) DEFAULT NULL,
            `status` int DEFAULT '1',
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        INSERT INTO `approvals` (`id`, `approval`, `status`, `created_at`, `updated_at`) VALUES
            (1, 'PACE', 1, NOW(), NOW()),
            (2, 'STATE', 1, NOW(), NOW()),
            (3, 'NCBTMB', 1, NOW(), NOW()),
            (4, 'AMERICAN HEART ASSOCIATION', 1, NOW(), NOW()),
            (5, 'NA', 1, NOW(), NOW()),
            (6, 'GOVERNMENT', 1, NOW(), NOW());
    END IF;

    -- -------------------------------------------------------------------------
    -- 4) lessons — Zoom columns (older live DBs may be missing these)
    -- -------------------------------------------------------------------------
    IF EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'lessons'
    ) THEN
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'lessons' AND column_name = 'has_zoom'
        ) THEN
            ALTER TABLE `lessons` ADD COLUMN `has_zoom` tinyint(1) NOT NULL DEFAULT 0;
        END IF;
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'lessons' AND column_name = 'zoom_link'
        ) THEN
            ALTER TABLE `lessons` ADD COLUMN `zoom_link` varchar(500) NULL DEFAULT NULL;
        END IF;
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'lessons' AND column_name = 'zoom_meeting_id'
        ) THEN
            ALTER TABLE `lessons` ADD COLUMN `zoom_meeting_id` varchar(50) NULL DEFAULT NULL;
        END IF;
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'lessons' AND column_name = 'zoom_password'
        ) THEN
            ALTER TABLE `lessons` ADD COLUMN `zoom_password` varchar(100) NULL DEFAULT NULL;
        END IF;
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'lessons' AND column_name = 'zoom_date'
        ) THEN
            ALTER TABLE `lessons` ADD COLUMN `zoom_date` date NULL DEFAULT NULL;
        END IF;
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'lessons' AND column_name = 'zoom_time'
        ) THEN
            ALTER TABLE `lessons` ADD COLUMN `zoom_time` varchar(20) NULL DEFAULT NULL;
        END IF;
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_schema = db_name AND table_name = 'lessons' AND column_name = 'zoom_description'
        ) THEN
            ALTER TABLE `lessons` ADD COLUMN `zoom_description` text NULL DEFAULT NULL;
        END IF;
    END IF;

    -- -------------------------------------------------------------------------
    -- 6) projects (admin /admin/projects — was never in backup)
    -- -------------------------------------------------------------------------
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'projects'
    ) THEN
        CREATE TABLE `projects` (
            `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` bigint UNSIGNED DEFAULT NULL,
            `project_name` varchar(255) DEFAULT NULL,
            `project_description` text,
            `start_date` date DEFAULT NULL,
            `end_date` date DEFAULT NULL,
            `project_file` varchar(255) DEFAULT NULL,
            `status` varchar(50) DEFAULT NULL,
            `added_by` bigint UNSIGNED DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    END IF;

    -- -------------------------------------------------------------------------
    -- 7) user_bot_conversations (admin User Bot Conversation log)
    -- -------------------------------------------------------------------------
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'user_bot_conversations'
    ) THEN
        CREATE TABLE `user_bot_conversations` (
            `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
            `question` varchar(5000) NOT NULL,
            `answer` varchar(5000) NOT NULL,
            `ip` varchar(255) NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    END IF;

    -- -------------------------------------------------------------------------
    -- 8) course_approvals + seminar_approvals (admin approval lists)
    -- -------------------------------------------------------------------------
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'course_approvals'
    ) THEN
        CREATE TABLE `course_approvals` (
            `id` int NOT NULL AUTO_INCREMENT,
            `approval_id` int DEFAULT NULL,
            `lesson_id` int DEFAULT NULL,
            `approval_no` varchar(255) DEFAULT NULL,
            `provider_no` varchar(255) DEFAULT NULL,
            `start_date` date DEFAULT NULL,
            `end_date` date DEFAULT NULL,
            `status` int DEFAULT '1',
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'seminar_approvals'
    ) THEN
        CREATE TABLE `seminar_approvals` (
            `id` int NOT NULL AUTO_INCREMENT,
            `approval_id` int DEFAULT NULL,
            `seminar_id` int DEFAULT NULL,
            `approval_no` varchar(150) DEFAULT NULL,
            `provider_no` varchar(150) DEFAULT NULL,
            `start_date` date DEFAULT NULL,
            `end_date` date DEFAULT NULL,
            `status` int DEFAULT '1',
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    END IF;

    -- -------------------------------------------------------------------------
    -- 9) seminar_users.completed_at (live usually has this; ensure if missing)
    -- -------------------------------------------------------------------------
    IF EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'seminar_users'
    ) AND NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_schema = db_name AND table_name = 'seminar_users' AND column_name = 'completed_at'
    ) THEN
        ALTER TABLE `seminar_users`
            ADD COLUMN `completed_at` timestamp NULL DEFAULT NULL AFTER `enrolled_at`;
    END IF;

    -- -------------------------------------------------------------------------
    -- 10) migrations table — record patches so `php artisan migrate` stays in sync
    -- -------------------------------------------------------------------------
    IF EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = db_name AND table_name = 'migrations'
    ) THEN
        IF NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_05_12_000001_create_chat_escalations_table') THEN
            INSERT INTO `migrations` (`migration`, `batch`)
            SELECT '2026_05_12_000001_create_chat_escalations_table', IFNULL(MAX(`batch`), 0) + 1 FROM `migrations`;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_05_13_000001_add_course_name_to_chat_escalations_table') THEN
            INSERT INTO `migrations` (`migration`, `batch`)
            SELECT '2026_05_13_000001_add_course_name_to_chat_escalations_table', IFNULL(MAX(`batch`), 0) + 1 FROM `migrations`;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_05_14_000001_ensure_chat_escalations_columns') THEN
            INSERT INTO `migrations` (`migration`, `batch`)
            SELECT '2026_05_14_000001_ensure_chat_escalations_columns', IFNULL(MAX(`batch`), 0) + 1 FROM `migrations`;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_05_15_000001_create_chatbot_qas_table') THEN
            INSERT INTO `migrations` (`migration`, `batch`)
            SELECT '2026_05_15_000001_create_chatbot_qas_table', IFNULL(MAX(`batch`), 0) + 1 FROM `migrations`;
        END IF;
    END IF;

END$$

DELIMITER ;

CALL `po_patch_live_schema`();
DROP PROCEDURE IF EXISTS `po_patch_live_schema`;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- Done. Your course / certificate / enrollment data was NOT modified.
-- =============================================================================
