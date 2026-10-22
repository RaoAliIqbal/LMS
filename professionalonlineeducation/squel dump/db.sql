/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.20-0ubuntu0.20.04.1 : Database - base
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `mail_templates` */

DROP TABLE IF EXISTS `mail_templates`;

CREATE TABLE `mail_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email_key` varchar(30) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `body` text,
  `status` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `mail_templates` */

insert  into `mail_templates`(`id`,`email_key`,`title`,`subject`,`body`,`status`,`created_at`,`updated_at`) values
(1,'new_project_admin','New Project Admin','New Project Added','<h1>A New Project Is Added By Client : [CLIENT]</h1>\r\n<p>View At [PROJECT-URL-ADMIN]</p>\r\n<p><span style=\"color: rgba(70, 90, 110, 0.85); font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, \'Noto Sans\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\'; font-size: 12.8px; background-color: #ffffff;\">[CLIENT]</span></p>\r\n<p>&nbsp;</p>',1,NULL,'2020-07-24 01:48:22'),
(2,'new_project_client','New Project Client','New Project Added','<h1>A New Project Is Added  Client : [CLIENT] </h1>\r\n<p>View At [PROJECT-URL]<p>',1,NULL,NULL),
(3,'new_project_agent','New Project Agent','New Project Added','<h1>A New Project Is Added  Client : [CLIENT] </h1>\r\n<p>View At [PROJECT-URL]<p>',1,NULL,NULL),
(4,'project_archive','Project Archive Notification','Your Project Is Archived','<h1>A Project That you are in is now archived</h1>\r\n<p>View At [PROJECT-URL]<p>',1,NULL,NULL),
(5,'project_restored','Project Restored Notification','Your PRoject is Restored','<h1>A Project That you are in is now restored</h1>\r\n<p>View At [PROJECT-URL]<p>',1,NULL,NULL),
(6,'new_msg','New Message','New Message Reviced','<h1>You have a new message</h1>\r\n[MESSAGE]\r\n<br>\r\n[PROJECT-URL]\r\n<br>\r\n[MESSAGE-URL]',1,NULL,NULL),
(7,'new_reply','New Reply','New Reply','<h1>You Got a new message</h1>\r\n[MESSAGE]\r\n<br>\r\n[PROJECT-URL]\r\n<br>\r\n[MESSAGE-URL]',1,NULL,NULL),
(8,'project-status-update','Project Completed','Your project is complete','project completed\r\n',NULL,NULL,NULL);

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2020_07_15_162041_create_permission_tables',1);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values
(2,'App\\User',1),
(3,'App\\User',2),
(2,'App\\User',3),
(1,'App\\User',4),
(2,'App\\User',5),
(3,'App\\User',6),
(2,'App\\User',7),
(2,'App\\User',8),
(2,'App\\User',9),
(2,'App\\User',10),
(2,'App\\User',11),
(2,'App\\User',12);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `password_resets` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `permissions` */

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `project_name` varchar(50) DEFAULT NULL,
  `project_description` text,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `project_file` text,
  `status` tinyint DEFAULT '1',
  `added_by` int DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `projects` */

insert  into `projects`(`id`,`user_id`,`project_name`,`project_description`,`start_date`,`end_date`,`project_file`,`status`,`added_by`,`created_at`,`updated_at`,`deleted_at`) values
(105,2,'My project','<p>this is the project description5555</p>','2020-08-27 00:00:00','2020-08-31 00:00:00','attachment-1595548701.jpeg',2,10,'2020-07-23','2020-08-26',NULL),
(106,5,'sdsds','<p>sdssdds</p>','2020-07-26 00:00:00','2020-07-30 00:00:00','attachment-1595635563.jpeg',1,5,'2020-07-25','2020-07-25',NULL),
(107,2,'15151','<p>1515166666</p>','2020-08-26 00:00:00','2020-08-29 00:00:00','attachment-1598311675.jpeg',1,10,'2020-08-24','2020-08-26','2020-08-24 23:28:36');

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `role_has_permissions` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values
(1,'Admin','web','2020-07-15 20:29:05','2020-07-15 20:29:05'),
(2,'Client','web','2020-07-15 20:29:34','2020-07-15 20:29:34'),
(3,'Agent','web','2020-07-15 20:29:45','2020-07-15 20:29:45');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `postcode` varchar(30) DEFAULT NULL,
  `address` text,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `intended_url` text,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`first_name`,`last_name`,`phone`,`city`,`state`,`postcode`,`address`,`email`,`email_verified_at`,`password`,`intended_url`,`remember_token`,`created_at`,`updated_at`) values
