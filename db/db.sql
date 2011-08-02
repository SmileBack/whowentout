# --------------------------------------------------------
# Host:                         
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-08-01 18:46:30
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table whowentout.party_attendees
CREATE TABLE IF NOT EXISTS `party_attendees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `party_id` int(10) unsigned NOT NULL,
  `checkin_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_party_id` (`user_id`,`party_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

# Dumping data for table whowentout.party_attendees: ~46 rows (approximately)
DELETE FROM `party_attendees`;
/*!40000 ALTER TABLE `party_attendees` DISABLE KEYS */;
INSERT INTO `party_attendees` (`id`, `user_id`, `party_id`, `checkin_time`) VALUES
	(3, 6, 15, '2011-07-15 23:18:22'),
	(4, 7, 11, '2011-07-17 00:04:12'),
	(5, 9, 9, '2011-07-17 00:18:32'),
	(6, 10, 14, '2011-07-13 06:34:44'),
	(8, 14, 14, '2011-07-13 06:34:47'),
	(10, 33, 14, '2011-07-27 23:56:21'),
	(15, 82, 11, '2011-07-17 00:45:33'),
	(16, 82, 14, '2011-07-13 06:34:41'),
	(18, 86, 11, '2011-07-17 00:47:10'),
	(20, 89, 9, '2011-07-17 17:50:46'),
	(22, 91, 9, '2011-07-17 00:17:35'),
	(23, 91, 17, '2011-07-30 22:12:26'),
	(24, 93, 15, '2011-07-15 23:18:20'),
	(25, 95, 15, '2011-07-15 23:18:18'),
	(28, 98, 11, '2011-07-17 00:45:31'),
	(29, 98, 15, '2011-07-15 23:18:21'),
	(30, 99, 15, '2011-07-15 23:18:19'),
	(31, 100, 11, '2011-07-17 00:47:08'),
	(32, 101, 15, '2011-07-15 22:55:24'),
	(35, 104, 14, '2011-07-13 06:34:46'),
	(38, 108, 11, '2011-07-17 00:45:29'),
	(45, 156, 14, '2011-07-27 23:56:06'),
	(46, 156, 17, '2011-07-30 22:08:14'),
	(49, 156, 10, '2011-08-02 00:50:00'),
	(50, 122, 10, '2011-08-02 00:52:01'),
	(51, 132, 10, '2011-08-02 00:54:04'),
	(52, 80, 10, '2011-08-02 01:06:57'),
	(53, 138, 10, '2011-08-02 01:10:26'),
	(54, 134, 10, '2011-08-02 01:12:54');
/*!40000 ALTER TABLE `party_attendees` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
