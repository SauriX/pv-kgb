# ************************************************************
# Sequel Pro SQL dump
# Versión 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Base de datos: vendefacil_restaurante
# Tiempo de Generación: 2019-10-07 17:04:09 +0000
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
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;

INSERT INTO `categorias` (`id_categoria`, `nombre`, `impresora`, `activo`, `ocultar`, `es_paquete`)
VALUES
	(1,'SNACKS','EPSON',1,1,0),
	(2,'BEBIDAS','MESERO',1,1,0),
	(3,'BURGERS','PARRILLA',1,1,0),
	(4,'INGREDIENTES','EPSON',1,1,0),
	(5,'sin','',1,0,0),
	(6,'PAQUETES','EPSON',1,1,1),
	(8,'PRUEBA','PRUEBA',0,1,0),
	(9,'PRUEBA2','HOLI',1,1,0);

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
  `facturacion` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `configuracion` WRITE;
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;

INSERT INTO `configuracion` (`establecimiento`, `representante`, `rfc`, `telefono`, `celular`, `enviar_sms`, `direccion`, `bkp_alias`, `autobackup`, `autoprint`, `abrir_caja`, `impresora_sd`, `impresora_cuentas`, `impresora_cobros`, `impresora_cortes`, `alerta_corte`, `email_notificacion`, `header_1`, `header_2`, `header_3`, `header_4`, `header_5`, `header_6`, `header_7`, `header_8`, `header_9`, `header_10`, `footer_1`, `footer_2`, `footer_3`, `footer_4`, `footer_5`, `footer_6`, `footer_7`, `footer_8`, `footer_9`, `footer_10`, `auto_cobro`, `comandain`, `pagada`, `paquetes`, `facturacion`)
VALUES
	('KANGRE BURGER','ARGENIS GONGORA','XAXX010101000','9831715609','9831435202',1,'AV MAXUXAC','kgb',1,1,1,'EPSON','EPSON','EPSON','EPSON',NULL,'hola@epicmedia.pro','KANGRE BURGER','AV MAXUXAC',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'PROPINA NO INCLUIDA','GRACIAS POR SU PREFERENCIA','ESTE NO ES UN COMPROBANTE FISCAL',NULL,NULL,NULL,NULL,0,1,0,1,0);

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
	(27,1,'17:14:41','2019-09-18',NULL,200.00,300.00,0.00,200.00,'2019-09-18 16:12:49',NULL,0,1),
	(29,1,'18:47:35','2019-10-07',NULL,2000.00,0.00,0.00,200.00,'2019-09-19 09:49:04',NULL,0,0);

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
	(1,'PRUEBA','20',1);

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
  `comentario` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_dotacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `dotaciones` WRITE;
/*!40000 ALTER TABLE `dotaciones` DISABLE KEYS */;

INSERT INTO `dotaciones` (`id_dotacion`, `id_usuario`, `fecha`, `hora`, `activo`, `comentario`)
VALUES
	(42,1,'2019-09-19',NULL,0,'hola');

/*!40000 ALTER TABLE `dotaciones` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `dotaciones_detalle` WRITE;
/*!40000 ALTER TABLE `dotaciones_detalle` DISABLE KEYS */;

INSERT INTO `dotaciones_detalle` (`id_detalle`, `id_dotacion`, `id_producto`, `cantidad`, `activo`)
VALUES
	(32,42,137,'309',0),
	(33,42,91,'20',0);

/*!40000 ALTER TABLE `dotaciones_detalle` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla existencias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `existencias`;

CREATE TABLE `existencias` (
  `id_producto` bigint(20) NOT NULL,
  `existencia` varchar(300) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `existencias` WRITE;
/*!40000 ALTER TABLE `existencias` DISABLE KEYS */;

INSERT INTO `existencias` (`id_producto`, `existencia`)
VALUES
	(16,'-7'),
	(16,'-7'),
	(17,'0'),
	(18,'0'),
	(19,'0'),
	(20,'-3'),
	(21,'8'),
	(22,'4'),
	(23,'-4'),
	(24,'0'),
	(26,'0'),
	(35,'0'),
	(36,'0'),
	(41,'0'),
	(43,'0'),
	(46,'0'),
	(47,'0'),
	(48,'-3'),
	(49,'22'),
	(50,'0'),
	(51,'0'),
	(52,'0'),
	(53,'0'),
	(54,'0'),
	(55,'0'),
	(56,'0'),
	(57,'0'),
	(58,'0'),
	(59,'-2'),
	(60,'0'),
	(61,'0'),
	(62,'0'),
	(63,'-2'),
	(64,'0'),
	(65,'0'),
	(66,'0'),
	(67,'0'),
	(68,'0'),
	(69,'0'),
	(70,'0'),
	(71,'-13'),
	(72,'0'),
	(73,'-1'),
	(74,'-4'),
	(75,'-7'),
	(76,'0'),
	(77,'0'),
	(78,'0'),
	(79,'0'),
	(80,'0'),
	(81,'0'),
	(82,'0'),
	(83,'0'),
	(84,'0'),
	(85,'0'),
	(86,'0'),
	(87,'-4'),
	(88,'0'),
	(89,'0'),
	(90,'0'),
	(91,'20'),
	(92,'0'),
	(93,'0'),
	(94,'0'),
	(95,'0'),
	(96,'0'),
	(97,'0'),
	(98,'0'),
	(99,'0'),
	(100,'0'),
	(101,'0'),
	(103,'-5'),
	(104,'-4'),
	(105,'0'),
	(106,'-1'),
	(107,'0'),
	(108,'0'),
	(109,'-1'),
	(110,'0'),
	(111,'0'),
	(112,'-1'),
	(113,'0'),
	(114,'0'),
	(115,'-4'),
	(116,'0'),
	(117,'0'),
	(118,'-2'),
	(119,'-1'),
	(120,'-2'),
	(121,'0'),
	(122,'0'),
	(123,'-2'),
	(124,'0'),
	(125,'0'),
	(126,'-14'),
	(127,'-12'),
	(128,'-6'),
	(129,'0'),
	(130,'0'),
	(131,'0'),
	(132,'-12'),
	(133,'3'),
	(134,'0'),
	(135,'-7'),
	(136,'-38'),
	(137,'312'),
	(138,'0'),
	(139,'0'),
	(140,'-27'),
	(141,'0'),
	(142,'0'),
	(143,'0'),
	(144,'0'),
	(145,'0'),
	(146,'-1'),
	(147,'0'),
	(148,'0'),
	(149,'0'),
	(150,'0'),
	(151,'-1'),
	(152,'0'),
	(153,'0');

