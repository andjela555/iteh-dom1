/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 10.4.11-MariaDB : Database - bioskop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`bioskop` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `bioskop`;

/*Table structure for table `film` */

DROP TABLE IF EXISTS `film`;

CREATE TABLE `film` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(40) DEFAULT NULL,
  `trajanje` int(11) DEFAULT NULL,
  `ocena` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `film` */

insert  into `film`(`id`,`naziv`,`trajanje`,`ocena`) values 
(1,'djavo nosi pradu',95,4.20),
(2,'nesto',110,4.40);

/*Table structure for table `prikaz` */

DROP TABLE IF EXISTS `prikaz`;

CREATE TABLE `prikaz` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `film_id` bigint(20) DEFAULT NULL,
  `sala` bigint(20) DEFAULT NULL,
  `cena` decimal(11,2) DEFAULT NULL,
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `film_id` (`film_id`),
  KEY `sala` (`sala`),
  CONSTRAINT `prikaz_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`),
  CONSTRAINT `prikaz_ibfk_2` FOREIGN KEY (`sala`) REFERENCES `sala` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `prikaz` */

insert  into `prikaz`(`id`,`film_id`,`sala`,`cena`,`datum`) values 
(2,1,1,1500.00,'2022-08-28 13:17:00'),
(5,1,1,1500.00,'2022-08-28 13:16:00'),
(6,1,1,1400.00,'2022-09-01 21:23:00');

/*Table structure for table `sala` */

DROP TABLE IF EXISTS `sala`;

CREATE TABLE `sala` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(49) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `sala` */

insert  into `sala`(`id`,`naziv`) values 
(1,'glavna'),
(2,'pomocna'),
(3,'3D');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
