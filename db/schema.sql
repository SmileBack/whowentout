# --------------------------------------------------------
# Host:                         
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-07-18 18:22:25
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.friends
CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `friend_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.options
CREATE TABLE IF NOT EXISTS `options` (
  `id` varchar(512) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


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

# Data exporting was unselected.


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
