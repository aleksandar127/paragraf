-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 23, 2020 at 06:59 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `osiguranje`
--

-- --------------------------------------------------------

--
-- Table structure for table `osiguranici`
--

DROP TABLE IF EXISTS `osiguranici`;
CREATE TABLE IF NOT EXISTS `osiguranici` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ime` varchar(45) NOT NULL,
  `datum_rodjenja` date NOT NULL,
  `broj_pasosa` char(9) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telefon` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `broj_pasosa` (`broj_pasosa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `osiguranici_polise`
--

DROP TABLE IF EXISTS `osiguranici_polise`;
CREATE TABLE IF NOT EXISTS `osiguranici_polise` (
  `osiguranici_id` int(11) NOT NULL,
  `polise_id` int(11) NOT NULL,
  `nosilac` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`osiguranici_id`,`polise_id`),
  KEY `fk_osiguranici_has_polise_polise1_idx` (`polise_id`),
  KEY `fk_osiguranici_has_polise_osiguranici_idx` (`osiguranici_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `polise`
--

DROP TABLE IF EXISTS `polise`;
CREATE TABLE IF NOT EXISTS `polise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datum_kreiranja` datetime DEFAULT CURRENT_TIMESTAMP,
  `datum_polaska` date NOT NULL,
  `datum_dolaska` date NOT NULL,
  `tip_polise` enum('individualna','grupna') DEFAULT 'individualna',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `osiguranici_polise`
--
ALTER TABLE `osiguranici_polise`
  ADD CONSTRAINT `fk_osiguranici_has_polise_osiguranici` FOREIGN KEY (`osiguranici_id`) REFERENCES `osiguranici` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_osiguranici_has_polise_polise1` FOREIGN KEY (`polise_id`) REFERENCES `polise` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
