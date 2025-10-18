-- Backup Database: yensurvival
-- Created at: 2025-10-19 03:35:17



CREATE TABLE `aset_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL DEFAULT curdate(),
  `nama` varchar(255) NOT NULL,
  `akun_id` int(11) DEFAULT NULL,
  `jumlah` decimal(16,2) NOT NULL DEFAULT 0.00,
  `nilai_sekarang` decimal(16,2) NOT NULL DEFAULT 0.00,
  `deskripsi` varchar(255) DEFAULT NULL,
  `status` enum('aktif','selesai') DEFAULT 'aktif',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;



CREATE TABLE `auth_activation_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `auth_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO auth_groups VALUES ('1','Admin','Site Administrator');
INSERT INTO auth_groups VALUES ('2','User','Site Reguler User');


CREATE TABLE `auth_groups_permissions` (
  `group_id` int(11) unsigned NOT NULL DEFAULT 0,
  `permission_id` int(11) unsigned NOT NULL DEFAULT 0,
  KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  KEY `group_id_permission_id` (`group_id`,`permission_id`),
  CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO auth_groups_permissions VALUES ('1','1');
INSERT INTO auth_groups_permissions VALUES ('1','2');
INSERT INTO auth_groups_permissions VALUES ('2','2');


CREATE TABLE `auth_groups_users` (
  `group_id` int(11) unsigned NOT NULL DEFAULT 0,
  `user_id` int(11) unsigned NOT NULL DEFAULT 0,
  KEY `auth_groups_users_user_id_foreign` (`user_id`),
  KEY `group_id_user_id` (`group_id`,`user_id`),
  CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO auth_groups_users VALUES ('1','8');
INSERT INTO auth_groups_users VALUES ('1','8');
INSERT INTO auth_groups_users VALUES ('2','10');
INSERT INTO auth_groups_users VALUES ('2','12');


CREATE TABLE `auth_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

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


