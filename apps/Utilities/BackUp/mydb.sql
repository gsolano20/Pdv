-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 04, 2018 at 08:07 AM
-- Server version: 5.5.45
-- PHP Version: 7.0.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getORsetConsecutivo` (IN `getORset` INT)  begin
  IF  (1=getORset) THEN
   SELECT Valor FROM `parameter` WHERE parametro='consecutivoFactura';
  ELSE
    Update parameter SET valor=valor+1 where parametro='consecutivoFactura';
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertVentasDetalle` (IN `Idencabezado` INT, IN `precio` DECIMAL(18,2), IN `cantidad` INT, IN `ImpuestoVenta` DECIMAL(18,2), IN `IdProducto` INT, IN `Descuento` DECIMAL(18,2))  begin
INSERT INTO `ventasdetalle` (`IdDetalle`, `IdEncabezado`, `Precio`, `Cantidad`, `ImpuestodeVenta`, `IdProducto`, `Descuento`) VALUES (NULL, Idencabezado, precio, cantidad, ImpuestoVenta, IdProducto, Descuento);
select 1 as result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertVentasEncabezado` (IN `Idencabezado` INT, IN `NumFact` VARCHAR(25), IN `Total` DECIMAL(18,2), IN `Cliente` VARCHAR(250), IN `Fecha` DATE, IN `ImpuestoVenta` DECIMAL(18,2))  begin

INSERT INTO ventasencabezado (`IdEncabezado` , `NumFact` , `Total` , `Cliente` , `Fecha` , `impuestodeventa`) VALUES ( NULL ,NumFact ,Total ,Cliente ,Fecha ,ImpuestoVenta ); SELECT @@IDENTITY as Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateSales` (IN `idencabezado` INT)  begin

 INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) 
 SELECT  NULL, ventasdetalle.`IdProducto`,ventasdetalle.`Cantidad`,ventasdetalle.`Precio`,ventasencabezado.Fecha 
 FROM `ventasdetalle` inner join ventasencabezado on ventasencabezado.IdEncabezado=ventasdetalle.IdEncabezado 
 WHERE ventasencabezado.IdEncabezado=idencabezado;
CALL updateSales2(idencabezado);
CALL `getORsetConsecutivo`(0);

select 1 as result;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateSales2` (IN `idencabezado` INT)  begin

UPDATE
products inner JOIN ventasdetalle on ventasdetalle.IdProducto=products.id
SET products.quantity=(products.quantity-ventasdetalle.Cantidad)
where ventasdetalle.IdEncabezado=idencabezado;


END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Repuestos');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `file_name`, `file_type`) VALUES
(1, 'filter.jpg', 'image/jpeg'),
(4, 'carburador.jpg', 'image/jpeg'),
(5, 'tornillos.jpg', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `parameter`
--

CREATE TABLE `parameter` (
  `parametro` varchar(100) NOT NULL,
  `Valor` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parameter`
--

