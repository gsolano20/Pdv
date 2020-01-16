CREATE DATABASE IF NOT EXISTS mydb;

USE mydb;

DROP TABLE IF EXISTS categories;

CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO categories VALUES("1","Repuestos");



DROP TABLE IF EXISTS media;

CREATE TABLE `media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO media VALUES("1","filter.jpg","image/jpeg");
INSERT INTO media VALUES("4","carburador.jpg","image/jpeg");
INSERT INTO media VALUES("5","tornillos.jpg","image/jpeg");



DROP TABLE IF EXISTS parameter;

CREATE TABLE `parameter` (
  `parametro` varchar(100) NOT NULL,
  `Valor` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO parameter VALUES("Compania","Sistema PDV");
INSERT INTO parameter VALUES("ConsecutivoFactura","17");
INSERT INTO parameter VALUES("TelCompania","87355029");
INSERT INTO parameter VALUES("CedJuridica","00001");



DROP TABLE IF EXISTS products;

CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) unsigned NOT NULL,
  `media_id` int(11) DEFAULT '0',
  `date` datetime NOT NULL,
  `barcode` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `categorie_id` (`categorie_id`),
  KEY `media_id` (`media_id`),
  CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO products VALUES("1","Filtro de gasolina","63","5.00","10.00","1","1","2017-06-16 07:03:16","00001");
INSERT INTO products VALUES("2","carburador","-2","100.00","110.00","1","4","2018-08-16 17:17:14","00002");
INSERT INTO products VALUES("3","carburador 2","100","1000.00","1500.00","1","4","2018-08-20 13:09:11","00003");
INSERT INTO products VALUES("4","tornillos","9","25.00","30.00","1","5","2018-08-31 19:31:03","00004");



DROP TABLE IF EXISTS sales;

CREATE TABLE `sales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

INSERT INTO sales VALUES("1","2","5","550.00","2018-08-16");
INSERT INTO sales VALUES("2","2","2","220.00","2018-08-16");
INSERT INTO sales VALUES("3","1","1","5.00","2018-07-05");
INSERT INTO sales VALUES("4","2","1","100.00","2018-07-05");
INSERT INTO sales VALUES("6","1","1","5.00","2018-07-05");
INSERT INTO sales VALUES("7","2","1","100.00","2018-07-05");
INSERT INTO sales VALUES("9","2","2","200.00","2018-07-05");
INSERT INTO sales VALUES("10","2","1","100.00","2018-07-05");
INSERT INTO sales VALUES("11","1","25","125.00","2018-07-05");
INSERT INTO sales VALUES("13","1","1","5.00","2018-07-05");
INSERT INTO sales VALUES("14","1","1","5.00","2018-07-05");
INSERT INTO sales VALUES("15","4","1","25.00","2018-07-05");
INSERT INTO sales VALUES("16","2","1","100.00","2018-07-05");
INSERT INTO sales VALUES("17","1","1","5.00","2018-09-01");
INSERT INTO sales VALUES("18","2","1","100.00","2018-09-01");
INSERT INTO sales VALUES("20","1","2","10.00","2018-09-01");
INSERT INTO sales VALUES("21","2","1","100.00","2018-09-01");
INSERT INTO sales VALUES("22","1","1","5.00","2018-09-01");
INSERT INTO sales VALUES("24","2","3","300.00","2018-09-01");
INSERT INTO sales VALUES("25","2","1","100.00","2018-09-01");
INSERT INTO sales VALUES("26","1","1","5.00","2018-09-01");
INSERT INTO sales VALUES("27","2","1","100.00","2018-09-01");
INSERT INTO sales VALUES("28","2","1","100.00","2018-09-01");
INSERT INTO sales VALUES("29","1","1","5.00","2018-09-01");
INSERT INTO sales VALUES("30","1","1","5.00","2018-09-01");
INSERT INTO sales VALUES("31","1","1","5.00","2018-09-03");
INSERT INTO sales VALUES("32","1","1","5.00","2018-09-05");
INSERT INTO sales VALUES("33","2","1","100.00","2018-09-05");



DROP TABLE IF EXISTS user_groups;

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO user_groups VALUES("1","Admin","1","1");
INSERT INTO user_groups VALUES("2","Special","2","0");
INSERT INTO user_groups VALUES("3","User","3","1");



DROP TABLE IF EXISTS users;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `user_level` (`user_level`),
  CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO users VALUES("1","Admin Users","admin","admin","1","i1bgqgre1.png","1","2018-09-05 10:54:09");
INSERT INTO users VALUES("2","Special User","special","ba36b97a41e7faf742ab09bf88405ac04f99599a","2","bi942akz2.png","1","2018-08-20 13:31:38");
INSERT INTO users VALUES("3","Default User","user","12dea96fec20593566ab75692c9949596833adc9","3","si8xyzkm3.png","1","2018-08-20 13:30:43");



DROP TABLE IF EXISTS ventasdetalle;

