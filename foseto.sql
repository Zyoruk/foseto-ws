-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 22-05-2016 a las 00:13:25
-- Versión del servidor: 5.6.30-0ubuntu0.15.10.1
-- Versión de PHP: 5.6.11-1ubuntu3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `foseto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `commentId` int(11) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=ascii;

--
-- Volcado de datos para la tabla `comments`
--

INSERT INTO `comments` (`commentId`, `comment`, `userId`) VALUES
(1, 'Muy rico el helado de Vainilla', 1),
(2, 'Me echaron mucho barquillo', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `available` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '0=NoDisponible, 1=Disponible',
  `price` int(5) unsigned NOT NULL DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT 'N' COMMENT 'N=normal , F=few, E=empty ',
  `serving` int(6) NOT NULL DEFAULT '0' COMMENT '0=SinServirse, 1=Sirviendose',
  `type` int(4) NOT NULL COMMENT '0=Helado, 1=Topping, 2=Adicional',
  `image` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=ascii;

--
-- Volcado de datos para la tabla `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `available`, `price`, `status`, `serving`, `type`, `image`) VALUES
(1, 'Chocolate', 1, 900, 'N', 0, 0, 'https://db.tt/m0YammJv'),
(2, 'Vainilla', 1, 900, 'N', 1, 0, ''),
(3, 'Barquillo', 1, 400, 'N', 0, 2, 'https://db.tt/v34kyjTU'),
(4, 'Fresa', 1, 500, 'N', 0, 1, ''),
(5, 'Chispas', 1, 600, 'N', 0, 1, 'https://db.tt/UQjaK2v7'),
(6, 'Galleta', 1, 350, 'N', 0, 2, ''),
(7, 'Chicle', 0, 800, 'N', 0, 0, ''),
(8, 'Limon', 1, 550, 'N', 0, 0, ''),
(9, 'Sirope', 1, 350, 'N', 1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL,
  `clientId` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiration` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total` int(5) NOT NULL DEFAULT '0',
  `status` varchar(1) NOT NULL DEFAULT 'O' COMMENT 'o : open , e:expired, p :processed'
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=ascii;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `clientId`, `created`, `expiration`, `total`, `status`) VALUES
(10, 1, '2016-05-19 20:34:26', '0000-00-00 00:00:00', 0, 'O'),
(11, 1, '2016-05-19 20:35:17', '0000-00-00 00:00:00', 0, 'O'),
(12, 1, '2016-05-19 21:33:52', '0000-00-00 00:00:00', 0, 'O'),
(13, 1, '2016-05-19 21:36:54', '0000-00-00 00:00:00', 0, 'O'),
(14, 1, '2016-05-19 21:37:49', '0000-00-00 00:00:00', 0, 'O'),
(15, 1, '2016-05-19 21:38:45', '0000-00-00 00:00:00', 0, 'O'),
(16, 1, '2016-05-19 21:40:07', '0000-00-00 00:00:00', 0, 'O'),
(17, 1, '2016-05-19 21:45:14', '0000-00-00 00:00:00', 0, 'O'),
(18, 1, '2016-05-19 21:45:50', '0000-00-00 00:00:00', 0, 'O'),
(19, 1, '2016-05-19 21:52:58', '0000-00-00 00:00:00', 0, 'O'),
(20, 1, '2016-05-19 21:58:15', '0000-00-00 00:00:00', 0, 'O'),
(21, 1, '2016-05-19 21:59:48', '0000-00-00 00:00:00', 0, 'O'),
(22, 1, '2016-05-19 22:00:33', '0000-00-00 00:00:00', 0, 'O'),
(23, 1, '2016-05-19 22:01:06', '0000-00-00 00:00:00', 0, 'O'),
(24, 1, '2016-05-19 22:01:36', '0000-00-00 00:00:00', 0, 'O'),
(25, 1, '2016-05-19 22:04:50', '0000-00-00 00:00:00', 0, 'O'),
(26, 5, '2016-05-19 22:05:34', '0000-00-00 00:00:00', 0, 'O'),
(27, 5, '2016-05-19 22:06:55', '0000-00-00 00:00:00', 0, 'O'),
(28, 5, '2016-05-19 22:08:02', '0000-00-00 00:00:00', 0, 'O'),
(29, 5, '2016-05-19 22:09:03', '0000-00-00 00:00:00', 0, 'O'),
(30, 5, '2016-05-19 22:22:28', '0000-00-00 00:00:00', 0, 'O'),
(31, 5, '2016-05-19 22:23:24', '0000-00-00 00:00:00', 0, 'O'),
(32, 5, '2016-05-19 22:24:34', '0000-00-00 00:00:00', 0, 'O'),
(33, 5, '2016-05-19 22:25:17', '0000-00-00 00:00:00', 0, 'O'),
(34, 5, '2016-05-19 22:26:32', '0000-00-00 00:00:00', 0, 'O'),
(35, 5, '2016-05-19 22:32:26', '0000-00-00 00:00:00', 0, 'O'),
(36, 5, '2016-05-19 22:33:38', '0000-00-00 00:00:00', 0, 'O'),
(37, 5, '2016-05-19 22:35:27', '0000-00-00 00:00:00', 0, 'O'),
(38, 5, '2016-05-19 22:37:28', '0000-00-00 00:00:00', 0, 'O'),
(39, 5, '2016-05-19 22:39:16', '0000-00-00 00:00:00', 0, 'O'),
(40, 5, '2016-05-19 22:40:29', '0000-00-00 00:00:00', 0, 'O'),
(41, 5, '2016-05-19 22:42:34', '0000-00-00 00:00:00', 0, 'O'),
(42, 5, '2016-05-19 22:47:04', '0000-00-00 00:00:00', 0, 'O'),
(43, 5, '2016-05-19 22:49:15', '0000-00-00 00:00:00', 0, 'O'),
(44, 5, '2016-05-19 22:51:43', '0000-00-00 00:00:00', 0, 'O'),
(45, 5, '2016-05-19 22:52:14', '0000-00-00 00:00:00', 0, 'O'),
(46, 5, '2016-05-19 22:52:40', '0000-00-00 00:00:00', 0, 'O'),
(47, 5, '2016-05-19 22:53:09', '0000-00-00 00:00:00', 0, 'O'),
(48, 5, '2016-05-19 22:54:14', '0000-00-00 00:00:00', 0, 'O'),
(49, 5, '2016-05-19 22:56:00', '0000-00-00 00:00:00', 0, 'O'),
(50, 5, '2016-05-19 22:57:21', '0000-00-00 00:00:00', 0, 'O'),
(51, 5, '2016-05-19 22:58:30', '0000-00-00 00:00:00', 0, 'O'),
(52, 5, '2016-05-19 22:59:11', '0000-00-00 00:00:00', 0, 'O'),
(53, 5, '2016-05-19 22:59:49', '0000-00-00 00:00:00', 0, 'O'),
(54, 5, '2016-05-19 23:00:08', '0000-00-00 00:00:00', 0, 'O'),
(55, 5, '2016-05-19 23:00:38', '0000-00-00 00:00:00', 0, 'O'),
(56, 5, '2016-05-19 23:01:50', '0000-00-00 00:00:00', 0, 'O'),
(57, 5, '2016-05-19 23:02:29', '0000-00-00 00:00:00', 0, 'O'),
(58, 5, '2016-05-19 23:03:00', '0000-00-00 00:00:00', 0, 'O'),
(59, 5, '2016-05-19 23:04:00', '0000-00-00 00:00:00', 0, 'O'),
(60, 5, '2016-05-19 23:05:12', '0000-00-00 00:00:00', 0, 'O'),
(61, 5, '2016-05-19 23:08:05', '0000-00-00 00:00:00', 0, 'O'),
(62, 5, '2016-05-19 23:11:13', '0000-00-00 00:00:00', 0, 'O'),
(63, 5, '2016-05-19 23:11:50', '0000-00-00 00:00:00', 0, 'O'),
(64, 5, '2016-05-19 23:12:50', '0000-00-00 00:00:00', 0, 'O'),
(65, 5, '2016-05-19 23:13:25', '0000-00-00 00:00:00', 0, 'O'),
(66, 1, '2016-05-20 02:37:25', '0000-00-00 00:00:00', 1650, 'O');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_ingredient`
--

CREATE TABLE IF NOT EXISTS `order_ingredient` (
  `order_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` varchar(1) NOT NULL COMMENT 'N: normal, F:Few, M:more'
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Volcado de datos para la tabla `order_ingredient`
--

INSERT INTO `order_ingredient` (`order_id`, `ingredient_id`, `quantity`) VALUES
(38, 1, 'P'),
(40, 1, 'P'),
(41, 1, 'P'),
(43, 1, 'P'),
(47, 1, 'P'),
(48, 1, 'P'),
(51, 1, ''),
(52, 1, 'R'),
(53, 1, 'P'),
(54, 1, 'R'),
(55, 1, 'P'),
(56, 1, 'P'),
(57, 1, 'P'),
(60, 1, 'P'),
(61, 1, 'P'),
(63, 1, 'P'),
(64, 1, ''),
(65, 1, ''),
(66, 1, 'P'),
(66, 5, 'M');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `request`
--

CREATE TABLE IF NOT EXISTS `request` (
  `rid` int(11) NOT NULL,
  `oid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `nick` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(32) NOT NULL,
  `pass` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=ascii;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `nick`, `name`, `email`, `pass`) VALUES
(0, 'admin', 'Administrador', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3'),
(1, 'Prueba', 'Prueba prueba', 'prueba@gmail.com', 'c893bad68927b457dbed39460e6afd62'),
(5, '', 'Luis Alonsote', 'luis@gmail.com', 'bbfcf4f1c86d2c6adcb3a4d7632bb16e'),
(6, 'root', 'Erick Cordero Rojas', 'eguicoro2@hotmail.com', '7b55f59d034002b5fdb7eee735c8846f');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentId`),
  ADD UNIQUE KEY `commentId` (`commentId`);

--
-- Indices de la tabla `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_ingredient`
--
ALTER TABLE `order_ingredient`
  ADD PRIMARY KEY (`order_id`,`ingredient_id`),
  ADD KEY `ing_id_fk` (`ingredient_id`);

--
-- Indices de la tabla `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`rid`,`oid`),
  ADD KEY `oid` (`oid`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `order_ingredient`
--
ALTER TABLE `order_ingredient`
  ADD CONSTRAINT `ing_id_fk` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`),
  ADD CONSTRAINT `order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