/*!40000 ALTER TABLE `existencias` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `merma` WRITE;
/*!40000 ALTER TABLE `merma` DISABLE KEYS */;

INSERT INTO `merma` (`id_merma`, `id_usuario`, `fecha`, `activo`, `observaciones`)
VALUES
	(1,1,'2019-09-18',0,'miaw'),
	(2,1,'2019-09-19',0,''),
	(3,1,'2019-09-19',0,''),
	(4,1,'2019-09-19',0,'ola'),
	(5,1,'2019-09-19',0,'qeqwe'),
	(6,1,'2019-09-19',0,'GHFLKGHFL');

/*!40000 ALTER TABLE `merma` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `merma_detalle` WRITE;
/*!40000 ALTER TABLE `merma_detalle` DISABLE KEYS */;

INSERT INTO `merma_detalle` (`id_detalle`, `id_merma`, `id_producto`, `cantidad`, `activo`)
VALUES
	(1,1,137,1,0),
	(2,1,137,2,0),
	(3,2,137,2,0),
	(4,3,137,23,0),
	(5,4,63,2,0),
	(6,5,87,2,0),
	(7,6,137,4,0);

/*!40000 ALTER TABLE `merma_detalle` ENABLE KEYS */;
UNLOCK TABLES;


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
	(197,135,126,1),
	(198,135,127,1),
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
	(508,136,149,0);

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
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;

