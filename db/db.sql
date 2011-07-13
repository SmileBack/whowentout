# --------------------------------------------------------
# Host:                         
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-07-12 17:54:10
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.colleges: ~24 rows (approximately)
/*!40000 ALTER TABLE `colleges` DISABLE KEYS */;
INSERT INTO `colleges` (`id`, `facebook_network_id`, `facebook_school_id`, `enabled`, `name`, `email_domain`) VALUES
	(1, '16777270', '108727889151725', 1, 'GWU', 'gwu.edu'),
	(2, '16777219', '6192688417', 0, 'Stanford', 'stanford.edu'),
	(3, '16777274', '113889395291269', 1, 'UMD', 'umd.edu'),
	(5, '16777217', NULL, 0, 'Harvard', '0'),
	(6, '33580108', NULL, 0, 'Methacton High School', '0'),
	(7, '16777224', NULL, 0, 'MIT', '0'),
	(8, '50432424', NULL, 0, 'The Advisory Board Company', '0'),
	(9, '33569646', NULL, 0, 'Wheaton Warrenville South HS', '0'),
	(10, '33566185', NULL, 0, 'Roseville High', '0'),
	(11, '16777568', NULL, 0, 'Duquesne', '0'),
	(12, '33567389', NULL, 0, 'Kingswood-Oxford', '0'),
	(13, '16777546', NULL, 0, 'Rollins', '0'),
	(14, '33575752', NULL, 0, 'Roxbury High School', '0'),
	(15, '33567391', NULL, 0, 'Kent', '0'),
	(16, '33567247', NULL, 0, 'Greenwich Academy', '0'),
	(17, '16777218', NULL, 0, 'Columbia', '0'),
	(18, '16777587', NULL, 0, 'LSE', '0'),
	(19, '16777419', NULL, 0, 'Denver', '0'),
	(20, '33579756', NULL, 0, 'Shipley School', '0'),
	(21, '33576985', NULL, 0, 'Our Lady Of Mercy Academy', '0'),
	(22, '33576814', NULL, 0, 'Plainview- Old Bethpage/JFK High School', '0'),
	(23, '33574700', NULL, 0, 'Whitfield School', '0'),
	(24, '33579875', NULL, 0, 'Germantown Academy', '0'),
	(25, '33581615', NULL, 0, 'Austin High School', '0');
/*!40000 ALTER TABLE `colleges` ENABLE KEYS */;


