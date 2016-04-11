-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 11, 2016 at 05:02 PM
-- Server version: 5.5.47-0+deb8u1
-- PHP Version: 5.6.17-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `foseto`
--

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

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

CREATE TABLE IF NOT EXISTS `orders` (
`id` int(11) NOT NULL,
  `client` varchar(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiration` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total` int(5) NOT NULL DEFAULT '0',
  `status` varchar(1) NOT NULL DEFAULT 'O'
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Triggers `orders`
--
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

CREATE TABLE IF NOT EXISTS `order_ingredient` (
  `order_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` varchar(1) NOT NULL COMMENT 'N: normal, F:Few, M:more'
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Triggers `order_ingredient`
--
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
-- Constraints for dumped tables
--

--
-- Constraints for table `order_ingredient`
--
ALTER TABLE `order_ingredient`
ADD CONSTRAINT `ing_id_fk` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
