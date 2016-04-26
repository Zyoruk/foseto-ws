-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 26, 2016 at 05:08 PM
-- Server version: 5.5.47-0+deb8u1
-- PHP Version: 5.6.17-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `foseto`
--
CREATE DATABASE IF NOT EXISTS `foseto` DEFAULT CHARACTER SET ascii COLLATE ascii_general_ci;
USE `foseto`;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE IF NOT EXISTS `ingredients` (
`id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `available` int(6) unsigned NOT NULL DEFAULT '0',
  `price` int(5) unsigned NOT NULL DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT 'N' COMMENT 'N = normal , F = few, E: empty ',
  `serving` int(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
`id` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiration` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total` int(5) NOT NULL DEFAULT '0',
  `status` varchar(1) NOT NULL DEFAULT 'O' COMMENT 'o : open , e:expired, p :processed'
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- RELATIONS FOR TABLE `orders`:
--   `client`
--       `user` -> `id`
--

--
-- Triggers `orders`
--
DROP TRIGGER IF EXISTS `add_Expiration`;
DELIMITER //
CREATE TRIGGER `add_Expiration` AFTER INSERT ON `orders`
 FOR EACH ROW UPDATE orders
SET expiration = (SELECT sysdate, sysdate + interval '30' minute FROM dual)
WHERE id = new.id
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_ingredient`
--

DROP TABLE IF EXISTS `order_ingredient`;
CREATE TABLE IF NOT EXISTS `order_ingredient` (
  `order_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` varchar(1) NOT NULL COMMENT 'N: normal, F:Few, M:more'
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- RELATIONS FOR TABLE `order_ingredient`:
--   `ingredient_id`
--       `ingredients` -> `id`
--   `order_id`
--       `orders` -> `id`
--

--
-- Triggers `order_ingredient`
--
DROP TRIGGER IF EXISTS `addTotal`;
DELIMITER //
CREATE TRIGGER `addTotal` AFTER INSERT ON `order_ingredient`
 FOR EACH ROW BEGIN
    DECLARE price integer;
    DECLARE total integer; 
    SET @price := (SELECT `ingredients`.price 
                 FROM ingredients 
                 WHERE `ingredients`.id = ingredient_id);
    IF quantity = 'F' THEN
        SET @price := price / 2;
    END IF;
    IF quantity = 'M' THEN
        SET @price := price * 2;
    END IF  ;
    SET @total := (SELECT `orders`.total 
                 FROM orders 
                 WHERE `orders`.id = order_id);
                 
    SET @total := @total + @price;
    
    UPDATE orders 
    SET `oders`.total = @total  
    WHERE `orders`.id = order_id;      
    
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `nick` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` int(30) NOT NULL,
  `pass` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_ingredient`
--
ALTER TABLE `order_ingredient`
 ADD PRIMARY KEY (`order_id`,`ingredient_id`), ADD KEY `ing_id_fk` (`ingredient_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_ingredient`
--
ALTER TABLE `order_ingredient`
ADD CONSTRAINT `ing_id_fk` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