INSERT INTO `parameter` (`parametro`, `Valor`) VALUES
('Compania', 'Sistema PDV'),
('ConsecutivoFactura', '15'),
('TelCompania', '87355029'),
('CedJuridica', '00001');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) UNSIGNED NOT NULL,
  `media_id` int(11) DEFAULT '0',
  `date` datetime NOT NULL,
  `barcode` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`, `barcode`) VALUES
(1, 'Filtro de gasolina', '62', '5.00', '10.00', 1, 1, '2017-06-16 07:03:16', '00001'),
(2, 'carburador', '-1', '100.00', '110.00', 1, 4, '2018-08-16 17:17:14', '00002'),
(3, 'carburador 2', '100', '1000.00', '1500.00', 1, 4, '2018-08-20 13:09:11', '00003'),
(4, 'tornillos', '9', '25.00', '30.00', 1, 5, '2018-08-31 19:31:03', '00004');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) VALUES
(1, 2, 5, '550.00', '2018-08-16'),
(2, 2, 2, '220.00', '2018-08-16'),
(3, 1, 1, '5.00', '2018-07-05'),
(4, 2, 1, '100.00', '2018-07-05'),
(6, 1, 1, '5.00', '2018-07-05'),
(7, 2, 1, '100.00', '2018-07-05'),
(9, 2, 2, '200.00', '2018-07-05'),
(10, 2, 1, '100.00', '2018-07-05'),
(11, 1, 25, '125.00', '2018-07-05'),
(13, 1, 1, '5.00', '2018-07-05'),
(14, 1, 1, '5.00', '2018-07-05'),
(15, 4, 1, '25.00', '2018-07-05'),
(16, 2, 1, '100.00', '2018-07-05'),
(17, 1, 1, '5.00', '2018-09-01'),
(18, 2, 1, '100.00', '2018-09-01'),
(20, 1, 2, '10.00', '2018-09-01'),
(21, 2, 1, '100.00', '2018-09-01'),
(22, 1, 1, '5.00', '2018-09-01'),
(24, 2, 3, '300.00', '2018-09-01'),
(25, 2, 1, '100.00', '2018-09-01'),
(26, 1, 1, '5.00', '2018-09-01'),
(27, 2, 1, '100.00', '2018-09-01'),
(28, 2, 1, '100.00', '2018-09-01'),
(29, 1, 1, '5.00', '2018-09-01'),
(30, 1, 1, '5.00', '2018-09-01'),
(31, 1, 1, '5.00', '2018-09-03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'Admin Users', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'i1bgqgre1.png', 1, '2018-09-02 20:35:01'),
(2, 'Special User', 'special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'bi942akz2.png', 1, '2018-08-20 13:31:38'),
(3, 'Default User', 'user', '12dea96fec20593566ab75692c9949596833adc9', 3, 'si8xyzkm3.png', 1, '2018-08-20 13:30:43');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'Special', 2, 0),
(3, 'User', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ventasdetalle`
--