(1,'test','Client','0','00','00','00','00','00','pm33@mailnator.com','2020-07-15 23:38:13','$2y$10$XgjzoO/4JWxdf8Y2/5rQD.lutS3frlYyQrncaKeH1rjrEreTGfl2S','http://stevemajka.test',NULL,'2020-07-15 20:40:31','2021-01-10 21:49:42'),
(2,'test','Agent','M','00','00','00','00','00','pm3@mailnator.com','2020-07-15 23:38:13','$2y$10$Bjf2h/TmQldwyLfSxqxSVOvl4lCWhqy.srvsCtNQCss4nmjlq7Z52','http://stevemajka.test',NULL,'2020-07-15 20:42:00','2020-07-15 20:42:00'),
(3,'test','Client','test','00000000000','NY','kansas','00000','Kansas St\r\n50','pm4@mailnator.com','2020-07-15 23:38:13','$2y$10$OlLURYnUf88inV9TjA/oCep0j5KbaE6F3Bc2gnYtoplm0YI8ZGhCC','http://stevemajka.test',NULL,'2020-07-15 20:43:47','2020-07-17 04:08:55'),
(4,'test','admin','admin','00000000000','00','00','00','Kansas St\r\n50','mikecalhon7966@gmail.com','2020-07-17 06:02:41','$2y$10$QR0714nbNS/ID.1RqppUf.jpyZlEKDrRjBxXDbOYvryR9VllyIBae','http://stevemajka.test',NULL,'2020-07-17 05:07:39','2021-01-10 19:24:57'),
(5,'pm445','test','test','00','00','00','0','00','pm456@mailinator.com','2020-07-24 22:36:41','$2y$10$sqKOixBhtQuLFhVUTNIbO.shBWsxjMnWnyqqJtZa4PpclrLXCSkvO','http://stevemajka.test','rFucKn1BquuFfiPhWi1cJs6yZztFWb1LxVJZIFl0Frs8pucgkBDsrKIhcqz4','2020-07-21 00:28:16','2020-07-24 22:36:41'),
(8,'John Richardson','John','Richardson','1234567890','Houston','Texas','75201','Pacific Avenue','john.richardson.8589@gmail.com','2020-07-23 01:14:34','$2y$10$dhQicDTMP0UMPGeOq1Il5eTsvAXzTMTe0IIs1wSp9tczgTgxk.7u.','http://stevemajka.logodesigninfinity.com',NULL,'2020-07-23 01:12:10','2020-07-23 01:15:27'),
(10,'Trent J','Trent','JeO','0000000000000','00000000000','0000000000000','000000000000','00','trentjoe7966@gmail.com','2020-07-24 03:22:25','$2y$10$hseTeen3/A2FfESaOIVWQuGa.nAwLrDjXgongh2DhKStNwWKNqgfi','http://stevemajka.logodesigninfinity.com',NULL,'2020-07-24 03:22:08','2020-07-24 04:40:21'),
(11,'ss',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ss@gmail.com',NULL,'$2y$10$oJgqVt8EbjKkoGDN.1TBwu1FS7nkrhOiVN1fgNz36VykXMSNaYrgm','http://stevemajka.logodesigninfinity.com',NULL,'2020-07-24 17:05:44','2020-07-24 17:05:44'),
(12,'5+56+',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'5gaga@yopmail.com',NULL,'$2y$10$stxRN4zG13AvrNy5Qi.hYO83Vh94cLejP2UvfnJ9aIZ7ZpbI5VO.y','http://marius.test',NULL,'2020-08-26 15:51:33','2020-08-26 15:51:33');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