CREATE TABLE `ventasdetalle` (
  `IdDetalle` int(11) NOT NULL AUTO_INCREMENT,
  `IdEncabezado` int(11) NOT NULL,
  `Precio` decimal(18,2) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `ImpuestodeVenta` decimal(18,2) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `Descuento` decimal(18,2) NOT NULL,
  PRIMARY KEY (`IdDetalle`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

INSERT INTO ventasdetalle VALUES("1","4","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("2","4","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("3","5","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("4","5","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("5","8","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("6","8","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("7","10","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("8","10","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("9","12","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("10","12","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("11","14","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("12","14","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("13","15","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("14","15","400.00","4","52.00","2","0.00");
INSERT INTO ventasdetalle VALUES("15","16","200.00","2","26.00","2","0.00");
INSERT INTO ventasdetalle VALUES("16","17","200.00","2","26.00","2","0.00");
INSERT INTO ventasdetalle VALUES("17","18","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("18","18","125.00","25","16.25","1","0.00");
INSERT INTO ventasdetalle VALUES("19","19","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("20","20","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("21","23","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("22","24","25.00","1","3.25","4","0.00");
INSERT INTO ventasdetalle VALUES("23","25","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("24","26","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("25","26","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("26","27","10.00","2","1.30","1","0.00");
INSERT INTO ventasdetalle VALUES("27","27","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("28","28","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("29","28","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("30","29","300.00","3","39.00","2","0.00");
INSERT INTO ventasdetalle VALUES("31","29","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("32","29","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("33","30","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("34","31","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("35","31","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("36","32","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("37","33","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("38","34","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("39","35","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("40","36","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("41","37","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("42","38","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("43","38","1000.00","1","130.00","3","0.00");
INSERT INTO ventasdetalle VALUES("44","39","100.00","1","13.00","2","0.00");
INSERT INTO ventasdetalle VALUES("45","39","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("46","40","5.00","1","0.65","1","0.00");
INSERT INTO ventasdetalle VALUES("47","41","100.00","1","13.00","2","0.00");



DROP TABLE IF EXISTS ventasencabezado;

CREATE TABLE `ventasencabezado` (
  `IdEncabezado` int(11) NOT NULL AUTO_INCREMENT,
  `NumFact` int(11) NOT NULL,
  `Total` decimal(18,2) NOT NULL,
  `Cliente` varchar(250) NOT NULL,
  `Fecha` date NOT NULL,
  `impuestodeventa` int(11) NOT NULL,
  PRIMARY KEY (`IdEncabezado`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

INSERT INTO ventasencabezado VALUES("4","1","10.00","test","2018-07-05","1");
INSERT INTO ventasencabezado VALUES("5","1","105.00","Prueba","2018-07-05","14");
INSERT INTO ventasencabezado VALUES("8","1","105.00","Prueba","2018-07-05","14");
INSERT INTO ventasencabezado VALUES("11","1","105.00","Prueba","2018-07-05","14");
INSERT INTO ventasencabezado VALUES("13","1","105.00","Prueba","2018-07-05","14");
INSERT INTO ventasencabezado VALUES("14","1","105.00","Prueba","2018-07-05","14");
INSERT INTO ventasencabezado VALUES("15","1","405.00","Prueba","2018-07-05","53");
INSERT INTO ventasencabezado VALUES("16","1","200.00","Prueba","2018-07-05","26");
INSERT INTO ventasencabezado VALUES("17","1","200.00","Prueba","2018-07-05","26");
INSERT INTO ventasencabezado VALUES("18","1","225.00","Prueba","2018-07-05","29");
INSERT INTO ventasencabezado VALUES("19","1","5.00","Prueba","2018-07-05","1");
INSERT INTO ventasencabezado VALUES("20","1","5.00","Prueba","2018-07-05","1");
INSERT INTO ventasencabezado VALUES("21","1","5.00","Prueba","2018-07-05","1");
INSERT INTO ventasencabezado VALUES("22","1","5.00","Prueba","2018-07-05","1");
INSERT INTO ventasencabezado VALUES("23","1","5.00","Prueba","2018-07-05","1");
INSERT INTO ventasencabezado VALUES("24","1","25.00","Prueba","2018-07-05","3");
INSERT INTO ventasencabezado VALUES("25","1","100.00","Prueba","2018-07-05","13");
INSERT INTO ventasencabezado VALUES("26","2","105.00","Prueba","2018-09-01","14");
INSERT INTO ventasencabezado VALUES("27","3","110.00","Prueba","2018-09-01","14");
INSERT INTO ventasencabezado VALUES("28","4","105.00","Prueba","2018-09-01","14");
INSERT INTO ventasencabezado VALUES("29","5","405.00","Prueba","2018-09-01","53");
INSERT INTO ventasencabezado VALUES("30","6","100.00","Prueba","2018-09-01","13");
INSERT INTO ventasencabezado VALUES("31","7","105.00","Prueba","2018-09-01","14");
INSERT INTO ventasencabezado VALUES("32","8","5.00","Prueba","2018-09-01","1");
INSERT INTO ventasencabezado VALUES("33","8","5.00","Prueba","2018-09-01","1");
INSERT INTO ventasencabezado VALUES("34","10","5.00","Prueba","2018-09-01","1");
INSERT INTO ventasencabezado VALUES("35","11","5.00","Prueba","2018-09-01","1");
INSERT INTO ventasencabezado VALUES("36","12","5.00","Prueba","2018-09-01","1");
INSERT INTO ventasencabezado VALUES("37","13","5.00","Prueba","2018-09-01","1");
INSERT INTO ventasencabezado VALUES("38","14","1005.00","Prueba","2018-09-03","131");
INSERT INTO ventasencabezado VALUES("39","15","105.00","Prueba","2018-09-03","14");
INSERT INTO ventasencabezado VALUES("40","15","5.00","Prueba","2018-09-05","1");
INSERT INTO ventasencabezado VALUES("41","16","100.00","Prueba","2018-09-05","13");



