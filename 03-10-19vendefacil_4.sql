# ************************************************************
# Sequel Pro SQL dump
# Versión 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Base de datos: vendefacil_4
# Tiempo de Generación: 2019-10-03 22:30:12 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Volcado de tabla categorias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id_categoria` mediumint(3) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `impresora` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `activo` int(1) NOT NULL DEFAULT '1',
  `ocultar` int(11) NOT NULL DEFAULT '1',
  `es_paquete` tinyint(4) NOT NULL DEFAULT '0',
  `alerta` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;

INSERT INTO `categorias` (`id_categoria`, `nombre`, `impresora`, `activo`, `ocultar`, `es_paquete`, `alerta`)
VALUES
	(1,'SNACKS','COCINA',1,1,0,0),
	(2,'BEBIDAS','MESERO',1,1,0,0),
	(3,'BURGERS','PARRILLA',1,1,0,0),
	(4,'INGREDIENTES','EPSON',1,0,0,0),
	(5,'sin','',1,0,0,0),
	(6,'PAQUETES','COCINA',1,1,1,0),
	(8,'PRUEBA','PRUEBA',0,1,0,0),
	(9,'ESPECIALIDADES','PARILLA',1,1,0,0);

/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla configuracion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `configuracion`;

CREATE TABLE `configuracion` (
  `establecimiento` varchar(160) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `representante` varchar(120) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `rfc` varchar(14) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `telefono` varchar(10) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `celular` varchar(10) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `enviar_sms` int(1) NOT NULL,
  `direccion` varchar(250) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `bkp_alias` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `autobackup` tinyint(1) NOT NULL,
  `autoprint` smallint(1) NOT NULL DEFAULT '1',
  `abrir_caja` tinyint(1) NOT NULL DEFAULT '1',
  `impresora_sd` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `impresora_cuentas` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `impresora_cobros` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `impresora_cortes` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `alerta_corte` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email_notificacion` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_1` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_2` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_3` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_4` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_5` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_6` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_7` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_8` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_9` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `header_10` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_1` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_2` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_3` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_4` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_5` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_6` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_7` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_8` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_9` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `footer_10` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `auto_cobro` tinyint(4) DEFAULT '0',
  `comandain` tinyint(4) NOT NULL DEFAULT '0',
  `pagada` tinyint(4) NOT NULL DEFAULT '0',
  `paquetes` tinyint(4) NOT NULL DEFAULT '0',
  `facturacion` tinyint(1) DEFAULT '0',
  `insumos` tinyint(4) NOT NULL DEFAULT '0',
  `ajustes_facturacion` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `configuracion` WRITE;
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;

INSERT INTO `configuracion` (`establecimiento`, `representante`, `rfc`, `telefono`, `celular`, `enviar_sms`, `direccion`, `bkp_alias`, `autobackup`, `autoprint`, `abrir_caja`, `impresora_sd`, `impresora_cuentas`, `impresora_cobros`, `impresora_cortes`, `alerta_corte`, `email_notificacion`, `header_1`, `header_2`, `header_3`, `header_4`, `header_5`, `header_6`, `header_7`, `header_8`, `header_9`, `header_10`, `footer_1`, `footer_2`, `footer_3`, `footer_4`, `footer_5`, `footer_6`, `footer_7`, `footer_8`, `footer_9`, `footer_10`, `auto_cobro`, `comandain`, `pagada`, `paquetes`, `facturacion`, `insumos`, `ajustes_facturacion`)
VALUES
	('KANGRE BURGER GRLL','ARGENIS GONGORA','GOVA830917','9831715609','9831435202',1,'AV MAGISTERIA ESQ. SERGIO BUTRON CASAS','kgb',0,1,1,'EPSON','EPSON','EPSON','EPSON',NULL,'hola@epicmedia.pro','KANGRE BURGER','AV MAGISTERIAL ESQ. SERGIO BUTRON CASAS','','','','','','','','','','','','PROPINA NO INCLUIDA','GRACIAS POR SU PREFERENCIA!!!','ESTE NO ES UN COMPROBANTE FISCAL','','','','',0,1,0,1,1,0,1);

/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cortes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cortes`;

CREATE TABLE `cortes` (
  `id_corte` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `hora` time NOT NULL,
  `fecha` date NOT NULL,
  `codigo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `efectivoCaja` decimal(12,2) NOT NULL,
  `tpv` decimal(12,2) NOT NULL,
  `otrosMet` decimal(12,2) NOT NULL,
  `fondo_caja` decimal(10,2) NOT NULL,
  `fh_abierto` datetime DEFAULT NULL,
  `fh_cerrado` datetime DEFAULT NULL,
  `abierto` tinyint(4) NOT NULL DEFAULT '1',
  `ajuste` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_corte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `cortes` WRITE;
/*!40000 ALTER TABLE `cortes` DISABLE KEYS */;

INSERT INTO `cortes` (`id_corte`, `id_usuario`, `hora`, `fecha`, `codigo`, `efectivoCaja`, `tpv`, `otrosMet`, `fondo_caja`, `fh_abierto`, `fh_cerrado`, `abierto`, `ajuste`)
VALUES
	(1,1,'16:13:31','2019-10-02',NULL,200.00,0.00,0.00,40.00,'2019-10-01 09:47:34',NULL,0,0),
	(2,1,'16:23:01','2019-10-02',NULL,100.00,0.00,0.00,300.00,'2019-10-02 15:20:39',NULL,0,0),
	(3,1,'10:50:39','2019-10-03',NULL,200.00,0.00,0.00,200.00,'2019-10-02 15:43:07',NULL,0,0),
	(4,1,'13:16:24','2019-10-03',NULL,200.00,0.00,0.00,23.00,'2019-10-03 10:01:47',NULL,0,0),
	(5,1,'13:21:33','2019-10-03',NULL,200.00,0.00,0.00,300.00,'2019-10-03 12:17:47',NULL,0,0),
	(6,1,'00:00:00','0000-00-00',NULL,0.00,0.00,0.00,2333.00,'2019-10-03 12:21:43',NULL,1,0);

/*!40000 ALTER TABLE `cortes` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cupones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cupones`;

CREATE TABLE `cupones` (
  `id_cupon` int(2) NOT NULL AUTO_INCREMENT,
  `cupon` varchar(60) NOT NULL,
  `porcentaje` varchar(3) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cupon`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `cupones` WRITE;
/*!40000 ALTER TABLE `cupones` DISABLE KEYS */;

INSERT INTO `cupones` (`id_cupon`, `cupon`, `porcentaje`, `activo`)
VALUES
	(1,'DESCUENTOS','20',1),
	(2,'EPICMEDIA','25',1),
	(3,'SISTÃ‰MAS','15',1),
	(4,'CORTESIA','100',1);

/*!40000 ALTER TABLE `cupones` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla domicilio
# ------------------------------------------------------------

DROP TABLE IF EXISTS `domicilio`;

CREATE TABLE `domicilio` (
  `id_domicilio` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(64) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_domicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



# Volcado de tabla domicilio_direcciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `domicilio_direcciones`;

CREATE TABLE `domicilio_direcciones` (
  `id_domicilio_direccion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_domicilio` int(11) DEFAULT NULL,
  `direccion` text,
  PRIMARY KEY (`id_domicilio_direccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla dotaciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dotaciones`;

CREATE TABLE `dotaciones` (
  `id_dotacion` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `activo` int(1) NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`id_dotacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



# Volcado de tabla dotaciones_detalle
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dotaciones_detalle`;

CREATE TABLE `dotaciones_detalle` (
  `id_detalle` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_dotacion` bigint(20) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `activo` int(1) NOT NULL,
  PRIMARY KEY (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



# Volcado de tabla gastos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gastos`;

CREATE TABLE `gastos` (
  `id_gasto` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_corte` bigint(20) NOT NULL DEFAULT '0',
  `id_usuario` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `monto` decimal(8,2) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `provision` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_gasto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `gastos` WRITE;
/*!40000 ALTER TABLE `gastos` DISABLE KEYS */;

INSERT INTO `gastos` (`id_gasto`, `id_corte`, `id_usuario`, `descripcion`, `monto`, `fecha_hora`, `provision`)
VALUES
	(1,0,0,'PRUEBA',50.00,'2019-10-03 12:42:46','1');

/*!40000 ALTER TABLE `gastos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla impresion_domicilio
# ------------------------------------------------------------

DROP TABLE IF EXISTS `impresion_domicilio`;

CREATE TABLE `impresion_domicilio` (
  `id_impresion_domicilio` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  PRIMARY KEY (`id_impresion_domicilio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla merma
# ------------------------------------------------------------

DROP TABLE IF EXISTS `merma`;

CREATE TABLE `merma` (
  `id_merma` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` mediumint(9) NOT NULL,
  `fecha` date NOT NULL,
  `activo` smallint(1) NOT NULL,
  `observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_merma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



# Volcado de tabla merma_detalle
# ------------------------------------------------------------

DROP TABLE IF EXISTS `merma_detalle`;

CREATE TABLE `merma_detalle` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_merma` int(11) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `activo` smallint(1) NOT NULL,
  PRIMARY KEY (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



# Volcado de tabla metodo_pago
# ------------------------------------------------------------

DROP TABLE IF EXISTS `metodo_pago`;

CREATE TABLE `metodo_pago` (
  `id_metodo` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `metodo_pago` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_metodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `metodo_pago` WRITE;
/*!40000 ALTER TABLE `metodo_pago` DISABLE KEYS */;

INSERT INTO `metodo_pago` (`id_metodo`, `metodo_pago`)
VALUES
	(1,'EFECTIVO'),
	(4,'TARJETA CREDITO'),
	(28,'TARJETA DEBITO');

/*!40000 ALTER TABLE `metodo_pago` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla msn
# ------------------------------------------------------------

DROP TABLE IF EXISTS `msn`;

CREATE TABLE `msn` (
  `id_msn` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` varchar(45) DEFAULT NULL,
  `id_tipo_usuario` varchar(45) DEFAULT NULL,
  `numero` varchar(45) DEFAULT NULL,
  `activo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_msn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla producto_extra
# ------------------------------------------------------------

DROP TABLE IF EXISTS `producto_extra`;

CREATE TABLE `producto_extra` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_extra` int(11) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `producto_extra` WRITE;
/*!40000 ALTER TABLE `producto_extra` DISABLE KEYS */;

INSERT INTO `producto_extra` (`id`, `id_producto`, `id_extra`, `nivel`)
VALUES
	(1,49,106,1),
	(2,49,105,1),
	(3,49,104,1),
	(4,50,106,1),
	(5,50,104,1),
	(6,50,105,1),
	(7,51,104,1),
	(8,51,107,1),
	(9,51,108,1),
	(10,52,104,1),
	(11,52,108,1),
	(12,52,107,1),
	(13,53,104,1),
	(14,53,106,1),
	(15,53,109,1),
	(16,54,104,1),
	(17,54,106,1),
	(18,54,109,1),
	(19,55,111,1),
	(20,55,105,1),
	(21,55,110,1),
	(22,55,106,1),
	(23,55,109,1),
	(24,56,105,1),
	(25,56,110,1),
	(26,56,111,1),
	(27,56,106,1),
	(28,56,109,1),
	(29,57,104,1),
	(30,57,123,1),
	(31,57,112,1),
	(32,57,105,1),
	(33,57,107,1),
	(34,57,106,1),
	(35,57,113,1),
	(36,57,115,1),
	(37,57,114,1),
	(38,57,111,1),
	(39,58,104,1),
	(40,58,105,1),
	(41,58,107,1),
	(42,58,106,1),
	(43,58,112,1),
	(44,58,123,1),
	(45,58,113,1),
	(46,58,115,1),
	(47,58,114,1),
	(48,58,111,1),
	(49,59,112,1),
	(50,59,108,1),
	(51,59,117,1),
	(52,59,111,1),
	(53,59,104,1),
	(54,60,104,1),
	(55,60,112,1),
	(56,60,108,1),
	(57,60,111,1),
	(58,60,117,1),
	(59,61,104,1),
	(60,61,114,1),
	(61,61,111,1),
	(62,61,107,1),
	(63,62,107,1),
	(64,62,104,1),
	(65,62,114,1),
	(66,62,111,1),
	(67,63,104,1),
	(68,63,112,1),
	(69,63,107,1),
	(70,63,108,1),
	(71,63,105,1),
	(72,64,104,1),
	(73,64,107,1),
	(74,64,108,1),
	(75,64,112,1),
	(76,64,105,1),
	(77,65,107,1),
	(78,65,104,1),
	(79,65,112,1),
	(80,65,113,1),
	(81,66,104,1),
	(82,66,112,1),
	(83,66,107,1),
	(84,66,111,1),
	(85,67,105,1),
	(86,67,104,1),
	(87,67,115,1),
	(88,67,114,1),
	(89,68,114,1),
	(90,68,105,1),
	(91,68,115,1),
	(92,68,104,1),
	(93,69,123,1),
	(94,69,104,1),
	(95,69,112,1),
	(96,69,111,1),
	(97,69,105,1),
	(98,69,107,1),
	(99,69,106,1),
	(100,70,123,1),
	(101,70,112,1),
	(102,70,111,1),
	(103,70,104,1),
	(104,70,105,1),
	(105,70,107,1),
	(106,70,106,1),
	(107,71,104,1),
	(108,71,108,1),
	(109,71,111,1),
	(114,72,108,1),
	(116,72,111,1),
	(117,72,104,1),
	(118,73,118,1),
	(119,73,109,1),
	(120,73,119,1),
	(121,73,120,1),
	(122,73,115,1),
	(123,74,120,1),
	(124,74,118,1),
	(125,74,109,1),
	(126,74,119,1),
	(127,74,115,1),
	(128,75,104,1),
	(129,75,112,1),
	(130,75,123,1),
	(131,75,106,1),
	(132,76,123,1),
	(133,76,112,1),
	(134,76,106,1),
	(135,76,104,1),
	(136,77,104,1),
	(137,77,111,1),
	(138,77,114,1),
	(139,77,112,1),
	(140,78,114,1),
	(141,78,104,1),
	(142,78,112,1),
	(143,78,111,1),
	(144,79,104,1),
	(145,79,105,1),
	(146,79,115,1),
	(147,80,104,1),
	(148,80,105,1),
	(149,80,115,1),
	(150,81,104,1),
	(151,81,112,1),
	(152,81,105,1),
	(153,81,123,1),
	(154,82,104,1),
	(155,82,112,1),
	(156,82,123,1),
	(157,82,105,1),
	(158,83,123,1),
	(159,83,104,1),
	(160,83,112,1),
	(161,84,104,1),
	(162,84,112,1),
	(163,84,123,1),
	(164,85,104,1),
	(165,85,105,1),
	(166,85,114,1),
	(167,86,104,1),
	(168,86,114,1),
	(169,86,105,1),
	(170,87,104,1),
	(171,87,123,1),
	(172,88,104,1),
	(173,88,123,1),
	(174,129,106,1),
	(175,129,114,1),
	(176,129,111,1),
	(177,129,105,1),
	(178,130,126,1),
	(179,130,120,1),
	(180,13,126,1),
	(181,13,120,1),
	(182,131,126,1),
	(183,131,120,1),
	(184,132,127,1),
	(186,133,124,1),
	(187,133,113,1),
	(188,133,114,1),
	(189,133,109,1),
	(190,133,117,1),
	(191,134,126,1),
	(192,134,127,1),
	(193,134,113,1),
	(194,134,109,1),
	(195,134,117,1),
	(196,134,114,1),
	(199,136,126,1),
	(200,136,128,1),
	(201,136,127,1),
	(202,136,115,1),
	(203,26,138,1),
	(204,26,139,1),
	(205,26,140,1),
	(206,26,141,1),
	(207,26,142,1),
	(208,26,143,1),
	(209,36,138,1),
	(210,36,140,1),
	(211,36,141,1),
	(212,36,142,1),
	(213,36,139,1),
	(214,36,143,1),
	(215,93,138,1),
	(216,93,140,1),
	(217,93,139,1),
	(218,93,142,1),
	(219,93,141,1),
	(220,93,143,1),
	(221,49,144,0),
	(222,49,145,0),
	(223,49,146,0),
	(224,49,147,0),
	(225,49,148,0),
	(226,49,149,0),
	(227,50,144,0),
	(228,50,145,0),
	(229,50,146,0),
	(230,50,147,0),
	(231,50,148,0),
	(232,50,149,0),
	(233,51,144,0),
	(234,51,145,0),
	(235,51,146,0),
	(236,51,147,0),
	(237,51,148,0),
	(238,51,149,0),
	(239,52,144,0),
	(240,52,145,0),
	(241,52,146,0),
	(242,52,147,0),
	(243,52,148,0),
	(244,52,149,0),
	(245,53,144,0),
	(246,53,145,0),
	(247,53,146,0),
	(248,53,147,0),
	(249,53,148,0),
	(250,53,149,0),
	(251,54,144,0),
	(252,54,145,0),
	(253,54,146,0),
	(254,54,147,0),
	(255,54,148,0),
	(256,54,149,0),
	(257,55,144,0),
	(258,55,145,0),
	(259,55,146,0),
	(260,55,147,0),
	(261,55,148,0),
	(262,55,149,0),
	(263,56,144,0),
	(264,56,145,0),
	(265,56,146,0),
	(266,56,147,0),
	(267,56,148,0),
	(268,56,149,0),
	(269,57,144,0),
	(270,57,145,0),
	(271,57,146,0),
	(272,57,147,0),
	(273,57,148,0),
	(274,57,149,0),
	(275,58,144,0),
	(276,58,145,0),
	(277,58,146,0),
	(278,58,147,0),
	(279,58,148,0),
	(280,58,149,0),
	(281,59,144,0),
	(282,59,145,0),
	(283,59,146,0),
	(284,59,147,0),
	(285,59,148,0),
	(286,59,149,0),
	(287,60,144,0),
	(288,60,145,0),
	(289,60,146,0),
	(290,60,147,0),
	(291,60,148,0),
	(292,60,149,0),
	(293,61,144,0),
	(294,61,145,0),
	(295,61,146,0),
	(296,61,147,0),
	(297,61,148,0),
	(298,61,149,0),
	(299,63,144,0),
	(300,63,145,0),
	(301,63,146,0),
	(302,63,147,0),
	(303,63,148,0),
	(304,63,149,0),
	(305,64,144,0),
	(306,64,145,0),
	(307,64,146,0),
	(308,64,147,0),
	(309,64,148,0),
	(310,64,149,0),
	(311,65,144,0),
	(312,65,145,0),
	(313,65,146,0),
	(314,65,147,0),
	(315,65,148,0),
	(316,65,149,0),
	(317,66,144,0),
	(318,66,145,0),
	(319,66,146,0),
	(320,66,147,0),
	(321,66,148,0),
	(322,66,149,0),
	(323,67,144,0),
	(324,67,145,0),
	(325,67,146,0),
	(326,67,147,0),
	(327,67,148,0),
	(328,67,149,0),
	(329,68,144,0),
	(330,68,145,0),
	(331,68,146,0),
	(332,68,147,0),
	(333,68,148,0),
	(334,68,149,0),
	(335,69,144,0),
	(336,69,145,0),
	(337,69,146,0),
	(338,69,147,0),
	(339,69,148,0),
	(340,69,149,0),
	(341,70,144,0),
	(342,70,145,0),
	(343,70,146,0),
	(344,70,147,0),
	(345,70,148,0),
	(346,70,149,0),
	(347,71,144,0),
	(348,71,145,0),
	(349,71,146,0),
	(350,71,147,0),
	(351,71,148,0),
	(352,71,149,0),
	(353,72,144,0),
	(354,72,145,0),
	(355,72,146,0),
	(356,72,147,0),
	(357,72,148,0),
	(358,72,149,0),
	(359,73,144,0),
	(360,73,145,0),
	(361,73,146,0),
	(362,73,147,0),
	(363,73,148,0),
	(364,73,149,0),
	(365,74,144,0),
	(366,74,145,0),
	(367,74,146,0),
	(368,74,147,0),
	(369,74,148,0),
	(370,74,149,0),
	(371,75,144,0),
	(372,75,145,0),
	(373,75,146,0),
	(374,75,147,0),
	(375,75,148,0),
	(376,75,149,0),
	(377,76,144,0),
	(378,76,145,0),
	(379,76,146,0),
	(380,76,147,0),
	(381,76,148,0),
	(382,76,149,0),
	(383,77,144,0),
	(384,77,145,0),
	(385,77,146,0),
	(386,77,147,0),
	(387,77,148,0),
	(388,77,149,0),
	(389,78,144,0),
	(390,78,145,0),
	(391,78,146,0),
	(392,78,147,0),
	(393,78,148,0),
	(394,78,149,0),
	(395,79,144,0),
	(396,79,145,0),
	(397,79,146,0),
	(398,79,147,0),
	(399,79,148,0),
	(400,79,149,0),
	(401,80,144,0),
	(402,80,145,0),
	(403,80,146,0),
	(404,80,147,0),
	(405,80,148,0),
	(406,80,149,0),
	(407,81,144,0),
	(408,81,145,0),
	(409,81,146,0),
	(410,81,147,0),
	(411,81,148,0),
	(412,81,149,0),
	(413,82,144,0),
	(414,82,145,0),
	(415,82,146,0),
	(416,82,147,0),
	(417,82,148,0),
	(418,82,149,0),
	(419,83,144,0),
	(420,83,145,0),
	(421,83,146,0),
	(422,83,147,0),
	(423,83,148,0),
	(424,83,149,0),
	(425,84,144,0),
	(426,84,145,0),
	(427,84,146,0),
	(428,84,147,0),
	(429,84,148,0),
	(430,84,149,0),
	(431,85,144,0),
	(432,85,145,0),
	(433,85,146,0),
	(434,85,147,0),
	(435,85,148,0),
	(436,85,149,0),
	(437,86,144,0),
	(438,86,145,0),
	(439,86,146,0),
	(440,86,147,0),
	(441,86,148,0),
	(442,86,149,0),
	(443,87,144,0),
	(444,87,145,0),
	(445,87,146,0),
	(446,87,147,0),
	(447,87,148,0),
	(448,87,149,0),
	(449,88,144,0),
	(450,88,145,0),
	(451,88,146,0),
	(452,88,147,0),
	(453,88,148,0),
	(454,88,149,0),
	(461,129,144,0),
	(462,129,145,0),
	(463,129,146,0),
	(464,129,147,0),
	(465,129,148,0),
	(466,129,149,0),
	(467,130,144,0),
	(468,130,145,0),
	(469,130,146,0),
	(470,130,147,0),
	(471,130,148,0),
	(472,130,149,0),
	(473,131,144,0),
	(474,131,145,0),
	(475,131,146,0),
	(476,131,147,0),
	(477,131,148,0),
	(478,131,149,0),
	(479,132,144,0),
	(480,132,145,0),
	(481,132,146,0),
	(482,132,147,0),
	(483,132,148,0),
	(484,132,149,0),
	(485,133,144,0),
	(486,133,145,0),
	(487,133,146,0),
	(488,133,147,0),
	(489,133,148,0),
	(490,133,149,0),
	(491,134,144,0),
	(492,134,145,0),
	(493,134,146,0),
	(494,134,147,0),
	(495,134,148,0),
	(496,134,149,0),
	(497,135,144,0),
	(498,135,145,0),
	(499,135,146,0),
	(500,135,147,0),
	(501,135,148,0),
	(502,135,149,0),
	(503,136,144,0),
	(504,136,145,0),
	(505,136,146,0),
	(506,136,147,0),
	(507,136,148,0),
	(508,136,149,0),
	(509,135,105,1),
	(510,135,123,1),
	(511,135,111,1),
	(512,135,114,1),
	(513,135,110,1),
	(514,135,106,1),
	(515,135,115,1),
	(516,135,108,1),
	(517,135,107,1),
	(518,135,112,1),
	(519,135,113,1),
	(520,136,105,1),
	(521,136,123,1),
	(522,136,111,1),
	(523,136,114,1),
	(524,136,110,1),
	(525,136,106,1),
	(526,136,115,1),
	(527,136,108,1),
	(528,136,107,1),
	(529,136,112,1),
	(530,136,113,1),
	(531,134,105,1),
	(532,134,123,1),
	(533,134,111,1),
	(534,134,114,1),
	(535,134,110,1),
	(536,134,106,1),
	(537,134,115,1),
	(538,134,108,1),
	(539,134,107,1),
	(540,134,112,1),
	(541,134,113,1),
	(542,133,105,1),
	(543,133,123,1),
	(544,133,111,1),
	(545,133,114,1),
	(546,133,110,1),
	(547,133,106,1),
	(548,133,115,1),
	(549,133,108,1),
	(550,133,107,1),
	(551,133,112,1),
	(552,133,113,1),
	(553,132,105,1),
	(554,132,123,1),
	(555,132,111,1),
	(556,132,114,1),
	(557,132,110,1),
	(558,132,106,1),
	(559,132,115,1),
	(560,132,108,1),
	(561,132,107,1),
	(562,132,112,1),
	(563,132,113,1),
	(564,131,105,1),
	(565,131,123,1),
	(566,131,111,1),
	(567,131,114,1),
	(568,131,110,1),
	(569,131,106,1),
	(570,131,115,1),
	(571,131,108,1),
	(572,131,107,1),
	(573,131,112,1),
	(574,131,113,1),
	(575,130,105,1),
	(576,130,123,1),
	(577,130,111,1),
	(578,130,114,1),
	(579,130,110,1),
	(580,130,106,1),
	(581,130,115,1),
	(582,130,108,1),
	(583,130,107,1),
	(584,130,112,1),
	(585,130,113,1),
	(586,129,105,1),
	(587,129,123,1),
	(588,129,111,1),
	(589,129,114,1),
	(590,129,110,1),
	(591,129,106,1),
	(592,129,115,1),
	(593,129,108,1),
	(594,129,107,1),
	(595,129,112,1),
	(596,129,113,1),
	(597,49,105,1),
	(598,49,123,1),
	(599,49,111,1),
	(600,49,114,1),
	(601,49,110,1),
	(602,49,106,1),
	(603,49,115,1),
	(604,49,108,1),
	(605,49,107,1),
	(606,49,112,1),
	(607,49,113,1),
	(608,50,105,1),
	(609,50,123,1),
	(610,50,111,1),
	(611,50,114,1),
	(612,50,110,1),
	(613,50,106,1),
	(614,50,115,1),
	(615,50,108,1),
	(616,50,107,1),
	(617,50,112,1),
	(618,50,113,1),
	(619,52,105,1),
	(620,52,123,1),
	(621,52,111,1),
	(622,52,114,1),
	(623,52,110,1),
	(624,52,106,1),
	(625,52,115,1),
	(626,52,108,1),
	(627,52,107,1),
	(628,52,112,1),
	(629,52,113,1),
	(630,53,105,1),
	(631,53,123,1),
	(632,53,111,1),
	(633,53,114,1),
	(634,53,110,1),
	(635,53,106,1),
	(636,53,115,1),
	(637,53,108,1),
	(638,53,107,1),
	(639,53,112,1),
	(640,53,113,1),
	(641,54,105,1),
	(642,54,123,1),
	(643,54,111,1),
	(644,54,114,1),
	(645,54,110,1),
	(646,54,106,1),
	(647,54,115,1),
	(648,54,108,1),
	(649,54,107,1),
	(650,54,112,1),
	(651,54,113,1),
	(652,55,105,1),
	(653,55,123,1),
	(654,55,111,1),
	(655,55,114,1),
	(656,55,110,1),
	(657,55,106,1),
	(658,55,115,1),
	(659,55,108,1),
	(660,55,107,1),
	(661,55,112,1),
	(662,55,113,1),
	(663,56,105,1),
	(664,56,123,1),
	(665,56,111,1),
	(666,56,114,1),
	(667,56,110,1),
	(668,56,106,1),
	(669,56,115,1),
	(670,56,108,1),
	(671,56,107,1),
	(672,56,112,1),
	(673,56,113,1),
	(674,57,105,1),
	(675,57,123,1),
	(676,57,111,1),
	(677,57,114,1),
	(678,57,110,1),
	(679,57,106,1),
	(680,57,115,1),
	(681,57,108,1),
	(682,57,107,1),
	(683,57,112,1),
	(684,57,113,1),
	(685,58,105,1),
	(686,58,123,1),
	(687,58,111,1),
	(688,58,114,1),
	(689,58,110,1),
	(690,58,106,1),
	(691,58,115,1),
	(692,58,108,1),
	(693,58,107,1),
	(694,58,112,1),
	(695,58,113,1),
	(696,59,105,1),
	(697,59,123,1),
	(698,59,111,1),
	(699,59,114,1),
	(700,59,110,1),
	(701,59,106,1),
	(702,59,115,1),
	(703,59,108,1),
	(704,59,107,1),
	(705,59,112,1),
	(706,59,113,1),
	(707,60,105,1),
	(708,60,123,1),
	(709,60,111,1),
	(710,60,114,1),
	(711,60,110,1),
	(712,60,106,1),
	(713,60,115,1),
	(714,60,108,1),
	(715,60,107,1),
	(716,60,112,1),
	(717,60,113,1),
	(718,61,105,1),
	(719,61,123,1),
	(720,61,111,1),
	(721,61,114,1),
	(722,61,110,1),
	(723,61,106,1),
	(724,61,115,1),
	(725,61,108,1),
	(726,61,107,1),
	(727,61,112,1),
	(728,61,113,1),
	(729,62,105,1),
	(730,62,123,1),
	(731,62,111,1),
	(732,62,114,1),
	(733,62,110,1),
	(734,62,106,1),
	(735,62,115,1),
	(736,62,108,1),
	(737,62,107,1),
	(738,62,112,1),
	(739,62,113,1),
	(740,63,105,1),
	(741,63,123,1),
	(742,63,111,1),
	(743,63,114,1),
	(744,63,110,1),
	(745,63,106,1),
	(746,63,115,1),
	(747,63,108,1),
	(748,63,107,1),
	(749,63,112,1),
	(750,63,113,1),
	(751,64,105,1),
	(752,64,123,1),
	(753,64,111,1),
	(754,64,114,1),
	(755,64,110,1),
	(756,64,106,1),
	(757,64,115,1),
	(758,64,108,1),
	(759,64,107,1),
	(760,64,112,1),
	(761,64,113,1),
	(762,65,105,1),
	(763,65,123,1),
	(764,65,111,1),
	(765,65,114,1),
	(766,65,110,1),
	(767,65,106,1),
	(768,65,115,1),
	(769,65,108,1),
	(770,65,107,1),
	(771,65,112,1),
	(772,65,113,1),
	(773,66,105,1),
	(774,66,123,1),
	(775,66,111,1),
	(776,66,114,1),
	(777,66,110,1),
	(778,66,106,1),
	(779,66,115,1),
	(780,66,108,1),
	(781,66,107,1),
	(782,66,112,1),
	(783,66,113,1),
	(784,67,105,1),
	(785,67,123,1),
	(786,67,111,1),
	(787,67,114,1),
	(788,67,110,1),
	(789,67,106,1),
	(790,67,115,1),
	(791,67,108,1),
	(792,67,107,1),
	(793,67,112,1),
	(794,67,113,1),
	(795,68,105,1),
	(796,68,123,1),
	(797,68,111,1),
	(798,68,114,1),
	(799,68,110,1),
	(800,68,106,1),
	(801,68,115,1),
	(802,68,108,1),
	(803,68,107,1),
	(804,68,112,1),
	(805,68,113,1),
	(806,69,105,1),
	(807,69,123,1),
	(808,69,111,1),
	(809,69,114,1),
	(810,69,110,1),
	(811,69,106,1),
	(812,69,115,1),
	(813,69,108,1),
	(814,69,107,1),
	(815,69,112,1),
	(816,69,113,1),
	(817,70,105,1),
	(818,70,123,1),
	(819,70,111,1),
	(820,70,114,1),
	(821,70,110,1),
	(822,70,106,1),
	(823,70,115,1),
	(824,70,108,1),
	(825,70,107,1),
	(826,70,112,1),
	(827,70,113,1),
	(828,71,105,1),
	(829,71,123,1),
	(830,71,111,1),
	(831,71,114,1),
	(832,71,110,1),
	(833,71,106,1),
	(834,71,115,1),
	(835,71,108,1),
	(836,71,107,1),
	(837,71,112,1),
	(838,71,113,1),
	(839,72,105,1),
	(840,72,123,1),
	(841,72,111,1),
	(842,72,114,1),
	(843,72,110,1),
	(844,72,106,1),
	(845,72,115,1),
	(846,72,108,1),
	(847,72,107,1),
	(848,72,112,1),
	(849,72,113,1),
	(850,73,105,1),
	(851,73,123,1),
	(852,73,111,1),
	(853,73,114,1),
	(854,73,110,1),
	(855,73,106,1),
	(856,73,115,1),
	(857,73,108,1),
	(858,73,107,1),
	(859,73,112,1),
	(860,73,113,1),
	(861,74,105,1),
	(862,74,123,1),
	(863,74,111,1),
	(864,74,114,1),
	(865,74,110,1),
	(866,74,106,1),
	(867,74,115,1),
	(868,74,108,1),
	(869,74,107,1),
	(870,74,112,1),
	(871,74,113,1),
	(872,75,105,1),
	(873,75,123,1),
	(874,75,111,1),
	(875,75,114,1),
	(876,75,110,1),
	(877,75,106,1),
	(878,75,115,1),
	(879,75,108,1),
	(880,75,107,1),
	(881,75,112,1),
	(882,75,113,1),
	(883,76,105,1),
	(884,76,123,1),
	(885,76,111,1),
	(886,76,114,1),
	(887,76,110,1),
	(888,76,106,1),
	(889,76,115,1),
	(890,76,108,1),
	(891,76,107,1),
	(892,76,112,1),
	(893,76,113,1),
	(894,77,105,1),
	(895,77,123,1),
	(896,77,111,1),
	(897,77,114,1),
	(898,77,110,1),
	(899,77,106,1),
	(900,77,115,1),
	(901,77,108,1),
	(902,77,107,1),
	(903,77,112,1),
	(904,77,113,1),
	(905,78,105,1),
	(906,78,123,1),
	(907,78,111,1),
	(908,78,114,1),
	(909,78,110,1),
	(910,78,106,1),
	(911,78,115,1),
	(912,78,108,1),
	(913,78,107,1),
	(914,78,112,1),
	(915,78,113,1),
	(916,79,105,1),
	(917,79,123,1),
	(918,79,111,1),
	(919,79,114,1),
	(920,79,110,1),
	(921,79,106,1),
	(922,79,115,1),
	(923,79,108,1),
	(924,79,107,1),
	(925,79,112,1),
	(926,79,113,1),
	(927,80,105,1),
	(928,80,123,1),
	(929,80,111,1),
	(930,80,114,1),
	(931,80,110,1),
	(932,80,106,1),
	(933,80,115,1),
	(934,80,108,1),
	(935,80,107,1),
	(936,80,112,1),
	(937,80,113,1),
	(938,81,105,1),
	(939,81,123,1),
	(940,81,111,1),
	(941,81,114,1),
	(942,81,110,1),
	(943,81,106,1),
	(944,81,115,1),
	(945,81,108,1),
	(946,81,107,1),
	(947,81,112,1),
	(948,81,113,1),
	(949,154,104,1),
	(950,154,103,1),
	(951,154,105,1);

/*!40000 ALTER TABLE `producto_extra` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla productos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id_producto` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_categoria` mediumint(3) NOT NULL,
  `codigo` varchar(120) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `nombre` varchar(120) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `precio_venta` decimal(8,2) DEFAULT '0.00',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `extra` int(11) DEFAULT '0',
  `tiene` int(11) DEFAULT '0',
  `sinn` int(11) DEFAULT '0',
  `imprimir_solo` int(11) DEFAULT '0',
  `impresora` int(11) DEFAULT '0',
  `paquete` tinyint(4) NOT NULL DEFAULT '0',
  `id_unidad` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `ingrediente` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;

INSERT INTO `productos` (`id_producto`, `id_categoria`, `codigo`, `nombre`, `precio_venta`, `activo`, `extra`, `tiene`, `sinn`, `imprimir_solo`, `impresora`, `paquete`, `id_unidad`, `ingrediente`)
VALUES
	(16,2,'355','COCA',18.00,1,0,0,0,0,0,0,'0',0),
	(17,2,'17','PITAYA',18.00,1,0,0,0,0,0,0,'0',0),
	(18,2,'18','JAMAICA',18.00,1,0,0,0,0,0,0,'0',0),
	(19,2,'19','LIMONADA CHIA',18.00,1,0,0,0,0,0,0,'0',0),
	(20,2,'20','TAMARINDO',18.00,1,0,0,0,0,0,0,'0',0),
	(21,2,'21','HORCHATA',18.00,1,0,0,0,0,0,0,'0',0),
	(22,2,'22','CEBADA',18.00,1,0,0,0,0,0,0,'0',0),
	(23,2,'23','NARANJA AGRIA',18.00,1,0,0,0,0,0,0,'0',0),
	(24,1,'D2','DEDOS DE QUESO',45.00,1,0,0,0,0,0,0,'0',0),
	(26,1,'BBQ','ALITAS',119.00,1,0,1,0,0,0,0,'0',0),
	(35,1,'P1','PAK NUGGETS',140.00,1,0,0,0,0,0,0,'0',0),
	(36,1,'P2','PAK ALITAS',155.00,1,0,1,0,0,0,0,'0',0),
	(41,1,'PK2','PAK ALITAS BBQ 5PZA',155.00,1,0,0,0,0,0,0,'0',0),
	(43,1,'PROMO','MIERCOLES DE ALITAS',10.00,1,0,0,0,0,0,0,'0',0),
	(46,1,'HD','HOT DOG SENCILLO',15.00,1,0,0,0,0,0,0,'0',0),
	(47,1,'HOTDOGES','HOT DOG ESPECIAL',18.00,1,0,0,0,0,0,0,'0',0),
	(48,2,'REF','CRSITAL SABOR',18.00,1,0,0,0,0,0,0,'0',0),
	(49,3,'1234','ESPAÃ‘OLA',45.00,1,0,1,0,0,0,0,'0',0),
	(50,3,'ESPA','ESPAÃ‘OLA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(51,3,'0003','RANCHERA',45.00,1,0,1,0,0,0,0,'0',0),
	(52,3,'0004','RANCHERA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(53,3,'0005','MOZZARELA',45.00,1,0,1,0,0,0,0,'0',0),
	(54,3,'0006','MOZZARELA DOBLE',45.00,1,0,1,0,0,0,0,'0',0),
	(55,3,'0007','SUPREMA',60.00,1,0,1,0,0,0,0,'0',0),
	(56,3,'0008','SUPREMA DOBLE',90.00,1,0,1,0,0,0,0,'0',0),
	(57,3,'0009','KGB',65.00,1,0,1,0,0,0,0,'0',0),
	(58,3,'0010','KGB DOBLE',100.00,1,0,1,0,0,0,0,'0',0),
	(59,3,'0011','BBQ HOT',55.00,1,0,1,0,0,0,0,'0',0),
	(60,3,'0012','BBQ HOT DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(61,3,'0013','CUBANA',50.00,1,0,1,0,0,0,0,'0',0),
	(62,3,'0014','CUBANA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(63,3,'0015','FEROZ',45.00,1,0,1,0,0,0,0,'0',0),
	(64,3,'0016','FEROZ DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(65,3,'0017','MEXICANA',45.00,1,0,1,0,0,0,0,'0',0),
	(66,3,'0018','MEXICANA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(67,3,'0019','TROPICANA',45.00,1,0,1,0,0,0,0,'0',0),
	(68,3,'0020','TROPICANA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(69,3,'0021','CAVERNICOLA',60.00,1,0,1,0,0,0,0,'0',0),
	(70,3,'0022','CAVERNICOLA DOBLE',90.00,1,0,1,0,0,0,0,'0',0),
	(71,3,'0023','PARRILLERA',45.00,1,0,1,0,0,0,0,'0',0),
	(72,3,'0024','PARRILLERA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(73,3,'0025','CHEESE',45.00,1,0,1,0,0,0,0,'0',0),
	(74,3,'0026','CHEESE DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(75,3,'0027','CHIPI HOT',55.00,1,0,1,0,0,0,0,'0',0),
	(76,3,'0028','CHIPI HOT DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(77,3,'0029','INCOGNITA',55.00,1,0,1,0,0,0,0,'0',0),
	(78,3,'0030','INCOGNITA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(79,3,'0031','ITALIANA',45.00,1,0,1,0,0,0,0,'0',0),
	(80,3,'0032','ITALIANA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(81,3,'0033','MR. KRAB',45.00,1,0,1,0,0,0,0,'0',0),
	(82,3,'0034','MR. KRAB DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(83,3,'0035','GABACHA',45.00,1,0,1,0,0,0,0,'0',0),
	(84,3,'0036','GABACHA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(85,3,'0037','HAWAIANA',45.00,1,0,1,0,0,0,0,'0',0),
	(86,3,'0038','HAWAIANA DOBLE',65.00,1,0,1,0,0,0,0,'0',0),
	(87,3,'0039','SENCILLA',38.00,1,0,1,0,0,0,0,'0',0),
	(88,3,'0040','SENCILLA DOBLE',60.00,1,0,1,0,0,0,0,'0',0),
	(89,1,'0041','ORDEN DE NUGGETS',45.00,1,0,0,0,0,0,0,'0',0),
	(90,1,'0042','ORDEN DE PAPAS GAJO',40.00,1,0,0,0,0,0,0,'0',0),
	(91,1,'0043','ORDEN DE AROS DE CEBOLLA',40.00,1,0,0,0,0,0,0,'0',0),
	(92,1,'0044','ORDEN DE TENDERS',80.00,1,0,0,0,0,0,0,'0',0),
	(93,1,'0045','ORDEN DE ALITAS DE POLLO',119.00,1,0,1,0,0,0,0,'0',0),
	(94,1,'0046','ORDEN CALIFORNIANA',75.00,1,0,0,0,0,0,0,'0',0),
	(95,1,'0047','1/2 ORDEN CALIFORNIANA',40.00,1,0,0,0,0,0,0,'0',0),
	(96,1,'0048','NACHOS DE CARNES FRÃAS',90.00,1,0,0,0,0,0,0,'0',0),
	(97,1,'0049','1/2 NACHOS DE CARNES FRIAS',55.00,1,0,0,0,0,0,0,'0',0),
	(98,1,'0050','NACHOS MEXICANA',85.00,1,0,0,0,0,0,0,'0',0),
	(99,1,'0051','1/2 NACHOS MEXICANA',50.00,1,0,0,0,0,0,0,'0',0),
	(100,1,'0052','ORDEN NACHOS PASTOR',110.00,1,0,0,0,0,0,0,'0',0),
	(101,1,'0053','1/2 NACHOS PASTOR',60.00,1,0,0,0,0,0,0,'0',0),
	(103,4,'CARNE2','CARNES',10.00,1,1,0,0,0,0,0,'3',0),
	(104,4,'queso','QUESO',10.00,1,1,0,0,0,0,0,'3',0),
	(105,4,'salami','SALAMI',10.00,1,1,0,0,0,0,0,'3',0),
	(106,4,'peperoni','PEPERONI',10.00,1,1,0,0,0,0,0,'3',0),
	(107,4,'CHORIZO','CHORIZO',10.00,1,1,0,0,0,0,0,'3',0),
	(108,4,'JALAPENO','JALAPENO',10.00,1,1,0,0,0,0,0,'3',0),
	(109,4,'mozarela','MOZARELA',10.00,1,1,0,0,0,0,0,'3',0),
	(110,4,'FILA','FILADELFIA',10.00,1,1,0,0,0,0,0,'3',0),
	(111,4,'S/AZAR','SALCHICHA/ASAR',10.00,1,1,0,0,0,0,0,'3',0),
	(112,4,'tocino','TOCINO',10.00,1,1,0,0,0,0,0,'3',0),
	(113,4,'aguacate','AGUACATE',10.00,1,1,0,0,0,0,0,'3',0),
	(114,4,'PINA','PINA',10.00,1,1,0,0,0,0,0,'3',0),
	(115,4,'CHAMPI','CHAMPINON',10.00,1,1,0,0,0,0,0,'3',0),
	(116,4,'SALSA BBQ','SALSA BBQ',10.00,1,1,0,0,0,0,0,'3',0),
	(117,4,'legumbres','LEGUMBRES',10.00,1,1,0,0,0,0,0,'3',0),
	(118,4,'gouda','GOUDA',10.00,1,1,0,0,0,0,0,'3',0),
	(119,4,'chihuahua','CHIHUAHUA',10.00,1,1,0,0,0,0,0,'3',0),
	(120,4,'manchego','MANCHEGO',10.00,1,1,0,0,0,0,0,'3',0),
	(121,4,'s/chipi','SALSA CHIPI',10.00,1,1,0,0,0,0,0,'3',0),
	(122,4,'s/tamarindo','SALSA TAMARINDO',10.00,1,1,0,0,0,0,0,'3',0),
	(123,4,'SALCHI','SALCHICHA',10.00,1,1,0,0,0,0,0,'3',0),
	(124,4,'PASTOR','PASTOR',10.00,1,1,0,0,0,0,0,'3',0),
	(125,4,'POLLO/C','POLLO CRUJIENTE',10.00,1,1,0,0,0,0,0,'3',0),
	(126,4,'PEPI','PEPINILLOS',10.00,1,1,0,0,0,0,0,'3',0),
	(127,4,'ARACHERA','ARACHERA',10.00,1,1,0,0,0,0,0,'3',0),
	(128,4,'QUESOB','QUESO DE BOLA',10.00,1,1,0,0,0,0,0,'3',0),
	(129,9,'KRAKEN','KRAKEN',125.00,1,0,1,0,0,0,0,'0',0),
	(130,9,'TENTACION','TENTACION',75.00,1,0,1,0,0,0,0,'0',0),
	(131,9,'TENTACIONDOBLE','TENTACION DOBLE',140.00,1,0,1,0,0,0,0,'0',0),
	(132,9,'ARRARING','ARRACHERA ONION RINGS',95.00,1,0,1,0,0,0,0,'0',0),
	(133,9,'PAS','PASTOR',59.00,1,0,1,0,0,0,0,'0',0),
	(134,9,'PASTOR2','PASTOR DOBLE',89.00,1,0,1,0,0,0,0,'0',0),
	(135,9,'ARRAC','ARRACHERA',99.00,1,0,1,0,0,0,0,'0',0),
	(136,9,'ARRACHERA MUSHROOM','ARRACHERA MUSHROOM',99.00,1,0,1,0,0,0,0,'0',0),
	(137,4,'PAN','PAN',0.00,1,1,0,0,0,0,0,'3',0),
	(138,4,'rhot','RED HOT',0.00,1,1,0,0,0,0,0,'3',0),
	(139,4,'BBQH','SALSA BBQ HOT',0.00,1,1,0,0,0,0,0,'3',0),
	(140,4,'BCHE','BLUE CHESSE',0.00,1,1,0,0,0,0,0,'3',0),
	(141,4,'TAM','TAMARINDO CHIPOTLE',0.00,1,1,0,0,0,0,0,'3',0),
	(142,4,'VALEFUE','VALENTINA FUEGO',0.00,1,1,0,0,0,0,0,'3',0),
	(143,4,'VALEBBQ','VALENTINA BBQ',0.00,1,1,0,0,0,0,0,'3',0),
	(144,5,'SINCEBOLLA','SIN CEBOLLA',0.00,1,0,0,1,0,0,0,'0',0),
	(145,5,'CATS','SIN CATSUP',0.00,1,0,0,1,0,0,0,'0',0),
	(146,5,'SINMOS','SIN MOSTAZA',0.00,1,0,0,1,0,0,0,'0',0),
	(147,5,'LECH','SIN LECHUGA',0.00,1,0,0,1,0,0,0,'0',0),
	(148,5,'PICA','SIN JALAPENO',0.00,1,0,0,1,0,0,0,'0',0),
	(149,5,'SINQUE','SIN QUESO',0.00,1,0,0,1,0,0,0,'0',0),
	(151,6,'PAK1','SNAK PAK 1',155.00,1,0,0,0,0,0,1,'0',0),
	(152,1,'ORPAPAS2','ORDEN DE PAPAS',35.00,1,0,0,0,0,0,0,'0',0),
	(153,6,'PAK2','SNAK PAK 2',140.00,1,0,0,0,0,0,1,'0',0),
	(154,9,'4D','4D BURGER',120.00,1,0,1,0,0,0,0,'0',0),
	(157,4,'PRUEBA65','ENE',0.00,1,1,0,0,0,0,0,'0',0),
	(158,4,'ENNE','PRUEBA43ENNE',0.00,1,1,0,0,0,0,0,'0',0),
	(159,4,'SALC','SALCHICA PRO AZAR',12.00,1,1,0,0,0,0,0,'0',0);

/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla productos_base
# ------------------------------------------------------------

DROP TABLE IF EXISTS `productos_base`;

CREATE TABLE `productos_base` (
  `id_base` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `precio` int(250) NOT NULL,
  PRIMARY KEY (`id_base`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `productos_base` WRITE;
/*!40000 ALTER TABLE `productos_base` DISABLE KEYS */;

INSERT INTO `productos_base` (`id_base`, `producto`, `id_unidad`, `precio`)
VALUES
	(1,'PAN',3,0),
	(2,'CARNES',3,0),
	(3,'QUESO',3,0),
	(4,'SALAMI',3,0),
	(5,'PEPERONI',3,0),
	(6,'CHORIZO',3,0),
	(7,'JALAPENO',3,0),
	(8,'MOZARELA',3,0),
	(9,'FILADELFIA',3,0),
	(10,'SALCHICHA/ASAR',3,0),
	(11,'TOCINO',3,0),
	(12,'AGUACATE',3,0),
	(13,'PINA',3,0),
	(14,'CHAMPINON',3,0),
	(15,'LEGUMBRES',3,0),
	(16,'GOUDA',3,0),
	(17,'CHIHUAHUA',3,0),
	(18,'MANCHEGO',3,0),
	(19,'SALCHICHA',3,0),
	(20,'PASTOR',3,0),
	(21,'POLLO',3,0),
	(22,'PEPINILLOS',3,0),
	(23,'ARRACHERA',3,0),
	(24,'QUESO DE BOLA',3,0),
	(25,'AROS DE CEBOLLA',3,0),
	(27,'PAPA',3,0),
	(28,'PÃˆUBÃ€',3,0),
	(29,'SADASÃ‰D',3,0),
	(30,'ENE',3,0),
	(31,'PRUEBA43ENNE',3,0),
	(32,'SALCHICA PRO AZAR',3,0),
	(33,'QWE',1,0),
	(34,'QW',1,0),
	(35,'2131',1,0),
	(36,'QWE',1,0);

/*!40000 ALTER TABLE `productos_base` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla productos_paquete
# ------------------------------------------------------------

DROP TABLE IF EXISTS `productos_paquete`;

CREATE TABLE `productos_paquete` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_paquete` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `productos_paquete` WRITE;
/*!40000 ALTER TABLE `productos_paquete` DISABLE KEYS */;

INSERT INTO `productos_paquete` (`id_detalle`, `id_producto`, `id_paquete`, `cantidad`)
VALUES
	(13,150,49,3),
	(14,150,16,3),
	(15,151,90,1),
	(16,151,91,1),
	(17,151,152,1),
	(18,151,26,1),
	(19,153,91,1),
	(20,153,89,1),
	(21,153,90,1),
	(22,153,152,1);

/*!40000 ALTER TABLE `productos_paquete` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla productosxbase
# ------------------------------------------------------------

DROP TABLE IF EXISTS `productosxbase`;

CREATE TABLE `productosxbase` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_base` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id_detalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `productosxbase` WRITE;
/*!40000 ALTER TABLE `productosxbase` DISABLE KEYS */;

INSERT INTO `productosxbase` (`id_detalle`, `id_base`, `id_producto`, `cantidad`)
VALUES
	(1,2,49,1),
	(2,1,49,1),
	(3,5,49,1),
	(4,4,49,1),
	(5,3,49,1),
	(6,1,50,1),
	(7,5,50,1),
	(8,3,50,1),
	(9,4,50,1),
	(10,2,50,1),
	(11,1,51,1),
	(12,2,51,1),
	(13,3,51,1),
	(14,6,51,1),
	(15,7,51,1),
	(16,1,52,1),
	(17,3,52,1),
	(18,6,52,1),
	(19,7,52,1),
	(20,2,52,1),
	(21,1,53,1),
	(22,8,53,1),
	(23,5,53,1),
	(24,2,53,1),
	(25,3,53,1),
	(26,1,54,1),
	(27,2,54,1),
	(28,3,54,1),
	(29,8,54,1),
	(30,5,54,1),
	(31,2,55,1),
	(32,1,55,1),
	(33,4,55,1),
	(34,9,55,1),
	(35,5,55,1),
	(36,8,55,1),
	(37,10,55,1),
	(38,1,56,1),
	(39,9,56,1),
	(40,2,56,1),
	(41,4,56,1),
	(42,5,56,1),
	(43,8,56,1),
	(44,10,56,1),
	(45,1,57,1),
	(46,2,57,1),
	(47,3,57,1),
	(48,11,57,1),
	(49,19,57,1),
	(50,4,57,1),
	(51,6,57,1),
	(52,13,57,1),
	(53,12,57,1),
	(54,5,57,1),
	(55,14,57,1),
	(56,10,57,1),
	(57,4,58,1),
	(58,1,58,1),
	(59,3,58,1),
	(60,11,58,1),
	(61,19,58,1),
	(62,2,58,1),
	(63,6,58,1),
	(64,14,58,1),
	(65,12,58,1),
	(66,13,58,1),
	(67,5,58,1),
	(68,10,58,1),
	(69,1,59,1),
	(70,10,59,1),
	(71,2,59,1),
	(72,7,59,1),
	(73,3,59,1),
	(74,11,59,1),
	(75,15,59,1),
	(76,1,60,1),
	(77,2,60,1),
	(78,10,60,1),
	(79,11,60,1),
	(80,7,60,1),
	(81,3,60,1),
	(82,15,60,1),
	(83,3,61,1),
	(84,13,61,1),
	(85,6,61,1),
	(86,1,61,1),
	(87,2,61,1),
	(88,10,61,1),
	(89,1,62,1),
	(90,2,62,1),
	(91,3,62,1),
	(92,6,62,1),
	(93,13,62,1),
	(94,10,62,1),
	(95,6,63,1),
	(96,1,63,1),
	(97,2,63,1),
	(98,4,63,1),
	(99,11,63,1),
	(100,3,63,1),
	(101,7,63,1),
	(102,2,64,1),
	(103,1,64,1),
	(104,11,64,1),
	(105,3,64,1),
	(106,6,64,1),
	(107,4,64,1),
	(108,7,64,1),
	(109,2,65,1),
	(110,1,65,1),
	(111,6,65,1),
	(112,11,65,1),
	(113,3,65,1),
	(114,12,65,1),
	(115,2,66,1),
	(116,11,66,1),
	(117,12,66,1),
	(118,3,66,1),
	(119,6,66,1),
	(120,1,66,1),
	(121,13,67,1),
	(122,2,67,1),
	(123,1,67,1),
	(124,4,67,1),
	(125,14,67,1),
	(126,1,68,1),
	(127,4,68,1),
	(128,3,68,1),
	(129,2,68,1),
	(130,13,68,1),
	(131,14,68,1),
	(132,1,69,1),
	(133,19,69,1),
	(134,2,69,1),
	(135,10,69,1),
	(136,11,69,1),
	(137,3,69,1),
	(138,6,69,1),
	(139,4,69,1),
	(140,5,69,1),
	(141,2,70,1),
	(142,3,70,1),
	(143,11,70,1),
	(144,1,70,1),
	(145,4,70,1),
	(146,10,70,1),
	(147,6,70,1),
	(148,5,70,1),
	(149,1,71,1),
	(150,7,71,1),
	(151,2,71,1),
	(152,3,71,1),
	(153,10,71,1),
	(167,17,73,1),
	(166,1,73,1),
	(165,7,72,1),
	(164,2,72,1),
	(163,10,72,1),
	(162,3,72,1),
	(161,1,72,1),
	(168,16,73,1),
	(169,2,73,1),
	(170,8,73,1),
	(171,14,73,1),
	(172,18,73,1),
	(173,1,74,1),
	(174,8,74,1),
	(175,2,74,1),
	(176,17,74,1),
	(177,16,74,1),
	(178,18,74,1),
	(179,14,74,1),
	(180,3,75,1),
	(181,2,75,1),
	(182,1,75,1),
	(183,11,75,1),
	(184,19,75,1),
	(185,5,75,1),
	(186,19,76,1),
	(187,5,76,1),
	(188,1,76,1),
	(189,11,76,1),
	(190,3,76,1),
	(191,2,76,1),
	(192,1,77,1),
	(193,13,77,1),
	(194,2,77,1),
	(195,10,77,1),
	(196,11,77,1),
	(197,3,77,1),
	(198,1,78,1),
	(199,3,78,1),
	(200,10,78,1),
	(201,2,78,1),
	(202,11,78,1),
	(203,13,78,1),
	(204,1,79,1),
	(205,4,79,1),
	(206,14,79,1),
	(207,3,79,1),
	(208,2,79,1),
	(209,3,80,1),
	(210,2,80,1),
	(211,1,80,1),
	(212,14,80,1),
	(213,4,80,1),
	(214,2,81,1),
	(215,19,81,1),
	(216,4,81,1),
	(217,1,81,1),
	(218,3,81,1),
	(219,11,81,1),
	(220,1,82,1),
	(221,3,82,1),
	(222,11,82,1),
	(223,19,82,1),
	(224,2,82,1),
	(225,4,82,1),
	(226,3,83,1),
	(227,2,83,1),
	(228,19,83,1),
	(229,11,83,1),
	(230,1,83,1),
	(231,19,84,1),
	(232,2,84,1),
	(233,3,84,1),
	(234,11,84,1),
	(235,1,84,1),
	(236,2,85,1),
	(237,1,85,1),
	(238,3,85,1),
	(239,13,85,1),
	(240,19,85,1),
	(241,1,86,1),
	(242,2,86,1),
	(243,3,86,1),
	(244,13,86,1),
	(245,19,86,1),
	(246,1,87,1),
	(247,3,87,1),
	(248,19,87,1),
	(249,2,87,1),
	(250,1,88,1),
	(251,3,88,1),
	(252,19,88,1),
	(253,2,88,1),
	(254,23,129,1),
	(255,13,129,1),
	(256,1,129,1),
	(257,20,129,1),
	(258,21,129,1),
	(259,2,129,1),
	(260,5,129,1),
	(261,10,129,1),
	(262,4,129,1),
	(263,18,130,1),
	(264,15,130,1),
	(265,1,130,1),
	(266,22,130,1),
	(267,21,130,1),
	(268,1,131,1),
	(269,15,131,1),
	(270,21,131,1),
	(271,18,131,1),
	(272,22,131,1),
	(273,1,132,1),
	(274,25,132,1),
	(275,23,132,1),
	(276,22,132,1),
	(277,1,133,1),
	(278,20,133,1),
	(279,2,133,1),
	(280,13,133,1),
	(281,12,133,1),
	(282,15,133,1),
	(283,8,133,1),
	(284,12,134,1),
	(285,2,134,1),
	(286,15,134,1),
	(287,20,134,1),
	(288,1,134,1),
	(289,13,134,1),
	(290,8,134,1),
	(291,1,135,1),
	(292,23,135,1),
	(293,22,135,1),
	(294,14,136,1),
	(295,23,136,1),
	(296,25,136,1),
	(297,1,136,1),
	(298,22,136,1),
	(305,73,150,1),
	(306,3,154,1),
	(307,2,154,1);

/*!40000 ALTER TABLE `productosxbase` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla refresh
# ------------------------------------------------------------

DROP TABLE IF EXISTS `refresh`;

CREATE TABLE `refresh` (
  `r_productos` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `r_venta` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `r_clientes` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `r_actualiza` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `refresh` WRITE;
/*!40000 ALTER TABLE `refresh` DISABLE KEYS */;

INSERT INTO `refresh` (`r_productos`, `r_venta`, `r_clientes`, `r_actualiza`)
VALUES
	('0501ebda173fbc5f1351522bfdd1adf0','0501ebda173fbc5f1351522bfdd1adf0','AS222FAasdasSF23','Faasd222aSA23');

/*!40000 ALTER TABLE `refresh` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla tipo_usuario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tipo_usuario`;

CREATE TABLE `tipo_usuario` (
  `id_tipo_usuario` smallint(6) NOT NULL,
  `tipo` varchar(20) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `tipo_usuario` WRITE;
/*!40000 ALTER TABLE `tipo_usuario` DISABLE KEYS */;

INSERT INTO `tipo_usuario` (`id_tipo_usuario`, `tipo`)
VALUES
	(1,'Administrador'),
	(2,'Vendedor');

/*!40000 ALTER TABLE `tipo_usuario` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla unidades
# ------------------------------------------------------------

DROP TABLE IF EXISTS `unidades`;

CREATE TABLE `unidades` (
  `id_unidad` int(11) NOT NULL AUTO_INCREMENT,
  `unidad` varchar(150) NOT NULL,
  `abreviatura` varchar(6) NOT NULL,
  PRIMARY KEY (`id_unidad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `unidades` WRITE;
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;

INSERT INTO `unidades` (`id_unidad`, `unidad`, `abreviatura`)
VALUES
	(1,'GRAMOS','G'),
	(2,'PIEZAS','PZS'),
	(3,'Unidad','uds.');

/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla usuarios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id_usuario` mediumint(2) NOT NULL AUTO_INCREMENT,
  `id_tipo_usuario` int(1) NOT NULL,
  `nombre` varchar(64) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `usuario` varchar(24) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `contrasena` varchar(128) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `cortes` tinyint(1) NOT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `devoluciones` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;

INSERT INTO `usuarios` (`id_usuario`, `id_tipo_usuario`, `nombre`, `usuario`, `contrasena`, `cortes`, `ultimo_acceso`, `activo`, `devoluciones`)
VALUES
	(1,1,'Admin','admin','21232f297a57a5a743894a0e4a801fc3',1,'2019-10-03 21:07:58',1,0),
	(2,2,'Vendedor','Vendedor','c4ca4238a0b923820dcc509a6f75849b',1,NULL,1,0),
	(3,2,'Yasuri Baeza','Caja','936400f151ba2146a86cfcc342279f57',1,'2019-09-26 19:19:27',1,1),
	(4,1,'Argenis','Argenis','29d1e2357d7c14db51e885053a58ec67',0,NULL,1,0);

/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla venta_detalle
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venta_detalle`;

CREATE TABLE `venta_detalle` (
  `id_detalle` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_venta` int(10) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` decimal(10,0) NOT NULL,
  `precio_venta` decimal(8,2) NOT NULL,
  `comentarios` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_detalle`),
  KEY `id_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `venta_detalle` WRITE;
/*!40000 ALTER TABLE `venta_detalle` DISABLE KEYS */;

INSERT INTO `venta_detalle` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`, `precio_venta`, `comentarios`, `tipo`)
VALUES
	(1,1,87,1,38.00,'',0),
	(2,1,0,1,0.00,'',0),
	(3,2,134,1,89.00,'',0),
	(4,2,0,1,0.00,'',0),
	(5,3,151,1,155.00,'',0),
	(6,3,90,1,0.00,'',0),
	(7,3,91,1,0.00,'',0),
	(8,3,152,1,0.00,'',0),
	(9,3,26,1,0.00,'',0),
	(10,4,79,1,45.00,'',0),
	(11,4,107,1,10.00,'',0),
	(12,4,0,1,0.00,'',0),
	(13,5,151,1,155.00,'',0),
	(14,5,90,1,0.00,'',0),
	(15,5,91,1,0.00,'',0),
	(16,5,152,1,0.00,'',0),
	(17,5,26,1,0.00,'',0),
	(18,6,79,1,45.00,'',0),
	(19,6,0,1,0.00,'',0),
	(20,7,79,1,45.00,'',0),
	(21,7,112,1,10.00,'',0),
	(22,7,0,1,0.00,'',0),
	(23,8,79,1,45.00,'',0),
	(24,8,107,1,10.00,'',0),
	(25,8,0,1,0.00,'',0),
	(26,9,18,1,18.00,'',0),
	(27,10,79,1,45.00,'',0),
	(28,10,113,1,10.00,'',0),
	(29,10,0,1,0.00,'',0),
	(30,11,134,1,89.00,'',0),
	(31,11,111,1,10.00,'',0),
	(32,11,0,1,0.00,'',0),
	(33,12,134,1,89.00,'',0),
	(34,12,108,1,10.00,'',0),
	(35,12,0,1,0.00,'',0),
	(36,13,151,1,155.00,'',0),
	(37,13,90,1,0.00,'',0),
	(38,13,91,1,0.00,'',0),
	(39,13,152,1,0.00,'',0),
	(40,13,26,1,0.00,'',0),
	(41,14,134,1,89.00,'',0),
	(42,14,0,1,0.00,'',0),
	(43,15,151,1,155.00,'',0),
	(44,15,90,1,0.00,'',0),
	(45,15,91,1,0.00,'',0),
	(46,15,152,1,0.00,'',0),
	(47,15,26,1,0.00,'',0),
	(48,16,79,1,45.00,'',0),
	(49,16,112,1,10.00,'',0),
	(50,16,0,1,0.00,'',0),
	(51,17,134,1,89.00,'',0),
	(52,17,111,1,10.00,'',0),
	(53,17,0,1,0.00,'',0),
	(54,18,134,1,89.00,'',0),
	(55,18,0,1,0.00,'',0),
	(56,19,79,1,45.00,'',0),
	(57,19,112,1,10.00,'',0),
	(58,19,0,1,0.00,'',0),
	(59,20,134,1,89.00,'',0),
	(60,20,0,1,0.00,'',0),
	(61,21,134,1,89.00,'',0),
	(62,21,115,1,10.00,'',0),
	(63,21,0,1,0.00,'',0),
	(64,22,134,1,89.00,'',0),
	(65,22,107,1,10.00,'',0),
	(66,22,0,1,0.00,'',0),
	(67,23,134,1,89.00,'',0),
	(68,23,0,1,0.00,'',0),
	(69,24,151,1,155.00,'',0),
	(70,24,90,1,0.00,'',0),
	(71,24,91,1,0.00,'',0),
	(72,24,152,1,0.00,'',0),
	(73,24,26,1,0.00,'',0),
	(74,25,151,2,155.00,'',0),
	(75,25,90,1,0.00,'',0),
	(76,25,91,1,0.00,'',0),
	(77,25,152,1,0.00,'',0),
	(78,25,26,1,0.00,'',0),
	(79,26,134,1,89.00,'',0),
	(80,26,115,1,10.00,'',0),
	(81,26,0,1,0.00,'',0),
	(82,27,134,1,89.00,'',0),
	(83,27,115,1,10.00,'',0),
	(84,27,0,1,0.00,'',0),
	(85,28,79,1,45.00,'',0),
	(86,28,112,1,10.00,'',0),
	(87,28,0,1,0.00,'',0),
	(88,29,79,1,45.00,'',0),
	(89,29,0,1,0.00,'',0),
	(90,30,79,1,45.00,'',0),
	(91,30,113,1,10.00,'',0),
	(92,30,0,1,0.00,'',0),
	(93,31,134,1,89.00,'',0),
	(94,31,0,1,0.00,'',0),
	(95,32,134,1,89.00,'',0),
	(96,32,107,1,10.00,'',0),
	(97,32,0,1,0.00,'',0),
	(98,33,135,1,99.00,'',0),
	(99,33,0,1,0.00,'',0),
	(100,34,135,1,99.00,'',0),
	(101,34,0,1,0.00,'',0),
	(102,35,135,1,99.00,'',0),
	(103,35,0,1,0.00,'',0),
	(104,36,135,1,99.00,'',0),
	(105,36,0,1,0.00,'',0),
	(106,37,79,1,45.00,'',0),
	(107,37,0,1,0.00,'',0),
	(108,38,79,1,45.00,'',0),
	(109,38,0,1,0.00,'',0),
	(110,39,132,1,95.00,'',0),
	(111,39,107,1,10.00,'',0),
	(112,39,0,1,0.00,'',0),
	(113,40,134,1,89.00,'',0),
	(114,40,0,1,0.00,'',0),
	(115,41,134,1,89.00,'',0),
	(116,41,0,1,0.00,'',0),
	(117,42,134,1,89.00,'',0),
	(118,42,0,1,0.00,'',0),
	(119,43,134,1,89.00,'',0),
	(120,43,0,1,0.00,'',0),
	(121,44,79,1,45.00,'',0),
	(122,44,0,1,0.00,'',0),
	(123,45,134,1,89.00,'',0),
	(124,45,107,1,10.00,'',0),
	(125,45,0,1,0.00,'',0),
	(126,46,134,1,89.00,'',0),
	(127,46,0,1,0.00,'',0),
	(128,47,134,1,89.00,'',0),
	(129,47,0,1,0.00,'',0),
	(130,48,134,1,89.00,'',0),
	(131,48,0,1,0.00,'',0),
	(132,49,134,1,89.00,'',0),
	(133,49,0,1,0.00,'',0),
	(134,50,79,1,45.00,'',0),
	(135,50,113,1,10.00,'',0),
	(136,50,0,1,0.00,'',0),
	(137,51,79,1,45.00,'',0),
	(138,51,0,1,0.00,'',0),
	(139,52,79,1,45.00,'',0),
	(140,52,112,1,10.00,'',0),
	(141,52,0,1,0.00,'',0),
	(142,53,79,1,45.00,'',0),
	(143,53,113,1,10.00,'',0),
	(144,53,0,1,0.00,'',0),
	(145,54,79,1,45.00,'',0),
	(146,54,113,1,10.00,'',0),
	(147,54,0,1,0.00,'',0),
	(148,55,79,1,45.00,'',0),
	(149,55,0,1,0.00,'',0),
	(150,56,79,1,45.00,'',0),
	(151,56,0,1,0.00,'',0),
	(152,57,79,1,45.00,'',0),
	(153,57,0,1,0.00,'',0),
	(154,58,17,1,18.00,'',0),
	(155,59,79,1,45.00,'',0),
	(156,59,0,1,0.00,'',0),
	(157,60,79,1,45.00,'',0),
	(158,60,113,1,10.00,'',0),
	(159,60,0,1,0.00,'',0),
	(160,61,79,1,45.00,'',0),
	(161,61,0,1,0.00,'',0),
	(162,62,17,1,18.00,'',0),
	(163,63,18,1,18.00,'',0),
	(164,64,17,1,18.00,'',0),
	(165,65,79,1,45.00,'',0),
	(166,65,0,1,0.00,'',0),
	(167,66,79,1,45.00,'',0),
	(168,66,0,1,0.00,'',0),
	(169,67,79,1,45.00,'',0),
	(170,67,0,1,0.00,'',0),
	(171,68,79,1,45.00,'',0),
	(172,68,0,1,0.00,'',0),
	(173,69,79,1,45.00,'',0),
	(174,69,0,1,0.00,'',0),
	(175,70,79,1,45.00,'',0),
	(176,70,0,1,0.00,'',0),
	(177,71,79,1,45.00,'',0),
	(178,71,113,1,10.00,'',0),
	(179,71,0,1,0.00,'',0),
	(180,72,79,1,45.00,'',0),
	(181,72,0,1,0.00,'',0),
	(182,73,79,1,45.00,'',0),
	(183,73,0,1,0.00,'',0),
	(184,74,81,1,45.00,'',0),
	(185,74,113,1,10.00,'',0),
	(186,74,0,1,0.00,'',0),
	(187,75,134,1,89.00,'',0),
	(188,75,0,1,0.00,'',0);

/*!40000 ALTER TABLE `venta_detalle` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla ventas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `id_venta` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_corte` int(11) NOT NULL DEFAULT '0',
  `mesa` varchar(10) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `abierta` tinyint(1) NOT NULL DEFAULT '1',
  `pagada` tinyint(1) NOT NULL DEFAULT '0',
  `fechahora_cerrada` datetime NOT NULL,
  `fechahora_pagada` datetime NOT NULL,
  `id_metodo` int(11) NOT NULL,
  `num_cta` varchar(4) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `facturado` int(11) NOT NULL,
  `monto_facturado` decimal(10,2) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `reabierta` int(1) NOT NULL DEFAULT '0',
  `codigo` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `metodo_txt` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `recibe_txt` decimal(10,2) NOT NULL,
  `cambio_txt` decimal(10,2) NOT NULL,
  `pendiente_facturar` tinyint(4) NOT NULL,
  `pendiente_monto` decimal(10,2) NOT NULL,
  `descuento_txt` int(2) NOT NULL,
  `DescEfec_txt` decimal(10,2) NOT NULL,
  `pagarOriginal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;

INSERT INTO `ventas` (`id_venta`, `id_usuario`, `fecha`, `hora`, `id_corte`, `mesa`, `abierta`, `pagada`, `fechahora_cerrada`, `fechahora_pagada`, `id_metodo`, `num_cta`, `facturado`, `monto_facturado`, `monto_pagado`, `reabierta`, `codigo`, `metodo_txt`, `recibe_txt`, `cambio_txt`, `pendiente_facturar`, `pendiente_monto`, `descuento_txt`, `DescEfec_txt`, `pagarOriginal`)
VALUES
	(50,1,'2019-10-02','14:53:49',1,'BARRA',0,1,'2019-10-02 14:53:49','2019-10-02 15:09:49',1,'',1,8.80,63.80,0,'','EFECTIVO',100.00,36.20,0,0.00,0,0.00,55.00),
	(51,1,'2019-10-02','14:58:59',1,'BARRA',0,1,'2019-10-02 14:58:59','2019-10-02 15:13:08',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,1,9.00,45.00),
	(52,1,'2019-10-02','15:00:49',1,'BARRA',0,1,'2019-10-02 15:00:49','2019-10-02 15:13:20',1,'',1,8.80,63.80,0,'','EFECTIVO',200.00,136.20,0,0.00,2,13.75,55.00),
	(53,1,'2019-10-02','15:01:32',1,'BARRA',0,1,'2019-10-02 15:01:32','2019-10-02 15:01:37',1,'',1,8.80,63.80,0,'','EFECTIVO',100.00,36.20,0,0.00,0,0.00,55.00),
	(54,1,'2019-10-02','15:20:51',2,'BARRA',0,1,'2019-10-02 15:20:51','2019-10-02 15:22:13',1,'',0,0.00,44.00,0,'','EFECTIVO',100.00,56.00,0,0.00,1,11.00,55.00),
	(55,1,'2019-10-02','15:22:32',2,'BARRA',0,1,'2019-10-02 15:22:32','2019-10-02 15:22:55',1,'',1,5.76,41.76,0,'','EFECTIVO',100.00,58.24,0,0.00,1,9.00,45.00),
	(56,1,'2019-10-02','15:43:12',3,'BARRA',0,1,'2019-10-02 15:43:12','2019-10-02 15:43:19',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,0,0.00,45.00),
	(57,1,'2019-10-02','15:46:01',3,'BARRA',0,1,'2019-10-02 15:46:01','2019-10-02 15:46:15',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,0,0.00,45.00),
	(58,1,'2019-10-02','15:46:45',3,'BARRA',0,1,'2019-10-02 15:46:45','2019-10-02 15:46:50',1,'',0,0.00,18.00,0,'','EFECTIVO',100.00,82.00,0,0.00,0,0.00,18.00),
	(59,1,'2019-10-02','15:48:58',3,'10',0,1,'0000-00-00 00:00:00','2019-10-02 15:49:02',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,0,0.00,45.00),
	(60,1,'2019-10-02','15:50:33',3,'BARRA',0,1,'2019-10-02 15:50:33','2019-10-02 15:50:40',1,'',0,0.00,55.00,0,'','EFECTIVO',100.00,45.00,0,0.00,0,0.00,55.00),
	(61,1,'2019-10-02','15:51:20',3,'BARRA',0,1,'2019-10-02 15:51:20','2019-10-02 15:51:23',1,'',0,0.00,45.00,0,'','EFECTIVO',100.00,55.00,0,0.00,0,0.00,45.00),
	(62,1,'2019-10-02','15:52:09',3,'10',0,1,'0000-00-00 00:00:00','2019-10-02 15:52:12',1,'',0,0.00,18.00,0,'','EFECTIVO',100.00,82.00,0,0.00,0,0.00,18.00),
	(63,1,'2019-10-02','15:54:35',3,'100',0,1,'0000-00-00 00:00:00','2019-10-02 15:54:59',1,'',1,2.88,20.88,0,'','EFECTIVO',21.00,0.12,0,0.00,0,0.00,18.00),
	(64,1,'2019-10-02','15:55:56',3,'BARRA',0,1,'2019-10-02 15:55:56','2019-10-02 15:56:11',1,'',1,2.88,20.88,0,'','EFECTIVO',22.00,1.12,0,0.00,0,0.00,18.00),
	(65,1,'2019-10-02','15:56:50',3,'BARRA',0,1,'2019-10-02 15:56:50','2019-10-02 15:56:54',1,'',0,0.00,45.00,0,'','EFECTIVO',50.00,5.00,0,0.00,0,0.00,45.00),
	(66,1,'2019-10-02','15:58:31',3,'BARRA',0,1,'2019-10-02 15:58:31','2019-10-02 15:58:38',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,0,0.00,45.00),
	(67,1,'2019-10-02','16:01:38',3,'BARRA',0,1,'2019-10-02 16:01:38','2019-10-02 16:01:47',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,0,0.00,45.00),
	(68,1,'2019-10-02','16:03:05',3,'BARRA',0,1,'2019-10-02 16:03:05','2019-10-02 16:03:17',1,'',1,7.20,52.20,0,'','EFECTIVO',54.00,1.80,0,0.00,0,0.00,45.00),
	(69,1,'2019-10-02','16:04:53',3,'BARRA',0,1,'2019-10-02 16:04:53','2019-10-02 16:04:58',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,0,0.00,45.00),
	(70,1,'2019-10-02','16:09:24',3,'BARRA',0,1,'2019-10-02 16:09:24','2019-10-02 16:09:29',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,0,0.00,45.00),
	(71,1,'2019-10-02','16:10:07',3,'BARRA',0,1,'2019-10-02 16:10:07','2019-10-02 16:10:13',1,'',1,8.80,63.80,0,'','EFECTIVO',100.00,36.20,0,0.00,0,0.00,55.00),
	(72,1,'2019-10-03','09:45:45',3,'BARRA',0,1,'2019-10-03 09:45:45','2019-10-03 09:45:56',1,'',1,7.20,52.20,0,'','EFECTIVO',100.00,47.80,0,0.00,0,0.00,45.00),
	(73,1,'2019-10-03','10:01:57',4,'BARRA',0,1,'2019-10-03 10:01:57','2019-10-03 12:16:15',1,'',0,0.00,45.00,0,'','EFECTIVO',50.00,5.00,0,0.00,0,0.00,45.00),
	(74,1,'2019-10-03','12:21:09',5,'23',0,1,'0000-00-00 00:00:00','2019-10-03 12:21:21',1,'',0,0.00,55.00,0,'','EFECTIVO',60.00,5.00,0,0.00,0,0.00,55.00),
	(75,1,'2019-10-03','12:28:35',0,'234',1,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,'',0,0.00,0.00,0,'','',0.00,0.00,0,0.00,0,0.00,0.00);

/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla ventas_cancelaciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ventas_cancelaciones`;

CREATE TABLE `ventas_cancelaciones` (
  `id_venta` int(11) NOT NULL,
  `id_usuario_cancelador` int(11) NOT NULL,
  `fechahora_cancelacion` datetime NOT NULL,
  `motivo` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_venta_cancelacion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_venta_cancelacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



# Volcado de tabla ventas_cancelaciones_detalle
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ventas_cancelaciones_detalle`;

CREATE TABLE `ventas_cancelaciones_detalle` (
  `id_detalle` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_venta_cancelacion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` varchar(10) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `precio_venta` decimal(10,2) NOT NULL,
  `id_venta` int(11) NOT NULL,
  PRIMARY KEY (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
