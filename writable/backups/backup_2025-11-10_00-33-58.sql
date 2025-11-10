-- Backup Database: yensurvival
-- Created at: 2025-11-10 00:33:58



CREATE TABLE `aset_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL DEFAULT (curdate()),
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `akun_id` int DEFAULT NULL,
  `jumlah` decimal(16,2) NOT NULL DEFAULT '0.00',
  `nilai_sekarang` decimal(16,2) NOT NULL DEFAULT '0.00',
  `deskripsi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('aktif','selesai') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `auth_activation_attempts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;



CREATE TABLE `auth_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

INSERT INTO auth_groups VALUES ('1','Admin','Site Administrator');
INSERT INTO auth_groups VALUES ('2','User','Site Reguler User');


CREATE TABLE `auth_groups_permissions` (
  `group_id` int unsigned NOT NULL DEFAULT '0',
  `permission_id` int unsigned NOT NULL DEFAULT '0',
  KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  KEY `group_id_permission_id` (`group_id`,`permission_id`),
  CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO auth_groups_permissions VALUES ('1','1');
INSERT INTO auth_groups_permissions VALUES ('1','2');
INSERT INTO auth_groups_permissions VALUES ('2','2');


CREATE TABLE `auth_groups_users` (
  `group_id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL DEFAULT '0',
  KEY `auth_groups_users_user_id_foreign` (`user_id`),
  KEY `group_id_user_id` (`group_id`,`user_id`),
  CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO auth_groups_users VALUES ('1','8');
INSERT INTO auth_groups_users VALUES ('1','8');
INSERT INTO auth_groups_users VALUES ('2','10');
INSERT INTO auth_groups_users VALUES ('2','62');
INSERT INTO auth_groups_users VALUES ('2','63');
INSERT INTO auth_groups_users VALUES ('2','64');
INSERT INTO auth_groups_users VALUES ('2','65');
INSERT INTO auth_groups_users VALUES ('2','66');
INSERT INTO auth_groups_users VALUES ('2','67');
INSERT INTO auth_groups_users VALUES ('2','68');
INSERT INTO auth_groups_users VALUES ('2','69');
INSERT INTO auth_groups_users VALUES ('2','70');
INSERT INTO auth_groups_users VALUES ('2','71');
INSERT INTO auth_groups_users VALUES ('2','72');
INSERT INTO auth_groups_users VALUES ('2','73');
INSERT INTO auth_groups_users VALUES ('2','74');
INSERT INTO auth_groups_users VALUES ('2','75');
INSERT INTO auth_groups_users VALUES ('2','76');
INSERT INTO auth_groups_users VALUES ('2','77');
INSERT INTO auth_groups_users VALUES ('2','78');
INSERT INTO auth_groups_users VALUES ('2','79');
INSERT INTO auth_groups_users VALUES ('2','80');
INSERT INTO auth_groups_users VALUES ('2','81');
INSERT INTO auth_groups_users VALUES ('2','82');
INSERT INTO auth_groups_users VALUES ('2','83');
INSERT INTO auth_groups_users VALUES ('2','84');
INSERT INTO auth_groups_users VALUES ('2','85');
INSERT INTO auth_groups_users VALUES ('2','86');
INSERT INTO auth_groups_users VALUES ('2','87');
INSERT INTO auth_groups_users VALUES ('2','88');
INSERT INTO auth_groups_users VALUES ('2','89');
INSERT INTO auth_groups_users VALUES ('2','90');
INSERT INTO auth_groups_users VALUES ('2','91');
INSERT INTO auth_groups_users VALUES ('2','92');
INSERT INTO auth_groups_users VALUES ('2','93');
INSERT INTO auth_groups_users VALUES ('2','94');
INSERT INTO auth_groups_users VALUES ('2','95');
INSERT INTO auth_groups_users VALUES ('2','96');
INSERT INTO auth_groups_users VALUES ('2','97');
INSERT INTO auth_groups_users VALUES ('2','98');
INSERT INTO auth_groups_users VALUES ('2','99');
INSERT INTO auth_groups_users VALUES ('2','100');
INSERT INTO auth_groups_users VALUES ('2','101');
INSERT INTO auth_groups_users VALUES ('2','102');
INSERT INTO auth_groups_users VALUES ('2','103');
INSERT INTO auth_groups_users VALUES ('2','104');
INSERT INTO auth_groups_users VALUES ('2','105');
INSERT INTO auth_groups_users VALUES ('2','106');


CREATE TABLE `auth_logins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb3;

INSERT INTO auth_logins VALUES ('1','::1','reyhcreator.97@gmail.com','8','2025-09-25 04:10:55','1');
INSERT INTO auth_logins VALUES ('2','::1','reyhcreator.97@gmail.com','8','2025-09-25 04:18:02','1');
INSERT INTO auth_logins VALUES ('3','::1','reyhcreator.97@gmail.com','8','2025-09-25 04:18:12','1');
INSERT INTO auth_logins VALUES ('4','::1','reyhcreator.97@gmail.com','8','2025-09-25 14:24:13','1');
INSERT INTO auth_logins VALUES ('5','::1','reyhcreator.97@gmail.com','8','2025-09-26 01:59:19','1');
INSERT INTO auth_logins VALUES ('6','::1','reyhcreator.97@gmail.com','8','2025-09-26 02:09:52','1');
INSERT INTO auth_logins VALUES ('7','::1','tasya@gmail.com','9','2025-09-26 02:14:55','1');
INSERT INTO auth_logins VALUES ('8','::1','tasya@gmail.com','9','2025-09-26 02:24:06','1');
INSERT INTO auth_logins VALUES ('9','::1','reyhcreator.97@gmail.com','8','2025-09-26 02:28:53','1');
INSERT INTO auth_logins VALUES ('10','::1','reyhcreator.97@gmail.com','8','2025-09-26 06:40:28','1');
INSERT INTO auth_logins VALUES ('11','::1','reyhcreator.97@gmail.com','8','2025-09-26 07:57:41','1');
INSERT INTO auth_logins VALUES ('12','::1','tasya@gmail.com','9','2025-09-26 08:06:16','1');
INSERT INTO auth_logins VALUES ('13','::1','reyhcreator.97@gmail.com','8','2025-09-26 08:13:45','1');
INSERT INTO auth_logins VALUES ('14','::1','tasya@gmail.com','9','2025-09-26 08:14:05','1');
INSERT INTO auth_logins VALUES ('15','::1','reyhcreator.97@gmail.com','8','2025-09-26 08:17:25','1');
INSERT INTO auth_logins VALUES ('16','::1','reyhcreator.97@gmail.com','8','2025-09-26 08:18:36','1');
INSERT INTO auth_logins VALUES ('17','::1','tasya@gmail.com','9','2025-09-26 08:20:25','1');
INSERT INTO auth_logins VALUES ('18','::1','reyhcreator.97@gmail.com','8','2025-09-26 08:20:36','1');
INSERT INTO auth_logins VALUES ('19','::1','reyhcreator.97@gmail.com','8','2025-09-26 08:29:22','1');
INSERT INTO auth_logins VALUES ('20','::1','reyhcreator.97@gmail.com','8','2025-09-28 14:12:49','1');
INSERT INTO auth_logins VALUES ('21','::1','tasya@gmail.com','9','2025-09-28 14:18:34','1');
INSERT INTO auth_logins VALUES ('22','::1','tasya@gmail.com','9','2025-09-28 15:57:38','1');
INSERT INTO auth_logins VALUES ('23','::1','reyhcreator.97@gmail.com','8','2025-09-28 22:14:36','1');
INSERT INTO auth_logins VALUES ('24','::1','tasya@gmail.com','9','2025-09-29 09:46:28','1');
INSERT INTO auth_logins VALUES ('25','::1','reyhcreator.97@gmail.com','8','2025-10-01 10:37:40','1');
INSERT INTO auth_logins VALUES ('26','::1','tasya@gmail.com','9','2025-10-01 10:40:15','1');
INSERT INTO auth_logins VALUES ('27','::1','reyhcreator.97@gmail.com','8','2025-10-01 10:43:22','1');
INSERT INTO auth_logins VALUES ('28','::1','reyhcreator.97@gmail.com','8','2025-10-01 15:10:28','1');
INSERT INTO auth_logins VALUES ('29','::1','tasya@gmail.com','9','2025-10-02 12:02:21','1');
INSERT INTO auth_logins VALUES ('30','::1','reyhcreator.97@gmail.com','8','2025-10-02 12:03:45','1');
INSERT INTO auth_logins VALUES ('31','::1','tasya@gmail.com','9','2025-10-02 12:44:00','1');
INSERT INTO auth_logins VALUES ('32','::1','uci@gmail.com','10','2025-10-02 13:49:40','1');
INSERT INTO auth_logins VALUES ('33','::1','uci@gmail.com','10','2025-10-02 14:48:03','1');
INSERT INTO auth_logins VALUES ('34','::1','uci@gmail.com','10','2025-10-02 14:52:41','1');
INSERT INTO auth_logins VALUES ('35','::1','tasya@gmail.com','9','2025-10-02 14:53:00','1');
INSERT INTO auth_logins VALUES ('36','::1','tasya@gmail.com','9','2025-10-02 16:37:44','1');
INSERT INTO auth_logins VALUES ('37','::1','tasya@gmail.com','9','2025-10-02 16:38:51','1');
INSERT INTO auth_logins VALUES ('38','::1','tasya@gmail.com','9','2025-10-03 11:55:29','1');
INSERT INTO auth_logins VALUES ('39','::1','tasya@gmail.com','9','2025-10-04 09:50:45','1');
INSERT INTO auth_logins VALUES ('40','::1','tasya@gmail.com','9','2025-10-05 11:32:01','1');
INSERT INTO auth_logins VALUES ('41','::1','tasya@gmail.com','9','2025-10-06 11:19:49','1');
INSERT INTO auth_logins VALUES ('42','::1','tasya@gmail.com','9','2025-10-06 21:42:31','1');
INSERT INTO auth_logins VALUES ('43','::1','tasya@gmail.com','9','2025-10-07 09:53:45','1');
INSERT INTO auth_logins VALUES ('44','::1','tasya@gmail.com','9','2025-10-07 22:59:31','1');
INSERT INTO auth_logins VALUES ('45','::1','tasya@gmail.com','9','2025-10-08 06:02:01','1');
INSERT INTO auth_logins VALUES ('46','::1','tasya@gmail.com','9','2025-10-08 21:27:00','1');
INSERT INTO auth_logins VALUES ('47','::1','tasya',NULL,'2025-10-09 12:24:16','0');
INSERT INTO auth_logins VALUES ('48','::1','tasya@gmail.com','9','2025-10-09 12:24:22','1');
INSERT INTO auth_logins VALUES ('49','::1','tasya@gmail.com','9','2025-10-09 21:34:57','1');
INSERT INTO auth_logins VALUES ('50','::1','tasya@gmail.com','9','2025-10-10 09:38:52','1');
INSERT INTO auth_logins VALUES ('51','::1','tasya@gmail.com','9','2025-10-10 12:47:02','1');
INSERT INTO auth_logins VALUES ('52','::1','tasya@gmail.com','9','2025-10-10 21:53:27','1');
INSERT INTO auth_logins VALUES ('53','::1','tasya@gmail.com','9','2025-10-11 11:04:48','1');
INSERT INTO auth_logins VALUES ('54','::1','tasya@gmail.com','9','2025-10-11 22:08:04','1');
INSERT INTO auth_logins VALUES ('55','::1','tasya@gmail.com','9','2025-10-12 08:45:36','1');
INSERT INTO auth_logins VALUES ('56','::1','tasya@gmail.com','9','2025-10-12 20:33:32','1');
INSERT INTO auth_logins VALUES ('57','::1','taasya',NULL,'2025-10-13 07:22:00','0');
INSERT INTO auth_logins VALUES ('58','::1','tasya@gmail.com','9','2025-10-13 07:22:12','1');
INSERT INTO auth_logins VALUES ('59','::1','uci@gmail.com','10','2025-10-13 07:59:48','1');
INSERT INTO auth_logins VALUES ('60','::1','uci@gmail.com','10','2025-10-13 08:09:39','1');
INSERT INTO auth_logins VALUES ('61','::1','uci@gmail.com','10','2025-10-13 08:16:07','1');
INSERT INTO auth_logins VALUES ('62','::1','uci@gmail.com','10','2025-10-13 10:35:21','1');
INSERT INTO auth_logins VALUES ('63','::1','reyhcreator.97@gmail.com','8','2025-10-13 13:45:50','1');
INSERT INTO auth_logins VALUES ('64','::1','reyhcreator.97@gmail.com','8','2025-10-13 21:25:19','1');
INSERT INTO auth_logins VALUES ('65','::1','uci@gmail.com','10','2025-10-13 21:44:10','1');
INSERT INTO auth_logins VALUES ('66','::1','reyhcreator.97@gmail.com','8','2025-10-13 21:44:24','1');
INSERT INTO auth_logins VALUES ('67','::1','reyhcreator.97@gmail.com','8','2025-10-13 21:56:41','1');
INSERT INTO auth_logins VALUES ('68','::1','uci@gmail.com','10','2025-10-14 10:32:45','1');
INSERT INTO auth_logins VALUES ('69','::1','reycreator',NULL,'2025-10-14 10:33:26','0');
INSERT INTO auth_logins VALUES ('70','::1','reyhcreator.97@gmail.com','8','2025-10-14 10:33:36','1');
INSERT INTO auth_logins VALUES ('71','::1','reyhcreator.97@gmail.com','8','2025-10-14 10:41:45','1');
INSERT INTO auth_logins VALUES ('72','::1','reyhcreator.97@gmail.com','8','2025-10-14 10:55:44','1');
INSERT INTO auth_logins VALUES ('73','::1','reyhcreator.97@gmail.com','8','2025-10-14 11:02:29','1');
INSERT INTO auth_logins VALUES ('74','::1','uci',NULL,'2025-10-14 11:02:56','0');
INSERT INTO auth_logins VALUES ('75','::1','uci@gmail.com','10','2025-10-14 11:03:06','1');
INSERT INTO auth_logins VALUES ('76','::1','reyhcreator.97@gmail.com','8','2025-10-14 11:17:40','1');
INSERT INTO auth_logins VALUES ('77','::1','uci',NULL,'2025-10-14 12:41:42','0');
INSERT INTO auth_logins VALUES ('78','::1','uci',NULL,'2025-10-14 12:41:53','0');
INSERT INTO auth_logins VALUES ('79','::1','uci',NULL,'2025-10-14 12:42:02','0');
INSERT INTO auth_logins VALUES ('80','::1','uci',NULL,'2025-10-14 12:42:17','0');
INSERT INTO auth_logins VALUES ('81','::1','reyhcreator.97@gmail.com','8','2025-10-14 12:42:26','1');
INSERT INTO auth_logins VALUES ('82','::1','uci',NULL,'2025-10-14 12:42:49','0');
INSERT INTO auth_logins VALUES ('83','::1','uci',NULL,'2025-10-14 12:43:18','0');
INSERT INTO auth_logins VALUES ('84','::1','reyhcreator.97@gmail.com','8','2025-10-14 12:43:29','1');
INSERT INTO auth_logins VALUES ('85','::1','uci',NULL,'2025-10-14 12:46:31','0');
INSERT INTO auth_logins VALUES ('86','::1','uci',NULL,'2025-10-14 12:47:18','0');
INSERT INTO auth_logins VALUES ('87','::1','reyhcreator.97@gmail.com','8','2025-10-14 13:02:08','1');
INSERT INTO auth_logins VALUES ('88','::1','uci@gmail.com','10','2025-10-14 13:24:45','1');
INSERT INTO auth_logins VALUES ('89','::1','reyhcreator.97@gmail.com','8','2025-10-14 13:25:20','1');
INSERT INTO auth_logins VALUES ('90','::1','uci','10','2025-10-14 13:25:49','0');
INSERT INTO auth_logins VALUES ('91','::1','uci','10','2025-10-14 13:28:30','0');
INSERT INTO auth_logins VALUES ('92','::1','reyhcreator.97@gmail.com','8','2025-10-14 13:38:34','1');
INSERT INTO auth_logins VALUES ('93','::1','uci','10','2025-10-14 13:39:02','0');
INSERT INTO auth_logins VALUES ('94','::1','uci','10','2025-10-14 13:41:06','0');
INSERT INTO auth_logins VALUES ('95','::1','uci','10','2025-10-14 13:46:29','0');
INSERT INTO auth_logins VALUES ('96','::1','uci','10','2025-10-14 13:48:31','0');
INSERT INTO auth_logins VALUES ('97','::1','uci','10','2025-10-14 13:49:27','0');
INSERT INTO auth_logins VALUES ('98','::1','uci','10','2025-10-14 13:49:49','0');
INSERT INTO auth_logins VALUES ('99','::1','uci','10','2025-10-14 14:04:05','0');
INSERT INTO auth_logins VALUES ('100','::1','reyhcreator.97@gmail.com','8','2025-10-14 14:17:26','1');
INSERT INTO auth_logins VALUES ('101','::1','uci@gmail.com','10','2025-10-14 14:17:56','1');
INSERT INTO auth_logins VALUES ('102','::1','reyhcreator.97@gmail.com','8','2025-10-14 14:20:24','1');
INSERT INTO auth_logins VALUES ('103','::1','reyhcreator.97@gmail.com','8','2025-10-14 21:24:00','1');
INSERT INTO auth_logins VALUES ('104','::1','reyhcreator.97@gmail.com','8','2025-10-14 21:35:11','1');
INSERT INTO auth_logins VALUES ('105','::1','reyhcreator.97@gmail.com','8','2025-10-15 11:08:36','1');
INSERT INTO auth_logins VALUES ('106','::1','reyhcreator.97@gmail.com','8','2025-10-15 15:00:03','1');
INSERT INTO auth_logins VALUES ('107','::1','reyhcreator.97@gmail.com','8','2025-10-15 15:07:34','1');
INSERT INTO auth_logins VALUES ('108','::1','reyhcreator.97@gmail.com','8','2025-10-15 21:19:55','1');
INSERT INTO auth_logins VALUES ('109','::1','reyhcreator.97@gmail.com','8','2025-10-16 12:22:56','1');
INSERT INTO auth_logins VALUES ('110','::1','reyhcreator.97@gmail.com','8','2025-10-16 21:23:03','1');
INSERT INTO auth_logins VALUES ('111','::1','reyhcreator.97@gmail.com','8','2025-10-17 13:43:28','1');
INSERT INTO auth_logins VALUES ('112','::1','reyhcreator.97@gmail.com','8','2025-10-18 02:15:23','1');
INSERT INTO auth_logins VALUES ('113','::1','reyhcreator.97@gmail.com','8','2025-10-18 06:19:51','1');
INSERT INTO auth_logins VALUES ('114','::1','uci',NULL,'2025-10-18 06:20:26','0');
INSERT INTO auth_logins VALUES ('115','::1','uci',NULL,'2025-10-18 06:20:45','0');
INSERT INTO auth_logins VALUES ('116','::1','uci',NULL,'2025-10-18 06:20:57','0');
INSERT INTO auth_logins VALUES ('117','::1','reyhcreator.97@gmail.com','8','2025-10-18 06:21:07','1');
INSERT INTO auth_logins VALUES ('118','::1','uci@gmail.com','10','2025-10-18 06:22:37','1');
INSERT INTO auth_logins VALUES ('119','::1','reyhcreator.97@gmail.com','8','2025-10-18 06:24:33','1');
INSERT INTO auth_logins VALUES ('120','::1','uci@gmail.com','10','2025-10-18 06:25:21','1');
INSERT INTO auth_logins VALUES ('121','::1','reyhcreator.97@gmail.com','8','2025-10-18 06:25:56','1');
INSERT INTO auth_logins VALUES ('122','::1','reyhcreator.97@gmail.com','8','2025-10-18 11:24:40','1');
INSERT INTO auth_logins VALUES ('123','::1','reyhcreator.97@gmail.com','8','2025-10-18 11:44:26','1');
INSERT INTO auth_logins VALUES ('124','::1','reyhcreator.97@gmail.com','8','2025-10-18 11:46:36','1');
INSERT INTO auth_logins VALUES ('125','::1','reyhcreator.97@gmail.com','8','2025-10-18 13:05:28','1');
INSERT INTO auth_logins VALUES ('126','::1','reyhcreator.97@gmail.com','8','2025-10-20 21:07:38','1');
INSERT INTO auth_logins VALUES ('127','::1','uci@gmail.com','10','2025-10-20 21:08:06','1');
INSERT INTO auth_logins VALUES ('128','::1','uci',NULL,'2025-10-21 07:06:26','0');
INSERT INTO auth_logins VALUES ('129','::1','reyhcreator.97@gmail.com',NULL,'2025-10-21 07:06:37','0');
INSERT INTO auth_logins VALUES ('130','::1','reyhcreator.97@gmail.com','8','2025-10-21 07:06:47','1');
INSERT INTO auth_logins VALUES ('131','::1','reyhcreator.97@gmail.com','8','2025-10-21 07:08:38','1');
INSERT INTO auth_logins VALUES ('132','::1','uci@gmail.com','10','2025-10-21 07:09:14','1');
INSERT INTO auth_logins VALUES ('133','::1','uci@gmail.com','10','2025-10-21 19:56:05','1');
INSERT INTO auth_logins VALUES ('134','::1','uci',NULL,'2025-10-21 20:10:27','0');
INSERT INTO auth_logins VALUES ('135','::1','uci',NULL,'2025-10-21 20:10:34','0');
INSERT INTO auth_logins VALUES ('136','::1','uci',NULL,'2025-10-21 20:10:43','0');
INSERT INTO auth_logins VALUES ('137','::1','reyhcreator.97@gmail.com','8','2025-10-21 20:13:27','1');
INSERT INTO auth_logins VALUES ('138','::1','uci@gmail.com','10','2025-10-21 20:13:44','1');
INSERT INTO auth_logins VALUES ('139','::1','uci',NULL,'2025-10-21 20:14:17','0');
INSERT INTO auth_logins VALUES ('140','::1','reyhcreator.97@gmail.com','8','2025-10-21 20:19:25','1');
INSERT INTO auth_logins VALUES ('141','::1','uci@gmail.com','10','2025-10-21 20:20:01','1');
INSERT INTO auth_logins VALUES ('142','::1','uci@gmail.com','10','2025-10-21 20:26:00','1');
INSERT INTO auth_logins VALUES ('143','::1','uci',NULL,'2025-10-21 20:26:28','0');
INSERT INTO auth_logins VALUES ('144','::1','uci@gmail.com','10','2025-10-21 20:33:38','1');
INSERT INTO auth_logins VALUES ('145','::1','reyhcreator.97@gmail.com','8','2025-10-21 22:48:53','1');
INSERT INTO auth_logins VALUES ('146','::1','uci@gmail.com','10','2025-10-21 22:49:06','1');
INSERT INTO auth_logins VALUES ('147','::1','uci@gmail.com','10','2025-10-21 23:11:57','1');
INSERT INTO auth_logins VALUES ('148','::1','uci@gmail.com','10','2025-10-21 23:12:11','1');
INSERT INTO auth_logins VALUES ('149','::1','uci',NULL,'2025-10-22 11:03:28','0');
INSERT INTO auth_logins VALUES ('150','::1','uci',NULL,'2025-10-22 11:03:37','0');
INSERT INTO auth_logins VALUES ('151','::1','uci@gmail.com','10','2025-10-22 11:03:44','1');
INSERT INTO auth_logins VALUES ('152','::1','reyhcreator.97@gmail.com','8','2025-10-22 11:55:02','1');
INSERT INTO auth_logins VALUES ('153','::1','uci',NULL,'2025-10-22 18:21:21','0');
INSERT INTO auth_logins VALUES ('154','::1','uci',NULL,'2025-10-22 18:21:33','0');
INSERT INTO auth_logins VALUES ('155','::1','uci',NULL,'2025-10-22 18:21:45','0');
INSERT INTO auth_logins VALUES ('156','::1','uci@gmail.com','10','2025-10-22 18:21:53','1');
INSERT INTO auth_logins VALUES ('157','::1','reyhcreator.97@gmail.com','8','2025-10-22 22:57:49','1');
INSERT INTO auth_logins VALUES ('158','::1','reyhcreator.97@gmail.com','8','2025-10-23 06:52:12','1');
INSERT INTO auth_logins VALUES ('159','::1','uci',NULL,'2025-10-23 07:16:53','0');
INSERT INTO auth_logins VALUES ('160','::1','uci',NULL,'2025-10-23 07:17:07','0');
INSERT INTO auth_logins VALUES ('161','::1','uci@gmail.com','10','2025-10-23 07:17:14','1');
INSERT INTO auth_logins VALUES ('162','::1','uci',NULL,'2025-10-23 20:18:25','0');
INSERT INTO auth_logins VALUES ('163','::1','uci',NULL,'2025-10-23 20:18:31','0');
INSERT INTO auth_logins VALUES ('164','::1','uci',NULL,'2025-10-23 20:18:40','0');
INSERT INTO auth_logins VALUES ('165','::1','uci@gmail.com','10','2025-10-23 20:18:47','1');
INSERT INTO auth_logins VALUES ('166','::1','reyhcreator.97@gmail.com','8','2025-10-23 21:19:08','1');
INSERT INTO auth_logins VALUES ('167','::1','reyhcreator.97@gmail.com','8','2025-10-24 00:22:44','1');
INSERT INTO auth_logins VALUES ('168','::1','uci@gmail.com','10','2025-10-24 06:36:28','1');
INSERT INTO auth_logins VALUES ('169','::1','reyhcreator.97@gmail.com','8','2025-10-24 06:37:35','1');
INSERT INTO auth_logins VALUES ('170','::1','uci@gmail.com','10','2025-10-24 21:16:51','1');
INSERT INTO auth_logins VALUES ('171','::1','reyhcreator.97@gmail.com','8','2025-10-24 21:18:09','1');
INSERT INTO auth_logins VALUES ('172','::1','uci@gmail.com','10','2025-10-26 14:40:56','1');
INSERT INTO auth_logins VALUES ('173','::1','uci@gmail.com','10','2025-10-26 18:07:22','1');
INSERT INTO auth_logins VALUES ('174','::1','reyhcreator.97@gmail.com','8','2025-10-26 18:15:41','1');
INSERT INTO auth_logins VALUES ('175','::1','uci@gmail.com','10','2025-10-26 21:25:21','1');
INSERT INTO auth_logins VALUES ('176','::1','reyhcreator.97@gmail.com','8','2025-10-26 21:26:50','1');
INSERT INTO auth_logins VALUES ('177','::1','tes10',NULL,'2025-10-27 20:33:56','0');
INSERT INTO auth_logins VALUES ('178','::1','tes10',NULL,'2025-10-27 20:34:04','0');
INSERT INTO auth_logins VALUES ('179','::1','reyhcreator.97@gmail.com','8','2025-10-27 20:34:16','1');
INSERT INTO auth_logins VALUES ('180','::1','reyhcreator.97@gmail.com','8','2025-10-27 20:34:32','1');
INSERT INTO auth_logins VALUES ('181','::1','tes10',NULL,'2025-10-27 20:35:47','0');
INSERT INTO auth_logins VALUES ('182','::1','tes10',NULL,'2025-10-27 20:36:14','0');
INSERT INTO auth_logins VALUES ('183','::1','tes10',NULL,'2025-10-27 20:36:21','0');
INSERT INTO auth_logins VALUES ('184','::1','tes10',NULL,'2025-10-27 20:36:30','0');
INSERT INTO auth_logins VALUES ('185','::1','tes10@gmail.com','25','2025-10-27 20:36:45','1');
INSERT INTO auth_logins VALUES ('186','::1','uci',NULL,'2025-10-27 20:39:30','0');
INSERT INTO auth_logins VALUES ('187','::1','uci',NULL,'2025-10-27 20:39:36','0');
INSERT INTO auth_logins VALUES ('188','::1','uci@gmail.com','10','2025-10-27 20:39:41','1');
INSERT INTO auth_logins VALUES ('189','::1','uci@gmail.com','10','2025-10-27 20:51:42','1');
INSERT INTO auth_logins VALUES ('190','::1','reyhcreator.97@gmail.com','8','2025-10-27 21:11:33','1');
INSERT INTO auth_logins VALUES ('191','::1','uci@gmail.com','10','2025-10-27 21:12:16','1');
INSERT INTO auth_logins VALUES ('192','::1','reyhcreator.97@gmail.com','8','2025-10-27 21:13:52','1');
INSERT INTO auth_logins VALUES ('193','::1','reyhcreator.97@gmail.com','8','2025-10-27 21:14:11','1');
INSERT INTO auth_logins VALUES ('194','::1','reyhcreator.97@gmail.com','8','2025-10-27 21:14:53','1');
INSERT INTO auth_logins VALUES ('195','::1','uci',NULL,'2025-10-27 21:15:30','0');
INSERT INTO auth_logins VALUES ('196','::1','uci@gmail.com','10','2025-10-27 21:15:38','1');
INSERT INTO auth_logins VALUES ('197','::1','reyhcreator.97@gmail.com','8','2025-10-27 21:16:16','1');
INSERT INTO auth_logins VALUES ('198','::1','reyhcreator.97@gmail.com','8','2025-10-27 21:20:40','1');
INSERT INTO auth_logins VALUES ('199','::1','reyhcreator.97@gmail.com','8','2025-10-27 21:21:39','1');
INSERT INTO auth_logins VALUES ('200','::1','uci@gmail.com','10','2025-10-27 21:27:08','1');
INSERT INTO auth_logins VALUES ('201','::1','uci@gmail.com','10','2025-10-27 21:27:43','1');
INSERT INTO auth_logins VALUES ('202','::1','reyhcreator.97@gmail.com','8','2025-10-27 21:30:41','1');
INSERT INTO auth_logins VALUES ('203','::1','uci',NULL,'2025-10-27 21:33:34','0');
INSERT INTO auth_logins VALUES ('204','::1','uci@gmail.com','10','2025-10-27 21:33:43','1');
INSERT INTO auth_logins VALUES ('205','::1','uci@gmail.com','10','2025-10-27 21:38:27','1');
INSERT INTO auth_logins VALUES ('206','::1','uci@gmail.com','10','2025-10-27 22:10:43','1');
INSERT INTO auth_logins VALUES ('207','::1','uci@gmail.com','10','2025-10-27 22:47:21','1');
INSERT INTO auth_logins VALUES ('208','::1','reyhcreator.97@gmail.com','8','2025-10-27 22:47:42','1');
INSERT INTO auth_logins VALUES ('209','::1','reyhcreator.97@gmail.com','8','2025-10-28 00:03:39','1');
INSERT INTO auth_logins VALUES ('210','::1','reyhcreator.97@gmail.com','8','2025-10-28 00:04:09','1');
INSERT INTO auth_logins VALUES ('211','::1','reyhcreator.97@gmail.com','8','2025-10-28 00:04:40','1');
INSERT INTO auth_logins VALUES ('212','::1','reyhcreator.97@gmail.com','8','2025-10-28 00:05:58','1');
INSERT INTO auth_logins VALUES ('213','::1','uci@gmail.com','10','2025-10-28 00:06:24','1');
INSERT INTO auth_logins VALUES ('214','::1','reyhcreator.97@gmail.com','8','2025-10-28 07:14:39','1');
INSERT INTO auth_logins VALUES ('215','::1','reyhcreator.97@gmail.com','8','2025-10-28 07:17:07','1');
INSERT INTO auth_logins VALUES ('216','::1','reyhcreator.97@gmail.com','8','2025-10-28 07:18:37','1');
INSERT INTO auth_logins VALUES ('217','::1','reyhcreator.97@gmail.com','8','2025-10-28 07:20:50','1');
INSERT INTO auth_logins VALUES ('218','::1','uci',NULL,'2025-10-28 07:21:05','0');
INSERT INTO auth_logins VALUES ('219','::1','reyhcreator.97@gmail.com','8','2025-10-28 20:04:29','1');
INSERT INTO auth_logins VALUES ('220','::1','uci@gmail.com','10','2025-10-28 20:05:39','1');
INSERT INTO auth_logins VALUES ('221','::1','tes10',NULL,'2025-10-28 20:13:15','0');
INSERT INTO auth_logins VALUES ('222','::1','tes10@gmail.com','25','2025-10-28 20:13:41','1');
INSERT INTO auth_logins VALUES ('223','::1','tes10@gmail.com','25','2025-10-28 20:13:53','1');
INSERT INTO auth_logins VALUES ('224','::1','uci',NULL,'2025-10-28 20:14:22','0');
INSERT INTO auth_logins VALUES ('225','::1','uci@gmail.com','10','2025-10-28 20:14:30','1');
INSERT INTO auth_logins VALUES ('226','::1','tes10@gmail.com','25','2025-10-28 20:15:16','1');
INSERT INTO auth_logins VALUES ('227','::1','reyhcreator.97@gmail.com','8','2025-10-28 20:15:38','1');
INSERT INTO auth_logins VALUES ('228','::1','adrian',NULL,'2025-10-28 20:18:28','0');
INSERT INTO auth_logins VALUES ('229','::1','adrian',NULL,'2025-10-28 20:18:57','0');
INSERT INTO auth_logins VALUES ('230','::1','septi',NULL,'2025-10-28 20:53:50','0');
INSERT INTO auth_logins VALUES ('231','::1','septi',NULL,'2025-10-28 20:54:02','0');
INSERT INTO auth_logins VALUES ('232','::1','amel@gmail.com','33','2025-10-28 21:01:28','1');
INSERT INTO auth_logins VALUES ('233','::1','amel@gmail.com','33','2025-10-28 21:01:43','1');
INSERT INTO auth_logins VALUES ('234','::1','angga@gmail.com','34','2025-10-28 21:12:13','1');
INSERT INTO auth_logins VALUES ('235','::1','indro@gmail.com','35','2025-10-28 21:21:25','1');
INSERT INTO auth_logins VALUES ('236','::1','uci@gmail.com','10','2025-10-28 21:58:55','1');
INSERT INTO auth_logins VALUES ('237','::1','uci@gmail.com','10','2025-10-28 22:08:52','1');
INSERT INTO auth_logins VALUES ('238','::1','uci@gmail.com','10','2025-10-28 22:09:21','1');
INSERT INTO auth_logins VALUES ('239','::1','uci@gmail.com','10','2025-10-28 22:09:36','1');
INSERT INTO auth_logins VALUES ('240','::1','uci@gmail.com','10','2025-10-28 22:14:06','1');
INSERT INTO auth_logins VALUES ('241','::1','uci@gmail.com','10','2025-10-28 22:15:03','1');
INSERT INTO auth_logins VALUES ('242','::1','uci@gmail.com','10','2025-10-28 22:15:18','1');
INSERT INTO auth_logins VALUES ('243','::1','uci@gmail.com','10','2025-10-28 22:16:03','1');
INSERT INTO auth_logins VALUES ('244','::1','uci@gmail.com','10','2025-10-28 22:16:28','1');
INSERT INTO auth_logins VALUES ('245','::1','uci@gmail.com','10','2025-10-29 12:00:02','1');
INSERT INTO auth_logins VALUES ('246','::1','reyhcreator.97@gmail.com','8','2025-11-04 23:11:57','1');
INSERT INTO auth_logins VALUES ('247','::1','uci@gmail.com','10','2025-11-04 23:12:34','1');
INSERT INTO auth_logins VALUES ('248','::1','uci@gmail.com','10','2025-11-05 21:18:51','1');
INSERT INTO auth_logins VALUES ('249','::1','cicilan@gmail.com','78','2025-11-05 22:36:35','1');
INSERT INTO auth_logins VALUES ('250','::1','reyhcreator.97@gmail.com','8','2025-11-05 22:54:41','1');
INSERT INTO auth_logins VALUES ('251','::1','reyhcreator.97@gmail.com','8','2025-11-07 20:18:54','1');
INSERT INTO auth_logins VALUES ('252','::1','cicilan@gmail.com','78','2025-11-07 20:19:41','1');
INSERT INTO auth_logins VALUES ('253','::1','uci',NULL,'2025-11-07 23:47:13','0');
INSERT INTO auth_logins VALUES ('254','::1','uci@gmail.com','10','2025-11-07 23:47:22','1');
INSERT INTO auth_logins VALUES ('255','::1','uci',NULL,'2025-11-08 01:57:58','0');
INSERT INTO auth_logins VALUES ('256','::1','uci@gmail.com','10','2025-11-08 01:58:04','1');
INSERT INTO auth_logins VALUES ('257','::1','reyhcreator.97@gmail.com','8','2025-11-09 13:18:35','1');
INSERT INTO auth_logins VALUES ('258','::1','reyhcreator.97@gmail.com','8','2025-11-09 18:28:11','1');
INSERT INTO auth_logins VALUES ('259','::1','lowtiket1@gmail.com','97','2025-11-09 20:59:31','1');
INSERT INTO auth_logins VALUES ('260','::1','jajang@gmail.com','105','2025-11-09 23:22:22','1');
INSERT INTO auth_logins VALUES ('261','::1','arif@gmail.com','106','2025-11-09 23:24:19','1');


CREATE TABLE `auth_permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

INSERT INTO auth_permissions VALUES ('1','manage-users','Manage All User');
INSERT INTO auth_permissions VALUES ('2','manage-profile','Manage User Profile');


CREATE TABLE `auth_reset_attempts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;



CREATE TABLE `auth_tokens` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int unsigned NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_tokens_user_id_foreign` (`user_id`),
  KEY `selector` (`selector`),
  CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;



CREATE TABLE `auth_users_permissions` (
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `permission_id` int unsigned NOT NULL DEFAULT '0',
  KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  KEY `user_id_permission_id` (`user_id`,`permission_id`),
  CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;



CREATE TABLE `coupons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `jenis` enum('percent','fixed') DEFAULT 'percent',
  `nilai` decimal(10,2) DEFAULT '0.00',
  `keterangan` varchar(255) DEFAULT NULL,
  `berlaku_mulai` date DEFAULT NULL,
  `berlaku_sampai` date DEFAULT NULL,
  `max_usage` int DEFAULT '0',
  `used_count` int DEFAULT '0',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO coupons VALUES ('2','TASYA1','percent','10.00',NULL,'2025-11-08','2025-11-15','0','0','active','2025-11-09 13:21:26');
INSERT INTO coupons VALUES ('4','UCI','fixed','300.00',NULL,'2025-11-09','2025-11-29','0','25','active','2025-11-09 18:30:30');


CREATE TABLE `gold_price` (
  `id` int NOT NULL AUTO_INCREMENT,
  `berat` varchar(10) DEFAULT '1 gr',
  `harga_dasar` varchar(50) DEFAULT NULL,
  `harga_pph` varchar(50) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO gold_price VALUES ('1','1 gr','2,296,000','2,301,740','2025-11-08 02:13:30','2025-11-08 02:13:30');
INSERT INTO gold_price VALUES ('2','1 gr','2,299,000','2,304,748','2025-11-09 22:12:40','2025-11-09 22:12:40');


CREATE TABLE `investasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `akun_id` int DEFAULT NULL,
  `jumlah` decimal(20,2) NOT NULL DEFAULT '0.00',
  `nilai_sekarang` decimal(20,2) NOT NULL DEFAULT '0.00',
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `status` enum('aktif','selesai') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `kekayaan_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `kategori` enum('uang','utang','piutang','aset','investasi') COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` decimal(16,2) DEFAULT '0.00',
  `tanggal` date DEFAULT NULL,
  `saldo_terkini` decimal(18,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_kategori` (`user_id`,`kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO kekayaan_items VALUES ('88','10','uang','Cash','50000.00',NULL,'35000.00','2025-10-18 06:23:28','2025-10-26 18:10:53');
INSERT INTO kekayaan_items VALUES ('93','10','uang','BRI','10000.00',NULL,'40000.00','2025-10-18 06:25:38','2025-10-26 18:10:53');


CREATE TABLE `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `ip_address` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=278 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO logs VALUES ('225','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 21:58:55');
INSERT INTO logs VALUES ('226','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-28 22:08:46');
INSERT INTO logs VALUES ('227','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 22:08:52');
INSERT INTO logs VALUES ('228','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-28 22:09:13');
INSERT INTO logs VALUES ('229','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 22:09:21');
INSERT INTO logs VALUES ('230','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-28 22:09:30');
INSERT INTO logs VALUES ('231','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 22:09:36');
INSERT INTO logs VALUES ('232','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-28 22:13:52');
INSERT INTO logs VALUES ('233','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 22:14:06');
INSERT INTO logs VALUES ('234','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-28 22:14:48');
INSERT INTO logs VALUES ('235','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 22:15:03');
INSERT INTO logs VALUES ('236','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-28 22:15:10');
INSERT INTO logs VALUES ('237','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 22:15:18');
INSERT INTO logs VALUES ('238','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-28 22:15:57');
INSERT INTO logs VALUES ('239','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 22:16:03');
INSERT INTO logs VALUES ('240','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-28 22:16:23');
INSERT INTO logs VALUES ('241','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-28 22:16:28');
INSERT INTO logs VALUES ('242','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-10-29 12:00:02');
INSERT INTO logs VALUES ('243','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-10-29 13:53:19');
INSERT INTO logs VALUES ('244','8','Admin','Login','User reycreator berhasil login.','::1','Chrome on Windows','2025-11-04 23:11:57');
INSERT INTO logs VALUES ('245','8','Admin','Logout','User reycreator melakukan logout.','::1','Chrome on Windows','2025-11-04 23:12:28');
INSERT INTO logs VALUES ('246','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-11-04 23:12:34');
INSERT INTO logs VALUES ('247','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-11-05 21:18:51');
INSERT INTO logs VALUES ('248','10','User','Logout','User uci melakukan logout.','::1','Chrome on Windows','2025-11-05 22:35:21');
INSERT INTO logs VALUES ('249','78','User','Login','User cicil berhasil login.','::1','Chrome on Windows','2025-11-05 22:36:35');
INSERT INTO logs VALUES ('250','8','Admin','Login','User reycreator berhasil login.','::1','Chrome on Windows','2025-11-05 22:54:41');
INSERT INTO logs VALUES ('251','8','Admin','Subcription Update','Admin men-update subscription user ID: 63','::1','Chrome on Windows','2025-11-05 23:01:33');
INSERT INTO logs VALUES ('252','8','Admin','Subcription Update','Admin men-update subscription user ID: 63','::1','Chrome on Windows','2025-11-05 23:01:59');
INSERT INTO logs VALUES ('253','8','Admin','Subcription Update','Admin men-update subscription user ID: 63','::1','Chrome on Windows','2025-11-05 23:58:46');
INSERT INTO logs VALUES ('254','8','Admin','Login','User reycreator berhasil login.','::1','Chrome on Windows','2025-11-07 20:18:54');
INSERT INTO logs VALUES ('255','8','Admin','Reset Password','Admin men-reset password user dengan ID: 78 dengan password : user8155','::1','Chrome on Windows','2025-11-07 20:19:25');
INSERT INTO logs VALUES ('256','78','User','Login','User cicil berhasil login.','::1','Chrome on Windows','2025-11-07 20:19:41');
INSERT INTO logs VALUES ('257','8','Admin','Subcription Update','Admin men-update subscription user ID: 65','::1','Chrome on Windows','2025-11-07 20:47:28');
INSERT INTO logs VALUES ('258','8','Admin','Subcription Update','Admin men-update subscription user ID: 65','::1','Chrome on Windows','2025-11-07 20:48:33');
INSERT INTO logs VALUES ('259','8','Admin','Subcription Update','Admin men-update subscription user ID: 65','::1','Chrome on Windows','2025-11-07 21:03:51');
INSERT INTO logs VALUES ('260','8','Admin','Subcription Update','Admin men-update subscription user ID: 67','::1','Chrome on Windows','2025-11-07 21:33:07');
INSERT INTO logs VALUES ('261','8','Admin','Subcription Update','Admin men-update subscription user ID: 67','::1','Chrome on Windows','2025-11-07 21:35:03');
INSERT INTO logs VALUES ('262','8','Admin','Subcription Update','Admin men-update subscription user ID: 67','::1','Chrome on Windows','2025-11-07 21:35:16');
INSERT INTO logs VALUES ('263','8','Admin','Subcription Update','Admin men-update subscription user ID: 67','::1','Chrome on Windows','2025-11-07 21:35:29');
INSERT INTO logs VALUES ('264','8','Admin','Subcription Update','Admin men-update subscription user ID: 68','::1','Chrome on Windows','2025-11-07 22:01:11');
INSERT INTO logs VALUES ('265','8','Admin','Subcription Update','Admin men-update subscription user ID: 68','::1','Chrome on Windows','2025-11-07 22:32:43');
INSERT INTO logs VALUES ('266','8','Admin','Subcription Update','Admin men-update subscription user ID: 68','::1','Chrome on Windows','2025-11-07 22:32:55');
INSERT INTO logs VALUES ('267','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-11-07 23:47:22');
INSERT INTO logs VALUES ('268','10','User','Login','User uci berhasil login.','::1','Chrome on Windows','2025-11-08 01:58:04');
INSERT INTO logs VALUES ('269','8','Admin','Login','User reycreator berhasil login.','::1','Chrome on Windows','2025-11-09 13:18:35');
INSERT INTO logs VALUES ('270','8','Admin','Login','User reycreator berhasil login.','::1','Chrome on Windows','2025-11-09 18:28:11');
INSERT INTO logs VALUES ('271','8','Admin','Reset Password','Admin men-reset password user dengan ID: 97 dengan password : user4581','::1','Chrome on Windows','2025-11-09 20:59:10');
INSERT INTO logs VALUES ('272','97','User','Login','User lowtiket1 berhasil login.','::1','Chrome on Windows','2025-11-09 20:59:31');
INSERT INTO logs VALUES ('273','8','Admin','Reset Password','Admin men-reset password user dengan ID: 105 dengan password : user9205','::1','Chrome on Windows','2025-11-09 23:21:59');
INSERT INTO logs VALUES ('274','97','User','Logout','User lowtiket1 melakukan logout.','::1','Chrome on Windows','2025-11-09 23:22:10');
INSERT INTO logs VALUES ('275','105','User','Login','User jajang berhasil login.','::1','Chrome on Windows','2025-11-09 23:22:22');
INSERT INTO logs VALUES ('276','105','User','Logout','User jajang melakukan logout.','::1','Chrome on Windows','2025-11-09 23:24:09');
INSERT INTO logs VALUES ('277','106','User','Login','User arif berhasil login.','::1','Chrome on Windows','2025-11-09 23:24:19');


CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

INSERT INTO migrations VALUES ('1','2017-11-20-223112','Myth\\Auth\\Database\\Migrations\\CreateAuthTables','default','Myth\\Auth','1758698489','1');


CREATE TABLE `piutang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `akun_id` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `jumlah` decimal(15,2) NOT NULL,
  `dibayar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('belum','lunas') COLLATE utf8mb4_general_ci DEFAULT 'belum',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `currency` varchar(10) COLLATE utf8mb4_general_ci DEFAULT '¥',
  `price_monthly` decimal(16,2) DEFAULT '0.00',
  `price_yearly` decimal(16,2) DEFAULT '0.00',
  `backup_schedule` enum('daily','weekly','monthly') COLLATE utf8mb4_general_ci DEFAULT 'weekly',
  `contact_whatsapp` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO settings VALUES ('1','¥','600.00','4000.00','daily','628557663472','2025-10-19 00:19:21','2025-10-22 22:58:16');


CREATE TABLE `subscriptions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `plan_type` enum('monthly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `status` enum('pending','active','expired','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_subscriptions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO subscriptions VALUES ('11','10','monthly','active','2025-10-24','2025-11-24','2025-10-24 07:08:41','2025-10-24 21:22:12');
INSERT INTO subscriptions VALUES ('12','10','yearly','active','2025-10-26','2026-10-26','2025-10-26 18:14:27','2025-10-26 18:16:06');
INSERT INTO subscriptions VALUES ('14','10','yearly','pending','2025-10-28','2026-10-28','2025-10-28 20:09:19','2025-10-28 20:09:19');
INSERT INTO subscriptions VALUES ('46','62','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:09:37','2025-11-05 00:09:37');
INSERT INTO subscriptions VALUES ('47','63','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:10:41','2025-11-05 00:10:41');
INSERT INTO subscriptions VALUES ('48','64','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:12:14','2025-11-05 00:12:14');
INSERT INTO subscriptions VALUES ('49','65','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:14:05','2025-11-05 00:14:05');
INSERT INTO subscriptions VALUES ('50','66','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:16:58','2025-11-05 00:16:58');
INSERT INTO subscriptions VALUES ('51','67','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:17:39','2025-11-05 00:17:39');
INSERT INTO subscriptions VALUES ('52','68','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:35:29','2025-11-05 00:35:29');
INSERT INTO subscriptions VALUES ('53','69','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:42:14','2025-11-05 00:42:14');
INSERT INTO subscriptions VALUES ('54','70','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:44:00','2025-11-05 00:44:00');
INSERT INTO subscriptions VALUES ('55','71','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:44:52','2025-11-05 00:44:52');
INSERT INTO subscriptions VALUES ('56','72','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:48:52','2025-11-05 00:48:52');
INSERT INTO subscriptions VALUES ('57','73','monthly','pending','2025-11-05','2026-11-05','2025-11-05 00:52:30','2025-11-05 00:52:30');
INSERT INTO subscriptions VALUES ('58','74','monthly','pending','2025-11-05','2026-11-05','2025-11-05 01:02:21','2025-11-05 01:02:21');
INSERT INTO subscriptions VALUES ('59','75','monthly','pending','2025-11-05','2026-11-05','2025-11-05 01:10:41','2025-11-05 01:10:41');
INSERT INTO subscriptions VALUES ('60','76','monthly','pending','2025-11-05','2026-11-05','2025-11-05 01:19:52','2025-11-05 01:19:52');
INSERT INTO subscriptions VALUES ('61','77','monthly','pending','2025-11-05','2026-11-05','2025-11-05 01:20:35','2025-11-05 01:20:35');
INSERT INTO subscriptions VALUES ('68','78','yearly','active','2025-11-07','2026-11-07','2025-11-07 22:00:35','2025-11-07 22:34:09');
INSERT INTO subscriptions VALUES ('69','79','monthly','pending','2025-11-09','2026-11-09','2025-11-09 19:03:28','2025-11-09 19:03:28');
INSERT INTO subscriptions VALUES ('70','80','monthly','pending','2025-11-09','2026-11-09','2025-11-09 19:07:51','2025-11-09 19:07:51');
INSERT INTO subscriptions VALUES ('71','81','monthly','pending','2025-11-09','2026-11-09','2025-11-09 19:20:37','2025-11-09 19:20:37');
INSERT INTO subscriptions VALUES ('72','82','monthly','pending','2025-11-09','2026-11-09','2025-11-09 19:28:27','2025-11-09 19:28:27');
INSERT INTO subscriptions VALUES ('73','83','monthly','pending','2025-11-09','2026-11-09','2025-11-09 19:40:13','2025-11-09 19:40:13');
INSERT INTO subscriptions VALUES ('74','84','monthly','pending','2025-11-09','2026-11-09','2025-11-09 19:43:03','2025-11-09 19:43:03');
INSERT INTO subscriptions VALUES ('75','85','monthly','pending','2025-11-09','2026-11-09','2025-11-09 19:52:07','2025-11-09 19:52:07');
INSERT INTO subscriptions VALUES ('76','86','monthly','pending','2025-11-09','2026-11-09','2025-11-09 19:57:46','2025-11-09 19:57:46');
INSERT INTO subscriptions VALUES ('77','87','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:04:49','2025-11-09 20:04:49');
INSERT INTO subscriptions VALUES ('78','88','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:15:15','2025-11-09 20:15:15');
INSERT INTO subscriptions VALUES ('79','89','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:17:53','2025-11-09 20:17:53');
INSERT INTO subscriptions VALUES ('80','90','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:18:49','2025-11-09 20:18:49');
INSERT INTO subscriptions VALUES ('81','91','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:23:04','2025-11-09 20:23:04');
INSERT INTO subscriptions VALUES ('82','92','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:53:09','2025-11-09 20:53:09');
INSERT INTO subscriptions VALUES ('83','93','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:54:05','2025-11-09 20:54:05');
INSERT INTO subscriptions VALUES ('84','94','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:55:40','2025-11-09 20:55:40');
INSERT INTO subscriptions VALUES ('85','95','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:56:39','2025-11-09 20:56:39');
INSERT INTO subscriptions VALUES ('86','96','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:57:44','2025-11-09 20:57:44');
INSERT INTO subscriptions VALUES ('87','97','monthly','pending','2025-11-09','2026-11-09','2025-11-09 20:58:36','2025-11-09 20:58:36');
INSERT INTO subscriptions VALUES ('88','98','monthly','pending','2025-11-09','2026-11-09','2025-11-09 22:04:54','2025-11-09 22:04:54');
INSERT INTO subscriptions VALUES ('89','99','monthly','pending','2025-11-09','2026-11-09','2025-11-09 22:05:42','2025-11-09 22:05:42');
INSERT INTO subscriptions VALUES ('90','100','monthly','pending','2025-11-09','2026-11-09','2025-11-09 22:06:59','2025-11-09 22:06:59');
INSERT INTO subscriptions VALUES ('91','101','monthly','pending','2025-11-09','2026-11-09','2025-11-09 22:08:34','2025-11-09 22:08:34');
INSERT INTO subscriptions VALUES ('92','102','monthly','pending','2025-11-09','2026-11-09','2025-11-09 22:12:02','2025-11-09 22:12:02');
INSERT INTO subscriptions VALUES ('93','103','monthly','pending','2025-11-09','2026-11-09','2025-11-09 22:33:37','2025-11-09 22:33:37');
INSERT INTO subscriptions VALUES ('94','104','monthly','pending','2025-11-09','2026-11-09','2025-11-09 22:47:53','2025-11-09 22:47:53');
INSERT INTO subscriptions VALUES ('95','105','monthly','pending','2025-11-09','2026-11-09','2025-11-09 23:21:34','2025-11-09 23:21:34');
INSERT INTO subscriptions VALUES ('96','106','monthly','active','2025-11-09','2025-12-09','2025-11-09 23:23:42','2025-11-09 23:46:31');


CREATE TABLE `transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('in','out','transfer') COLLATE utf8mb4_general_ci NOT NULL,
  `akun` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sumber_id` int DEFAULT NULL,
  `tujuan_id` int DEFAULT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('expired','pending','active','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `jumlah` decimal(14,2) NOT NULL DEFAULT '0.00',
  `is_initial` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_tanggal` (`user_id`,`tanggal`)
) ENGINE=InnoDB AUTO_INCREMENT=334 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO transaksi VALUES ('198','10','2025-10-18','in',NULL,'88',NULL,'Uang','Modal awal dari Uang','pending','50000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('200','10','2025-10-18','in',NULL,'90',NULL,'Piutang','Modal awal dari Piutang','pending','5000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('201','10','2025-10-18','in',NULL,'91',NULL,'Aset','Modal awal dari Aset','pending','1000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('202','10','2025-10-18','in',NULL,'92',NULL,'Investasi','Modal awal dari Investasi','pending','1000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('203','10','2025-10-18','in',NULL,'93',NULL,'Modal Awal','Saldo awal akun baru','pending','10000.00','0','2025-10-18 15:25:38','2025-10-18 15:25:38');
INSERT INTO transaksi VALUES ('205','10','2025-10-18','in',NULL,'93','88','Transfer','Transfer masuk','pending','5000.00','0','2025-10-18 15:26:18','2025-10-18 15:26:18');
INSERT INTO transaksi VALUES ('207','10','2025-10-18','in',NULL,'88',NULL,'investasi','Penjualan investasi: Kartu Pokemon','pending','1500.00','0','2025-10-18 15:28:01','2025-10-18 15:28:01');
INSERT INTO transaksi VALUES ('208','10','2025-10-18','in',NULL,'88',NULL,'investasi','Penjualan investasi: Saham','pending','2000.00','0','2025-10-18 15:28:39','2025-10-18 15:28:39');
INSERT INTO transaksi VALUES ('210','10','2025-10-18','in',NULL,'88',NULL,'Aset','Penjualan aset: A','pending','15000.00','0','2025-10-18 15:29:30','2025-10-18 15:29:30');
INSERT INTO transaksi VALUES ('211','10','2025-10-18','in',NULL,'93',NULL,'Aset','Penjualan aset: HP','pending','5000.00','0','2025-10-18 15:29:42','2025-10-18 15:29:42');
INSERT INTO transaksi VALUES ('212','10','2025-10-18','in',NULL,'88',NULL,'Utang','Menerima Utang Bank BCA','pending','10000.00','0','2025-10-18 15:30:21','2025-10-18 15:30:21');
INSERT INTO transaksi VALUES ('215','10','2025-10-18','in',NULL,'88',NULL,'Piutang','Penerimaan piutang: Temen','pending','5000.00','0','2025-10-18 15:30:56','2025-10-18 15:30:56');
INSERT INTO transaksi VALUES ('217','10','2025-10-18','in',NULL,'93',NULL,'Piutang','Penerimaan piutang: Bank BCA','pending','10000.00','0','2025-10-18 15:31:17','2025-10-18 15:31:17');
INSERT INTO transaksi VALUES ('219','10','2025-10-18','in',NULL,'93','88','Transfer','Transfer masuk','pending','10000.00','0','2025-10-18 15:40:33','2025-10-18 15:40:33');
INSERT INTO transaksi VALUES ('231','10','2025-10-24','in',NULL,'95',NULL,'Aset','Modal awal dari Aset','pending','1000.00','0','2025-10-24 00:08:44','2025-10-24 00:08:44');
INSERT INTO transaksi VALUES ('232','10','2025-10-24','in',NULL,'96',NULL,'Investasi','Modal awal dari Investasi','pending','1000.00','0','2025-10-24 00:08:44','2025-10-24 00:08:44');
INSERT INTO transaksi VALUES ('233','10','2025-10-24','in',NULL,'88',NULL,'Aset','Penjualan aset: B','pending','2000.00','0','2025-10-24 00:09:32','2025-10-24 00:09:32');
INSERT INTO transaksi VALUES ('234','10','2025-10-24','in',NULL,'93',NULL,'investasi','Penjualan investasi: A','pending','500.00','0','2025-10-24 00:10:56','2025-10-24 00:10:56');
INSERT INTO transaksi VALUES ('240','10','2025-10-26','out',NULL,'88','93','Transfer','Transfer keluar','pending','5000.00','0','2025-10-26 18:10:27','2025-10-26 18:10:27');
INSERT INTO transaksi VALUES ('241','10','2025-10-26','in',NULL,'93','88','Transfer','Transfer masuk','pending','5000.00','0','2025-10-26 18:10:27','2025-10-26 18:10:27');
INSERT INTO transaksi VALUES ('243','10','2025-10-26','in',NULL,'88','93','Transfer','Transfer masuk','pending','5500.00','0','2025-10-26 18:10:53','2025-10-26 18:10:53');
INSERT INTO transaksi VALUES ('244','10','2025-10-26','out',NULL,NULL,NULL,'subscription','Yearly Plan Subscription','active','4000.00','0','2025-10-26 18:14:27','2025-10-26 18:16:06');
INSERT INTO transaksi VALUES ('246','10','2025-10-28','out',NULL,NULL,NULL,'subscription','Yearly Plan Subscription','pending','4000.00','0','2025-10-28 20:09:19','2025-10-28 20:09:19');
INSERT INTO transaksi VALUES ('277','62','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:09:37','2025-11-05 00:09:37');
INSERT INTO transaksi VALUES ('278','63','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:10:41','2025-11-05 00:10:41');
INSERT INTO transaksi VALUES ('279','64','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:12:14','2025-11-05 00:12:14');
INSERT INTO transaksi VALUES ('280','65','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:14:05','2025-11-05 00:14:05');
INSERT INTO transaksi VALUES ('281','66','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:16:58','2025-11-05 00:16:58');
INSERT INTO transaksi VALUES ('282','67','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:17:39','2025-11-05 00:17:39');
INSERT INTO transaksi VALUES ('283','68','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:35:29','2025-11-05 00:35:29');
INSERT INTO transaksi VALUES ('284','69','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:42:14','2025-11-05 00:42:14');
INSERT INTO transaksi VALUES ('285','70','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:44:00','2025-11-05 00:44:00');
INSERT INTO transaksi VALUES ('286','71','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:44:52','2025-11-05 00:44:52');
INSERT INTO transaksi VALUES ('287','72','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:48:52','2025-11-05 00:48:52');
INSERT INTO transaksi VALUES ('288','73','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 00:52:30','2025-11-05 00:52:30');
INSERT INTO transaksi VALUES ('289','74','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 01:02:21','2025-11-05 01:02:21');
INSERT INTO transaksi VALUES ('290','75','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 01:10:41','2025-11-05 01:10:41');
INSERT INTO transaksi VALUES ('291','76','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 01:19:52','2025-11-05 01:19:52');
INSERT INTO transaksi VALUES ('292','77','2025-11-05','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-05 01:20:35','2025-11-05 01:20:35');
INSERT INTO transaksi VALUES ('304','78','2025-11-07','out',NULL,NULL,NULL,'subscription','Monthly Plan Subscription','active','600.00','0','2025-11-07 22:00:35','2025-11-07 22:00:54');
INSERT INTO transaksi VALUES ('305','78','2025-11-07','out',NULL,NULL,NULL,'subscription','Yearly Plan Subscription','active','4000.00','0','2025-11-07 22:33:25','2025-11-07 22:34:09');
INSERT INTO transaksi VALUES ('306','79','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 19:03:28','2025-11-09 19:03:28');
INSERT INTO transaksi VALUES ('307','80','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 19:07:51','2025-11-09 19:07:51');
INSERT INTO transaksi VALUES ('308','81','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 19:20:37','2025-11-09 19:20:37');
INSERT INTO transaksi VALUES ('309','82','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 19:28:27','2025-11-09 19:28:27');
INSERT INTO transaksi VALUES ('310','83','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 19:40:13','2025-11-09 19:40:13');
INSERT INTO transaksi VALUES ('311','84','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 19:43:03','2025-11-09 19:43:03');
INSERT INTO transaksi VALUES ('312','85','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 19:52:07','2025-11-09 19:52:07');
INSERT INTO transaksi VALUES ('313','86','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 19:57:46','2025-11-09 19:57:46');
INSERT INTO transaksi VALUES ('314','87','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 20:04:49','2025-11-09 20:04:49');
INSERT INTO transaksi VALUES ('315','88','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 20:15:15','2025-11-09 20:15:15');
INSERT INTO transaksi VALUES ('316','89','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 20:17:53','2025-11-09 20:17:53');
INSERT INTO transaksi VALUES ('317','90','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','0.00','0','2025-11-09 20:18:49','2025-11-09 20:18:49');
INSERT INTO transaksi VALUES ('318','91','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','32490.00','0','2025-11-09 20:23:04','2025-11-09 20:23:04');
INSERT INTO transaksi VALUES ('319','92','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','0.00','0','2025-11-09 20:53:09','2025-11-09 20:53:09');
INSERT INTO transaksi VALUES ('320','93','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','0.00','0','2025-11-09 20:54:05','2025-11-09 20:54:05');
INSERT INTO transaksi VALUES ('321','94','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','0.00','0','2025-11-09 20:55:40','2025-11-09 20:55:40');
INSERT INTO transaksi VALUES ('322','95','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-09 20:56:39','2025-11-09 20:56:39');
INSERT INTO transaksi VALUES ('323','96','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','600.00','0','2025-11-09 20:57:44','2025-11-09 20:57:44');
INSERT INTO transaksi VALUES ('324','97','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','0.00','0','2025-11-09 20:58:36','2025-11-09 20:58:36');
INSERT INTO transaksi VALUES ('325','98','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','3700.00','0','2025-11-09 22:04:54','2025-11-09 22:04:54');
INSERT INTO transaksi VALUES ('326','99','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','3700.00','0','2025-11-09 22:05:42','2025-11-09 22:05:42');
INSERT INTO transaksi VALUES ('327','100','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','3700.00','0','2025-11-09 22:06:59','2025-11-09 22:06:59');
INSERT INTO transaksi VALUES ('328','101','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan','pending','4000.00','0','2025-11-09 22:08:34','2025-11-09 22:08:34');
INSERT INTO transaksi VALUES ('329','102','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','3700.00','0','2025-11-09 22:12:02','2025-11-09 22:12:02');
INSERT INTO transaksi VALUES ('330','103','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','3700.00','0','2025-11-09 22:33:37','2025-11-09 22:33:37');
INSERT INTO transaksi VALUES ('331','104','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','3700.00','0','2025-11-09 22:47:53','2025-11-09 22:47:53');
INSERT INTO transaksi VALUES ('332','105','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','pending','300.00','0','2025-11-09 23:21:34','2025-11-09 23:21:34');
INSERT INTO transaksi VALUES ('333','106','2025-11-09','out',NULL,NULL,NULL,'subscription','Pembelian paket Monthly plan (Kupon: uci)','active','300.00','0','2025-11-09 23:23:42','2025-11-09 23:46:31');


CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) NOT NULL DEFAULT 'default.svg',
  `password_hash` varchar(255) NOT NULL,
  `reset_hash` varchar(255) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `activate_hash` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_setup` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb3;

INSERT INTO users VALUES ('8','reyhcreator.97@gmail.com','reycreator',NULL,'default.svg','$2y$10$zozKSMwcrNqaSFaDcbzF4uqXP/wpw0/csyAJQjxK64JqXLqQUyArS',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-09-25 04:10:31','2025-09-25 04:10:31',NULL,'0');
INSERT INTO users VALUES ('10','uci@gmail.com','uci',NULL,'default.svg','$2y$10$NscjU82Adw3YE12CQ6qxueNt6Bb1L3p401hc4iKLKeC77yg/iy5my',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-10-02 13:49:32','2025-10-21 23:11:43',NULL,'1');
INSERT INTO users VALUES ('62','anker@gmail.com','anker',NULL,'default.svg','$2y$10$5TnWWlfJ94T0vYmtqm8hRuzm85yiSChnC6teWAAZEsey55AwYw5O.',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:09:37','2025-11-05 00:09:37',NULL,'0');
INSERT INTO users VALUES ('63','ajeng@gmail.com','ajeng',NULL,'default.svg','$2y$10$xiv9F4sZMQBmlUsmButCg.DsQ5tmXmoRs7B4WWiX2/52M50Qmfeky',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:10:41','2025-11-05 00:10:41',NULL,'0');
INSERT INTO users VALUES ('64','jadi1@gmail.com','jadi1',NULL,'default.svg','$2y$10$pwC.0lWHsY0wTcSZAPY4feIC527WQ8zatRdV4C5uF/8QEA/iozjHC',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:12:14','2025-11-05 00:12:14',NULL,'0');
INSERT INTO users VALUES ('65','jadi2@gmail.com','jadi2',NULL,'default.svg','$2y$10$y8V3UxcYIBnZPcT0x2yITuwCsBsq1OxT7vdZM0Xy2y0CP1KBd/JPK',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:14:05','2025-11-05 00:14:05',NULL,'0');
INSERT INTO users VALUES ('66','cobain1@gmail.com','cobain',NULL,'default.svg','$2y$10$qY5L/dK/GDYuwqJqeAO5.uZ9CUKtnw9el4RbiEbYfgAMaeBF16DOK',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:16:58','2025-11-05 00:16:58',NULL,'0');
INSERT INTO users VALUES ('67','cobain2@gmail.com','cobain2',NULL,'default.svg','$2y$10$VE0vYvVCr9U4wIX741Asnuk9s1LAS80yLDjSSbJCwpUQCz2KYGovS',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:17:39','2025-11-05 00:17:39',NULL,'0');
INSERT INTO users VALUES ('68','arab1@gmail.com','arab',NULL,'default.svg','$2y$10$cSIbxlB7oqQkPy72wjC3ueJxM4gJ4E7PucHDtNEsfi/JGvLQiCn.2',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:35:29','2025-11-05 00:35:29',NULL,'0');
INSERT INTO users VALUES ('69','fixlahini@gmail.com','fixlah',NULL,'default.svg','$2y$10$XaJYsu3f5XlsS4tcbXBuwOBO6SHEk0HdT8wawl68nkY0sZOs41ahG',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:42:14','2025-11-05 00:42:14',NULL,'0');
INSERT INTO users VALUES ('70','harusnyafix@gmail.com','harusnyafix',NULL,'default.svg','$2y$10$rw2vf4AZ14t/DniiG51AuOzc9.W3YkXuhBN.xR/9q951z4M9JVdnC',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:44:00','2025-11-05 00:44:00',NULL,'0');
INSERT INTO users VALUES ('71','asu@gmail.com','asu',NULL,'default.svg','$2y$10$jXh6.oD6Sybv0b9vcgVBg.To3h8koW0qnD3NXIdxMpyafOHYVkhua',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:44:52','2025-11-05 00:44:52',NULL,'0');
INSERT INTO users VALUES ('72','pendi@gmial.com','pendi',NULL,'default.svg','$2y$10$FzjmRgVi80uhlBJmmI7WDO6Ze52ptIqhDnHNrpUwCo1Br3YIf6In6',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:48:52','2025-11-05 00:48:52',NULL,'0');
INSERT INTO users VALUES ('73','inibaru1@gmail.com','inibaru',NULL,'default.svg','$2y$10$LkHpmKnJgOntIfX7x/kWbeKeHQ0FQR1E/bYa7bG17x/XEjmrbxtua',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 00:52:30','2025-11-05 00:52:30',NULL,'0');
INSERT INTO users VALUES ('74','jadifix@gmail.com','fixjadiini',NULL,'default.svg','$2y$10$kdbaIVOSj4JJpE39alBLS.dQ98XOgPODpCA92yXqR.E0WGs60tgUK',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 01:02:21','2025-11-05 01:02:21',NULL,'0');
INSERT INTO users VALUES ('75','coba1@gmail.com','coba1',NULL,'default.svg','$2y$10$xMlahCTVR6Sz8tCu4FJxAeV.BMrkkLNb5urA8oixTUHjU8ioClD8O',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 01:10:41','2025-11-05 01:10:41',NULL,'0');
INSERT INTO users VALUES ('76','ayang12@gmail.com','ayang12',NULL,'default.svg','$2y$10$SYhPCKk9XvK3ru22tDBSseXI5Cewezg/x4/lRH1p4g83hpfgzecb2',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 01:19:52','2025-11-05 01:19:52',NULL,'0');
INSERT INTO users VALUES ('77','juragan@gmail.com','juragan',NULL,'default.svg','$2y$10$2ch3CGqpQI7gaQxlLZ/LGuBbILt7Xd9F8bUrVnvfoi2Q71rijed86',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 01:20:35','2025-11-05 01:20:35',NULL,'0');
INSERT INTO users VALUES ('78','cicilan@gmail.com','cicil',NULL,'default.svg','$2y$10$5KXLOP72OCFBQIDVdjbMo.fVxJGdbEZeu5hwyqHbq7ZpJlcySgW6i',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-05 22:36:25','2025-11-07 20:19:25',NULL,'0');
INSERT INTO users VALUES ('79','adrian@gmail.com','adrian',NULL,'default.svg','$2y$10$WBa5JQsM6on0om4cgNyPN.nf3auuklcRcD3YRyQ9ycgwzQD6cSnC2',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 19:03:28','2025-11-09 19:03:28',NULL,'0');
INSERT INTO users VALUES ('80','rafasya@gmail.com','rafasya',NULL,'default.svg','$2y$10$NsqzaUHV.ucWbd1LiwMX/uoJmLUbHNzLypxNLcRQmu2tScn1oWVee',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 19:07:51','2025-11-09 19:07:51',NULL,'0');
INSERT INTO users VALUES ('81','jadiinikupon@gmail.com','jadiinikupon',NULL,'default.svg','$2y$10$bUPQe8Ar0Wt.J09Z1OuBfeJ7x6fv.2Y/vAP7Y7rxs8dSryn4V.o5y',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 19:20:37','2025-11-09 19:20:37',NULL,'0');
INSERT INTO users VALUES ('82','jansen@gmail.com','jansen',NULL,'default.svg','$2y$10$AIonxb9gxuXKhFf8ZGpImu4ng48CYNouWtCYUYSXdm/lnzcNsm3Ai',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 19:28:27','2025-11-09 19:28:27',NULL,'0');
INSERT INTO users VALUES ('83','vikran@gmail.com','vikram',NULL,'default.svg','$2y$10$mwPIiOr8GftfGV8SbjnnHuHBpzn1h6fL0cZ23hREsaTsm8Nlor/lG',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 19:40:13','2025-11-09 19:40:13',NULL,'0');
INSERT INTO users VALUES ('84','januari@gmail.com','januari',NULL,'default.svg','$2y$10$NAHASIUGjVQNRnDmA9X1x.CxtBrDpwXEvvXH7kHJ86esU8Y0uXHrO',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 19:43:03','2025-11-09 19:43:03',NULL,'0');
INSERT INTO users VALUES ('85','raymod@gmail.com','raymond',NULL,'default.svg','$2y$10$pfTg0Yu2kIRSHU8SsC37mO/KQBLWKteBhWePm0ogB0cKEvBc2xGg2',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 19:52:07','2025-11-09 19:52:07',NULL,'0');
INSERT INTO users VALUES ('86','aichat@gmail.com','aichat',NULL,'default.svg','$2y$10$0BagtrrEq0fW.PawjPt8DOr6u3OqnOWdQXz0PUEXB9jlQmPAjzpfC',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 19:57:46','2025-11-09 19:57:46',NULL,'0');
INSERT INTO users VALUES ('87','rama@gmail.com','rama',NULL,'default.svg','$2y$10$4KIRSh0hX0Ozn7rO.ssRhuM8OQeX48HCj7ooEq/Tk4hq93tXcqQaO',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:04:49','2025-11-09 20:04:49',NULL,'0');
INSERT INTO users VALUES ('88','course@gmail.com','course',NULL,'default.svg','$2y$10$MOB7/WQ.wkyDcOzOmTfXsOchjZCQdQVUdmXk8FUX5OoeN0I.h8iyC',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:15:15','2025-11-09 20:15:15',NULL,'0');
INSERT INTO users VALUES ('89','gagal@gmail.com','gagal',NULL,'default.svg','$2y$10$GAU2/2O2DG8OrUkZ6d57wOuXH2kP9POEmi2MUJyd7ar0lLA2U1Rsm',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:17:53','2025-11-09 20:17:53',NULL,'0');
INSERT INTO users VALUES ('90','gagalidr@gmail.com','gagalidr',NULL,'default.svg','$2y$10$ZWYSBitH094ZF5LVzKuzXuio8/rd6b.2D2OW8ZN1YAdTtV6UMn5S6',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:18:49','2025-11-09 20:18:49',NULL,'0');
INSERT INTO users VALUES ('91','adit@gmail.com','adit',NULL,'default.svg','$2y$10$tzDwcRIXChYA6UEsQQOz1uPyrdAufipbGgNePIjr6ts8na9oS2Qfa',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:23:04','2025-11-09 20:23:04',NULL,'0');
INSERT INTO users VALUES ('92','ucitasya@gmail.com','ucitasya',NULL,'default.svg','$2y$10$2RxE0pumP7E99ARAEMoV2edVR91AyBCvqNDBAZAGXfVxkJy4PVZN6',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:53:09','2025-11-09 20:53:09',NULL,'0');
INSERT INTO users VALUES ('93','cicillagi@gmail.com','cicillagi',NULL,'default.svg','$2y$10$4PksI//pMfKZ3.lAUfPWLO59k4aLYnBcKb/b4vDMNGaIaqu4wzrxG',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:54:05','2025-11-09 20:54:05',NULL,'0');
INSERT INTO users VALUES ('94','umar@gmail.com','umar',NULL,'default.svg','$2y$10$BFiZTK5hfyzvkKAlLx3L4.FbnQJe114hRSpNL1GwpHpECczZdjU3.',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:55:40','2025-11-09 20:55:40',NULL,'0');
INSERT INTO users VALUES ('95','rifqi1@gmail.com','rifqi',NULL,'default.svg','$2y$10$wN0/qQMOcwRvrUHxvtborefO4rlaFMsrTsD7QJDTYvrD7TjZ3OW1e',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:56:39','2025-11-09 20:56:39',NULL,'0');
INSERT INTO users VALUES ('96','lowtiket@gmail.com','lowtiket',NULL,'default.svg','$2y$10$qDo.SDRK9kble.7xYSKwR.nJfGWuZvv1GYMEJ30AhM0TINfeZL882',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:57:44','2025-11-09 20:57:44',NULL,'0');
INSERT INTO users VALUES ('97','lowtiket1@gmail.com','lowtiket1',NULL,'default.svg','$2y$10$m7lCHhYXpgTsumhbDAhATuOf.bOrq9J6DW/26vTQVi8TPpknpRAJW',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 20:58:36','2025-11-09 20:59:10',NULL,'0');
INSERT INTO users VALUES ('98','jagoantes@gmail.com','jagoantes',NULL,'default.svg','$2y$10$LMQ95i5o9xK5IFtzKFHG/ucpJYxMxHlWjg5iunG/2FXMYpQuJc.96',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 22:04:54','2025-11-09 22:04:54',NULL,'0');
INSERT INTO users VALUES ('99','jagoantes11@gmail.com','jagoantes1',NULL,'default.svg','$2y$10$GGjJYijBxTrL9alLwLDf9.12nIuSUeCeTx1I2iSHr6Tyb74J6Qua2',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 22:05:42','2025-11-09 22:05:42',NULL,'0');
INSERT INTO users VALUES ('100','bagoy@gmail.com','bagoy',NULL,'default.svg','$2y$10$Hv37Lsm0E2k9h4Bt7J7kmeJRn7i7ht0sbDnCZzjpZjV1xZgpZe7ZC',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 22:06:58','2025-11-09 22:06:58',NULL,'0');
INSERT INTO users VALUES ('101','abang@gmail.com','abang',NULL,'default.svg','$2y$10$DcJ.eywNqDQF4RlNtqWIwu4NLXGh7CU2q1698LPI4vqD2ufNomJSK',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 22:08:34','2025-11-09 22:08:34',NULL,'0');
INSERT INTO users VALUES ('102','bangek@gmail.com','bangek',NULL,'default.svg','$2y$10$UILJz/GxBVsOJib1.tvWdOJP4IsQSD/oPBfQ0nW3hf3VgDrDt19BS',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 22:12:02','2025-11-09 22:12:02',NULL,'0');
INSERT INTO users VALUES ('103','testing100@gmail.com','testing100',NULL,'default.svg','$2y$10$845gOBcp9bjXpX0gsR8xw.qhk0Wz2v.Q2uwguNjlL1At0X09S26dS',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 22:33:37','2025-11-09 22:33:37',NULL,'0');
INSERT INTO users VALUES ('104','goblok@gmail.com','goblok',NULL,'default.svg','$2y$10$CsQA6bf.Py8Lzr/WTsPvr.eIZyXihpqCU1dFFZNyjfMnmRAFkwYpa',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 22:47:53','2025-11-09 22:47:53',NULL,'0');
INSERT INTO users VALUES ('105','jajang@gmail.com','jajang',NULL,'default.svg','$2y$10$tUvHMnAdeooUqXUXaV98hu6/c.Y870VVuWTpOv0G9Cpa1n1LVEaDK',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 23:21:34','2025-11-09 23:21:59',NULL,'0');
INSERT INTO users VALUES ('106','arif@gmail.com','arif',NULL,'default.svg','$2y$10$JUW7yZSWlmB2Ctt2fxq.ueLMNN1.1xLMCNp9n791gRoq4AMZdhoba',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-11-09 23:23:42','2025-11-09 23:23:42',NULL,'0');


CREATE TABLE `utang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `akun_id` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `jumlah` decimal(15,2) NOT NULL,
  `dibayar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('belum','lunas') COLLATE utf8mb4_general_ci DEFAULT 'belum',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

