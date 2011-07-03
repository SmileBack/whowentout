# --------------------------------------------------------
# Host:                         
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-07-03 14:49:15
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table whowentout.colleges
CREATE TABLE IF NOT EXISTS `colleges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.colleges: ~1 rows (approximately)
/*!40000 ALTER TABLE `colleges` DISABLE KEYS */;
INSERT INTO `colleges` (`id`, `name`) VALUES
	(1, 'GW');
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
  CONSTRAINT `party_attendees_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `party_attendees_ibfk_2` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.party_attendees: ~22 rows (approximately)
/*!40000 ALTER TABLE `party_attendees` DISABLE KEYS */;
INSERT INTO `party_attendees` (`user_id`, `party_id`, `checkin_time`) VALUES
	(1, 4, '2011-07-02 02:30:26'),
	(1, 5, NULL),
	(1, 6, '2011-07-02 03:10:36'),
	(2, 4, NULL),
	(3, 4, NULL),
	(4, 4, NULL),
	(5, 4, NULL),
	(6, 4, NULL),
	(7, 4, NULL),
	(8, 5, NULL),
	(9, 5, NULL),
	(10, 4, NULL),
	(10, 5, NULL),
	(10, 6, '2011-07-02 03:13:31'),
	(11, 4, NULL),
	(11, 5, NULL),
	(12, 4, NULL),
	(12, 5, NULL),
	(13, 4, NULL),
	(13, 6, NULL),
	(14, 4, NULL),
	(14, 6, NULL);
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
	('c831c3680681cf5a2815cad53f7665d7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/53', 1309729475, 'a:1:{s:7:"user_id";i:2;}');
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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.smiles: ~16 rows (approximately)
/*!40000 ALTER TABLE `smiles` DISABLE KEYS */;
INSERT INTO `smiles` (`id`, `sender_id`, `receiver_id`, `party_id`, `smile_time`) VALUES
	(4, 4, 2, 4, NULL),
	(5, 4, 3, 4, NULL),
	(6, 5, 1, 4, NULL),
	(7, 5, 2, 4, NULL),
	(8, 6, 1, 4, NULL),
	(9, 9, 1, 4, NULL),
	(10, 1, 11, 5, NULL),
	(11, 11, 1, 5, NULL),
	(26, 1, 6, 4, '2011-07-02 02:38:50'),
	(27, 1, 11, 4, '2011-07-02 02:41:03'),
	(28, 1, 4, 4, '2011-07-02 02:41:09'),
	(29, 1, 13, 6, '2011-07-02 02:41:24'),
	(30, 1, 14, 6, '2011-07-02 02:41:26'),
	(31, 2, 11, 4, '2011-07-02 03:01:49'),
	(32, 2, 4, 4, '2011-07-02 03:01:51'),
	(33, 2, 6, 4, '2011-07-02 03:01:58');
/*!40000 ALTER TABLE `smiles` ENABLE KEYS */;


# Dumping structure for table whowentout.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `college_id` int(10) unsigned NOT NULL,
  `grad_year` int(10) unsigned NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `registration_time` datetime DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `college_id` (`college_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.users: ~14 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `facebook_id`, `first_name`, `last_name`, `college_id`, `grad_year`, `profile_pic`, `email`, `gender`, `registration_time`, `date_of_birth`) VALUES
	(1, '8100231', 'Dan ', 'Berenholtz', 1, 2012, '1.jpg', 'dan@gwu.edu', 'M', NULL, '1987-09-10'),
	(2, '776200121', 'Venkat', 'Dinavahi', 1, 2012, '2.jpg', 'ven@gwu.edu', 'M', NULL, '0000-00-00'),
	(3, '100001150127674', 'Robert', 'Roose', 1, 2011, '3.jpg', 'robert@gwu.edu', 'M', NULL, '1990-10-24'),
	(4, '1243620029', 'Clara', 'Scheinmann', 1, 2013, '4.jpg', 'clara@gwu.edu', 'F', NULL, '1991-01-31'),
	(5, '1479330106', 'Natalie', 'Epelman', 1, 2012, '5.jpg', 'natalie@gwu.edu', 'F', NULL, '1990-05-16'),
	(6, '1067760090', 'Marissa', 'Ostroff', 1, 2013, '6.jpg', 'marissa@gwu.edu', 'F', NULL, '1990-12-09'),
	(7, '1204337494', 'Alex', 'Webb', 1, 2012, '7.jpg', 'alex@gwu.edu', 'M', NULL, '0000-00-00'),
	(8, '704222664', 'Leon', 'Harari', 1, 2012, '8.jpg', 'leon@gwu.edu', 'M', NULL, '0000-00-00'),
	(9, '760370505', 'Jonny', 'Cohen', 1, 2012, '9.jpg', 'johnny@gwu.edu', 'M', NULL, '0000-00-00'),
	(10, '719185695', 'Cassie', 'Scheinmann', 1, 2013, '10.jpg', 'cassie@gwu.edu', 'F', NULL, '1991-03-05'),
	(11, '1099920067', 'Erica ', 'Obersi', 1, 2013, '11.jpg', 'erica@gwu.edu', 'F', NULL, '1990-04-30'),
	(12, '1682940070', 'Ava', 'Rubin', 1, 2013, '12.jpg', 'ava@gwu.edu', 'F', NULL, '1991-01-09'),
	(13, '1067760099', 'Anna ', 'Lepkoski', 1, 2013, '13.jpg', 'anna@gwu.edu', 'F', NULL, '1991-03-02'),
	(14, '1120470019', 'Sara', 'Sopher', 1, 2012, '14.jpg', 'sara@gwu.edu', 'F', NULL, '0000-00-00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