# Dumping structure for table whowentout.options
CREATE TABLE IF NOT EXISTS `options` (
  `id` varchar(512) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.options: ~2 rows (approximately)
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` (`id`, `value`) VALUES
	('fake_time_point', 'a:2:{s:9:"fake_time";O:8:"DateTime":3:{s:4:"date";s:19:"2011-10-07 15:01:00";s:13:"timezone_type";i:1;s:8:"timezone";s:6:"-07:00";}s:9:"real_time";O:8:"DateTime":3:{s:4:"date";s:19:"2011-07-11 23:55:17";s:13:"timezone_type";i:3;s:8:"timezone";s:19:"America/Los_Angeles";}}'),
	('temp', '2011-09-28 22:06:04 -0700');
/*!40000 ALTER TABLE `options` ENABLE KEYS */;


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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.parties: ~8 rows (approximately)
/*!40000 ALTER TABLE `parties` DISABLE KEYS */;
INSERT INTO `parties` (`id`, `date`, `place_id`, `admin_id`) VALUES
	(9, '2011-10-06', 3, NULL),
	(10, '2011-10-06', 1, NULL),
	(11, '2011-10-06', 2, NULL),
	(14, '2011-10-07', 2, NULL),
	(15, '2011-10-08', 1, NULL),
	(16, '2011-10-13', 3, NULL),
	(17, '2011-10-13', 1, NULL),
	(18, '2011-10-13', 2, NULL);
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

# Dumping data for table whowentout.party_attendees: ~10 rows (approximately)
/*!40000 ALTER TABLE `party_attendees` DISABLE KEYS */;
/*!40000 ALTER TABLE `party_attendees` ENABLE KEYS */;


# Dumping structure for table whowentout.places
CREATE TABLE IF NOT EXISTS `places` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `college_id` int(10) unsigned NOT NULL,
  `admin_id` int(10) unsigned DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `college_id` (`college_id`),
  KEY `admin` (`admin_id`),
  CONSTRAINT `places_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  CONSTRAINT `places_college_id` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.places: ~3 rows (approximately)
/*!40000 ALTER TABLE `places` DISABLE KEYS */;
INSERT INTO `places` (`id`, `name`, `college_id`, `admin_id`, `created_at`) VALUES
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

# Dumping data for table whowentout.sessions: ~2 rows (approximately)
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
	('b04856a84008378ae025e0cb95abb692', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/53', 1310542998, 'a:1:{s:7:"user_id";i:125;}');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.smiles: ~0 rows (approximately)
/*!40000 ALTER TABLE `smiles` DISABLE KEYS */;
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
  `last_edit` datetime DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `pic_x` int(10) unsigned DEFAULT NULL,
  `pic_y` int(10) unsigned DEFAULT NULL,
  `pic_width` int(10) unsigned DEFAULT NULL,
  `pic_height` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_facebook_id` (`facebook_id`),
  KEY `college_id` (`college_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.users: ~44 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `facebook_id`, `first_name`, `last_name`, `hometown`, `college_id`, `grad_year`, `email`, `gender`, `registration_time`, `last_edit`, `date_of_birth`, `pic_x`, `pic_y`, `pic_width`, `pic_height`) VALUES
	(3, '100001150127674', 'Robert', 'Roose', 'Topeka, KS', 1, 2011, 'robert@gwu.edu', 'M', NULL, NULL, '1990-10-24', 38, 20, 105, 140),
	(4, '1243620029', 'Clara', 'Scheinmann', 'Topeka, KS', 1, 2013, 'clara@gwu.edu', 'F', NULL, NULL, '1991-01-31', 20, 20, 140, 187),
	(5, '1479330106', 'Natalie', 'Epelman', 'Topeka, KS', 1, 2012, 'natalie@gwu.edu', 'F', NULL, NULL, '1990-05-16', 17, 20, 147, 196),
	(6, '1067760090', 'Marissa', 'Ostroff', 'Topeka, KS', 1, 2013, 'marissa@gwu.edu', 'F', NULL, NULL, '1990-12-09', 20, 20, 140, 187),
	(7, '1204337494', 'Alex', 'Webb', 'Topeka, KS', 1, 2012, 'alex@gwu.edu', 'M', NULL, NULL, '0000-00-00', 59, 20, 83, 110),
	(8, '704222664', 'Leon', 'Harari', 'Topeka, KS', 1, 2012, 'leon@gwu.edu', 'M', NULL, NULL, '0000-00-00', 60, 20, 60, 80),
	(9, '760370505', 'Jonny', 'Cohen', 'Topeka, KS', 1, 2012, 'johnny@gwu.edu', 'M', NULL, NULL, '0000-00-00', 36, 20, 108, 144),
	(10, '719185695', 'Cassie', 'Scheinmann', 'Topeka, KS', 1, 2013, 'cassie@gwu.edu', 'F', NULL, NULL, '1991-03-05', 42, 20, 95, 127),
	(11, '1099920067', 'Erica ', 'Obersi', 'Topeka, KS', 1, 2013, 'erica@gwu.edu', 'F', NULL, NULL, '1990-04-30', 56, 20, 68, 91),
	(12, '1682940070', 'Ava', 'Rubin', 'Topeka, KS', 1, 2013, 'ava@gwu.edu', 'F', NULL, NULL, '1991-01-09', 32, 20, 116, 155),
	(13, '1067760099', 'Anna ', 'Lepkoski', 'Topeka, KS', 1, 2013, 'anna@gwu.edu', 'F', NULL, NULL, '1991-03-02', 35, 20, 110, 146),
	(14, '1120470019', 'Sara', 'Sopher', 'Topeka, KS', 1, 2012, 'sara@gwu.edu', 'F', NULL, NULL, '0000-00-00', 20, 20, 140, 187),
	(33, '8100231', 'Dan', 'Berenholtz', 'Topeka, KS', 1, 2014, 'db349@cornell.edu', 'M', '2011-07-06 07:48:07', NULL, '1986-09-10', 0, 0, 140, 187),
	(50, '5300477', 'Briana', 'Ashley', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 02:27:09', NULL, '0000-00-00', 24, 20, 133, 177),
	(78, '5311798', 'Pamela', 'Siegelaub', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:14:02', NULL, '0000-00-00', 20, 20, 140, 187),
	(80, '5312146', 'Emily', 'Aden', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:21:24', NULL, '0000-00-00', 28, 20, 125, 166),
	(81, '634575073', 'Casey', 'James', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:23:53', NULL, '0000-00-00', 20, 20, 140, 187),
	(82, '1346882983', 'Claire', 'Bennett', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:23:53', NULL, '0000-00-00', 20, 20, 140, 187),
	(83, '539471945', 'Senya', 'Merchant', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:23:53', NULL, '0000-00-00', 20, 20, 140, 187),
	(85, '1091460106', 'Jillian', 'Leviton', 'Topeka, KS', 1, 2012, '', 'F', '2011-07-10 03:25:40', NULL, '0000-00-00', 5, 0, 175, 233),
	(86, '527961219', 'Riley', 'Schamburg', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:40', NULL, '0000-00-00', 24, 20, 133, 177),
	(87, '1088610196', 'Jess', 'Sardella', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:41', NULL, '0000-00-00', 20, 20, 127, 169),
	(88, '1339500103', 'Melissa', 'Peters', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:41', NULL, '0000-00-00', 20, 20, 140, 187),
	(89, '1088670513', 'Jackie', 'Galoma', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:42', NULL, '0000-00-00', 12, 8, 134, 178),
	(90, '1084350150', 'Nicole', 'Pozzi', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:42', NULL, '0000-00-00', 20, 20, 140, 187),
	(91, '106631', 'Alisa', 'Brem', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:43', NULL, '0000-00-00', 53, 20, 75, 100),
	(92, '20203798', 'Emy', 'Gelb', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:43', NULL, '0000-00-00', 20, 20, 140, 187),
	(93, '1229670021', 'Nicole', 'White', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:44', NULL, '0000-00-00', 20, 20, 160, 213),
	(94, '7306880', 'Monica', 'Dreyer', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:44', NULL, '0000-00-00', 20, 20, 140, 187),
	(95, '5304695', 'Swati', 'Venugopal', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:44', NULL, '0000-00-00', 20, 20, 160, 213),
	(96, '5312769', 'Maggie', 'Brennan', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:44', NULL, '0000-00-00', 20, 20, 140, 187),
	(97, '1459620102', 'Remi', 'Rosenfeldt', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:45', NULL, '0000-00-00', 54, 20, 71, 95),
	(98, '1364970126', 'Rebecca', 'Parker', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:45', NULL, '0000-00-00', 20, 20, 140, 187),
	(99, '5312816', 'Chelsea', 'Bridge', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:46', NULL, '0000-00-00', 20, 20, 140, 187),
	(100, '504327686', 'Beth', 'Argaman', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:46', NULL, '0000-00-00', 20, 20, 140, 187),
	(101, '730755083', 'Alex', 'Caines', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:46', NULL, '0000-00-00', 20, 20, 137, 183),
	(102, '5312044', 'Alyssa', 'Cooper', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:47', NULL, '0000-00-00', 20, 20, 100, 133),
	(103, '1223850680', 'Betsy', 'Fortune', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:47', NULL, '0000-00-00', 20, 20, 140, 187),
	(104, '507233007', 'Allison', 'Markowitz', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:47', NULL, '0000-00-00', 20, 20, 140, 187),
	(105, '1307940055', 'Carsen', 'Zarin', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:47', NULL, '0000-00-00', 20, 20, 140, 187),
	(106, '1463190167', 'Jana', 'Teichman', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:48', NULL, '0000-00-00', 20, 20, 140, 187),
	(107, '1515390516', 'Rebekah', 'Yurco', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:48', NULL, '0000-00-00', 60, 20, 60, 80),
	(108, '1555110345', 'Jenny', 'Soderbergh', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:48', NULL, '0000-00-00', 20, 20, 140, 187),
	(125, '776200121', 'Venkat', 'Dinavahi', 'Severna Park, MD', 3, 2012, 'ven@stanford.edu', 'M', '2011-10-08 15:49:18', '2011-10-08 15:49:27', '1988-10-06', 20, 20, 104, 139);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
