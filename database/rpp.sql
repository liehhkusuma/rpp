-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.11 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table rpp.bo_users
CREATE TABLE IF NOT EXISTS `bo_users` (
  `bu_id` int(11) NOT NULL AUTO_INCREMENT,
  `bu_no_regis` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bu_real_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bu_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bu_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bu_passwd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bu_salt` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bu_init` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bu_pic` varchar(254) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bu_level` int(2) DEFAULT NULL,
  `bu_status` enum('y','n') COLLATE utf8_unicode_ci DEFAULT 'y',
  `bu_create_date` datetime DEFAULT NULL,
  PRIMARY KEY (`bu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table rpp.bo_users: ~4 rows (approximately)
DELETE FROM `bo_users`;
/*!40000 ALTER TABLE `bo_users` DISABLE KEYS */;
INSERT INTO `bo_users` (`bu_id`, `bu_no_regis`, `bu_real_name`, `bu_email`, `bu_name`, `bu_passwd`, `bu_salt`, `bu_init`, `bu_pic`, `bu_level`, `bu_status`, `bu_create_date`) VALUES
	(1, NULL, 'Super Admin', 'super@lingkar9.com', 'super', 'f865b53623b121fd34ee5426c792e5c33af8c227', NULL, 'SP', '1430812789-img-8487.jpg', 0, 'y', '2015-04-24 19:17:47'),
	(2, NULL, 'Galih Kusuma', 'galih@lingkar9.com', 'galih', 'f865b53623b121fd34ee5426c792e5c33af8c227', NULL, 'GK', '1430813034-img-8484.jpg', 1, 'y', NULL),
	(4, '12', 'Iwan Kurniawan', 'iwan@lingkar9.com', 'iwan', 'f865b53623b121fd34ee5426c792e5c33af8c227', NULL, 'IK', NULL, 2, 'y', '2016-10-07 00:46:06'),
	(5, '123/NJJDF/090/HB', 'Iyu Priatna', 'iyu@lingkar9.com', 'iyu', 'f865b53623b121fd34ee5426c792e5c33af8c227', NULL, 'IP', NULL, 2, 'y', '2016-10-07 00:49:38');
/*!40000 ALTER TABLE `bo_users` ENABLE KEYS */;


-- Dumping structure for table rpp.bo_user_level
CREATE TABLE IF NOT EXISTS `bo_user_level` (
  `bul_id` int(2) NOT NULL AUTO_INCREMENT,
  `bul_order` int(2) DEFAULT NULL,
  `bul_level_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bul_menu_role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bul_module_role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bul_status` enum('y','n') COLLATE utf8_unicode_ci DEFAULT 'y',
  PRIMARY KEY (`bul_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table rpp.bo_user_level: ~2 rows (approximately)
DELETE FROM `bo_user_level`;
/*!40000 ALTER TABLE `bo_user_level` DISABLE KEYS */;
INSERT INTO `bo_user_level` (`bul_id`, `bul_order`, `bul_level_name`, `bul_menu_role`, `bul_module_role`, `bul_status`) VALUES
	(1, 1, 'Administrator', '1', '', 'y'),
	(2, 2, 'Registration', '1', NULL, 'y');
/*!40000 ALTER TABLE `bo_user_level` ENABLE KEYS */;


-- Dumping structure for table rpp.registran
CREATE TABLE IF NOT EXISTS `registran` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_no_regis` text,
  `p_a1` text,
  `p_a2` text,
  `p_a3` text,
  `p_a4` text,
  `p_a5` text,
  `p_a6` text,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table rpp.registran: 1 rows
DELETE FROM `registran`;
/*!40000 ALTER TABLE `registran` DISABLE KEYS */;
INSERT INTO `registran` (`p_id`, `p_no_regis`, `p_a1`, `p_a2`, `p_a3`, `p_a4`, `p_a5`, `p_a6`) VALUES
	(2, '4', 'google.com', 'google.com', 'google.com', 'google.com', 'google.comasasas', 'google.com');
/*!40000 ALTER TABLE `registran` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
