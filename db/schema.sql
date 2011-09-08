# --------------------------------------------------------
# Host:                         127.0.0.1
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-09-08 16:58:31
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table whowentout.chat_messages
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT 'normal',
  `sent_at` int(10) unsigned NOT NULL,
  `sender_id` int(10) unsigned NOT NULL,
  `receiver_id` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `is_read` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


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


# Dumping structure for table whowentout.college_students
CREATE TABLE IF NOT EXISTS `college_students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `college_id` int(10) unsigned NOT NULL,
  `facebook_id` varchar(255) NOT NULL,
  `student_full_name` varchar(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `college_id` (`college_id`),
  KEY `student_full_name` (`student_full_name`),
  KEY `facebook_id` (`facebook_id`),
  CONSTRAINT `college_students_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.common_nicknames
CREATE TABLE IF NOT EXISTS `common_nicknames` (
  `name` varchar(256) NOT NULL,
  `nickname` varchar(256) NOT NULL,
  PRIMARY KEY (`name`,`nickname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `channel` varchar(255) NOT NULL DEFAULT 'site',
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.friends
CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_facebook_id` bigint(20) unsigned DEFAULT NULL,
  `friend_id` int(10) unsigned DEFAULT NULL,
  `friend_facebook_id` bigint(20) unsigned NOT NULL,
  `friend_full_name` varchar(256) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `friend_full_name` (`friend_full_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` varchar(40) NOT NULL,
  `type` varchar(64) NOT NULL,
  `status` varchar(32) DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `executed` int(10) unsigned DEFAULT NULL,
  `args` text,
  `error_message` text,
  `error_line` text,
  `error_file` text,
  `error` text,
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
  `version` int(10) unsigned NOT NULL DEFAULT '0',
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `party_id` int(10) unsigned NOT NULL,
  `checkin_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_party_id` (`user_id`,`party_id`),
  KEY `user_id_key` (`user_id`),
  KEY `party_id_key` (`party_id`),
  CONSTRAINT `party_attendee_party` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `party_attendee_user` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`)
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


# Dumping structure for table whowentout.schema_version
CREATE TABLE IF NOT EXISTS `schema_version` (
  `version` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table whowentout.sent_emails
CREATE TABLE IF NOT EXISTS `sent_emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recipient_email` varchar(255) NOT NULL,
  `subject` text,
  `body` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  `debug` text,
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


# Dumping structure for table whowentout.smile_matches
CREATE TABLE IF NOT EXISTS `smile_matches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_smile_id` int(10) unsigned NOT NULL,
  `second_smile_id` int(10) unsigned NOT NULL,
  `first_user_id` int(10) unsigned NOT NULL,
  `second_user_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `first_smile_id` (`first_smile_id`),
  KEY `second_smile_id` (`second_smile_id`),
  KEY `first_user_id` (`first_user_id`),
  KEY `second_user_id` (`second_user_id`),
  CONSTRAINT `first_smile_id_fk` FOREIGN KEY (`first_smile_id`) REFERENCES `smiles` (`id`),
  CONSTRAINT `first_user_id_fk` FOREIGN KEY (`first_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `second_smile_id_fk` FOREIGN KEY (`second_smile_id`) REFERENCES `smiles` (`id`),
  CONSTRAINT `second_user_id_fk` FOREIGN KEY (`second_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table whowentout.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned NOT NULL DEFAULT '0',
  `facebook_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `hometown_city` varchar(255) NOT NULL,
  `hometown_state` varchar(255) NOT NULL,
  `college_id` int(10) unsigned DEFAULT NULL,
  `grad_year` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `registration_time` datetime DEFAULT NULL,
  `last_ping` datetime DEFAULT NULL,
  `last_updated_friends` datetime DEFAULT NULL,
  `last_edit` datetime DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `pic_x` int(10) unsigned DEFAULT NULL,
  `pic_y` int(10) unsigned DEFAULT NULL,
  `pic_width` int(10) unsigned DEFAULT NULL,
  `pic_height` int(10) unsigned DEFAULT NULL,
  `pic_version` int(10) unsigned NOT NULL DEFAULT '1',
  `chatbar_state` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_facebook_id` (`facebook_id`),
  KEY `college_id` (`college_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