CREATE TABLE `ventasdetalle` (
  `IdDetalle` int(11) NOT NULL,
  `IdEncabezado` int(11) NOT NULL,
  `Precio` decimal(18,2) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `ImpuestodeVenta` decimal(18,2) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `Descuento` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ventasdetalle`
--

INSERT INTO `ventasdetalle` (`IdDetalle`, `IdEncabezado`, `Precio`, `Cantidad`, `ImpuestodeVenta`, `IdProducto`, `Descuento`) VALUES
(1, 4, '5.00', 1, '0.65', 1, '0.00'),
(2, 4, '100.00', 1, '13.00', 2, '0.00'),
(3, 5, '100.00', 1, '13.00', 2, '0.00'),
(4, 5, '5.00', 1, '0.65', 1, '0.00'),
(5, 8, '5.00', 1, '0.65', 1, '0.00'),
(6, 8, '100.00', 1, '13.00', 2, '0.00'),
(7, 10, '5.00', 1, '0.65', 1, '0.00'),
(8, 10, '100.00', 1, '13.00', 2, '0.00'),
(9, 12, '100.00', 1, '13.00', 2, '0.00'),
(10, 12, '5.00', 1, '0.65', 1, '0.00'),
(11, 14, '5.00', 1, '0.65', 1, '0.00'),
(12, 14, '100.00', 1, '13.00', 2, '0.00'),
(13, 15, '5.00', 1, '0.65', 1, '0.00'),
(14, 15, '400.00', 4, '52.00', 2, '0.00'),
(15, 16, '200.00', 2, '26.00', 2, '0.00'),
(16, 17, '200.00', 2, '26.00', 2, '0.00'),
(17, 18, '100.00', 1, '13.00', 2, '0.00'),
(18, 18, '125.00', 25, '16.25', 1, '0.00'),
(19, 19, '5.00', 1, '0.65', 1, '0.00'),
(20, 20, '5.00', 1, '0.65', 1, '0.00'),
(21, 23, '5.00', 1, '0.65', 1, '0.00'),
(22, 24, '25.00', 1, '3.25', 4, '0.00'),
(23, 25, '100.00', 1, '13.00', 2, '0.00'),
(24, 26, '5.00', 1, '0.65', 1, '0.00'),
(25, 26, '100.00', 1, '13.00', 2, '0.00'),
(26, 27, '10.00', 2, '1.30', 1, '0.00'),
(27, 27, '100.00', 1, '13.00', 2, '0.00'),
(28, 28, '100.00', 1, '13.00', 2, '0.00'),
(29, 28, '5.00', 1, '0.65', 1, '0.00'),
(30, 29, '300.00', 3, '39.00', 2, '0.00'),
(31, 29, '100.00', 1, '13.00', 2, '0.00'),
(32, 29, '5.00', 1, '0.65', 1, '0.00'),
(33, 30, '100.00', 1, '13.00', 2, '0.00'),
(34, 31, '100.00', 1, '13.00', 2, '0.00'),
(35, 31, '5.00', 1, '0.65', 1, '0.00'),
(36, 32, '5.00', 1, '0.65', 1, '0.00'),
(37, 33, '5.00', 1, '0.65', 1, '0.00'),
(38, 34, '5.00', 1, '0.65', 1, '0.00'),
(39, 35, '5.00', 1, '0.65', 1, '0.00'),
(40, 36, '5.00', 1, '0.65', 1, '0.00'),
(41, 37, '5.00', 1, '0.65', 1, '0.00'),
(42, 38, '5.00', 1, '0.65', 1, '0.00'),
(43, 38, '1000.00', 1, '130.00', 3, '0.00'),
(44, 39, '100.00', 1, '13.00', 2, '0.00'),
(45, 39, '5.00', 1, '0.65', 1, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `ventasencabezado`
--

CREATE TABLE `ventasencabezado` (
  `IdEncabezado` int(11) NOT NULL,
  `NumFact` int(11) NOT NULL,
  `Total` decimal(18,2) NOT NULL,
  `Cliente` varchar(250) NOT NULL,
  `Fecha` date NOT NULL,
  `impuestodeventa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ventasencabezado`
--

INSERT INTO `ventasencabezado` (`IdEncabezado`, `NumFact`, `Total`, `Cliente`, `Fecha`, `impuestodeventa`) VALUES
(4, 1, '10.00', 'test', '2018-07-05', 1),
(5, 1, '105.00', 'Prueba', '2018-07-05', 14),
(8, 1, '105.00', 'Prueba', '2018-07-05', 14),
(11, 1, '105.00', 'Prueba', '2018-07-05', 14),
(13, 1, '105.00', 'Prueba', '2018-07-05', 14),
(14, 1, '105.00', 'Prueba', '2018-07-05', 14),
(15, 1, '405.00', 'Prueba', '2018-07-05', 53),
(16, 1, '200.00', 'Prueba', '2018-07-05', 26),
(17, 1, '200.00', 'Prueba', '2018-07-05', 26),
(18, 1, '225.00', 'Prueba', '2018-07-05', 29),
(19, 1, '5.00', 'Prueba', '2018-07-05', 1),
(20, 1, '5.00', 'Prueba', '2018-07-05', 1),
(21, 1, '5.00', 'Prueba', '2018-07-05', 1),
(22, 1, '5.00', 'Prueba', '2018-07-05', 1),
(23, 1, '5.00', 'Prueba', '2018-07-05', 1),
(24, 1, '25.00', 'Prueba', '2018-07-05', 3),
(25, 1, '100.00', 'Prueba', '2018-07-05', 13),
(26, 2, '105.00', 'Prueba', '2018-09-01', 14),
(27, 3, '110.00', 'Prueba', '2018-09-01', 14),
(28, 4, '105.00', 'Prueba', '2018-09-01', 14),
(29, 5, '405.00', 'Prueba', '2018-09-01', 53),
(30, 6, '100.00', 'Prueba', '2018-09-01', 13),
(31, 7, '105.00', 'Prueba', '2018-09-01', 14),
(32, 8, '5.00', 'Prueba', '2018-09-01', 1),
(33, 8, '5.00', 'Prueba', '2018-09-01', 1),
(34, 10, '5.00', 'Prueba', '2018-09-01', 1),
(35, 11, '5.00', 'Prueba', '2018-09-01', 1),
(36, 12, '5.00', 'Prueba', '2018-09-01', 1),
(37, 13, '5.00', 'Prueba', '2018-09-01', 1),
(38, 14, '1005.00', 'Prueba', '2018-09-03', 131),
(39, 15, '105.00', 'Prueba', '2018-09-03', 14);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- Indexes for table `ventasdetalle`
--
ALTER TABLE `ventasdetalle`
  ADD PRIMARY KEY (`IdDetalle`);

--
-- Indexes for table `ventasencabezado`
--
ALTER TABLE `ventasencabezado`
  ADD PRIMARY KEY (`IdEncabezado`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ventasdetalle`
--
ALTER TABLE `ventasdetalle`
  MODIFY `IdDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `ventasencabezado`
--
ALTER TABLE `ventasencabezado`
  MODIFY `IdEncabezado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