CREATE TABLE `auth_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO auth_permissions VALUES ('1','manage-users','Manage All User');
INSERT INTO auth_permissions VALUES ('2','manage-profile','Manage User Profile');


CREATE TABLE `auth_reset_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `auth_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_tokens_user_id_foreign` (`user_id`),
  KEY `selector` (`selector`),
  CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `auth_users_permissions` (
  `user_id` int(11) unsigned NOT NULL DEFAULT 0,
  `permission_id` int(11) unsigned NOT NULL DEFAULT 0,
  KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  KEY `user_id_permission_id` (`user_id`,`permission_id`),
  CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `investasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(255) NOT NULL,
  `akun_id` int(11) DEFAULT NULL,
  `jumlah` decimal(20,2) NOT NULL DEFAULT 0.00,
  `nilai_sekarang` decimal(20,2) NOT NULL DEFAULT 0.00,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','selesai') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;



CREATE TABLE `kekayaan_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `kategori` enum('uang','utang','piutang','aset','investasi') NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `jumlah` decimal(16,2) DEFAULT 0.00,
  `tanggal` date DEFAULT NULL,
  `saldo_terkini` decimal(18,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_kategori` (`user_id`,`kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4;

INSERT INTO kekayaan_items VALUES ('88','10','uang','Cash','50000.00',NULL,'32500.00','2025-10-18 06:23:28','2025-10-18 06:40:33');
INSERT INTO kekayaan_items VALUES ('93','10','uang','BRI','10000.00',NULL,'40000.00','2025-10-18 06:25:38','2025-10-18 06:40:33');


CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4;

INSERT INTO logs VALUES ('6','8','Admin','Login','User reycreator berhasil login.','::1',NULL,'2025-10-18 11:44:26');
INSERT INTO logs VALUES ('7','8','Admin','Logout','User reycreator melakukan logout.','::1',NULL,'2025-10-18 11:46:30');
INSERT INTO logs VALUES ('8','8','Admin','Login','User reycreator berhasil login.','::1',NULL,'2025-10-18 11:46:36');
INSERT INTO logs VALUES ('9','8','Admin','Logout','User reycreator melakukan logout.','::1','Chrome on Windows','2025-10-18 13:05:21');
INSERT INTO logs VALUES ('10','8','Admin','Login','User reycreator berhasil login.','::1','Chrome on Windows','2025-10-18 13:05:28');
INSERT INTO logs VALUES ('11','8','Admin','Suspend User','Admin men-suspend user dengan ID: 11','::1','Chrome on Windows','2025-10-18 13:12:23');
INSERT INTO logs VALUES ('12','8','Admin','Suspend User','Admin men-suspend user dengan ID: 11','::1','Chrome on Windows','2025-10-18 13:15:26');
INSERT INTO logs VALUES ('13','8','Admin','Aktif User','Admin men-aktifkan user dengan ID: 11','::1','Chrome on Windows','2025-10-18 13:15:31');
INSERT INTO logs VALUES ('14','8','Admin','Reset Password','Admin men-reset password user dengan ID: 11','::1','Chrome on Windows','2025-10-18 13:15:53');
INSERT INTO logs VALUES ('15','8','Admin','Reset Password','Admin men-reset password user dengan ID: 11 dengan password : user9832','::1','Chrome on Windows','2025-10-18 13:17:00');
INSERT INTO logs VALUES ('16','8','Admin','Hapus User','Admin men-hapus user dengan ID: 11','::1','Chrome on Windows','2025-10-18 13:17:24');
INSERT INTO logs VALUES ('17','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:20:58');
INSERT INTO logs VALUES ('18','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:21:12');
INSERT INTO logs VALUES ('19','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:21:45');
INSERT INTO logs VALUES ('20','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:22:00');
INSERT INTO logs VALUES ('21','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:23:20');
INSERT INTO logs VALUES ('22','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:23:23');
INSERT INTO logs VALUES ('23','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:23:25');
INSERT INTO logs VALUES ('24','8','Admin','Subcription Update','Admin men-update subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:25:03');
INSERT INTO logs VALUES ('25','8','Admin','Suspend User','Admin men-suspend user dengan ID: 10','::1','Chrome on Windows','2025-10-18 13:25:28');
INSERT INTO logs VALUES ('26','8','Admin','Aktif User','Admin men-aktifkan user dengan ID: 10','::1','Chrome on Windows','2025-10-18 13:25:32');
INSERT INTO logs VALUES ('27','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:25:38');
INSERT INTO logs VALUES ('28','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:29:41');
INSERT INTO logs VALUES ('29','8','Admin','Subcription Update','Admin men-update subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:29:50');
INSERT INTO logs VALUES ('30','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:30:28');
INSERT INTO logs VALUES ('31','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:30:32');
INSERT INTO logs VALUES ('32','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:31:38');
INSERT INTO logs VALUES ('33','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:32:26');
INSERT INTO logs VALUES ('34','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 13:38:08');
INSERT INTO logs VALUES ('35','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:01:13');
INSERT INTO logs VALUES ('36','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:01:16');
INSERT INTO logs VALUES ('37','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:01:21');
INSERT INTO logs VALUES ('38','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:08:04');
INSERT INTO logs VALUES ('39','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:08:12');
INSERT INTO logs VALUES ('40','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:11:45');
INSERT INTO logs VALUES ('41','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:11:47');
INSERT INTO logs VALUES ('42','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:14:36');
INSERT INTO logs VALUES ('43','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:14:37');
INSERT INTO logs VALUES ('44','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:17:27');
INSERT INTO logs VALUES ('45','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:18:43');
INSERT INTO logs VALUES ('46','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:44:55');
INSERT INTO logs VALUES ('47','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:45:00');
INSERT INTO logs VALUES ('48','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:45:08');
INSERT INTO logs VALUES ('49','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:45:14');
INSERT INTO logs VALUES ('50','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:45:57');
INSERT INTO logs VALUES ('51','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:46:00');
INSERT INTO logs VALUES ('52','8','Admin','Subcription Update','Admin men-update subscription user ID: 4','::1','Chrome on Windows','2025-10-18 14:55:59');
INSERT INTO logs VALUES ('53','8','Admin','Suspend User','Admin men-suspend user dengan ID: 12','::1','Chrome on Windows','2025-10-18 15:03:38');
INSERT INTO logs VALUES ('54','8','Admin','Aktif User','Admin men-aktifkan user dengan ID: 12','::1','Chrome on Windows','2025-10-18 15:03:45');
INSERT INTO logs VALUES ('55','8','Admin','Subcription Update','Admin men-update subscription user ID: 4','::1','Chrome on Windows','2025-10-18 15:04:24');
INSERT INTO logs VALUES ('56','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 15:04:30');
INSERT INTO logs VALUES ('57','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 15:04:34');
INSERT INTO logs VALUES ('58','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 15:06:13');
INSERT INTO logs VALUES ('59','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 15:07:22');
INSERT INTO logs VALUES ('60','8','Admin','Suspend User','Admin men-suspend user dengan ID: 12','::1','Chrome on Windows','2025-10-18 15:09:03');
INSERT INTO logs VALUES ('61','8','Admin','Aktif User','Admin men-aktifkan user dengan ID: 12','::1','Chrome on Windows','2025-10-18 15:09:06');
INSERT INTO logs VALUES ('62','8','Admin','Reset Password','Admin men-reset password user dengan ID: 12 dengan password : user2182','::1','Chrome on Windows','2025-10-18 15:14:34');
INSERT INTO logs VALUES ('63','8','Admin','Reset Password','Admin men-reset password user dengan ID: 12 dengan password : user4240','::1','Chrome on Windows','2025-10-18 15:18:14');
INSERT INTO logs VALUES ('64','8','Admin','Suspend User','Admin men-suspend user dengan ID: 12','::1','Chrome on Windows','2025-10-18 15:18:25');
INSERT INTO logs VALUES ('65','8','Admin','Aktif User','Admin men-aktifkan user dengan ID: 12','::1','Chrome on Windows','2025-10-18 15:18:29');
INSERT INTO logs VALUES ('66','8','Admin','Subcription Cancel','Admin men-batalkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 15:18:44');
INSERT INTO logs VALUES ('67','8','Admin','Subcription Aktif','Admin men-aktifkan subscription user ID: 4','::1','Chrome on Windows','2025-10-18 15:18:48');
INSERT INTO logs VALUES ('68','8','Admin','Suspend User','Admin men-suspend user dengan ID: 12','::1','Chrome on Windows','2025-10-18 15:58:33');
INSERT INTO logs VALUES ('69','8','Admin','Aktif User','Admin men-aktifkan user dengan ID: 12','::1','Chrome on Windows','2025-10-18 15:58:43');
INSERT INTO logs VALUES ('70','8','Admin','Suspend User','Admin men-suspend user dengan ID: 12','::1','Chrome on Windows','2025-10-18 16:08:36');
INSERT INTO logs VALUES ('71','8','Admin','Aktif User','Admin men-aktifkan user dengan ID: 12','::1','Chrome on Windows','2025-10-18 16:08:39');
INSERT INTO logs VALUES ('72','8','Admin','Suspend User','Admin men-suspend user dengan ID: 10','::1','Chrome on Windows','2025-10-19 01:19:12');
INSERT INTO logs VALUES ('73','8','Admin','Aktif User','Admin men-aktifkan user dengan ID: 10','::1','Chrome on Windows','2025-10-19 01:19:15');
INSERT INTO logs VALUES ('74','8','Admin','Ubah Pengaturan','Admin memperbarui konfigurasi sistem.','::1','Chrome on Windows','2025-10-19 02:10:31');
INSERT INTO logs VALUES ('75','8','Admin','Ubah Pengaturan','Admin memperbarui konfigurasi sistem.','::1','Chrome on Windows','2025-10-19 02:40:30');
INSERT INTO logs VALUES ('76','8','Admin','Gagal Backup Database','Error: Class \'CodeIgniter\\Database\\Utilities\' not found','::1','Chrome on Windows','2025-10-19 02:40:52');
INSERT INTO logs VALUES ('77','8','Admin','Gagal Backup Database','Error: Class \'CodeIgniter\\Database\\Utilities\' not found','::1','Chrome on Windows','2025-10-19 02:41:59');
INSERT INTO logs VALUES ('78','8','Admin','Gagal Backup Database','Error: Class \'CodeIgniter\\Database\\Utilities\' not found','::1','Chrome on Windows','2025-10-19 02:42:01');
INSERT INTO logs VALUES ('79','8','Admin','Gagal Backup Database','Error: Class \'CodeIgniter\\Database\\Utilities\' not found','::1','Chrome on Windows','2025-10-19 02:42:03');
INSERT INTO logs VALUES ('80','8','Admin','Gagal Backup Database','Error: Class \'CodeIgniter\\Database\\Utilities\' not found','::1','Chrome on Windows','2025-10-19 02:42:31');
INSERT INTO logs VALUES ('81','8','Admin','Gagal Backup Database','Error: Class \'CodeIgniter\\Database\\Utilities\' not found','::1','Chrome on Windows','2025-10-19 02:42:41');
INSERT INTO logs VALUES ('82','8','Admin','Gagal Backup Database','Error: Class \'CodeIgniter\\Database\\Utilities\' not found','::1','Chrome on Windows','2025-10-19 02:43:03');
INSERT INTO logs VALUES ('83','8','Admin','Gagal Backup Database','Error: Call to undefined method CodeIgniter\\Database\\MySQLi\\Connection::util()','::1','Chrome on Windows','2025-10-19 02:46:20');
INSERT INTO logs VALUES ('84','8','Admin','Gagal Backup Database','Error: Call to undefined method CodeIgniter\\Database\\MySQLi\\Connection::utils()','::1','Chrome on Windows','2025-10-19 02:46:51');
INSERT INTO logs VALUES ('85','8','Admin','Gagal Backup Database','Error: Class \'CodeIgniter\\Database\\Utilities\' not found','::1','Chrome on Windows','2025-10-19 02:57:19');
INSERT INTO logs VALUES ('86','8','Admin','Gagal Backup Database','Error: Unsupported feature of the database platform you are using.','::1','Chrome on Windows','2025-10-19 03:04:52');
INSERT INTO logs VALUES ('87','8','Admin','Gagal Backup Database','Error: Unsupported feature of the database platform you are using.','::1','Chrome on Windows','2025-10-19 03:05:20');
INSERT INTO logs VALUES ('88','8','Admin','Gagal Backup Database','Error: Trying to access array offset on value of type null','::1','Chrome on Windows','2025-10-19 03:18:41');
INSERT INTO logs VALUES ('89','8','Admin','Gagal Backup Database','Error: Trying to access array offset on value of type null','::1','Chrome on Windows','2025-10-19 03:22:39');
INSERT INTO logs VALUES ('90','8','Admin','Gagal Backup Database','Error: Trying to access array offset on value of type null','::1','Chrome on Windows','2025-10-19 03:25:26');


CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO migrations VALUES ('1','2017-11-20-223112','Myth\\Auth\\Database\\Migrations\\CreateAuthTables','default','Myth\\Auth','1758698489','1');


CREATE TABLE `piutang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `akun_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `dibayar` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('belum','lunas') DEFAULT 'belum',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;



CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(10) DEFAULT '¥',
  `price_monthly` decimal(16,2) DEFAULT 0.00,
  `price_yearly` decimal(16,2) DEFAULT 0.00,
  `backup_schedule` enum('daily','weekly','monthly') DEFAULT 'weekly',
  `contact_whatsapp` varchar(50) DEFAULT '',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO settings VALUES ('1','¥','600.00','4000.00','weekly','+628557663472','2025-10-19 00:19:21','2025-10-19 02:40:30');


CREATE TABLE `subscriptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `plan_type` enum('monthly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `status` enum('active','expired','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_subscriptions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO subscriptions VALUES ('4','12','yearly','active','2023-10-01','2025-11-30','2025-10-15 06:54:07','2025-10-18 15:18:48');


CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('in','out','transfer') NOT NULL,
  `akun` varchar(100) DEFAULT NULL,
  `sumber_id` int(11) DEFAULT NULL,
  `tujuan_id` int(11) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `deskripsi` varchar(200) DEFAULT NULL,
  `jumlah` decimal(14,2) NOT NULL DEFAULT 0.00,
  `is_initial` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_tanggal` (`user_id`,`tanggal`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8mb4;

INSERT INTO transaksi VALUES ('198','10','2025-10-18','in',NULL,'88',NULL,'Uang','Modal awal dari Uang','50000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('199','10','2025-10-18','out',NULL,'89',NULL,'Utang','Modal awal dari Utang','10000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('200','10','2025-10-18','in',NULL,'90',NULL,'Piutang','Modal awal dari Piutang','5000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('201','10','2025-10-18','in',NULL,'91',NULL,'Aset','Modal awal dari Aset','1000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('202','10','2025-10-18','in',NULL,'92',NULL,'Investasi','Modal awal dari Investasi','1000.00','0','2025-10-18 15:23:28','2025-10-18 15:23:28');
INSERT INTO transaksi VALUES ('203','10','2025-10-18','in',NULL,'93',NULL,'Modal Awal','Saldo awal akun baru','10000.00','0','2025-10-18 15:25:38','2025-10-18 15:25:38');
INSERT INTO transaksi VALUES ('204','10','2025-10-18','out',NULL,'88','93','Transfer','Transfer keluar','5000.00','0','2025-10-18 15:26:18','2025-10-18 15:26:18');
INSERT INTO transaksi VALUES ('205','10','2025-10-18','in',NULL,'93','88','Transfer','Transfer masuk','5000.00','0','2025-10-18 15:26:18','2025-10-18 15:26:18');
INSERT INTO transaksi VALUES ('206','10','2025-10-18','out',NULL,'88',NULL,'Investasi','Beli investasi: Kartu Pokemon','1000.00','0','2025-10-18 15:27:22','2025-10-18 15:27:22');
INSERT INTO transaksi VALUES ('207','10','2025-10-18','in',NULL,'88',NULL,'investasi','Penjualan investasi: Kartu Pokemon','1500.00','0','2025-10-18 15:28:01','2025-10-18 15:28:01');
INSERT INTO transaksi VALUES ('208','10','2025-10-18','in',NULL,'88',NULL,'investasi','Penjualan investasi: Saham','2000.00','0','2025-10-18 15:28:39','2025-10-18 15:28:39');
INSERT INTO transaksi VALUES ('209','10','2025-10-18','out',NULL,'88',NULL,'Aset','Pembelian aset: A','5000.00','0','2025-10-18 15:29:01','2025-10-18 15:29:01');
INSERT INTO transaksi VALUES ('210','10','2025-10-18','in',NULL,'88',NULL,'Aset','Penjualan aset: A','15000.00','0','2025-10-18 15:29:30','2025-10-18 15:29:30');
INSERT INTO transaksi VALUES ('211','10','2025-10-18','in',NULL,'93',NULL,'Aset','Penjualan aset: HP','5000.00','0','2025-10-18 15:29:42','2025-10-18 15:29:42');
INSERT INTO transaksi VALUES ('212','10','2025-10-18','in',NULL,'88',NULL,'Utang','Menerima Utang Bank BCA','10000.00','0','2025-10-18 15:30:21','2025-10-18 15:30:21');
INSERT INTO transaksi VALUES ('213','10','2025-10-18','out',NULL,'88',NULL,'Utang','Pembayaran utang: Bank BCA','10000.00','0','2025-10-18 15:30:30','2025-10-18 15:30:30');
INSERT INTO transaksi VALUES ('214','10','2025-10-18','out',NULL,'88',NULL,'Utang','Pembayaran utang: Orang Tua','10000.00','0','2025-10-18 15:30:41','2025-10-18 15:30:41');
INSERT INTO transaksi VALUES ('215','10','2025-10-18','in',NULL,'88',NULL,'Piutang','Penerimaan piutang: Temen','5000.00','0','2025-10-18 15:30:56','2025-10-18 15:30:56');
INSERT INTO transaksi VALUES ('216','10','2025-10-18','out',NULL,'88',NULL,'Piutang','Memberi Piutang: Bank BCA','10000.00','0','2025-10-18 15:31:08','2025-10-18 15:31:08');
INSERT INTO transaksi VALUES ('217','10','2025-10-18','in',NULL,'93',NULL,'Piutang','Penerimaan piutang: Bank BCA','10000.00','0','2025-10-18 15:31:17','2025-10-18 15:31:17');
INSERT INTO transaksi VALUES ('218','10','2025-10-18','out',NULL,'88','93','Transfer','Transfer keluar','10000.00','0','2025-10-18 15:40:33','2025-10-18 15:40:33');
INSERT INTO transaksi VALUES ('219','10','2025-10-18','in',NULL,'93','88','Transfer','Transfer masuk','10000.00','0','2025-10-18 15:40:33','2025-10-18 15:40:33');


CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_setup` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO users VALUES ('8','reyhcreator.97@gmail.com','reycreator',NULL,'default.svg','$2y$10$zozKSMwcrNqaSFaDcbzF4uqXP/wpw0/csyAJQjxK64JqXLqQUyArS',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-09-25 04:10:31','2025-09-25 04:10:31',NULL,'0');
INSERT INTO users VALUES ('10','uci@gmail.com','uci',NULL,'default.svg','$2y$10$l1eX1xVW65g74i7ADJR8YutahKgGWYjTS3g7KQBdDg4monKQ97IFO',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-10-02 13:49:32','2025-10-19 01:19:15',NULL,'1');
INSERT INTO users VALUES ('12','tes2@gmail.com','tes2',NULL,'default.svg','$2y$10$qmCavplE.VA639F3KHaOLerftneiIPHQP0kOdzZev258LHzztoGlW',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-10-14 21:34:51','2025-10-18 16:08:39',NULL,'0');


CREATE TABLE `utang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `akun_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `dibayar` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('belum','lunas') DEFAULT 'belum',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