INSERT INTO `productos` (`id_producto`, `id_categoria`, `codigo`, `nombre`, `precio_venta`, `activo`, `extra`, `tiene`, `sinn`, `imprimir_solo`, `impresora`, `paquete`)
VALUES
	(16,2,'355','COCA',15.00,1,0,0,0,0,0,0),
	(17,2,'17','PITAYA',15.00,1,0,0,0,0,0,0),
	(18,2,'18','JAMAICA',15.00,1,0,0,0,0,0,0),
	(19,2,'19','LIMONADA CHIA',15.00,1,0,0,0,0,0,0),
	(20,2,'20','TAMARINDO',15.00,1,0,0,0,0,0,0),
	(21,2,'21','HORCHATA',15.00,1,0,0,0,0,0,0),
	(22,2,'22','CEBADA',15.00,1,0,0,0,0,0,0),
	(23,2,'23','NARANJA AGRIA',15.00,1,0,0,0,0,0,0),
	(24,1,'D2','DEDOS DE QUESO',45.00,1,0,0,0,0,0,0),
	(26,1,'BBQ','ALITAS',119.00,1,0,1,0,0,0,0),
	(35,1,'P1','PAK NUGGETS',140.00,1,0,0,0,0,0,0),
	(36,1,'P2','PAK ALITAS',155.00,1,0,1,0,0,0,0),
	(41,1,'PK2','PAK ALITAS BBQ 5PZA',155.00,1,0,0,0,0,0,0),
	(43,1,'PROMO','MIÃ‰RCOLES DE ALITAS',10.00,1,0,0,0,0,0,0),
	(46,1,'HD','HOT DOG SENCILLO',15.00,1,0,0,0,0,0,0),
	(47,1,'HOTDOGES','HOT DOG ESPECIAL',18.00,1,0,0,0,0,0,0),
	(48,2,'REF','CRSITAL SABOR',15.00,1,0,0,0,0,0,0),
	(49,3,'1234','ESPAÃ‘OLA',45.00,1,0,1,0,0,0,0),
	(50,3,'ESPA','ESPAÃ‘OLA DOBLE',65.00,1,0,1,0,0,0,0),
	(51,3,'0003','RANCHERA',45.00,1,0,1,0,0,0,0),
	(52,3,'0004','RANCHERA DOBLE',65.00,1,0,1,0,0,0,0),
	(53,3,'0005','MOZZARELA',45.00,1,0,1,0,0,0,0),
	(54,3,'0006','MOZZARELA DOBLE',45.00,1,0,1,0,0,0,0),
	(55,3,'0007','SUPREMA',60.00,1,0,1,0,0,0,0),
	(56,3,'0008','SUPREMA DOBLE',90.00,1,0,1,0,0,0,0),
	(57,3,'0009','KGB',65.00,1,0,1,0,0,0,0),
	(58,3,'0010','KGB DOBLE',100.00,1,0,1,0,0,0,0),
	(59,3,'0011','BBQ HOT',55.00,1,0,1,0,0,0,0),
	(60,3,'0012','BBQ HOT DOBLE',65.00,1,0,1,0,0,0,0),
	(61,3,'0013','CUBANA',50.00,1,0,1,0,0,0,0),
	(62,3,'0014','CUBANA DOBLE',65.00,1,0,1,0,0,0,0),
	(63,3,'0015','FEROZ',45.00,1,0,1,0,0,0,0),
	(64,3,'0016','FEROZ DOBLE',65.00,1,0,1,0,0,0,0),
	(65,3,'0017','MEXICANA',45.00,1,0,1,0,0,0,0),
	(66,3,'0018','MEXICANA DOBLE',65.00,1,0,1,0,0,0,0),
	(67,3,'0019','TROPICANA',45.00,1,0,1,0,0,0,0),
	(68,3,'0020','TROPICANA DOBLE',65.00,1,0,1,0,0,0,0),
	(69,3,'0021','CAVERNICOLA',60.00,1,0,1,0,0,0,0),
	(70,3,'0022','CAVERNICOLA DOBLE',90.00,1,0,1,0,0,0,0),
	(71,3,'0023','PARRILLERA',45.00,1,0,1,0,0,0,0),
	(72,3,'0024','PARRILLERA DOBLE',65.00,1,0,1,0,0,0,0),
	(73,3,'0025','CHEESE',45.00,1,0,1,0,0,0,0),
	(74,3,'0026','CHEESE DOBLE',65.00,1,0,1,0,0,0,0),
	(75,3,'0027','CHIPI HOT',55.00,1,0,1,0,0,0,0),
	(76,3,'0028','CHIPI HOT DOBLE',65.00,1,0,1,0,0,0,0),
	(77,3,'0029','INCÃ“GNITA',55.00,1,0,1,0,0,0,0),
	(78,3,'0030','INCÃ“GNITA DOBLE',65.00,1,0,1,0,0,0,0),
	(79,3,'0031','ITALIANA',45.00,1,0,1,0,0,0,0),
	(80,3,'0032','ITALIANA DOBLE',65.00,1,0,1,0,0,0,0),
	(81,3,'0033','MR. KRAB',45.00,1,0,1,0,0,0,0),
	(82,3,'0034','MR. KRAB DOBLE',65.00,1,0,1,0,0,0,0),
	(83,3,'0035','GABACHA',45.00,1,0,1,0,0,0,0),
	(84,3,'0036','GABACHA DOBLE',65.00,1,0,1,0,0,0,0),
	(85,3,'0037','HAWAIANA',45.00,1,0,1,0,0,0,0),
	(86,3,'0038','HAWAIANA DOBLE',65.00,1,0,1,0,0,0,0),
	(87,3,'0039','SENCILLA',38.00,1,0,1,0,0,0,0),
	(88,3,'0040','SENCILLA DOBLE',60.00,1,0,1,0,0,0,0),
	(89,1,'0041','ORDEN DE NUGGETS',45.00,1,0,0,0,0,0,0),
	(90,1,'0042','ORDEN DE PAPAS GAJO',45.00,1,0,0,0,0,0,0),
	(91,1,'0043','ORDEN DE AROS DE CEBOLLA',40.00,1,0,0,0,0,0,0),
	(92,1,'0044','ORDEN DE TENDERS',80.00,1,0,0,0,0,0,0),
	(93,1,'0045','ORDEN DE ALITAS DE POLLO',119.00,1,0,1,0,0,0,0),
	(94,1,'0046','ORDEN CALIFORNIANA',75.00,1,0,0,0,0,0,0),
	(95,1,'0047','1/2 ORDEN CALIFORNIANA',40.00,1,0,0,0,0,0,0),
	(96,1,'0048','NACHOS DE CARNES FRÃAS',90.00,1,0,0,0,0,0,0),
	(97,1,'0049','1/2 NACHOS DE CARNES FRÃAS',55.00,1,0,0,0,0,0,0),
	(98,1,'0050','NACHOS MEXICANA',85.00,1,0,0,0,0,0,0),
	(99,1,'0051','1/2 NACHOS MEXICANA',50.00,1,0,0,0,0,0,0),
	(100,1,'0052','ORDEN NACHOS PASTOR',110.00,1,0,0,0,0,0,0),
	(101,1,'0053','1/2 NACHOS PASTOR',60.00,1,0,0,0,0,0,0),
	(103,4,'carne','CARNE',10.00,1,1,0,0,0,0,0),
	(104,4,'queso','QUESO',10.00,1,1,0,0,0,0,0),
	(105,4,'salami','SALAMI',10.00,1,1,0,0,0,0,0),
	(106,4,'peperoni','PEPERONI',10.00,1,1,0,0,0,0,0),
	(107,4,'CHORIZO','CHORIZO',10.00,1,1,0,0,0,0,0),
	(108,4,'jalapeNo','JALAPENO',10.00,1,1,0,0,0,0,0),
	(109,4,'mozarela','MOZARELA',10.00,1,1,0,0,0,0,0),
	(110,4,'philadelphia','PHILADELPHIA',10.00,1,1,0,0,0,0,0),
	(111,4,'s/azar','SALCHICHA PARA ASAR',10.00,1,1,0,0,0,0,0),
	(112,4,'tocino','TOCINO',10.00,1,1,0,0,0,0,0),
	(113,4,'aguacate','AGUACATE',10.00,1,1,0,0,0,0,0),
	(114,4,'pina','PINA',10.00,1,1,0,0,0,0,0),
	(115,4,'champi','CHAMPINON',10.00,1,1,0,0,0,0,0),
	(116,4,'SALSA BBQ','SALSA BBQ',10.00,1,1,0,0,0,0,0),
	(117,4,'legumbres','LEGUMBRES',10.00,1,1,0,0,0,0,0),
	(118,4,'gouda','GOUDA',10.00,1,1,0,0,0,0,0),
	(119,4,'chihuahua','CHIHUAHUA',10.00,1,1,0,0,0,0,0),
	(120,4,'manchego','MANCHEGO',10.00,1,1,0,0,0,0,0),
	(121,4,'s/chipi','SALSA CHIPI',10.00,1,1,0,0,0,0,0),
	(122,4,'s/tamarindo','SALSA TAMARINDO',10.00,1,1,0,0,0,0,0),
	(123,4,'salchi','SALCHICHA',10.00,1,1,0,0,0,0,0),
	(124,4,'PASTOR','PASTOR',10.00,1,1,0,0,0,0,0),
	(125,4,'POLLO/C','POLLO CRUJIENTE',10.00,1,1,0,0,0,0,0),
	(126,4,'PEPI','PEPINILLOS',10.00,1,1,0,0,0,0,0),
	(127,4,'ARACHERA','ARACHERA',10.00,1,1,0,0,0,0,0),
	(128,4,'QUESOB','QUESO DE BOLA',10.00,1,1,0,0,0,0,0),
	(129,3,'KRAKEN','KRAKEN',125.00,1,0,1,0,0,0,0),
	(130,3,'TENTACION','TENTACION',75.00,1,0,1,0,0,0,0),
	(131,3,'TENTACIONDOBLE','TENTACIÃ“N DOBLE',105.00,1,0,1,0,0,0,0),
	(132,3,'ARRARING','ARRACHERA ONION RINGS',95.00,1,0,1,0,0,0,0),
	(133,3,'PAS','HAMBURGUESA  PASTOR',59.00,1,0,1,0,0,0,0),
	(134,3,'PASTOR2','HAMBURGUESA PASTOR DOBLE',89.00,1,0,1,0,0,0,0),
	(135,3,'ARRAC','ARRACHERA',85.00,1,0,1,0,0,0,0),
	(136,3,'ARRACHERA MUSHROOM','ARRACHERA MUSHROOM',99.00,1,0,1,0,0,0,0),
	(137,4,'PAN','PAN',0.00,1,1,0,0,0,0,0),
	(138,4,'rhot','RED HOT',0.00,1,1,0,0,0,0,0),
	(139,4,'BBQH','SALSA BBQ HOT',0.00,1,1,0,0,0,0,0),
	(140,4,'BCHE','BLUE CHESSE',0.00,1,1,0,0,0,0,0),
	(141,4,'TAM','TAMARINDO CHIPOTLE',0.00,1,1,0,0,0,0,0),
	(142,4,'VALEFUE','VALENTINA FUEGO',0.00,1,1,0,0,0,0,0),
	(143,4,'VALEBBQ','VALENTINA BBQ',0.00,1,1,0,0,0,0,0),
	(144,5,'SINCEBOLLA','SIN CEBOLLA',0.00,1,0,0,1,0,0,0),
	(145,5,'CATS','SIN CATSUP',0.00,1,0,0,1,0,0,0),
	(146,5,'SINMOS','SIN MOSTAZA',0.00,1,0,0,1,0,0,0),
	(147,5,'LECH','SIN LECHUGA',0.00,1,0,0,1,0,0,0),
	(148,5,'PICA','SIN JALAPENO',0.00,1,0,0,1,0,0,0),
	(149,5,'SINQUE','SIN QUESO',0.00,1,0,0,1,0,0,0),
	(151,6,'PAK1','SNAK PAK 1',155.00,1,0,0,0,0,0,1),
	(152,1,'ORPAPAS2','ORDEN DE PAPAS',40.00,1,0,0,0,0,0,0),
	(153,6,'PAK2','SNAK PAK 2',140.00,1,0,0,0,0,0,1);

