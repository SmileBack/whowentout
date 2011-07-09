# --------------------------------------------------------
# Host:                         
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-07-09 15:55:13
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table whowentout.colleges
CREATE TABLE IF NOT EXISTS `colleges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_network_id` varchar(255) DEFAULT NULL,
  `facebook_school_id` varchar(255) DEFAULT NULL,
  `enabled` int(10) unsigned DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `email_domain` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.colleges: ~3 rows (approximately)
/*!40000 ALTER TABLE `colleges` DISABLE KEYS */;
INSERT INTO `colleges` (`id`, `facebook_network_id`, `facebook_school_id`, `enabled`, `name`, `email_domain`) VALUES
	(1, '16777270', NULL, 1, 'GWU', 'gwu.edu'),
	(2, '16777219', '6192688417', 0, 'Stanford', 'stanford.edu'),
	(3, '16777274', '113889395291269', 1, 'University of Maryland', 'umd.edu');
/*!40000 ALTER TABLE `colleges` ENABLE KEYS */;


# Dumping structure for table whowentout.parties
CREATE TABLE IF NOT EXISTS `parties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `place_id` int(10) unsigned NOT NULL,
  `admin_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `place_id` (`place_id`),
  KEY `admin` (`admin_id`),
  CONSTRAINT `parties_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  CONSTRAINT `parties_place_id` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.parties: ~6 rows (approximately)
/*!40000 ALTER TABLE `parties` DISABLE KEYS */;
INSERT INTO `parties` (`id`, `date`, `place_id`, `admin_id`) VALUES
	(1, '2011-05-27', 1, 7),
	(2, '2011-05-27', 2, 8),
	(3, '2011-05-27', 3, 9),
	(4, '2011-05-26', 1, 7),
	(5, '2011-05-25', 2, 8),
	(6, '2011-05-24', 3, 9);
/*!40000 ALTER TABLE `parties` ENABLE KEYS */;


# Dumping structure for table whowentout.party_attendees
CREATE TABLE IF NOT EXISTS `party_attendees` (
  `user_id` int(10) unsigned NOT NULL,
  `party_id` int(10) unsigned NOT NULL,
  `checkin_time` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`party_id`),
  KEY `party_id` (`party_id`),
  CONSTRAINT `party_attendees_ibfk_2` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`),
  CONSTRAINT `party_attendees_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.party_attendees: ~25 rows (approximately)
/*!40000 ALTER TABLE `party_attendees` DISABLE KEYS */;
INSERT INTO `party_attendees` (`user_id`, `party_id`, `checkin_time`) VALUES
	(3, 4, NULL),
	(4, 4, NULL),
	(5, 4, NULL),
	(6, 1, '2011-07-07 05:43:07'),
	(6, 4, NULL),
	(6, 6, '2011-07-04 00:32:44'),
	(7, 1, '2011-07-07 08:12:38'),
	(7, 4, NULL),
	(8, 3, '2011-07-07 07:44:47'),
	(8, 5, NULL),
	(10, 4, NULL),
	(10, 5, NULL),
	(10, 6, '2011-07-02 03:13:31'),
	(11, 3, '2011-07-07 07:31:30'),
	(11, 4, NULL),
	(11, 5, NULL),
	(12, 1, '2011-07-07 08:04:02'),
	(12, 4, NULL),
	(12, 5, NULL),
	(13, 4, NULL),
	(13, 6, NULL),
	(14, 3, '2011-07-07 07:29:35'),
	(14, 4, NULL),
	(14, 6, NULL),
	(31, 1, '2011-07-08 02:28:32'),
	(33, 1, '2011-07-09 22:39:48');
/*!40000 ALTER TABLE `party_attendees` ENABLE KEYS */;


# Dumping structure for table whowentout.places
CREATE TABLE IF NOT EXISTS `places` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `college_id` int(10) unsigned NOT NULL,
  `admin_id` int(10) unsigned DEFAULT NULL,
  `welcome_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `college_id` (`college_id`),
  KEY `admin` (`admin_id`),
  CONSTRAINT `places_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  CONSTRAINT `places_college_id` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.places: ~3 rows (approximately)
/*!40000 ALTER TABLE `places` DISABLE KEYS */;
INSERT INTO `places` (`id`, `name`, `college_id`, `admin_id`, `welcome_date`) VALUES
	(1, 'McFaddens', 1, 7, NULL),
	(2, 'Sig Chi', 1, 8, NULL),
	(3, 'Lambda Chi', 1, 9, NULL);
/*!40000 ALTER TABLE `places` ENABLE KEYS */;


# Dumping structure for table whowentout.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.sessions: ~1 rows (approximately)
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
	('169170a19a3fffdf37a509217f68920c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/53', 1310252037, 'a:1:{s:7:"user_id";i:6;}');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;


# Dumping structure for table whowentout.smiles
CREATE TABLE IF NOT EXISTS `smiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL,
  `receiver_id` int(10) unsigned NOT NULL,
  `party_id` int(10) unsigned NOT NULL,
  `smile_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_smiles` (`sender_id`,`receiver_id`,`party_id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `party_id` (`party_id`),
  CONSTRAINT `smiles_party_id` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`),
  CONSTRAINT `smiles_receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  CONSTRAINT `smiles_sender_id` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.smiles: ~11 rows (approximately)
/*!40000 ALTER TABLE `smiles` DISABLE KEYS */;
INSERT INTO `smiles` (`id`, `sender_id`, `receiver_id`, `party_id`, `smile_time`) VALUES
	(31, 31, 10, 6, '2011-07-05 00:34:38'),
	(32, 31, 14, 6, '2011-07-05 00:34:42'),
	(33, 10, 31, 6, '2011-07-05 00:35:55'),
	(34, 6, 31, 1, '2011-07-07 07:26:48'),
	(35, 6, 33, 1, '2011-07-07 07:26:50'),
	(36, 31, 6, 1, '2011-07-07 07:27:08'),
	(37, 33, 6, 1, '2011-07-07 07:28:06'),
	(38, 8, 12, 5, '2011-07-07 07:51:07'),
	(39, 12, 31, 1, '2011-07-07 08:04:08'),
	(41, 12, 33, 1, '2011-07-07 08:14:18'),
	(45, 31, 12, 1, '2011-07-08 02:28:42');
/*!40000 ALTER TABLE `smiles` ENABLE KEYS */;


# Dumping structure for table whowentout.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `hometown` varchar(255) NOT NULL,
  `college_id` int(10) unsigned DEFAULT NULL,
  `grad_year` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `registration_time` datetime DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `pic_x` int(10) unsigned DEFAULT NULL,
  `pic_y` int(10) unsigned DEFAULT NULL,
  `pic_width` int(10) unsigned DEFAULT NULL,
  `pic_height` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_facebook_id` (`facebook_id`),
  KEY `college_id` (`college_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.users: ~14 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `facebook_id`, `first_name`, `last_name`, `hometown`, `college_id`, `grad_year`, `email`, `gender`, `registration_time`, `date_of_birth`, `pic_x`, `pic_y`, `pic_width`, `pic_height`) VALUES
	(3, '100001150127674', 'Robert', 'Roose', '', 1, 2011, 'robert@gwu.edu', 'M', NULL, '1990-10-24', 38, 20, 105, 140),
	(4, '1243620029', 'Clara', 'Scheinmann', '', 1, 2013, 'clara@gwu.edu', 'F', NULL, '1991-01-31', NULL, NULL, NULL, NULL),
	(5, '1479330106', 'Natalie', 'Epelman', '', 1, 2012, 'natalie@gwu.edu', 'F', NULL, '1990-05-16', NULL, NULL, NULL, NULL),
	(6, '1067760090', 'Marissa', 'Ostroff', '', 1, 2013, 'marissa@gwu.edu', 'F', NULL, '1990-12-09', 20, 20, 140, 187),
	(7, '1204337494', 'Alex', 'Webb', '', 1, 2012, 'alex@gwu.edu', 'M', NULL, '0000-00-00', 59, 20, 83, 110),
	(8, '704222664', 'Leon', 'Harari', '', 1, 2012, 'leon@gwu.edu', 'M', NULL, '0000-00-00', 60, 20, 60, 80),
	(9, '760370505', 'Jonny', 'Cohen', '', 1, 2012, 'johnny@gwu.edu', 'M', NULL, '0000-00-00', NULL, NULL, NULL, NULL),
	(10, '719185695', 'Cassie', 'Scheinmann', '', 1, 2013, 'cassie@gwu.edu', 'F', NULL, '1991-03-05', 42, 20, 95, 127),
	(11, '1099920067', 'Erica ', 'Obersi', '', 1, 2013, 'erica@gwu.edu', 'F', NULL, '1990-04-30', NULL, NULL, NULL, NULL),
	(12, '1682940070', 'Ava', 'Rubin', '', 1, 2013, 'ava@gwu.edu', 'F', NULL, '1991-01-09', 32, 20, 116, 155),
	(13, '1067760099', 'Anna ', 'Lepkoski', '', 1, 2013, 'anna@gwu.edu', 'F', NULL, '1991-03-02', NULL, NULL, NULL, NULL),
	(14, '1120470019', 'Sara', 'Sopher', '', 1, 2012, 'sara@gwu.edu', 'F', NULL, '0000-00-00', 9, 8, 163, 217),
	(31, '776200121', 'Venkat', 'Dinavahi', 'Severna Park, MD', 3, 2010, 'ven@stanford.edu', 'M', '2011-07-05 00:17:35', '1988-10-06', 25, 0, 45, 60),
	(33, '8100231', 'Dan', 'Berenholtz', 'Kfar Saba, China', 1, 2014, 'db349@cornell.edu', 'M', '2011-07-06 07:48:07', '1986-09-10', 0, 0, 140, 187);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
