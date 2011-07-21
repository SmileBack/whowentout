# --------------------------------------------------------
# Host:                         
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-07-21 10:03:53
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.colleges: ~51 rows (approximately)
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
	(25, '33581615', NULL, 0, 'Austin High School', '0'),
	(26, '33568076', NULL, 0, 'Donna Klein Jewish Academy', '0'),
	(27, '16777298', NULL, 0, 'Binghamton', '0'),
	(28, '50434401', NULL, 0, 'United States Congress', '0'),
	(29, '33572581', NULL, 0, 'New Jewish High School/Gann Academy', '0'),
	(30, '16777221', NULL, 0, 'Cornell', '0'),
	(31, '16777381', NULL, 0, 'CUNY Baruch', '0'),
	(32, '16777318', NULL, 0, 'Arizona', '0'),
	(33, '16777572', NULL, 0, 'Ursinus', '0'),
	(34, '33579702', NULL, 0, 'Wissahickon Senior High School', '0'),
	(35, '33567356', NULL, 0, 'Gunnery', '0'),
	(36, '33572565', NULL, 0, 'Framingham High School', '0'),
	(37, '33575443', NULL, 0, 'Chatham High', '0'),
	(38, '33566538', NULL, 0, 'Viewpoint School', '0'),
	(39, '16828524', NULL, 0, 'Universidad Latina', '0'),
	(40, '33571784', NULL, 0, 'Cheverus High School', '0'),
	(41, '33566550', NULL, 0, 'Harvard-Westlake', '0'),
	(42, '33575662', NULL, 0, 'Old Bridge High School', '0'),
	(43, '33582887', NULL, 0, 'Bellaire High School', '0'),
	(44, '67109026', NULL, 0, 'Northern Indiana, IN', '0'),
	(45, '33570087', NULL, 0, 'Valparaiso High School', '0'),
	(46, '33572381', NULL, 0, 'Brooks School', '0'),
	(47, '33572537', NULL, 0, 'Tabor Academy', '0'),
	(48, '33575797', NULL, 0, 'Seton Hall Preparatory School', '0'),
	(49, '33568239', NULL, 0, 'The Lovett School', '0'),
	(50, '33575800', NULL, 0, 'Westfield Senior High School', '0'),
	(51, '33572307', NULL, 0, 'Holliston High School', '0'),
	(52, '33572711', NULL, 0, 'Cranbrook Kingswood', '0');
/*!40000 ALTER TABLE `colleges` ENABLE KEYS */;


# Dumping structure for table whowentout.friends
CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `friend_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.friends: ~4 rows (approximately)
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
INSERT INTO `friends` (`id`, `user_id`, `friend_id`) VALUES
	(2, 33, 109),
	(46, 109, 33),
	(47, 109, 11),
	(48, 109, 7);
/*!40000 ALTER TABLE `friends` ENABLE KEYS */;


# Dumping structure for table whowentout.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` varchar(40) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `type` varchar(64) NOT NULL,
  `args` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;