/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla productos_base
# ------------------------------------------------------------

DROP TABLE IF EXISTS `productos_base`;

CREATE TABLE `productos_base` (
  `id_base` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `id_unidad` int(11) NOT NULL,
  `precio` int(250) NOT NULL,
  PRIMARY KEY (`id_base`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `productos_base` WRITE;
/*!40000 ALTER TABLE `productos_base` DISABLE KEYS */;

INSERT INTO `productos_base` (`id_base`, `producto`, `id_unidad`, `precio`)
VALUES
	(1,'PAN',3,0),
	(2,'CARNE',3,0),
	(3,'QUESO',3,0),
	(4,'SALAMI',3,0),
	(5,'PEPERONI',3,0),
	(6,'CHORIZO',3,0),
	(7,'JALAPENO',3,0),
	(8,'MOZARELA',3,0),
	(9,'PHILADELFIA',3,0),
	(10,'SALCHICA PARA ASAR',3,0),
	(11,'TOCINO',3,0),
	(12,'AGUACATE',3,0),
	(13,'PINA',3,0),
	(14,'CHAMPINON',3,0),
	(15,'LEGUMBRES',3,0),
	(16,'GOUDA',3,0),
	(17,'CHIHUAHA',3,0),
	(18,'MANCHEGO',3,0),
	(19,'SALCHICHA',3,0),
	(20,'PASTOR',3,0),
	(21,'POLLO',3,0),
	(22,'PEPINILLOS',3,0),
	(23,'ARRACHERA',3,0),
	(24,'QUESO DE BOLA',3,0),
	(25,'AROS DE CEBOLLA',3,0);

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
	(305,73,150,1);

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
	('5987207e3875f5ee2bc52b02a805ac11','5987207e3875f5ee2bc52b02a805ac11','ASFAasdasSF23','FaasdaSA23');

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
	(1,1,'Admin','admin','21232f297a57a5a743894a0e4a801fc3',1,'2019-09-19 14:26:44',1,0),
	(2,2,'Vendedor','Vendedor','c4ca4238a0b923820dcc509a6f75849b',1,NULL,1,0);

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
	(1,1,22,1,15.00,'',0),
	(2,2,22,1,15.00,'',0),
	(3,3,22,1,15.00,'',0),
	(4,4,22,1,15.00,'',0),
	(5,5,16,1,15.00,'',0),
	(6,6,22,1,15.00,'',0),
	(7,7,59,1,55.00,'',0),
	(8,7,112,1,10.00,'',0),
	(9,7,111,1,10.00,'',0),
	(10,7,104,1,10.00,'',0),
	(11,7,0,1,0.00,'',0),
	(12,7,135,1,85.00,'',0),
	(13,7,126,1,10.00,'',0),
	(14,7,0,1,0.00,'',0),
	(15,7,61,1,50.00,'',0),
	(16,7,114,1,10.00,'',0),
	(17,7,104,1,10.00,'',0),
	(18,7,0,1,0.00,'',0),
	(19,7,63,1,45.00,'',0),
	(20,7,107,1,10.00,'',0),
	(21,7,112,1,10.00,'',0),
	(22,7,0,1,0.00,'',0),
	(24,9,22,1,15.00,'',0),
	(25,10,22,1,15.00,'',0),
	(26,11,22,2,15.00,'',0),
	(27,12,22,1,15.00,'',0),
	(28,13,22,1,15.00,'',0),
	(29,14,59,1,55.00,'',0),
	(30,14,117,1,10.00,'',0),
	(31,14,111,1,10.00,'',0),
	(32,14,145,1,0.00,'',0),
	(33,14,144,1,0.00,'',0),
	(34,14,0,1,0.00,'',0),
	(35,15,135,1,85.00,'',0),
	(36,15,126,1,10.00,'',0),
	(37,15,144,1,0.00,'',0),
	(38,15,145,1,0.00,'',0),
	(39,15,0,1,0.00,'',0),
	(40,16,135,1,85.00,'',0),
	(41,16,144,1,0.00,'',0),
	(42,16,145,1,0.00,'',0),
	(43,16,0,1,0.00,'',0),
	(44,17,135,1,85.00,'',0),
	(45,17,127,1,10.00,'',0),
	(46,17,0,1,0.00,'',0),
	(47,18,135,1,85.00,'',0),
	(48,18,0,1,0.00,'',0),
	(49,19,135,1,85.00,'',0),
	(50,19,126,1,10.00,'',0),
	(51,19,127,1,10.00,'',0),
	(52,19,0,1,0.00,'',0),
	(53,19,97,1,55.00,'',0),
	(54,19,43,1,10.00,'',0),
	(55,19,94,1,75.00,'',0),
	(56,19,26,1,119.00,'',0),
	(57,19,138,1,0.00,'',0),
	(58,19,143,1,0.00,'',0),
	(59,19,0,1,0.00,'',0),
	(60,20,22,3,15.00,'',0),
	(61,21,150,1,151.00,'',0),
	(62,21,49,3,0.00,'',0),
	(63,21,16,3,0.00,'',0),
	(64,22,150,1,151.00,'',0),
	(65,22,49,3,0.00,'',0),
	(66,22,16,3,0.00,'',0),
	(67,23,150,1,151.00,'',0),
	(68,23,49,3,0.00,'',0),
	(69,23,16,3,0.00,'',0),
	(70,24,150,1,151.00,'',0),
	(71,24,49,3,0.00,'',0),
	(72,24,16,3,0.00,'',0),
	(73,25,136,1,99.00,'',0),
	(74,25,126,1,10.00,'',0),
	(75,25,0,1,0.00,'',0),
	(76,26,22,1,15.00,'',0),
	(77,27,136,1,99.00,'',0),
	(78,27,126,1,10.00,'',0),
	(79,27,0,1,0.00,'',0),
	(80,28,135,1,85.00,'',0),
	(81,28,126,1,10.00,'',0),
	(82,28,0,1,0.00,'',0),
	(83,29,135,1,85.00,'',0),
	(84,29,126,1,10.00,'',0),
	(85,29,0,1,0.00,'',0),
	(86,30,136,1,99.00,'',0),
	(87,30,128,1,10.00,'',0),
	(88,30,0,1,0.00,'',0),
	(89,31,140,25,0.00,'',0),
	(90,31,127,3,10.00,'',0),
	(91,31,103,5,10.00,'',0),
	(92,32,22,1,15.00,'',0),
	(93,33,22,1,15.00,'',0),
	(94,34,16,1,15.00,'',0),
	(95,35,48,1,15.00,'',0),
	(96,36,22,1,15.00,'',0),
	(97,37,16,1,15.00,'',0),
	(98,38,22,1,15.00,'',0),
	(109,39,136,7,99.00,'',0),
	(110,39,126,1,10.00,'',0),
	(111,39,0,1,0.00,'',0),
	(112,39,59,2,55.00,'',0),
	(113,39,0,1,0.00,'',0),
	(125,40,22,1,15.00,'',0),
	(126,40,16,1,15.00,'',0),
	(127,40,73,1,45.00,'',0),
	(128,40,118,1,10.00,'',0),
	(129,40,109,1,10.00,'',0),
	(130,40,119,1,10.00,'',0),
	(131,40,120,1,10.00,'',0),
	(132,40,115,1,10.00,'',0),
	(133,40,0,1,0.00,'',0),
	(134,40,132,10,95.00,'',0),
	(135,40,127,1,10.00,'',0),
	(136,40,0,1,0.00,'',0),
	(137,41,16,1,15.00,'',0),
	(138,41,136,6,99.00,'',0),
	(139,41,127,1,10.00,'',0),
	(140,41,0,1,0.00,'',0),
	(141,42,136,1,99.00,'',0),
	(142,42,128,1,10.00,'',0),
	(143,42,127,1,10.00,'',0),
	(144,42,0,1,0.00,'',0),
	(145,42,74,4,65.00,'',0),
	(146,42,120,1,10.00,'',0),
	(147,42,118,1,10.00,'',0),
	(148,42,0,1,0.00,'',0),
	(149,43,22,2,15.00,'',0),
	(150,43,136,1,99.00,'',0),
	(151,43,126,1,10.00,'',0),
	(152,43,128,1,10.00,'',0),
	(153,43,127,1,10.00,'',0),
	(154,43,115,1,10.00,'',0),
	(155,43,0,1,0.00,'',0),
	(156,44,16,1,15.00,'',0),
	(157,44,136,7,99.00,'',0),
	(158,44,127,1,10.00,'',0),
	(159,44,0,1,0.00,'',0),
	(160,45,136,1,99.00,'',0),
	(161,45,128,1,10.00,'',0),
	(162,45,127,1,10.00,'',0),
	(163,45,115,1,10.00,'',0),
	(164,45,0,1,0.00,'',0),
	(165,45,132,1,95.00,'',0),
	(166,45,127,1,10.00,'',0),
	(167,45,0,1,0.00,'',0),
	(168,45,75,6,55.00,'',0),
	(169,45,104,1,10.00,'',0),
	(170,45,112,1,10.00,'',0),
	(171,45,123,1,10.00,'',0),
	(172,45,106,1,10.00,'',0),
	(173,45,0,1,0.00,'',0),
	(174,46,22,1,15.00,'',0),
	(175,46,135,1,85.00,'',0),
	(176,46,126,1,10.00,'',0),
	(177,46,0,1,0.00,'',0),
	(185,47,22,1,15.00,'',0),
	(186,47,135,1,85.00,'',0),
	(187,47,126,1,10.00,'',0),
	(188,47,0,1,0.00,'',0),
	(189,48,22,1,15.00,'',0),
	(190,48,75,1,55.00,'',0),
	(191,48,104,1,10.00,'',0),
	(192,48,0,1,0.00,'',0),
	(193,49,22,1,15.00,'',0),
	(194,50,22,1,15.00,'',0),
	(203,51,22,1,15.00,'',0),
	(204,52,22,1,15.00,'',0),
	(205,53,23,1,15.00,'',0),
	(206,53,21,1,15.00,'',0),
	(207,53,87,1,38.00,'',0),
	(208,53,104,1,10.00,'',0),
	(209,53,123,1,10.00,'',0),
	(210,53,0,1,0.00,'',0),
	(211,54,23,1,15.00,'',0),
	(212,54,71,2,45.00,'',0),
	(213,54,104,1,10.00,'',0),
	(214,54,146,1,0.00,'',0),
	(215,54,0,1,0.00,'',0),
	(217,55,20,3,15.00,'',0),
	(218,55,71,2,45.00,'',0),
	(219,55,0,1,0.00,'',0),
	(220,56,71,7,45.00,'',0),
	(221,57,23,1,15.00,'',0),
	(222,57,21,1,15.00,'',0),
	(223,58,71,1,45.00,'',0),
	(224,58,0,1,0.00,'',0),
	(225,59,71,1,45.00,'',0),
	(226,59,0,1,0.00,'',0),
	(227,59,87,1,38.00,'',0),
	(228,59,0,1,0.00,'',0),
	(229,59,140,1,0.00,'',0),
	(230,59,151,1,155.00,'',0),
	(231,59,90,1,0.00,'',0),
	(232,59,91,1,0.00,'',0),
	(233,59,152,1,0.00,'',0),
	(234,59,26,1,0.00,'',0);

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
	(1,1,'2019-09-12','09:56:21',1,'213',0,1,'0000-00-00 00:00:00','2019-09-12 09:56:26',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(2,1,'2019-09-12','09:58:35',8,'24',0,1,'0000-00-00 00:00:00','2019-09-12 09:58:39',1,'',0,0.00,15.00,0,'','EFECTIVO',300.00,285.00,0,0.00,0,0.00,15.00),
	(3,1,'2019-09-12','10:00:52',9,'20',0,1,'0000-00-00 00:00:00','2019-09-12 10:00:56',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(4,1,'2019-09-12','10:01:24',10,'20',0,1,'0000-00-00 00:00:00','2019-09-12 10:01:32',1,'',0,0.00,15.00,0,'','EFECTIVO',200.00,185.00,0,0.00,0,0.00,15.00),
	(5,1,'2019-09-12','10:03:25',11,'30',0,1,'0000-00-00 00:00:00','2019-09-12 10:03:33',1,'',0,0.00,15.00,0,'','EFECTIVO',30.00,15.00,0,0.00,0,0.00,15.00),
	(6,3,'2019-09-12','16:40:06',12,'1',0,1,'0000-00-00 00:00:00','2019-09-12 16:40:12',1,'',0,0.00,15.00,0,'','EFECTIVO',15.00,0.00,0,0.00,0,0.00,15.00),
	(7,1,'2019-09-12','16:40:14',12,'1',0,1,'2019-09-12 17:40:35','2019-09-12 16:40:56',1,'',0,0.00,315.00,0,'','EFECTIVO',315.00,0.00,0,0.00,0,0.00,315.00),
	(8,1,'2019-09-12','16:45:16',13,'50',0,1,'0000-00-00 00:00:00','2019-09-12 16:45:20',1,'',0,0.00,15.00,0,'','EFECTIVO',100.00,85.00,0,0.00,0,0.00,15.00),
	(9,1,'2019-09-13','09:38:50',13,'233',0,1,'0000-00-00 00:00:00','2019-09-13 09:38:55',1,'',0,0.00,15.00,0,'','EFECTIVO',30.00,15.00,0,0.00,0,0.00,15.00),
	(10,1,'2019-09-13','10:48:27',13,'324',0,1,'0000-00-00 00:00:00','2019-09-13 10:48:42',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(11,1,'2019-09-13','10:48:47',13,'213',0,1,'0000-00-00 00:00:00','2019-09-13 10:48:55',1,'',0,0.00,30.00,0,'','EFECTIVO',30.00,0.00,0,0.00,0,0.00,30.00),
	(12,1,'2019-09-13','10:50:05',13,'30',0,1,'2019-09-13 11:50:29','2019-09-13 16:05:18',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(13,1,'2019-09-13','10:51:28',13,'213',0,1,'2019-09-13 17:05:34','2019-09-13 16:05:50',1,'',0,0.00,15.00,0,'','EFECTIVO',50.00,35.00,0,0.00,0,0.00,15.00),
	(14,1,'2019-09-13','15:59:24',13,'BARRA',0,1,'2019-09-13 15:59:24','2019-09-13 15:59:55',1,'',0,0.00,75.00,0,'','EFECTIVO',100.00,25.00,0,0.00,0,0.00,75.00),
	(15,1,'2019-09-13','16:02:54',13,'BARRA',0,1,'2019-09-13 16:02:54','2019-09-13 16:03:10',1,'',0,0.00,95.00,0,'','EFECTIVO',100.00,5.00,0,0.00,0,0.00,95.00),
	(16,1,'2019-09-13','16:47:25',14,'BARRA',0,1,'2019-09-13 16:47:25','2019-09-13 16:47:36',1,'',0,0.00,85.00,0,'','EFECTIVO',100.00,15.00,0,0.00,0,0.00,85.00),
	(17,1,'2019-09-13','16:47:57',14,'12',0,1,'0000-00-00 00:00:00','2019-09-13 16:48:13',1,'',0,0.00,95.00,0,'','EFECTIVO',100.00,5.00,0,0.00,0,0.00,95.00),
	(18,1,'2019-09-13','16:48:35',14,'10',0,1,'2019-09-13 17:48:52','2019-09-13 16:49:40',1,'',0,0.00,85.00,0,'','EFECTIVO',100.00,15.00,0,0.00,0,0.00,85.00),
	(19,1,'2019-09-16','10:56:34',14,'23',0,1,'2019-09-16 11:56:38','2019-09-16 10:56:47',1,'',0,0.00,364.00,0,'','EFECTIVO',400.00,36.00,0,0.00,0,0.00,364.00),
	(20,3,'2019-09-16','14:21:10',15,'1',0,1,'2019-09-16 15:21:13','2019-09-16 14:21:22',1,'',0,0.00,45.00,0,'','EFECTIVO',50.00,5.00,0,0.00,0,0.00,45.00),
	(21,1,'2019-09-16','15:46:17',16,'PRUEBA',0,1,'2019-09-16 16:46:26','2019-09-16 15:47:12',28,'',0,0.00,151.00,0,'','TARJETA DEBITO',200.00,49.00,0,0.00,0,0.00,151.00),
	(22,1,'2019-09-16','16:04:38',17,'200',0,1,'2019-09-16 17:04:44','2019-09-16 16:04:52',1,'',0,0.00,151.00,0,'','EFECTIVO',200.00,49.00,0,0.00,0,0.00,151.00),
	(23,1,'2019-09-16','16:14:09',17,'23',0,1,'2019-09-16 17:14:13','2019-09-16 16:14:22',1,'',0,0.00,151.00,0,'','EFECTIVO',200.00,49.00,0,0.00,0,0.00,151.00),
	(24,1,'2019-09-16','16:18:45',18,'23',0,1,'2019-09-16 17:27:08','2019-09-16 16:27:14',1,'',0,0.00,151.00,0,'','EFECTIVO',200.00,49.00,0,0.00,0,0.00,151.00),
	(25,1,'2019-09-17','18:37:57',19,'Q23',0,1,'2019-09-17 19:38:05','2019-09-18 12:06:32',1,'',0,109.00,109.00,0,'','EFECTIVO',100.00,12.80,0,0.00,1,21.80,0.00),
	(26,1,'2019-09-17','18:40:14',19,'QWE',0,1,'2019-09-18 13:13:17','2019-09-18 12:14:51',1,'',0,0.00,15.00,3,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(27,1,'2019-09-18','10:22:32',19,'BARRA',0,1,'2019-09-18 10:22:32','2019-09-18 10:22:37',1,'',0,0.00,109.00,0,'','EFECTIVO',200.00,91.00,0,0.00,0,0.00,109.00),
	(28,1,'2019-09-18','10:23:04',19,'QWE',0,1,'2019-09-18 13:06:14','2019-09-18 12:14:58',1,'',0,0.00,95.00,0,'','EFECTIVO',100.00,5.00,0,0.00,0,0.00,95.00),
	(29,1,'2019-09-18','10:35:04',19,'BARRA',0,1,'2019-09-18 10:35:04','2019-09-18 10:35:13',1,'',0,0.00,95.00,0,'','EFECTIVO',100.00,5.00,0,0.00,0,0.00,95.00),
	(30,1,'2019-09-18','10:35:21',19,'WQ',0,1,'0000-00-00 00:00:00','2019-09-18 10:35:26',1,'',0,0.00,109.00,0,'','EFECTIVO',200.00,91.00,0,0.00,0,0.00,109.00),
	(31,1,'2019-09-18','11:21:31',19,'BARRA',0,1,'2019-09-18 11:21:31','2019-09-18 12:15:03',1,'',0,0.00,80.00,0,'','EFECTIVO',100.00,20.00,0,0.00,0,0.00,80.00),
	(32,1,'2019-09-18','12:07:12',19,'123',0,1,'0000-00-00 00:00:00','2019-09-18 12:07:17',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(33,1,'2019-09-18','12:07:32',19,'123',0,1,'2019-09-18 13:07:34','2019-09-18 12:15:09',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(34,1,'2019-09-18','12:16:46',20,'BARRA',0,1,'2019-09-18 12:16:46','2019-09-18 12:16:49',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(35,1,'2019-09-18','12:22:36',21,'123',0,1,'2019-09-18 13:31:40','2019-09-18 12:31:49',1,'',0,15.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,0.00),
	(36,1,'2019-09-18','13:10:17',22,'12',0,1,'2019-09-18 14:10:22','2019-09-18 13:10:39',1,'',0,15.00,15.00,0,'','EFECTIVO',20.00,8.00,0,0.00,1,3.00,0.00),
	(37,1,'2019-09-18','13:11:49',23,'12',0,1,'2019-09-18 14:11:53','2019-09-18 13:12:00',1,'',0,15.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,0.00),
	(38,1,'2019-09-18','13:49:19',24,'12',0,1,'2019-09-18 14:49:21','2019-09-18 13:49:28',1,'',0,0.00,15.00,0,'','EFECTIVO',23.00,8.00,0,0.00,0,0.00,15.00),
	(39,1,'2019-09-18','14:36:08',24,'BARRA',0,1,'2019-09-18 14:36:08','2019-09-18 14:36:13',1,'',0,0.00,813.00,0,'','EFECTIVO',1000.00,187.00,0,0.00,0,0.00,813.00),
	(40,1,'2019-09-18','14:37:01',24,'BARRA',0,1,'2019-09-18 14:37:01','2019-09-18 14:37:05',1,'',0,0.00,1085.00,0,'','EFECTIVO',2000.00,915.00,0,0.00,0,0.00,1085.00),
	(41,1,'2019-09-18','14:37:18',24,'BARRA',0,1,'2019-09-18 14:37:18','2019-09-18 14:37:22',4,'',0,0.00,619.00,0,'','TARJETA CREDITO',1000.00,381.00,0,0.00,0,0.00,619.00),
	(42,1,'2019-09-18','14:37:34',24,'BARRA',0,1,'2019-09-18 14:37:34','2019-09-18 14:37:39',4,'',0,0.00,399.00,0,'','TARJETA CREDITO',5000.00,4601.00,0,0.00,0,0.00,399.00),
	(43,1,'2019-09-18','14:37:48',24,'BARRA',0,1,'2019-09-18 14:37:48','2019-09-18 14:37:52',28,'',0,0.00,169.00,0,'','TARJETA DEBITO',200.00,31.00,0,0.00,0,0.00,169.00),
	(44,1,'2019-09-18','14:41:06',24,'123',0,1,'0000-00-00 00:00:00','2019-09-18 14:41:11',28,'',0,0.00,718.00,0,'','TARJETA DEBITO',1000.00,282.00,0,0.00,0,0.00,718.00),
	(45,1,'2019-09-18','14:41:25',24,'12312',0,1,'0000-00-00 00:00:00','2019-09-18 14:41:30',28,'',0,0.00,604.00,0,'','TARJETA DEBITO',1000.00,396.00,0,0.00,0,0.00,604.00),
	(46,1,'2019-09-18','15:25:59',25,'BARRA',0,1,'2019-09-18 15:25:59','2019-09-18 15:26:06',1,'',0,0.00,110.00,0,'','EFECTIVO',200.00,90.00,0,0.00,0,0.00,110.00),
	(47,1,'2019-09-18','15:26:52',25,'BARRA',0,1,'2019-09-18 15:26:52','2019-09-18 15:27:18',4,'',0,0.00,110.00,0,'','TARJETA CREDITO',110.00,0.00,0,0.00,0,0.00,110.00),
	(48,1,'2019-09-18','15:27:39',25,'12',0,1,'0000-00-00 00:00:00','2019-09-18 15:27:44',28,'',0,0.00,80.00,0,'','TARJETA DEBITO',100.00,20.00,0,0.00,0,0.00,80.00),
	(49,1,'2019-09-18','15:32:34',26,'2',0,1,'0000-00-00 00:00:00','2019-09-18 15:32:38',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(50,1,'2019-09-18','16:12:54',27,'122',0,1,'0000-00-00 00:00:00','2019-09-18 16:12:57',1,'',0,0.00,15.00,0,'','EFECTIVO',20.00,5.00,0,0.00,0,0.00,15.00),
	(51,1,'2019-09-18','16:13:27',27,'231',0,1,'0000-00-00 00:00:00','2019-09-18 16:13:29',28,'',0,0.00,15.00,0,'','TARJETA DEBITO',0.00,0.00,0,0.00,0,0.00,15.00),
	(53,1,'2019-09-19','09:57:13',29,'MUAW',0,1,'2019-09-19 11:45:22','2019-09-19 10:45:45',1,'',0,88.00,88.00,0,'','EFECTIVO',200.00,129.60,0,0.00,1,17.60,0.00),
	(54,1,'2019-09-19','10:46:29',29,'BARRA',0,1,'2019-09-19 10:46:29','2019-09-19 10:47:03',1,'',0,0.00,92.00,0,'','EFECTIVO',200.00,108.00,0,0.00,1,23.00,115.00),
	(55,1,'2019-09-19','13:27:08',29,'123',0,1,'2019-10-07 11:46:22','2019-10-07 11:46:31',1,'',0,0.00,135.00,7,'','EFECTIVO',200.00,65.00,0,0.00,0,0.00,135.00),
	(56,1,'2019-09-19','18:01:28',29,'213',0,1,'0000-00-00 00:00:00','2019-09-19 18:01:31',1,'',0,0.00,315.00,0,'','EFECTIVO',400.00,85.00,0,0.00,0,0.00,315.00),
	(57,1,'2019-09-19','18:01:47',29,'QW',0,1,'2019-09-19 19:01:57','2019-10-07 11:46:11',1,'',1,4.80,34.80,0,'','EFECTIVO',400.00,365.20,0,0.00,0,0.00,30.00),
	(58,1,'2019-10-03','13:54:24',29,'BARRA',0,1,'2019-10-03 13:54:24','2019-10-07 11:46:17',1,'',0,0.00,45.00,0,'','EFECTIVO',500.00,455.00,0,0.00,0,0.00,45.00),
	(59,1,'2019-10-07','11:46:02',29,'312',0,1,'2019-10-07 11:46:25','2019-10-07 11:46:38',1,'',0,0.00,238.00,0,'','EFECTIVO',390.00,152.00,0,0.00,0,0.00,238.00);

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

LOCK TABLES `ventas_cancelaciones` WRITE;
/*!40000 ALTER TABLE `ventas_cancelaciones` DISABLE KEYS */;

INSERT INTO `ventas_cancelaciones` (`id_venta`, `id_usuario_cancelador`, `fechahora_cancelacion`, `motivo`, `id_venta_cancelacion`)
VALUES
	(26,1,'2019-09-18 13:08:05','undefined',1),
	(26,1,'2019-09-18 13:08:38','undefined',2),
	(26,1,'2019-09-18 13:13:11','undefined',3),
	(55,1,'2019-09-19 19:03:14','',4),
	(55,1,'2019-09-19 19:11:44','',5),
	(55,1,'2019-09-19 19:20:06','',6),
	(55,1,'2019-09-19 19:35:25','',7);

/*!40000 ALTER TABLE `ventas_cancelaciones` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `ventas_cancelaciones_detalle` WRITE;
/*!40000 ALTER TABLE `ventas_cancelaciones_detalle` DISABLE KEYS */;

INSERT INTO `ventas_cancelaciones_detalle` (`id_detalle`, `id_venta_cancelacion`, `id_producto`, `cantidad`, `precio_venta`, `id_venta`)
VALUES
	(1,1,22,'1',15.00,26),
	(2,2,22,'1',15.00,26),
	(3,3,22,'1',15.00,26),
	(4,4,23,'1',15.00,55),
	(5,4,20,'3',15.00,55),
	(6,4,71,'2',45.00,55),
	(7,4,0,'1',0.00,55),
	(8,5,23,'1',15.00,55),
	(9,5,20,'3',15.00,55),
	(10,5,71,'2',45.00,55),
	(11,5,0,'1',0.00,55),
	(12,6,20,'3',15.00,55),
	(13,6,71,'2',45.00,55),
	(14,6,0,'1',0.00,55),
	(15,7,20,'3',15.00,55),
	(16,7,71,'2',45.00,55),
	(17,7,0,'1',0.00,55);

/*!40000 ALTER TABLE `ventas_cancelaciones_detalle` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
