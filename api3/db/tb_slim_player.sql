# Host: localhost  (Version 5.5.5-10.4.13-MariaDB)
# Date: 2020-10-12 17:35:31
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "tb_slim_player"
#

DROP TABLE IF EXISTS `tb_slim_player`;
CREATE TABLE `tb_slim_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api1_val` int(10) DEFAULT 0,
  `api2_val` int(10) DEFAULT NULL,
  `api3_val` int(10) DEFAULT NULL,
  `winner` varchar(20) DEFAULT NULL,
  `player_status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