# Dumping structure for table whowentout.options
CREATE TABLE IF NOT EXISTS `options` (
  `id` varchar(512) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.options: ~2 rows (approximately)
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` (`id`, `value`) VALUES
	('fake_time_point', 'a:2:{s:9:"fake_time";O:8:"DateTime":3:{s:4:"date";s:19:"2011-10-07 22:01:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:9:"real_time";O:8:"DateTime":3:{s:4:"date";s:19:"2011-07-20 23:50:13";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}}'),
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

# Dumping data for table whowentout.party_attendees: ~41 rows (approximately)
/*!40000 ALTER TABLE `party_attendees` DISABLE KEYS */;
INSERT INTO `party_attendees` (`user_id`, `party_id`, `checkin_time`) VALUES
	(3, 10, '2011-07-19 01:08:08'),
	(6, 10, '2011-07-17 00:47:21'),
	(6, 15, '2011-07-15 23:18:22'),
	(7, 11, '2011-07-17 00:04:12'),
	(9, 9, '2011-07-17 00:18:32'),
	(10, 14, '2011-07-13 06:34:44'),
	(12, 10, '2011-07-17 22:52:39'),
	(14, 14, '2011-07-13 06:34:47'),
	(33, 10, '2011-07-17 00:16:35'),
	(50, 10, '2011-07-19 01:04:15'),
	(78, 10, '2011-07-18 00:10:17'),
	(80, 10, '2011-07-17 00:47:23'),
	(81, 10, '2011-07-18 00:10:15'),
	(82, 11, '2011-07-17 00:45:33'),
	(82, 14, '2011-07-13 06:34:41'),
	(83, 10, '2011-07-18 00:10:19'),
	(86, 11, '2011-07-17 00:47:10'),
	(88, 10, '2011-07-17 00:47:20'),
	(89, 9, '2011-07-17 17:50:46'),
	(90, 10, '2011-07-19 01:06:41'),
	(91, 9, '2011-07-17 00:17:35'),
	(93, 15, '2011-07-15 23:18:20'),
	(95, 15, '2011-07-15 23:18:18'),
	(96, 10, '2011-07-17 01:38:15'),
	(97, 10, '2011-07-17 22:52:43'),
	(98, 11, '2011-07-17 00:45:31'),
	(98, 15, '2011-07-15 23:18:21'),
	(99, 15, '2011-07-15 23:18:19'),
	(100, 11, '2011-07-17 00:47:08'),
	(101, 15, '2011-07-15 22:55:24'),
	(102, 10, '2011-07-19 20:28:38'),
	(104, 10, '2011-07-18 00:10:11'),
	(104, 14, '2011-07-13 06:34:46'),
	(106, 10, '2011-07-19 01:07:46'),
	(107, 10, '2011-07-17 22:53:55'),
	(108, 11, '2011-07-17 00:45:29'),
	(112, 10, '2011-07-18 00:09:57'),
	(113, 10, '2011-07-18 00:09:59'),
	(122, 10, '2011-07-19 20:27:43'),
	(123, 10, '2011-07-18 00:10:13'),
	(147, 10, '2011-07-19 20:20:14');
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
	('1eb55a9456f4dbc07901c1074ed68970', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/53', 1311293007, ''),
	('3e3a9bfa80d23cf795c127dd00a52dbe', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/53', 1311245837, 'a:1:{s:7:"user_id";i:147;}');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.smiles: ~2 rows (approximately)
/*!40000 ALTER TABLE `smiles` DISABLE KEYS */;
INSERT INTO `smiles` (`id`, `sender_id`, `receiver_id`, `party_id`, `smile_time`) VALUES
	(12, 96, 147, 10, '2011-07-21 00:12:16'),
	(13, 147, 96, 10, '2011-07-21 00:16:41');
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
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.users: ~77 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `facebook_id`, `first_name`, `last_name`, `hometown`, `college_id`, `grad_year`, `email`, `gender`, `registration_time`, `last_edit`, `date_of_birth`, `pic_x`, `pic_y`, `pic_width`, `pic_height`) VALUES
	(3, '100001150127674', 'Robert', 'Roose', 'Topeka, KS', 1, 2011, 'robert@gwu.edu', 'M', NULL, NULL, '1990-10-24', 38, 20, 105, 140),
	(4, '1243620029', 'Clara', 'Scheinmann', 'Topeka, KS', 1, 2013, 'clara@gwu.edu', 'F', NULL, '2011-10-07 22:23:35', '1991-01-31', 20, 20, 140, 187),
	(5, '1479330106', 'Natalie', 'Epelman', 'Topeka, KS', 1, 2012, 'natalie@gwu.edu', 'F', NULL, NULL, '1990-05-16', 20, 20, 140, 187),
	(6, '1067760090', 'Marissa', 'Ostroff', 'Topeka, KS', 1, 2013, 'marissa@gwu.edu', 'F', NULL, NULL, '1990-12-09', 20, 20, 140, 187),
	(7, '569012997', 'Alex', 'Webb', 'Topeka, KS', 1, 2012, 'alex@gwu.edu', 'M', NULL, '2011-10-07 22:24:21', '0000-00-00', 37, 20, 106, 141),
	(8, '704222664', 'Leon', 'Harari', 'Topeka, KS', 1, 2012, 'leon@gwu.edu', 'M', NULL, NULL, '0000-00-00', 60, 20, 60, 80),
	(9, '760370505', 'Jonny', 'Cohen', 'Topeka, KS', 1, 2012, 'johnny@gwu.edu', 'M', NULL, '2011-10-07 22:57:45', '0000-00-00', 36, 20, 108, 144),
	(10, '719185695', 'Cassie', 'Scheinmann', 'Topeka, KS', 1, 2013, 'cassie@gwu.edu', 'F', NULL, NULL, '1991-03-05', 42, 20, 95, 127),
	(11, '1099920067', 'Erica ', 'Obersi', 'Topeka, KS', 1, 2013, 'erica@gwu.edu', 'F', NULL, NULL, '1990-04-30', 54, 20, 71, 95),
	(12, '1682940070', 'Ava', 'Rubin', 'Topeka, KS', 1, 2013, 'ava@gwu.edu', 'F', NULL, NULL, '1991-01-09', 20, 20, 140, 187),
	(13, '1067760099', 'Anna ', 'Lepkoski', 'Topeka, KS', 1, 2013, 'anna@gwu.edu', 'F', NULL, NULL, '1991-03-02', 35, 20, 110, 146),
	(14, '1120470019', 'Sara', 'Sopher', 'Topeka, KS', 1, 2012, 'sara@gwu.edu', 'F', NULL, NULL, '0000-00-00', 20, 20, 140, 187),
	(33, '8100231', 'Dan', 'Berenholtz', 'Topeka, KS', 1, 2014, 'db349@cornell.edu', 'M', '2011-07-06 07:48:07', '2011-10-07 22:11:30', '1986-09-10', 20, 20, 140, 187),
	(50, '5300477', 'Briana', 'Ashley', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 02:27:09', NULL, '0000-00-00', 24, 20, 133, 177),
	(78, '5311798', 'Pamela', 'Siegelaub', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:14:02', NULL, '0000-00-00', 20, 20, 140, 187),
	(80, '5312146', 'Emily', 'Aden', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:21:24', NULL, '0000-00-00', 28, 20, 125, 166),
	(81, '634575073', 'Casey', 'James', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:23:53', NULL, '0000-00-00', 53, 20, 75, 100),
	(82, '1346882983', 'Claire', 'Bennett', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:23:53', NULL, '0000-00-00', 20, 20, 140, 187),
	(83, '539471945', 'Senya', 'Merchant', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:23:53', NULL, '0000-00-00', 20, 20, 140, 187),
	(85, '1091460106', 'Jillian', 'Leviton', 'Topeka, KS', 1, 2012, '', 'F', '2011-07-10 03:25:40', NULL, '0000-00-00', 20, 20, 140, 187),
	(86, '527961219', 'Riley', 'Schamburg', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:40', NULL, '0000-00-00', 24, 20, 133, 177),
	(87, '1088610196', 'Jess', 'Sardella', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:41', NULL, '0000-00-00', 20, 20, 140, 187),
	(88, '1339500103', 'Melissa', 'Peters', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:41', '2011-10-07 22:38:18', '0000-00-00', 20, 20, 140, 187),
	(89, '1088670513', 'Jackie', 'Galoma', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:42', '2011-10-07 23:09:49', '0000-00-00', 43, 20, 94, 125),
	(90, '1084350150', 'Nicole', 'Pozzi', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:42', NULL, '0000-00-00', 20, 20, 140, 187),
	(91, '106631', 'Alisa', 'Brem', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:43', '2011-10-07 22:56:54', '0000-00-00', 53, 20, 75, 100),
	(92, '20203798', 'Emy', 'Gelb', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:43', NULL, '0000-00-00', 20, 20, 140, 187),
	(93, '1229670021', 'Nicole', 'White', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:44', NULL, '0000-00-00', 20, 20, 160, 213),
	(94, '7306880', 'Monica', 'Dreyer', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:44', NULL, '0000-00-00', 31, 20, 119, 158),
	(95, '5304695', 'Swati', 'Venugopal', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:44', NULL, '0000-00-00', 20, 20, 160, 213),
	(96, '5312769', 'Maggie', 'Brennan', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:44', '2011-10-07 22:20:18', '0000-00-00', 20, 20, 140, 187),
	(97, '1459620102', 'Remi', 'Rosenfeldt', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:45', NULL, '0000-00-00', 54, 20, 71, 95),
	(98, '1364970126', 'Rebecca', 'Parker', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:45', NULL, '0000-00-00', 20, 20, 140, 187),
	(99, '5312816', 'Chelsea', 'Bridge', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:46', NULL, '0000-00-00', 20, 20, 140, 187),
	(100, '504327686', 'Beth', 'Argaman', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:46', NULL, '0000-00-00', 17, 20, 146, 194),
	(101, '730755083', 'Alex', 'Caines', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:46', NULL, '0000-00-00', 20, 20, 137, 183),
	(102, '5312044', 'Alyssa', 'Cooper', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:47', '2011-10-08 01:12:45', '0000-00-00', 20, 20, 100, 133),
	(103, '1223850680', 'Betsy', 'Fortune', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:47', NULL, '0000-00-00', 54, 20, 71, 95),
	(104, '507233007', 'Allison', 'Markowitz', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:47', NULL, '0000-00-00', 20, 20, 140, 187),
	(105, '1307940055', 'Carsen', 'Zarin', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:47', NULL, '0000-00-00', 20, 20, 140, 187),
	(106, '1463190167', 'Jana', 'Teichman', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:48', NULL, '0000-00-00', 20, 20, 140, 187),
	(107, '1515390516', 'Rebekah', 'Yurco', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:48', '2011-10-08 01:32:26', '0000-00-00', 60, 20, 60, 80),
	(108, '1555110345', 'Jenny', 'Soderbergh', 'Topeka, KS', 1, 2013, '', 'F', '2011-07-10 03:25:48', '2011-10-07 22:43:07', '0000-00-00', 20, 20, 140, 187),
	(110, '531310504', 'Alexander', 'Zafran', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:47', NULL, '0000-00-00', 53, 20, 74, 99),
	(111, '8112397', 'Mike', 'Allian', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:48', NULL, '0000-00-00', 20, 20, 140, 187),
	(112, '1100520174', 'Sean', 'Burstyn', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:49', NULL, '0000-00-00', 54, 20, 71, 95),
	(113, '1244370118', 'Ari', 'Hoffman', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:50', NULL, '0000-00-00', 46, 20, 88, 117),
	(114, '5304934', 'Jonathan', 'Kudary', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:51', NULL, '0000-00-00', 59, 20, 83, 110),
	(115, '6314330', 'Jonathan', 'Yashari', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:52', NULL, '0000-00-00', 16, 20, 148, 197),
	(116, '16400099', 'Mourad', 'Shehebar', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:53', NULL, '0000-00-00', 38, 20, 104, 139),
	(117, '10105934', 'Noah', 'Lerman', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:54', NULL, '0000-00-00', 20, 20, 62, 83),
	(118, '5318741', 'Steve', 'Noghrey', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:22:55', NULL, '0000-00-00', 20, 20, 140, 187),
	(121, '1458000225', 'Jeff', 'Becker', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:23:00', NULL, '0000-00-00', 20, 20, 140, 187),
	(122, '1087620202', 'Adam', 'Katzenberg', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:23:01', '2011-10-07 22:14:38', '0000-00-00', 20, 20, 140, 187),
	(123, '1243890775', 'Andrew', 'Chester', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:23:03', NULL, '0000-00-00', 54, 20, 71, 95),
	(124, '1135539275', 'Peter', 'Cook', 'Topeka, KS', 1, 2012, '', 'M', '2011-10-08 05:23:04', NULL, '0000-00-00', 33, 20, 115, 153),
	(127, '746605354', 'Hunter', 'Pritchard', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:50:23', NULL, '0000-00-00', 20, 20, 140, 187),
	(128, '899965359', 'Brock', 'Treworgy', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:50:30', NULL, '0000-00-00', 20, 20, 140, 187),
	(129, '504949778', 'Ryan', 'Ashley', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:50:38', NULL, '0000-00-00', 20, 20, 140, 187),
	(130, '1488840082', 'Victor', 'Bogachev', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:50:46', NULL, '0000-00-00', 60, 20, 60, 80),
	(131, '591065975', 'Hursh', 'Vasant', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:50:53', NULL, '0000-00-00', 38, 20, 105, 140),
	(132, '575037558', 'Andrew', 'Schumacher', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:51:01', NULL, '0000-00-00', 20, 20, 140, 187),
	(133, '667836533', 'Nathan', 'Felton', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:52:23', NULL, '0000-00-00', 54, 20, 71, 95),
	(134, '1103820248', 'Nick', 'Mejia', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:52:29', NULL, '0000-00-00', 54, 20, 71, 95),
	(135, '525900942', 'Clement', 'Kristensen', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:52:35', NULL, '0000-00-00', 20, 20, 141, 188),
	(136, '501474887', 'Steven', 'Chen', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:52:43', NULL, '0000-00-00', 20, 20, 140, 187),
	(137, '1243050303', 'Luke', 'Stone', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:52:51', NULL, '0000-00-00', 49, 20, 83, 110),
	(138, '1340850473', 'Chas', 'Pressner', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:52:58', NULL, '0000-00-00', 19, 20, 143, 190),
	(139, '1114110458', 'Trey', 'O\'Callaghan', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:53:04', NULL, '0000-00-00', 67, 20, 46, 61),
	(140, '1063440857', 'Pete', 'Chattrabhuti', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:53:12', NULL, '0000-00-00', 20, 20, 140, 187),
	(141, '677726556', 'Jake', 'Shiffman', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:53:19', NULL, '0000-00-00', 16, 20, 148, 197),
	(142, '1511370180', 'Ryan', 'Thornton', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:53:27', NULL, '0000-00-00', 60, 20, 60, 80),
	(143, '1463220074', 'Ian', 'Braun', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:53:34', NULL, '0000-00-00', 20, 20, 140, 187),
	(144, '1236150489', 'Ben', 'Gillman', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:53:44', NULL, '0000-00-00', 20, 20, 140, 187),
	(145, '1248270638', 'Bradley', 'Schlafer', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:53:52', NULL, '0000-00-00', 20, 20, 140, 187),
	(146, '1372767357', 'Harry', 'Meng', 'Topeka, KS', 1, 2013, '', 'M', '2011-10-07 22:53:58', NULL, '0000-00-00', 17, 20, 166, 221),
	(147, '776200121', 'Venkat', 'Dinavahi', 'Severna Park, MD', 1, 2012, 'vendiddy@gmail.com', 'M', '2011-10-07 22:06:12', '2011-10-08 01:26:44', '1988-10-06', 20, 20, 104, 139);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
