-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 02-10-2019 a las 19:42:04
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `vendefacil_4`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `categorias`
-- 

CREATE TABLE `categorias` (
  `id_categoria` mediumint(3) NOT NULL auto_increment,
  `nombre` varchar(20) collate utf8_spanish_ci NOT NULL default '',
  `impresora` varchar(64) collate utf8_spanish_ci NOT NULL,
  `activo` int(1) NOT NULL default '1',
  `ocultar` int(11) NOT NULL default '1',
  `es_paquete` tinyint(4) NOT NULL default '0',
  `alerta` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=10 ;

-- 
-- Volcar la base de datos para la tabla `categorias`
-- 

INSERT INTO `categorias` VALUES (1, 'SNACKS', 'COCINA', 1, 1, 0, 0);
INSERT INTO `categorias` VALUES (2, 'BEBIDAS', 'MESERO', 1, 1, 0, 1);
INSERT INTO `categorias` VALUES (3, 'BURGERS', 'PARRILLA', 1, 1, 0, 0);
INSERT INTO `categorias` VALUES (4, 'INGREDIENTES', 'EPSON', 1, 0, 0, 1);
INSERT INTO `categorias` VALUES (5, 'sin', '', 1, 0, 0, 0);
INSERT INTO `categorias` VALUES (6, 'PAQUETES', 'COCINA', 1, 1, 1, 0);
INSERT INTO `categorias` VALUES (8, 'PRUEBA', 'PRUEBA', 0, 1, 0, 0);
INSERT INTO `categorias` VALUES (9, 'ESPECIALIDADES', 'PARILLA', 1, 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion`
-- 

CREATE TABLE `configuracion` (
  `establecimiento` varchar(160) collate utf8_spanish_ci NOT NULL default '',
  `representante` varchar(120) collate utf8_spanish_ci NOT NULL default '',
  `rfc` varchar(14) collate utf8_spanish_ci NOT NULL default '',
  `telefono` varchar(10) collate utf8_spanish_ci NOT NULL default '',
  `celular` varchar(10) collate utf8_spanish_ci NOT NULL default '',
  `enviar_sms` int(1) NOT NULL,
  `direccion` varchar(250) collate utf8_spanish_ci NOT NULL default '',
  `bkp_alias` varchar(20) collate utf8_spanish_ci NOT NULL,
  `autobackup` tinyint(1) NOT NULL,
  `autoprint` smallint(1) NOT NULL default '1',
  `abrir_caja` tinyint(1) NOT NULL default '1',
  `impresora_sd` varchar(64) collate utf8_spanish_ci NOT NULL,
  `impresora_cuentas` varchar(64) collate utf8_spanish_ci NOT NULL,
  `impresora_cobros` varchar(64) collate utf8_spanish_ci NOT NULL,
  `impresora_cortes` varchar(64) collate utf8_spanish_ci NOT NULL,
  `alerta_corte` varchar(255) collate utf8_spanish_ci default NULL,
  `email_notificacion` varchar(255) collate utf8_spanish_ci default NULL,
  `header_1` varchar(250) collate utf8_spanish_ci default NULL,
  `header_2` varchar(250) collate utf8_spanish_ci default NULL,
  `header_3` varchar(250) collate utf8_spanish_ci default NULL,
  `header_4` varchar(250) collate utf8_spanish_ci default NULL,
  `header_5` varchar(250) collate utf8_spanish_ci default NULL,
  `header_6` varchar(250) collate utf8_spanish_ci default NULL,
  `header_7` varchar(250) collate utf8_spanish_ci default NULL,
  `header_8` varchar(250) collate utf8_spanish_ci default NULL,
  `header_9` varchar(250) collate utf8_spanish_ci default NULL,
  `header_10` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_1` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_2` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_3` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_4` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_5` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_6` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_7` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_8` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_9` varchar(250) collate utf8_spanish_ci default NULL,
  `footer_10` varchar(250) collate utf8_spanish_ci default NULL,
  `auto_cobro` tinyint(4) default '0',
  `comandain` tinyint(4) NOT NULL default '0',
  `pagada` tinyint(4) NOT NULL default '0',
  `paquetes` tinyint(4) NOT NULL default '0',
  `facturacion` tinyint(1) default '0',
  `insumos` tinyint(4) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `configuracion`
-- 

INSERT INTO `configuracion` VALUES ('KANGRE BURGER GRLL', 'ARGENIS GONGORA', 'GOVA830917', '9831715609', '9831435202', 1, 'AV MAGISTERIA ESQ. SERGIO BUTRON CASAS', 'kgb', 0, 1, 1, 'EPSON', 'EPSON', 'EPSON', 'EPSON', NULL, 'hola@epicmedia.pro', 'KANGRE BURGER', 'AV MAGISTERIAL ESQ. SERGIO BUTRON CASAS', '', '', '', '', '', '', '', '', '', '', '', 'PROPINA NO INCLUIDA', 'GRACIAS POR SU PREFERENCIA!!!', 'ESTE NO ES UN COMPROBANTE FISCAL', '', '', '', '', 0, 1, 0, 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cortes`
-- 

CREATE TABLE `cortes` (
  `id_corte` bigint(20) NOT NULL auto_increment,
  `id_usuario` int(11) NOT NULL,
  `hora` time NOT NULL,
  `fecha` date NOT NULL,
  `codigo` varchar(50) collate utf8_spanish_ci default NULL,
  `efectivoCaja` decimal(12,2) NOT NULL,
  `tpv` decimal(12,2) NOT NULL,
  `otrosMet` decimal(12,2) NOT NULL,
  `fondo_caja` decimal(10,2) NOT NULL,
  `fh_abierto` datetime default NULL,
  `fh_cerrado` datetime default NULL,
  `abierto` tinyint(4) NOT NULL default '1',
  `ajuste` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_corte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

-- 
-- Volcar la base de datos para la tabla `cortes`
-- 

INSERT INTO `cortes` VALUES (1, 1, '16:13:31', '2019-10-02', NULL, 200.00, 0.00, 0.00, 40.00, '2019-10-01 09:47:34', NULL, 0, 0);
INSERT INTO `cortes` VALUES (2, 1, '16:23:01', '2019-10-02', NULL, 100.00, 0.00, 0.00, 300.00, '2019-10-02 15:20:39', NULL, 0, 0);
INSERT INTO `cortes` VALUES (3, 1, '00:00:00', '0000-00-00', NULL, 0.00, 0.00, 0.00, 200.00, '2019-10-02 15:43:07', NULL, 1, 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cupones`
-- 

CREATE TABLE `cupones` (
  `id_cupon` int(2) NOT NULL auto_increment,
  `cupon` varchar(60) NOT NULL,
  `porcentaje` varchar(3) NOT NULL,
  `activo` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_cupon`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- Volcar la base de datos para la tabla `cupones`
-- 

INSERT INTO `cupones` VALUES (1, 'DESCUENTOS', '20', 1);
INSERT INTO `cupones` VALUES (2, 'EPICMEDIA', '25', 1);
INSERT INTO `cupones` VALUES (3, 'SISTÃ‰MAS', '15', 1);
INSERT INTO `cupones` VALUES (4, 'CORTESIA', '100', 1);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `domicilio`
-- 

CREATE TABLE `domicilio` (
  `id_domicilio` int(11) unsigned NOT NULL auto_increment,
  `numero` varchar(10) collate utf8_spanish_ci default NULL,
  `nombre` varchar(64) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id_domicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `domicilio`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `domicilio_direcciones`
-- 

CREATE TABLE `domicilio_direcciones` (
  `id_domicilio_direccion` int(11) unsigned NOT NULL auto_increment,
  `id_domicilio` int(11) default NULL,
  `direccion` text,
  PRIMARY KEY  (`id_domicilio_direccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `domicilio_direcciones`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `dotaciones`
-- 

CREATE TABLE `dotaciones` (
  `id_dotacion` bigint(20) NOT NULL auto_increment,
  `id_usuario` int(2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time default NULL,
  `activo` int(1) NOT NULL,
  `comentario` text collate utf8_spanish_ci,
  PRIMARY KEY  (`id_dotacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `dotaciones`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `dotaciones_detalle`
-- 

CREATE TABLE `dotaciones_detalle` (
  `id_detalle` bigint(20) NOT NULL auto_increment,
  `id_dotacion` bigint(20) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` varchar(300) collate utf8_spanish_ci NOT NULL,
  `activo` int(1) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `dotaciones_detalle`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `existencias`
-- 

CREATE TABLE `existencias` (
  `id_producto` bigint(20) NOT NULL,
  `existencia` varchar(300) collate utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `existencias`
-- 

INSERT INTO `existencias` VALUES (16, '0');
INSERT INTO `existencias` VALUES (16, '0');
INSERT INTO `existencias` VALUES (17, '-3');
INSERT INTO `existencias` VALUES (18, '-2');
INSERT INTO `existencias` VALUES (19, '0');
INSERT INTO `existencias` VALUES (20, '0');
INSERT INTO `existencias` VALUES (21, '0');
INSERT INTO `existencias` VALUES (22, '0');
INSERT INTO `existencias` VALUES (23, '0');
INSERT INTO `existencias` VALUES (24, '0');
INSERT INTO `existencias` VALUES (26, '0');
INSERT INTO `existencias` VALUES (35, '0');
INSERT INTO `existencias` VALUES (36, '0');
INSERT INTO `existencias` VALUES (41, '0');
INSERT INTO `existencias` VALUES (43, '0');
INSERT INTO `existencias` VALUES (46, '0');
INSERT INTO `existencias` VALUES (47, '0');
INSERT INTO `existencias` VALUES (48, '0');
INSERT INTO `existencias` VALUES (49, '0');
INSERT INTO `existencias` VALUES (50, '0');
INSERT INTO `existencias` VALUES (51, '0');
INSERT INTO `existencias` VALUES (52, '0');
INSERT INTO `existencias` VALUES (53, '0');
INSERT INTO `existencias` VALUES (54, '0');
INSERT INTO `existencias` VALUES (55, '0');
INSERT INTO `existencias` VALUES (56, '0');
INSERT INTO `existencias` VALUES (57, '0');
INSERT INTO `existencias` VALUES (58, '0');
INSERT INTO `existencias` VALUES (59, '0');
INSERT INTO `existencias` VALUES (60, '0');
INSERT INTO `existencias` VALUES (61, '0');
INSERT INTO `existencias` VALUES (62, '0');
INSERT INTO `existencias` VALUES (63, '0');
INSERT INTO `existencias` VALUES (64, '0');
INSERT INTO `existencias` VALUES (65, '0');
INSERT INTO `existencias` VALUES (66, '0');
INSERT INTO `existencias` VALUES (67, '0');
INSERT INTO `existencias` VALUES (68, '0');
INSERT INTO `existencias` VALUES (69, '0');
INSERT INTO `existencias` VALUES (70, '0');
INSERT INTO `existencias` VALUES (71, '0');
INSERT INTO `existencias` VALUES (72, '0');
INSERT INTO `existencias` VALUES (73, '0');
INSERT INTO `existencias` VALUES (74, '0');
INSERT INTO `existencias` VALUES (75, '0');
INSERT INTO `existencias` VALUES (76, '0');
INSERT INTO `existencias` VALUES (77, '0');
INSERT INTO `existencias` VALUES (78, '0');
INSERT INTO `existencias` VALUES (79, '-31');
INSERT INTO `existencias` VALUES (80, '0');
INSERT INTO `existencias` VALUES (81, '0');
INSERT INTO `existencias` VALUES (82, '0');
INSERT INTO `existencias` VALUES (83, '0');
INSERT INTO `existencias` VALUES (84, '0');
INSERT INTO `existencias` VALUES (85, '0');
INSERT INTO `existencias` VALUES (86, '0');
INSERT INTO `existencias` VALUES (87, '-1');
INSERT INTO `existencias` VALUES (88, '0');
INSERT INTO `existencias` VALUES (89, '0');
INSERT INTO `existencias` VALUES (90, '0');
INSERT INTO `existencias` VALUES (91, '0');
INSERT INTO `existencias` VALUES (92, '0');
INSERT INTO `existencias` VALUES (93, '0');
INSERT INTO `existencias` VALUES (94, '0');
INSERT INTO `existencias` VALUES (95, '0');
INSERT INTO `existencias` VALUES (96, '0');
INSERT INTO `existencias` VALUES (97, '0');
INSERT INTO `existencias` VALUES (98, '0');
INSERT INTO `existencias` VALUES (99, '0');
INSERT INTO `existencias` VALUES (100, '0');
INSERT INTO `existencias` VALUES (101, '0');
INSERT INTO `existencias` VALUES (103, '-55');
INSERT INTO `existencias` VALUES (104, '-32');
INSERT INTO `existencias` VALUES (105, '-31');
INSERT INTO `existencias` VALUES (106, '0');
INSERT INTO `existencias` VALUES (107, '-6');
INSERT INTO `existencias` VALUES (108, '-1');
INSERT INTO `existencias` VALUES (109, '-23');
INSERT INTO `existencias` VALUES (110, '0');
INSERT INTO `existencias` VALUES (111, '-2');
INSERT INTO `existencias` VALUES (112, '-5');
INSERT INTO `existencias` VALUES (113, '-30');
INSERT INTO `existencias` VALUES (114, '-23');
INSERT INTO `existencias` VALUES (115, '-34');
INSERT INTO `existencias` VALUES (116, '0');
INSERT INTO `existencias` VALUES (117, '-23');
INSERT INTO `existencias` VALUES (118, '0');
INSERT INTO `existencias` VALUES (119, '0');
INSERT INTO `existencias` VALUES (120, '0');
INSERT INTO `existencias` VALUES (121, '0');
INSERT INTO `existencias` VALUES (122, '0');
INSERT INTO `existencias` VALUES (123, '-1');
INSERT INTO `existencias` VALUES (124, '-23');
INSERT INTO `existencias` VALUES (125, '0');
INSERT INTO `existencias` VALUES (126, '-5');
INSERT INTO `existencias` VALUES (127, '0');
INSERT INTO `existencias` VALUES (128, '0');
INSERT INTO `existencias` VALUES (129, '0');
INSERT INTO `existencias` VALUES (130, '0');
INSERT INTO `existencias` VALUES (131, '0');
INSERT INTO `existencias` VALUES (132, '-1');
INSERT INTO `existencias` VALUES (133, '-23');
INSERT INTO `existencias` VALUES (134, '-23');
INSERT INTO `existencias` VALUES (135, '-9');
INSERT INTO `existencias` VALUES (136, '0');
INSERT INTO `existencias` VALUES (137, '-60');
INSERT INTO `existencias` VALUES (138, '0');
INSERT INTO `existencias` VALUES (139, '0');
INSERT INTO `existencias` VALUES (140, '0');
INSERT INTO `existencias` VALUES (141, '0');
INSERT INTO `existencias` VALUES (142, '0');
INSERT INTO `existencias` VALUES (143, '0');
INSERT INTO `existencias` VALUES (144, '0');
INSERT INTO `existencias` VALUES (145, '0');
INSERT INTO `existencias` VALUES (146, '0');
INSERT INTO `existencias` VALUES (147, '0');
INSERT INTO `existencias` VALUES (148, '0');
INSERT INTO `existencias` VALUES (149, '0');
INSERT INTO `existencias` VALUES (150, '0');
INSERT INTO `existencias` VALUES (151, '-7');
INSERT INTO `existencias` VALUES (152, '0');
INSERT INTO `existencias` VALUES (153, '0');
INSERT INTO `existencias` VALUES (154, '0');
INSERT INTO `existencias` VALUES (155, '0');
INSERT INTO `existencias` VALUES (156, '0');
INSERT INTO `existencias` VALUES (157, '0');
INSERT INTO `existencias` VALUES (158, '0');
INSERT INTO `existencias` VALUES (159, '0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `gastos`
-- 

CREATE TABLE `gastos` (
  `id_gasto` bigint(20) NOT NULL auto_increment,
  `id_corte` bigint(20) NOT NULL default '0',
  `id_usuario` int(11) NOT NULL,
  `descripcion` varchar(255) collate utf8_spanish_ci NOT NULL,
  `monto` decimal(8,2) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `provision` varchar(10) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id_gasto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `gastos`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `impresion_domicilio`
-- 

CREATE TABLE `impresion_domicilio` (
  `id_impresion_domicilio` int(11) NOT NULL auto_increment,
  `numero` varchar(255) default NULL,
  `nombre` varchar(255) default NULL,
  `direccion` varchar(255) default NULL,
  `fecha_hora` datetime default NULL,
  PRIMARY KEY  (`id_impresion_domicilio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `impresion_domicilio`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `merma`
-- 

CREATE TABLE `merma` (
  `id_merma` int(11) NOT NULL auto_increment,
  `id_usuario` mediumint(9) NOT NULL,
  `fecha` date NOT NULL,
  `activo` smallint(1) NOT NULL,
  PRIMARY KEY  (`id_merma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `merma`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `merma_detalle`
-- 

CREATE TABLE `merma_detalle` (
  `id_detalle` int(11) NOT NULL auto_increment,
  `id_merma` int(11) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `activo` smallint(1) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `merma_detalle`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `metodo_pago`
-- 

CREATE TABLE `metodo_pago` (
  `id_metodo` int(11) unsigned NOT NULL auto_increment,
  `metodo_pago` varchar(32) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id_metodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=29 ;

-- 
-- Volcar la base de datos para la tabla `metodo_pago`
-- 

INSERT INTO `metodo_pago` VALUES (1, 'EFECTIVO');
INSERT INTO `metodo_pago` VALUES (4, 'TARJETA CREDITO');
INSERT INTO `metodo_pago` VALUES (28, 'TARJETA DEBITO');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `msn`
-- 

CREATE TABLE `msn` (
  `id_msn` int(11) NOT NULL auto_increment,
  `id_usuario` varchar(45) default NULL,
  `id_tipo_usuario` varchar(45) default NULL,
  `numero` varchar(45) default NULL,
  `activo` varchar(45) default NULL,
  PRIMARY KEY  (`id_msn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `msn`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `productos`
-- 

CREATE TABLE `productos` (
  `id_producto` bigint(20) NOT NULL auto_increment,
  `id_categoria` mediumint(3) NOT NULL,
  `codigo` varchar(120) collate utf8_spanish_ci NOT NULL default '',
  `nombre` varchar(120) collate utf8_spanish_ci NOT NULL default '',
  `precio_venta` decimal(8,2) default '0.00',
  `activo` tinyint(1) NOT NULL default '1',
  `extra` int(11) default '0',
  `tiene` int(11) default '0',
  `sinn` int(11) default '0',
  `imprimir_solo` int(11) default '0',
  `impresora` int(11) default '0',
  `paquete` tinyint(4) NOT NULL default '0',
  `id_unidad` varchar(1) collate utf8_spanish_ci NOT NULL default '0',
  PRIMARY KEY  (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=160 ;

-- 
-- Volcar la base de datos para la tabla `productos`
-- 

INSERT INTO `productos` VALUES (16, 2, '355', 'COCA', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (17, 2, '17', 'PITAYA', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (18, 2, '18', 'JAMAICA', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (19, 2, '19', 'LIMONADA CHIA', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (20, 2, '20', 'TAMARINDO', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (21, 2, '21', 'HORCHATA', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (22, 2, '22', 'CEBADA', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (23, 2, '23', 'NARANJA AGRIA', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (24, 1, 'D2', 'DEDOS DE QUESO', 45.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (26, 1, 'BBQ', 'ALITAS', 119.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (35, 1, 'P1', 'PAK NUGGETS', 140.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (36, 1, 'P2', 'PAK ALITAS', 155.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (41, 1, 'PK2', 'PAK ALITAS BBQ 5PZA', 155.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (43, 1, 'PROMO', 'MIERCOLES DE ALITAS', 10.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (46, 1, 'HD', 'HOT DOG SENCILLO', 15.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (47, 1, 'HOTDOGES', 'HOT DOG ESPECIAL', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (48, 2, 'REF', 'CRSITAL SABOR', 18.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (49, 3, '1234', 'ESPAÃ‘OLA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (50, 3, 'ESPA', 'ESPAÃ‘OLA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (51, 3, '0003', 'RANCHERA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (52, 3, '0004', 'RANCHERA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (53, 3, '0005', 'MOZZARELA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (54, 3, '0006', 'MOZZARELA DOBLE', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (55, 3, '0007', 'SUPREMA', 60.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (56, 3, '0008', 'SUPREMA DOBLE', 90.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (57, 3, '0009', 'KGB', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (58, 3, '0010', 'KGB DOBLE', 100.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (59, 3, '0011', 'BBQ HOT', 55.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (60, 3, '0012', 'BBQ HOT DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (61, 3, '0013', 'CUBANA', 50.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (62, 3, '0014', 'CUBANA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (63, 3, '0015', 'FEROZ', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (64, 3, '0016', 'FEROZ DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (65, 3, '0017', 'MEXICANA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (66, 3, '0018', 'MEXICANA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (67, 3, '0019', 'TROPICANA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (68, 3, '0020', 'TROPICANA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (69, 3, '0021', 'CAVERNICOLA', 60.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (70, 3, '0022', 'CAVERNICOLA DOBLE', 90.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (71, 3, '0023', 'PARRILLERA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (72, 3, '0024', 'PARRILLERA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (73, 3, '0025', 'CHEESE', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (74, 3, '0026', 'CHEESE DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (75, 3, '0027', 'CHIPI HOT', 55.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (76, 3, '0028', 'CHIPI HOT DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (77, 3, '0029', 'INCOGNITA', 55.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (78, 3, '0030', 'INCOGNITA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (79, 3, '0031', 'ITALIANA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (80, 3, '0032', 'ITALIANA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (81, 3, '0033', 'MR. KRAB', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (82, 3, '0034', 'MR. KRAB DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (83, 3, '0035', 'GABACHA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (84, 3, '0036', 'GABACHA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (85, 3, '0037', 'HAWAIANA', 45.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (86, 3, '0038', 'HAWAIANA DOBLE', 65.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (87, 3, '0039', 'SENCILLA', 38.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (88, 3, '0040', 'SENCILLA DOBLE', 60.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (89, 1, '0041', 'ORDEN DE NUGGETS', 45.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (90, 1, '0042', 'ORDEN DE PAPAS GAJO', 40.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (91, 1, '0043', 'ORDEN DE AROS DE CEBOLLA', 40.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (92, 1, '0044', 'ORDEN DE TENDERS', 80.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (93, 1, '0045', 'ORDEN DE ALITAS DE POLLO', 119.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (94, 1, '0046', 'ORDEN CALIFORNIANA', 75.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (95, 1, '0047', '1/2 ORDEN CALIFORNIANA', 40.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (96, 1, '0048', 'NACHOS DE CARNES FRÃAS', 90.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (97, 1, '0049', '1/2 NACHOS DE CARNES FRIAS', 55.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (98, 1, '0050', 'NACHOS MEXICANA', 85.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (99, 1, '0051', '1/2 NACHOS MEXICANA', 50.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (100, 1, '0052', 'ORDEN NACHOS PASTOR', 110.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (101, 1, '0053', '1/2 NACHOS PASTOR', 60.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (103, 4, 'CARNE2', 'CARNES', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (104, 4, 'queso', 'QUESO', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (105, 4, 'salami', 'SALAMI', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (106, 4, 'peperoni', 'PEPERONI', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (107, 4, 'CHORIZO', 'CHORIZO', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (108, 4, 'JALAPENO', 'JALAPENO', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (109, 4, 'mozarela', 'MOZARELA', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (110, 4, 'FILA', 'FILADELFIA', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (111, 4, 'S/AZAR', 'SALCHICHA/ASAR', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (112, 4, 'tocino', 'TOCINO', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (113, 4, 'aguacate', 'AGUACATE', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (114, 4, 'PINA', 'PINA', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (115, 4, 'CHAMPI', 'CHAMPINON', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (116, 4, 'SALSA BBQ', 'SALSA BBQ', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (117, 4, 'legumbres', 'LEGUMBRES', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (118, 4, 'gouda', 'GOUDA', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (119, 4, 'chihuahua', 'CHIHUAHUA', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (120, 4, 'manchego', 'MANCHEGO', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (121, 4, 's/chipi', 'SALSA CHIPI', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (122, 4, 's/tamarindo', 'SALSA TAMARINDO', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (123, 4, 'SALCHI', 'SALCHICHA', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (124, 4, 'PASTOR', 'PASTOR', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (125, 4, 'POLLO/C', 'POLLO CRUJIENTE', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (126, 4, 'PEPI', 'PEPINILLOS', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (127, 4, 'ARACHERA', 'ARACHERA', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (128, 4, 'QUESOB', 'QUESO DE BOLA', 10.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (129, 9, 'KRAKEN', 'KRAKEN', 125.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (130, 9, 'TENTACION', 'TENTACION', 75.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (131, 9, 'TENTACIONDOBLE', 'TENTACION DOBLE', 140.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (132, 9, 'ARRARING', 'ARRACHERA ONION RINGS', 95.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (133, 9, 'PAS', 'PASTOR', 59.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (134, 9, 'PASTOR2', 'PASTOR DOBLE', 89.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (135, 9, 'ARRAC', 'ARRACHERA', 99.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (136, 9, 'ARRACHERA MUSHROOM', 'ARRACHERA MUSHROOM', 99.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (137, 4, 'PAN', 'PAN', 0.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (138, 4, 'rhot', 'RED HOT', 0.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (139, 4, 'BBQH', 'SALSA BBQ HOT', 0.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (140, 4, 'BCHE', 'BLUE CHESSE', 0.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (141, 4, 'TAM', 'TAMARINDO CHIPOTLE', 0.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (142, 4, 'VALEFUE', 'VALENTINA FUEGO', 0.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (143, 4, 'VALEBBQ', 'VALENTINA BBQ', 0.00, 1, 1, 0, 0, 0, 0, 0, '3');
INSERT INTO `productos` VALUES (144, 5, 'SINCEBOLLA', 'SIN CEBOLLA', 0.00, 1, 0, 0, 1, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (145, 5, 'CATS', 'SIN CATSUP', 0.00, 1, 0, 0, 1, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (146, 5, 'SINMOS', 'SIN MOSTAZA', 0.00, 1, 0, 0, 1, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (147, 5, 'LECH', 'SIN LECHUGA', 0.00, 1, 0, 0, 1, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (148, 5, 'PICA', 'SIN JALAPENO', 0.00, 1, 0, 0, 1, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (149, 5, 'SINQUE', 'SIN QUESO', 0.00, 1, 0, 0, 1, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (151, 6, 'PAK1', 'SNAK PAK 1', 155.00, 1, 0, 0, 0, 0, 0, 1, '0');
INSERT INTO `productos` VALUES (152, 1, 'ORPAPAS2', 'ORDEN DE PAPAS', 35.00, 1, 0, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (153, 6, 'PAK2', 'SNAK PAK 2', 140.00, 1, 0, 0, 0, 0, 0, 1, '0');
INSERT INTO `productos` VALUES (154, 9, '4D', '4D BURGER', 120.00, 1, 0, 1, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (157, 4, 'PRUEBA65', 'ENE', 0.00, 1, 1, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (158, 4, 'ENNE', 'PRUEBA43ENNE', 0.00, 1, 1, 0, 0, 0, 0, 0, '0');
INSERT INTO `productos` VALUES (159, 4, 'SALC', 'SALCHICA PRO AZAR', 12.00, 1, 1, 0, 0, 0, 0, 0, '0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `productosxbase`
-- 

CREATE TABLE `productosxbase` (
  `id_detalle` int(11) NOT NULL auto_increment,
  `id_base` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=308 ;

-- 
-- Volcar la base de datos para la tabla `productosxbase`
-- 

INSERT INTO `productosxbase` VALUES (1, 2, 49, 1);
INSERT INTO `productosxbase` VALUES (2, 1, 49, 1);
INSERT INTO `productosxbase` VALUES (3, 5, 49, 1);
INSERT INTO `productosxbase` VALUES (4, 4, 49, 1);
INSERT INTO `productosxbase` VALUES (5, 3, 49, 1);
INSERT INTO `productosxbase` VALUES (6, 1, 50, 1);
INSERT INTO `productosxbase` VALUES (7, 5, 50, 1);
INSERT INTO `productosxbase` VALUES (8, 3, 50, 1);
INSERT INTO `productosxbase` VALUES (9, 4, 50, 1);
INSERT INTO `productosxbase` VALUES (10, 2, 50, 1);
INSERT INTO `productosxbase` VALUES (11, 1, 51, 1);
INSERT INTO `productosxbase` VALUES (12, 2, 51, 1);
INSERT INTO `productosxbase` VALUES (13, 3, 51, 1);
INSERT INTO `productosxbase` VALUES (14, 6, 51, 1);
INSERT INTO `productosxbase` VALUES (15, 7, 51, 1);
INSERT INTO `productosxbase` VALUES (16, 1, 52, 1);
INSERT INTO `productosxbase` VALUES (17, 3, 52, 1);
INSERT INTO `productosxbase` VALUES (18, 6, 52, 1);
INSERT INTO `productosxbase` VALUES (19, 7, 52, 1);
INSERT INTO `productosxbase` VALUES (20, 2, 52, 1);
INSERT INTO `productosxbase` VALUES (21, 1, 53, 1);
INSERT INTO `productosxbase` VALUES (22, 8, 53, 1);
INSERT INTO `productosxbase` VALUES (23, 5, 53, 1);
INSERT INTO `productosxbase` VALUES (24, 2, 53, 1);
INSERT INTO `productosxbase` VALUES (25, 3, 53, 1);
INSERT INTO `productosxbase` VALUES (26, 1, 54, 1);
INSERT INTO `productosxbase` VALUES (27, 2, 54, 1);
INSERT INTO `productosxbase` VALUES (28, 3, 54, 1);
INSERT INTO `productosxbase` VALUES (29, 8, 54, 1);
INSERT INTO `productosxbase` VALUES (30, 5, 54, 1);
INSERT INTO `productosxbase` VALUES (31, 2, 55, 1);
INSERT INTO `productosxbase` VALUES (32, 1, 55, 1);
INSERT INTO `productosxbase` VALUES (33, 4, 55, 1);
INSERT INTO `productosxbase` VALUES (34, 9, 55, 1);
INSERT INTO `productosxbase` VALUES (35, 5, 55, 1);
INSERT INTO `productosxbase` VALUES (36, 8, 55, 1);
INSERT INTO `productosxbase` VALUES (37, 10, 55, 1);
INSERT INTO `productosxbase` VALUES (38, 1, 56, 1);
INSERT INTO `productosxbase` VALUES (39, 9, 56, 1);
INSERT INTO `productosxbase` VALUES (40, 2, 56, 1);
INSERT INTO `productosxbase` VALUES (41, 4, 56, 1);
INSERT INTO `productosxbase` VALUES (42, 5, 56, 1);
INSERT INTO `productosxbase` VALUES (43, 8, 56, 1);
INSERT INTO `productosxbase` VALUES (44, 10, 56, 1);
INSERT INTO `productosxbase` VALUES (45, 1, 57, 1);
INSERT INTO `productosxbase` VALUES (46, 2, 57, 1);
INSERT INTO `productosxbase` VALUES (47, 3, 57, 1);
INSERT INTO `productosxbase` VALUES (48, 11, 57, 1);
INSERT INTO `productosxbase` VALUES (49, 19, 57, 1);
INSERT INTO `productosxbase` VALUES (50, 4, 57, 1);
INSERT INTO `productosxbase` VALUES (51, 6, 57, 1);
INSERT INTO `productosxbase` VALUES (52, 13, 57, 1);
INSERT INTO `productosxbase` VALUES (53, 12, 57, 1);
INSERT INTO `productosxbase` VALUES (54, 5, 57, 1);
INSERT INTO `productosxbase` VALUES (55, 14, 57, 1);
INSERT INTO `productosxbase` VALUES (56, 10, 57, 1);
INSERT INTO `productosxbase` VALUES (57, 4, 58, 1);
INSERT INTO `productosxbase` VALUES (58, 1, 58, 1);
INSERT INTO `productosxbase` VALUES (59, 3, 58, 1);
INSERT INTO `productosxbase` VALUES (60, 11, 58, 1);
INSERT INTO `productosxbase` VALUES (61, 19, 58, 1);
INSERT INTO `productosxbase` VALUES (62, 2, 58, 1);
INSERT INTO `productosxbase` VALUES (63, 6, 58, 1);
INSERT INTO `productosxbase` VALUES (64, 14, 58, 1);
INSERT INTO `productosxbase` VALUES (65, 12, 58, 1);
INSERT INTO `productosxbase` VALUES (66, 13, 58, 1);
INSERT INTO `productosxbase` VALUES (67, 5, 58, 1);
INSERT INTO `productosxbase` VALUES (68, 10, 58, 1);
INSERT INTO `productosxbase` VALUES (69, 1, 59, 1);
INSERT INTO `productosxbase` VALUES (70, 10, 59, 1);
INSERT INTO `productosxbase` VALUES (71, 2, 59, 1);
INSERT INTO `productosxbase` VALUES (72, 7, 59, 1);
INSERT INTO `productosxbase` VALUES (73, 3, 59, 1);
INSERT INTO `productosxbase` VALUES (74, 11, 59, 1);
INSERT INTO `productosxbase` VALUES (75, 15, 59, 1);
INSERT INTO `productosxbase` VALUES (76, 1, 60, 1);
INSERT INTO `productosxbase` VALUES (77, 2, 60, 1);
INSERT INTO `productosxbase` VALUES (78, 10, 60, 1);
INSERT INTO `productosxbase` VALUES (79, 11, 60, 1);
INSERT INTO `productosxbase` VALUES (80, 7, 60, 1);
INSERT INTO `productosxbase` VALUES (81, 3, 60, 1);
INSERT INTO `productosxbase` VALUES (82, 15, 60, 1);
INSERT INTO `productosxbase` VALUES (83, 3, 61, 1);
INSERT INTO `productosxbase` VALUES (84, 13, 61, 1);
INSERT INTO `productosxbase` VALUES (85, 6, 61, 1);
INSERT INTO `productosxbase` VALUES (86, 1, 61, 1);
INSERT INTO `productosxbase` VALUES (87, 2, 61, 1);
INSERT INTO `productosxbase` VALUES (88, 10, 61, 1);
INSERT INTO `productosxbase` VALUES (89, 1, 62, 1);
INSERT INTO `productosxbase` VALUES (90, 2, 62, 1);
INSERT INTO `productosxbase` VALUES (91, 3, 62, 1);
INSERT INTO `productosxbase` VALUES (92, 6, 62, 1);
INSERT INTO `productosxbase` VALUES (93, 13, 62, 1);
INSERT INTO `productosxbase` VALUES (94, 10, 62, 1);
INSERT INTO `productosxbase` VALUES (95, 6, 63, 1);
INSERT INTO `productosxbase` VALUES (96, 1, 63, 1);
INSERT INTO `productosxbase` VALUES (97, 2, 63, 1);
INSERT INTO `productosxbase` VALUES (98, 4, 63, 1);
INSERT INTO `productosxbase` VALUES (99, 11, 63, 1);
INSERT INTO `productosxbase` VALUES (100, 3, 63, 1);
INSERT INTO `productosxbase` VALUES (101, 7, 63, 1);
INSERT INTO `productosxbase` VALUES (102, 2, 64, 1);
INSERT INTO `productosxbase` VALUES (103, 1, 64, 1);
INSERT INTO `productosxbase` VALUES (104, 11, 64, 1);
INSERT INTO `productosxbase` VALUES (105, 3, 64, 1);
INSERT INTO `productosxbase` VALUES (106, 6, 64, 1);
INSERT INTO `productosxbase` VALUES (107, 4, 64, 1);
INSERT INTO `productosxbase` VALUES (108, 7, 64, 1);
INSERT INTO `productosxbase` VALUES (109, 2, 65, 1);
INSERT INTO `productosxbase` VALUES (110, 1, 65, 1);
INSERT INTO `productosxbase` VALUES (111, 6, 65, 1);
INSERT INTO `productosxbase` VALUES (112, 11, 65, 1);
INSERT INTO `productosxbase` VALUES (113, 3, 65, 1);
INSERT INTO `productosxbase` VALUES (114, 12, 65, 1);
INSERT INTO `productosxbase` VALUES (115, 2, 66, 1);
INSERT INTO `productosxbase` VALUES (116, 11, 66, 1);
INSERT INTO `productosxbase` VALUES (117, 12, 66, 1);
INSERT INTO `productosxbase` VALUES (118, 3, 66, 1);
INSERT INTO `productosxbase` VALUES (119, 6, 66, 1);
INSERT INTO `productosxbase` VALUES (120, 1, 66, 1);
INSERT INTO `productosxbase` VALUES (121, 13, 67, 1);
INSERT INTO `productosxbase` VALUES (122, 2, 67, 1);
INSERT INTO `productosxbase` VALUES (123, 1, 67, 1);
INSERT INTO `productosxbase` VALUES (124, 4, 67, 1);
INSERT INTO `productosxbase` VALUES (125, 14, 67, 1);
INSERT INTO `productosxbase` VALUES (126, 1, 68, 1);
INSERT INTO `productosxbase` VALUES (127, 4, 68, 1);
INSERT INTO `productosxbase` VALUES (128, 3, 68, 1);
INSERT INTO `productosxbase` VALUES (129, 2, 68, 1);
INSERT INTO `productosxbase` VALUES (130, 13, 68, 1);
INSERT INTO `productosxbase` VALUES (131, 14, 68, 1);
INSERT INTO `productosxbase` VALUES (132, 1, 69, 1);
INSERT INTO `productosxbase` VALUES (133, 19, 69, 1);
INSERT INTO `productosxbase` VALUES (134, 2, 69, 1);
INSERT INTO `productosxbase` VALUES (135, 10, 69, 1);
INSERT INTO `productosxbase` VALUES (136, 11, 69, 1);
INSERT INTO `productosxbase` VALUES (137, 3, 69, 1);
INSERT INTO `productosxbase` VALUES (138, 6, 69, 1);
INSERT INTO `productosxbase` VALUES (139, 4, 69, 1);
INSERT INTO `productosxbase` VALUES (140, 5, 69, 1);
INSERT INTO `productosxbase` VALUES (141, 2, 70, 1);
INSERT INTO `productosxbase` VALUES (142, 3, 70, 1);
INSERT INTO `productosxbase` VALUES (143, 11, 70, 1);
INSERT INTO `productosxbase` VALUES (144, 1, 70, 1);
INSERT INTO `productosxbase` VALUES (145, 4, 70, 1);
INSERT INTO `productosxbase` VALUES (146, 10, 70, 1);
INSERT INTO `productosxbase` VALUES (147, 6, 70, 1);
INSERT INTO `productosxbase` VALUES (148, 5, 70, 1);
INSERT INTO `productosxbase` VALUES (149, 1, 71, 1);
INSERT INTO `productosxbase` VALUES (150, 7, 71, 1);
INSERT INTO `productosxbase` VALUES (151, 2, 71, 1);
INSERT INTO `productosxbase` VALUES (152, 3, 71, 1);
INSERT INTO `productosxbase` VALUES (153, 10, 71, 1);
INSERT INTO `productosxbase` VALUES (167, 17, 73, 1);
INSERT INTO `productosxbase` VALUES (166, 1, 73, 1);
INSERT INTO `productosxbase` VALUES (165, 7, 72, 1);
INSERT INTO `productosxbase` VALUES (164, 2, 72, 1);
INSERT INTO `productosxbase` VALUES (163, 10, 72, 1);
INSERT INTO `productosxbase` VALUES (162, 3, 72, 1);
INSERT INTO `productosxbase` VALUES (161, 1, 72, 1);
INSERT INTO `productosxbase` VALUES (168, 16, 73, 1);
INSERT INTO `productosxbase` VALUES (169, 2, 73, 1);
INSERT INTO `productosxbase` VALUES (170, 8, 73, 1);
INSERT INTO `productosxbase` VALUES (171, 14, 73, 1);
INSERT INTO `productosxbase` VALUES (172, 18, 73, 1);
INSERT INTO `productosxbase` VALUES (173, 1, 74, 1);
INSERT INTO `productosxbase` VALUES (174, 8, 74, 1);
INSERT INTO `productosxbase` VALUES (175, 2, 74, 1);
INSERT INTO `productosxbase` VALUES (176, 17, 74, 1);
INSERT INTO `productosxbase` VALUES (177, 16, 74, 1);
INSERT INTO `productosxbase` VALUES (178, 18, 74, 1);
INSERT INTO `productosxbase` VALUES (179, 14, 74, 1);
INSERT INTO `productosxbase` VALUES (180, 3, 75, 1);
INSERT INTO `productosxbase` VALUES (181, 2, 75, 1);
INSERT INTO `productosxbase` VALUES (182, 1, 75, 1);
INSERT INTO `productosxbase` VALUES (183, 11, 75, 1);
INSERT INTO `productosxbase` VALUES (184, 19, 75, 1);
INSERT INTO `productosxbase` VALUES (185, 5, 75, 1);
INSERT INTO `productosxbase` VALUES (186, 19, 76, 1);
INSERT INTO `productosxbase` VALUES (187, 5, 76, 1);
INSERT INTO `productosxbase` VALUES (188, 1, 76, 1);
INSERT INTO `productosxbase` VALUES (189, 11, 76, 1);
INSERT INTO `productosxbase` VALUES (190, 3, 76, 1);
INSERT INTO `productosxbase` VALUES (191, 2, 76, 1);
INSERT INTO `productosxbase` VALUES (192, 1, 77, 1);
INSERT INTO `productosxbase` VALUES (193, 13, 77, 1);
INSERT INTO `productosxbase` VALUES (194, 2, 77, 1);
INSERT INTO `productosxbase` VALUES (195, 10, 77, 1);
INSERT INTO `productosxbase` VALUES (196, 11, 77, 1);
INSERT INTO `productosxbase` VALUES (197, 3, 77, 1);
INSERT INTO `productosxbase` VALUES (198, 1, 78, 1);
INSERT INTO `productosxbase` VALUES (199, 3, 78, 1);
INSERT INTO `productosxbase` VALUES (200, 10, 78, 1);
INSERT INTO `productosxbase` VALUES (201, 2, 78, 1);
INSERT INTO `productosxbase` VALUES (202, 11, 78, 1);
INSERT INTO `productosxbase` VALUES (203, 13, 78, 1);
INSERT INTO `productosxbase` VALUES (204, 1, 79, 1);
INSERT INTO `productosxbase` VALUES (205, 4, 79, 1);
INSERT INTO `productosxbase` VALUES (206, 14, 79, 1);
INSERT INTO `productosxbase` VALUES (207, 3, 79, 1);
INSERT INTO `productosxbase` VALUES (208, 2, 79, 1);
INSERT INTO `productosxbase` VALUES (209, 3, 80, 1);
INSERT INTO `productosxbase` VALUES (210, 2, 80, 1);
INSERT INTO `productosxbase` VALUES (211, 1, 80, 1);
INSERT INTO `productosxbase` VALUES (212, 14, 80, 1);
INSERT INTO `productosxbase` VALUES (213, 4, 80, 1);
INSERT INTO `productosxbase` VALUES (214, 2, 81, 1);
INSERT INTO `productosxbase` VALUES (215, 19, 81, 1);
INSERT INTO `productosxbase` VALUES (216, 4, 81, 1);
INSERT INTO `productosxbase` VALUES (217, 1, 81, 1);
INSERT INTO `productosxbase` VALUES (218, 3, 81, 1);
INSERT INTO `productosxbase` VALUES (219, 11, 81, 1);
INSERT INTO `productosxbase` VALUES (220, 1, 82, 1);
INSERT INTO `productosxbase` VALUES (221, 3, 82, 1);
INSERT INTO `productosxbase` VALUES (222, 11, 82, 1);
INSERT INTO `productosxbase` VALUES (223, 19, 82, 1);
INSERT INTO `productosxbase` VALUES (224, 2, 82, 1);
INSERT INTO `productosxbase` VALUES (225, 4, 82, 1);
INSERT INTO `productosxbase` VALUES (226, 3, 83, 1);
INSERT INTO `productosxbase` VALUES (227, 2, 83, 1);
INSERT INTO `productosxbase` VALUES (228, 19, 83, 1);
INSERT INTO `productosxbase` VALUES (229, 11, 83, 1);
INSERT INTO `productosxbase` VALUES (230, 1, 83, 1);
INSERT INTO `productosxbase` VALUES (231, 19, 84, 1);
INSERT INTO `productosxbase` VALUES (232, 2, 84, 1);
INSERT INTO `productosxbase` VALUES (233, 3, 84, 1);
INSERT INTO `productosxbase` VALUES (234, 11, 84, 1);
INSERT INTO `productosxbase` VALUES (235, 1, 84, 1);
INSERT INTO `productosxbase` VALUES (236, 2, 85, 1);
INSERT INTO `productosxbase` VALUES (237, 1, 85, 1);
INSERT INTO `productosxbase` VALUES (238, 3, 85, 1);
INSERT INTO `productosxbase` VALUES (239, 13, 85, 1);
INSERT INTO `productosxbase` VALUES (240, 19, 85, 1);
INSERT INTO `productosxbase` VALUES (241, 1, 86, 1);
INSERT INTO `productosxbase` VALUES (242, 2, 86, 1);
INSERT INTO `productosxbase` VALUES (243, 3, 86, 1);
INSERT INTO `productosxbase` VALUES (244, 13, 86, 1);
INSERT INTO `productosxbase` VALUES (245, 19, 86, 1);
INSERT INTO `productosxbase` VALUES (246, 1, 87, 1);
INSERT INTO `productosxbase` VALUES (247, 3, 87, 1);
INSERT INTO `productosxbase` VALUES (248, 19, 87, 1);
INSERT INTO `productosxbase` VALUES (249, 2, 87, 1);
INSERT INTO `productosxbase` VALUES (250, 1, 88, 1);
INSERT INTO `productosxbase` VALUES (251, 3, 88, 1);
INSERT INTO `productosxbase` VALUES (252, 19, 88, 1);
INSERT INTO `productosxbase` VALUES (253, 2, 88, 1);
INSERT INTO `productosxbase` VALUES (254, 23, 129, 1);
INSERT INTO `productosxbase` VALUES (255, 13, 129, 1);
INSERT INTO `productosxbase` VALUES (256, 1, 129, 1);
INSERT INTO `productosxbase` VALUES (257, 20, 129, 1);
INSERT INTO `productosxbase` VALUES (258, 21, 129, 1);
INSERT INTO `productosxbase` VALUES (259, 2, 129, 1);
INSERT INTO `productosxbase` VALUES (260, 5, 129, 1);
INSERT INTO `productosxbase` VALUES (261, 10, 129, 1);
INSERT INTO `productosxbase` VALUES (262, 4, 129, 1);
INSERT INTO `productosxbase` VALUES (263, 18, 130, 1);
INSERT INTO `productosxbase` VALUES (264, 15, 130, 1);
INSERT INTO `productosxbase` VALUES (265, 1, 130, 1);
INSERT INTO `productosxbase` VALUES (266, 22, 130, 1);
INSERT INTO `productosxbase` VALUES (267, 21, 130, 1);
INSERT INTO `productosxbase` VALUES (268, 1, 131, 1);
INSERT INTO `productosxbase` VALUES (269, 15, 131, 1);
INSERT INTO `productosxbase` VALUES (270, 21, 131, 1);
INSERT INTO `productosxbase` VALUES (271, 18, 131, 1);
INSERT INTO `productosxbase` VALUES (272, 22, 131, 1);
INSERT INTO `productosxbase` VALUES (273, 1, 132, 1);
INSERT INTO `productosxbase` VALUES (274, 25, 132, 1);
INSERT INTO `productosxbase` VALUES (275, 23, 132, 1);
INSERT INTO `productosxbase` VALUES (276, 22, 132, 1);
INSERT INTO `productosxbase` VALUES (277, 1, 133, 1);
INSERT INTO `productosxbase` VALUES (278, 20, 133, 1);
INSERT INTO `productosxbase` VALUES (279, 2, 133, 1);
INSERT INTO `productosxbase` VALUES (280, 13, 133, 1);
INSERT INTO `productosxbase` VALUES (281, 12, 133, 1);
INSERT INTO `productosxbase` VALUES (282, 15, 133, 1);
INSERT INTO `productosxbase` VALUES (283, 8, 133, 1);
INSERT INTO `productosxbase` VALUES (284, 12, 134, 1);
INSERT INTO `productosxbase` VALUES (285, 2, 134, 1);
INSERT INTO `productosxbase` VALUES (286, 15, 134, 1);
INSERT INTO `productosxbase` VALUES (287, 20, 134, 1);
INSERT INTO `productosxbase` VALUES (288, 1, 134, 1);
INSERT INTO `productosxbase` VALUES (289, 13, 134, 1);
INSERT INTO `productosxbase` VALUES (290, 8, 134, 1);
INSERT INTO `productosxbase` VALUES (291, 1, 135, 1);
INSERT INTO `productosxbase` VALUES (292, 23, 135, 1);
INSERT INTO `productosxbase` VALUES (293, 22, 135, 1);
INSERT INTO `productosxbase` VALUES (294, 14, 136, 1);
INSERT INTO `productosxbase` VALUES (295, 23, 136, 1);
INSERT INTO `productosxbase` VALUES (296, 25, 136, 1);
INSERT INTO `productosxbase` VALUES (297, 1, 136, 1);
INSERT INTO `productosxbase` VALUES (298, 22, 136, 1);
INSERT INTO `productosxbase` VALUES (305, 73, 150, 1);
INSERT INTO `productosxbase` VALUES (306, 3, 154, 1);
INSERT INTO `productosxbase` VALUES (307, 2, 154, 1);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `productos_base`
-- 

CREATE TABLE `productos_base` (
  `id_base` int(11) NOT NULL auto_increment,
  `producto` varchar(250) character set utf8 collate utf8_spanish_ci NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `precio` int(250) NOT NULL,
  PRIMARY KEY  (`id_base`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- 
-- Volcar la base de datos para la tabla `productos_base`
-- 

INSERT INTO `productos_base` VALUES (1, 'PAN', 3, 0);
INSERT INTO `productos_base` VALUES (2, 'CARNES', 3, 0);
INSERT INTO `productos_base` VALUES (3, 'QUESO', 3, 0);
INSERT INTO `productos_base` VALUES (4, 'SALAMI', 3, 0);
INSERT INTO `productos_base` VALUES (5, 'PEPERONI', 3, 0);
INSERT INTO `productos_base` VALUES (6, 'CHORIZO', 3, 0);
INSERT INTO `productos_base` VALUES (7, 'JALAPENO', 3, 0);
INSERT INTO `productos_base` VALUES (8, 'MOZARELA', 3, 0);
INSERT INTO `productos_base` VALUES (9, 'FILADELFIA', 3, 0);
INSERT INTO `productos_base` VALUES (10, 'SALCHICHA/ASAR', 3, 0);
INSERT INTO `productos_base` VALUES (11, 'TOCINO', 3, 0);
INSERT INTO `productos_base` VALUES (12, 'AGUACATE', 3, 0);
INSERT INTO `productos_base` VALUES (13, 'PINA', 3, 0);
INSERT INTO `productos_base` VALUES (14, 'CHAMPINON', 3, 0);
INSERT INTO `productos_base` VALUES (15, 'LEGUMBRES', 3, 0);
INSERT INTO `productos_base` VALUES (16, 'GOUDA', 3, 0);
INSERT INTO `productos_base` VALUES (17, 'CHIHUAHUA', 3, 0);
INSERT INTO `productos_base` VALUES (18, 'MANCHEGO', 3, 0);
INSERT INTO `productos_base` VALUES (19, 'SALCHICHA', 3, 0);
INSERT INTO `productos_base` VALUES (20, 'PASTOR', 3, 0);
INSERT INTO `productos_base` VALUES (21, 'POLLO', 3, 0);
INSERT INTO `productos_base` VALUES (22, 'PEPINILLOS', 3, 0);
INSERT INTO `productos_base` VALUES (23, 'ARRACHERA', 3, 0);
INSERT INTO `productos_base` VALUES (24, 'QUESO DE BOLA', 3, 0);
INSERT INTO `productos_base` VALUES (25, 'AROS DE CEBOLLA', 3, 0);
INSERT INTO `productos_base` VALUES (27, 'PAPA', 3, 0);
INSERT INTO `productos_base` VALUES (28, 'PÃˆUBÃ€', 3, 0);
INSERT INTO `productos_base` VALUES (29, 'SADASÃ‰D', 3, 0);
INSERT INTO `productos_base` VALUES (30, 'ENE', 3, 0);
INSERT INTO `productos_base` VALUES (31, 'PRUEBA43ENNE', 3, 0);
INSERT INTO `productos_base` VALUES (32, 'SALCHICA PRO AZAR', 3, 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `productos_paquete`
-- 

CREATE TABLE `productos_paquete` (
  `id_detalle` int(11) NOT NULL auto_increment,
  `id_producto` int(11) NOT NULL,
  `id_paquete` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=23 ;

-- 
-- Volcar la base de datos para la tabla `productos_paquete`
-- 

INSERT INTO `productos_paquete` VALUES (13, 150, 49, 3);
INSERT INTO `productos_paquete` VALUES (14, 150, 16, 3);
INSERT INTO `productos_paquete` VALUES (15, 151, 90, 1);
INSERT INTO `productos_paquete` VALUES (16, 151, 91, 1);
INSERT INTO `productos_paquete` VALUES (17, 151, 152, 1);
INSERT INTO `productos_paquete` VALUES (18, 151, 26, 1);
INSERT INTO `productos_paquete` VALUES (19, 153, 91, 1);
INSERT INTO `productos_paquete` VALUES (20, 153, 89, 1);
INSERT INTO `productos_paquete` VALUES (21, 153, 90, 1);
INSERT INTO `productos_paquete` VALUES (22, 153, 152, 1);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `producto_extra`
-- 

CREATE TABLE `producto_extra` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_producto` int(11) default NULL,
  `id_extra` int(11) default NULL,
  `nivel` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=952 ;

-- 
-- Volcar la base de datos para la tabla `producto_extra`
-- 

INSERT INTO `producto_extra` VALUES (1, 49, 106, 1);
INSERT INTO `producto_extra` VALUES (2, 49, 105, 1);
INSERT INTO `producto_extra` VALUES (3, 49, 104, 1);
INSERT INTO `producto_extra` VALUES (4, 50, 106, 1);
INSERT INTO `producto_extra` VALUES (5, 50, 104, 1);
INSERT INTO `producto_extra` VALUES (6, 50, 105, 1);
INSERT INTO `producto_extra` VALUES (7, 51, 104, 1);
INSERT INTO `producto_extra` VALUES (8, 51, 107, 1);
INSERT INTO `producto_extra` VALUES (9, 51, 108, 1);
INSERT INTO `producto_extra` VALUES (10, 52, 104, 1);
INSERT INTO `producto_extra` VALUES (11, 52, 108, 1);
INSERT INTO `producto_extra` VALUES (12, 52, 107, 1);
INSERT INTO `producto_extra` VALUES (13, 53, 104, 1);
INSERT INTO `producto_extra` VALUES (14, 53, 106, 1);
INSERT INTO `producto_extra` VALUES (15, 53, 109, 1);
INSERT INTO `producto_extra` VALUES (16, 54, 104, 1);
INSERT INTO `producto_extra` VALUES (17, 54, 106, 1);
INSERT INTO `producto_extra` VALUES (18, 54, 109, 1);
INSERT INTO `producto_extra` VALUES (19, 55, 111, 1);
INSERT INTO `producto_extra` VALUES (20, 55, 105, 1);
INSERT INTO `producto_extra` VALUES (21, 55, 110, 1);
INSERT INTO `producto_extra` VALUES (22, 55, 106, 1);
INSERT INTO `producto_extra` VALUES (23, 55, 109, 1);
INSERT INTO `producto_extra` VALUES (24, 56, 105, 1);
INSERT INTO `producto_extra` VALUES (25, 56, 110, 1);
INSERT INTO `producto_extra` VALUES (26, 56, 111, 1);
INSERT INTO `producto_extra` VALUES (27, 56, 106, 1);
INSERT INTO `producto_extra` VALUES (28, 56, 109, 1);
INSERT INTO `producto_extra` VALUES (29, 57, 104, 1);
INSERT INTO `producto_extra` VALUES (30, 57, 123, 1);
INSERT INTO `producto_extra` VALUES (31, 57, 112, 1);
INSERT INTO `producto_extra` VALUES (32, 57, 105, 1);
INSERT INTO `producto_extra` VALUES (33, 57, 107, 1);
INSERT INTO `producto_extra` VALUES (34, 57, 106, 1);
INSERT INTO `producto_extra` VALUES (35, 57, 113, 1);
INSERT INTO `producto_extra` VALUES (36, 57, 115, 1);
INSERT INTO `producto_extra` VALUES (37, 57, 114, 1);
INSERT INTO `producto_extra` VALUES (38, 57, 111, 1);
INSERT INTO `producto_extra` VALUES (39, 58, 104, 1);
INSERT INTO `producto_extra` VALUES (40, 58, 105, 1);
INSERT INTO `producto_extra` VALUES (41, 58, 107, 1);
INSERT INTO `producto_extra` VALUES (42, 58, 106, 1);
INSERT INTO `producto_extra` VALUES (43, 58, 112, 1);
INSERT INTO `producto_extra` VALUES (44, 58, 123, 1);
INSERT INTO `producto_extra` VALUES (45, 58, 113, 1);
INSERT INTO `producto_extra` VALUES (46, 58, 115, 1);
INSERT INTO `producto_extra` VALUES (47, 58, 114, 1);
INSERT INTO `producto_extra` VALUES (48, 58, 111, 1);
INSERT INTO `producto_extra` VALUES (49, 59, 112, 1);
INSERT INTO `producto_extra` VALUES (50, 59, 108, 1);
INSERT INTO `producto_extra` VALUES (51, 59, 117, 1);
INSERT INTO `producto_extra` VALUES (52, 59, 111, 1);
INSERT INTO `producto_extra` VALUES (53, 59, 104, 1);
INSERT INTO `producto_extra` VALUES (54, 60, 104, 1);
INSERT INTO `producto_extra` VALUES (55, 60, 112, 1);
INSERT INTO `producto_extra` VALUES (56, 60, 108, 1);
INSERT INTO `producto_extra` VALUES (57, 60, 111, 1);
INSERT INTO `producto_extra` VALUES (58, 60, 117, 1);
INSERT INTO `producto_extra` VALUES (59, 61, 104, 1);
INSERT INTO `producto_extra` VALUES (60, 61, 114, 1);
INSERT INTO `producto_extra` VALUES (61, 61, 111, 1);
INSERT INTO `producto_extra` VALUES (62, 61, 107, 1);
INSERT INTO `producto_extra` VALUES (63, 62, 107, 1);
INSERT INTO `producto_extra` VALUES (64, 62, 104, 1);
INSERT INTO `producto_extra` VALUES (65, 62, 114, 1);
INSERT INTO `producto_extra` VALUES (66, 62, 111, 1);
INSERT INTO `producto_extra` VALUES (67, 63, 104, 1);
INSERT INTO `producto_extra` VALUES (68, 63, 112, 1);
INSERT INTO `producto_extra` VALUES (69, 63, 107, 1);
INSERT INTO `producto_extra` VALUES (70, 63, 108, 1);
INSERT INTO `producto_extra` VALUES (71, 63, 105, 1);
INSERT INTO `producto_extra` VALUES (72, 64, 104, 1);
INSERT INTO `producto_extra` VALUES (73, 64, 107, 1);
INSERT INTO `producto_extra` VALUES (74, 64, 108, 1);
INSERT INTO `producto_extra` VALUES (75, 64, 112, 1);
INSERT INTO `producto_extra` VALUES (76, 64, 105, 1);
INSERT INTO `producto_extra` VALUES (77, 65, 107, 1);
INSERT INTO `producto_extra` VALUES (78, 65, 104, 1);
INSERT INTO `producto_extra` VALUES (79, 65, 112, 1);
INSERT INTO `producto_extra` VALUES (80, 65, 113, 1);
INSERT INTO `producto_extra` VALUES (81, 66, 104, 1);
INSERT INTO `producto_extra` VALUES (82, 66, 112, 1);
INSERT INTO `producto_extra` VALUES (83, 66, 107, 1);
INSERT INTO `producto_extra` VALUES (84, 66, 111, 1);
INSERT INTO `producto_extra` VALUES (85, 67, 105, 1);
INSERT INTO `producto_extra` VALUES (86, 67, 104, 1);
INSERT INTO `producto_extra` VALUES (87, 67, 115, 1);
INSERT INTO `producto_extra` VALUES (88, 67, 114, 1);
INSERT INTO `producto_extra` VALUES (89, 68, 114, 1);
INSERT INTO `producto_extra` VALUES (90, 68, 105, 1);
INSERT INTO `producto_extra` VALUES (91, 68, 115, 1);
INSERT INTO `producto_extra` VALUES (92, 68, 104, 1);
INSERT INTO `producto_extra` VALUES (93, 69, 123, 1);
INSERT INTO `producto_extra` VALUES (94, 69, 104, 1);
INSERT INTO `producto_extra` VALUES (95, 69, 112, 1);
INSERT INTO `producto_extra` VALUES (96, 69, 111, 1);
INSERT INTO `producto_extra` VALUES (97, 69, 105, 1);
INSERT INTO `producto_extra` VALUES (98, 69, 107, 1);
INSERT INTO `producto_extra` VALUES (99, 69, 106, 1);
INSERT INTO `producto_extra` VALUES (100, 70, 123, 1);
INSERT INTO `producto_extra` VALUES (101, 70, 112, 1);
INSERT INTO `producto_extra` VALUES (102, 70, 111, 1);
INSERT INTO `producto_extra` VALUES (103, 70, 104, 1);
INSERT INTO `producto_extra` VALUES (104, 70, 105, 1);
INSERT INTO `producto_extra` VALUES (105, 70, 107, 1);
INSERT INTO `producto_extra` VALUES (106, 70, 106, 1);
INSERT INTO `producto_extra` VALUES (107, 71, 104, 1);
INSERT INTO `producto_extra` VALUES (108, 71, 108, 1);
INSERT INTO `producto_extra` VALUES (109, 71, 111, 1);
INSERT INTO `producto_extra` VALUES (114, 72, 108, 1);
INSERT INTO `producto_extra` VALUES (116, 72, 111, 1);
INSERT INTO `producto_extra` VALUES (117, 72, 104, 1);
INSERT INTO `producto_extra` VALUES (118, 73, 118, 1);
INSERT INTO `producto_extra` VALUES (119, 73, 109, 1);
INSERT INTO `producto_extra` VALUES (120, 73, 119, 1);
INSERT INTO `producto_extra` VALUES (121, 73, 120, 1);
INSERT INTO `producto_extra` VALUES (122, 73, 115, 1);
INSERT INTO `producto_extra` VALUES (123, 74, 120, 1);
INSERT INTO `producto_extra` VALUES (124, 74, 118, 1);
INSERT INTO `producto_extra` VALUES (125, 74, 109, 1);
INSERT INTO `producto_extra` VALUES (126, 74, 119, 1);
INSERT INTO `producto_extra` VALUES (127, 74, 115, 1);
INSERT INTO `producto_extra` VALUES (128, 75, 104, 1);
INSERT INTO `producto_extra` VALUES (129, 75, 112, 1);
INSERT INTO `producto_extra` VALUES (130, 75, 123, 1);
INSERT INTO `producto_extra` VALUES (131, 75, 106, 1);
INSERT INTO `producto_extra` VALUES (132, 76, 123, 1);
INSERT INTO `producto_extra` VALUES (133, 76, 112, 1);
INSERT INTO `producto_extra` VALUES (134, 76, 106, 1);
INSERT INTO `producto_extra` VALUES (135, 76, 104, 1);
INSERT INTO `producto_extra` VALUES (136, 77, 104, 1);
INSERT INTO `producto_extra` VALUES (137, 77, 111, 1);
INSERT INTO `producto_extra` VALUES (138, 77, 114, 1);
INSERT INTO `producto_extra` VALUES (139, 77, 112, 1);
INSERT INTO `producto_extra` VALUES (140, 78, 114, 1);
INSERT INTO `producto_extra` VALUES (141, 78, 104, 1);
INSERT INTO `producto_extra` VALUES (142, 78, 112, 1);
INSERT INTO `producto_extra` VALUES (143, 78, 111, 1);
INSERT INTO `producto_extra` VALUES (144, 79, 104, 1);
INSERT INTO `producto_extra` VALUES (145, 79, 105, 1);
INSERT INTO `producto_extra` VALUES (146, 79, 115, 1);
INSERT INTO `producto_extra` VALUES (147, 80, 104, 1);
INSERT INTO `producto_extra` VALUES (148, 80, 105, 1);
INSERT INTO `producto_extra` VALUES (149, 80, 115, 1);
INSERT INTO `producto_extra` VALUES (150, 81, 104, 1);
INSERT INTO `producto_extra` VALUES (151, 81, 112, 1);
INSERT INTO `producto_extra` VALUES (152, 81, 105, 1);
INSERT INTO `producto_extra` VALUES (153, 81, 123, 1);
INSERT INTO `producto_extra` VALUES (154, 82, 104, 1);
INSERT INTO `producto_extra` VALUES (155, 82, 112, 1);
INSERT INTO `producto_extra` VALUES (156, 82, 123, 1);
INSERT INTO `producto_extra` VALUES (157, 82, 105, 1);
INSERT INTO `producto_extra` VALUES (158, 83, 123, 1);
INSERT INTO `producto_extra` VALUES (159, 83, 104, 1);
INSERT INTO `producto_extra` VALUES (160, 83, 112, 1);
INSERT INTO `producto_extra` VALUES (161, 84, 104, 1);
INSERT INTO `producto_extra` VALUES (162, 84, 112, 1);
INSERT INTO `producto_extra` VALUES (163, 84, 123, 1);
INSERT INTO `producto_extra` VALUES (164, 85, 104, 1);
INSERT INTO `producto_extra` VALUES (165, 85, 105, 1);
INSERT INTO `producto_extra` VALUES (166, 85, 114, 1);
INSERT INTO `producto_extra` VALUES (167, 86, 104, 1);
INSERT INTO `producto_extra` VALUES (168, 86, 114, 1);
INSERT INTO `producto_extra` VALUES (169, 86, 105, 1);
INSERT INTO `producto_extra` VALUES (170, 87, 104, 1);
INSERT INTO `producto_extra` VALUES (171, 87, 123, 1);
INSERT INTO `producto_extra` VALUES (172, 88, 104, 1);
INSERT INTO `producto_extra` VALUES (173, 88, 123, 1);
INSERT INTO `producto_extra` VALUES (174, 129, 106, 1);
INSERT INTO `producto_extra` VALUES (175, 129, 114, 1);
INSERT INTO `producto_extra` VALUES (176, 129, 111, 1);
INSERT INTO `producto_extra` VALUES (177, 129, 105, 1);
INSERT INTO `producto_extra` VALUES (178, 130, 126, 1);
INSERT INTO `producto_extra` VALUES (179, 130, 120, 1);
INSERT INTO `producto_extra` VALUES (180, 13, 126, 1);
INSERT INTO `producto_extra` VALUES (181, 13, 120, 1);
INSERT INTO `producto_extra` VALUES (182, 131, 126, 1);
INSERT INTO `producto_extra` VALUES (183, 131, 120, 1);
INSERT INTO `producto_extra` VALUES (184, 132, 127, 1);
INSERT INTO `producto_extra` VALUES (186, 133, 124, 1);
INSERT INTO `producto_extra` VALUES (187, 133, 113, 1);
INSERT INTO `producto_extra` VALUES (188, 133, 114, 1);
INSERT INTO `producto_extra` VALUES (189, 133, 109, 1);
INSERT INTO `producto_extra` VALUES (190, 133, 117, 1);
INSERT INTO `producto_extra` VALUES (191, 134, 126, 1);
INSERT INTO `producto_extra` VALUES (192, 134, 127, 1);
INSERT INTO `producto_extra` VALUES (193, 134, 113, 1);
INSERT INTO `producto_extra` VALUES (194, 134, 109, 1);
INSERT INTO `producto_extra` VALUES (195, 134, 117, 1);
INSERT INTO `producto_extra` VALUES (196, 134, 114, 1);
INSERT INTO `producto_extra` VALUES (199, 136, 126, 1);
INSERT INTO `producto_extra` VALUES (200, 136, 128, 1);
INSERT INTO `producto_extra` VALUES (201, 136, 127, 1);
INSERT INTO `producto_extra` VALUES (202, 136, 115, 1);
INSERT INTO `producto_extra` VALUES (203, 26, 138, 1);
INSERT INTO `producto_extra` VALUES (204, 26, 139, 1);
INSERT INTO `producto_extra` VALUES (205, 26, 140, 1);
INSERT INTO `producto_extra` VALUES (206, 26, 141, 1);
INSERT INTO `producto_extra` VALUES (207, 26, 142, 1);
INSERT INTO `producto_extra` VALUES (208, 26, 143, 1);
INSERT INTO `producto_extra` VALUES (209, 36, 138, 1);
INSERT INTO `producto_extra` VALUES (210, 36, 140, 1);
INSERT INTO `producto_extra` VALUES (211, 36, 141, 1);
INSERT INTO `producto_extra` VALUES (212, 36, 142, 1);
INSERT INTO `producto_extra` VALUES (213, 36, 139, 1);
INSERT INTO `producto_extra` VALUES (214, 36, 143, 1);
INSERT INTO `producto_extra` VALUES (215, 93, 138, 1);
INSERT INTO `producto_extra` VALUES (216, 93, 140, 1);
INSERT INTO `producto_extra` VALUES (217, 93, 139, 1);
INSERT INTO `producto_extra` VALUES (218, 93, 142, 1);
INSERT INTO `producto_extra` VALUES (219, 93, 141, 1);
INSERT INTO `producto_extra` VALUES (220, 93, 143, 1);
INSERT INTO `producto_extra` VALUES (221, 49, 144, 0);
INSERT INTO `producto_extra` VALUES (222, 49, 145, 0);
INSERT INTO `producto_extra` VALUES (223, 49, 146, 0);
INSERT INTO `producto_extra` VALUES (224, 49, 147, 0);
INSERT INTO `producto_extra` VALUES (225, 49, 148, 0);
INSERT INTO `producto_extra` VALUES (226, 49, 149, 0);
INSERT INTO `producto_extra` VALUES (227, 50, 144, 0);
INSERT INTO `producto_extra` VALUES (228, 50, 145, 0);
INSERT INTO `producto_extra` VALUES (229, 50, 146, 0);
INSERT INTO `producto_extra` VALUES (230, 50, 147, 0);
INSERT INTO `producto_extra` VALUES (231, 50, 148, 0);
INSERT INTO `producto_extra` VALUES (232, 50, 149, 0);
INSERT INTO `producto_extra` VALUES (233, 51, 144, 0);
INSERT INTO `producto_extra` VALUES (234, 51, 145, 0);
INSERT INTO `producto_extra` VALUES (235, 51, 146, 0);
INSERT INTO `producto_extra` VALUES (236, 51, 147, 0);
INSERT INTO `producto_extra` VALUES (237, 51, 148, 0);
INSERT INTO `producto_extra` VALUES (238, 51, 149, 0);
INSERT INTO `producto_extra` VALUES (239, 52, 144, 0);
INSERT INTO `producto_extra` VALUES (240, 52, 145, 0);
INSERT INTO `producto_extra` VALUES (241, 52, 146, 0);
INSERT INTO `producto_extra` VALUES (242, 52, 147, 0);
INSERT INTO `producto_extra` VALUES (243, 52, 148, 0);
INSERT INTO `producto_extra` VALUES (244, 52, 149, 0);
INSERT INTO `producto_extra` VALUES (245, 53, 144, 0);
INSERT INTO `producto_extra` VALUES (246, 53, 145, 0);
INSERT INTO `producto_extra` VALUES (247, 53, 146, 0);
INSERT INTO `producto_extra` VALUES (248, 53, 147, 0);
INSERT INTO `producto_extra` VALUES (249, 53, 148, 0);
INSERT INTO `producto_extra` VALUES (250, 53, 149, 0);
INSERT INTO `producto_extra` VALUES (251, 54, 144, 0);
INSERT INTO `producto_extra` VALUES (252, 54, 145, 0);
INSERT INTO `producto_extra` VALUES (253, 54, 146, 0);
INSERT INTO `producto_extra` VALUES (254, 54, 147, 0);
INSERT INTO `producto_extra` VALUES (255, 54, 148, 0);
INSERT INTO `producto_extra` VALUES (256, 54, 149, 0);
INSERT INTO `producto_extra` VALUES (257, 55, 144, 0);
INSERT INTO `producto_extra` VALUES (258, 55, 145, 0);
INSERT INTO `producto_extra` VALUES (259, 55, 146, 0);
INSERT INTO `producto_extra` VALUES (260, 55, 147, 0);
INSERT INTO `producto_extra` VALUES (261, 55, 148, 0);
INSERT INTO `producto_extra` VALUES (262, 55, 149, 0);
INSERT INTO `producto_extra` VALUES (263, 56, 144, 0);
INSERT INTO `producto_extra` VALUES (264, 56, 145, 0);
INSERT INTO `producto_extra` VALUES (265, 56, 146, 0);
INSERT INTO `producto_extra` VALUES (266, 56, 147, 0);
INSERT INTO `producto_extra` VALUES (267, 56, 148, 0);
INSERT INTO `producto_extra` VALUES (268, 56, 149, 0);
INSERT INTO `producto_extra` VALUES (269, 57, 144, 0);
INSERT INTO `producto_extra` VALUES (270, 57, 145, 0);
INSERT INTO `producto_extra` VALUES (271, 57, 146, 0);
INSERT INTO `producto_extra` VALUES (272, 57, 147, 0);
INSERT INTO `producto_extra` VALUES (273, 57, 148, 0);
INSERT INTO `producto_extra` VALUES (274, 57, 149, 0);
INSERT INTO `producto_extra` VALUES (275, 58, 144, 0);
INSERT INTO `producto_extra` VALUES (276, 58, 145, 0);
INSERT INTO `producto_extra` VALUES (277, 58, 146, 0);
INSERT INTO `producto_extra` VALUES (278, 58, 147, 0);
INSERT INTO `producto_extra` VALUES (279, 58, 148, 0);
INSERT INTO `producto_extra` VALUES (280, 58, 149, 0);
INSERT INTO `producto_extra` VALUES (281, 59, 144, 0);
INSERT INTO `producto_extra` VALUES (282, 59, 145, 0);
INSERT INTO `producto_extra` VALUES (283, 59, 146, 0);
INSERT INTO `producto_extra` VALUES (284, 59, 147, 0);
INSERT INTO `producto_extra` VALUES (285, 59, 148, 0);
INSERT INTO `producto_extra` VALUES (286, 59, 149, 0);
INSERT INTO `producto_extra` VALUES (287, 60, 144, 0);
INSERT INTO `producto_extra` VALUES (288, 60, 145, 0);
INSERT INTO `producto_extra` VALUES (289, 60, 146, 0);
INSERT INTO `producto_extra` VALUES (290, 60, 147, 0);
INSERT INTO `producto_extra` VALUES (291, 60, 148, 0);
INSERT INTO `producto_extra` VALUES (292, 60, 149, 0);
INSERT INTO `producto_extra` VALUES (293, 61, 144, 0);
INSERT INTO `producto_extra` VALUES (294, 61, 145, 0);
INSERT INTO `producto_extra` VALUES (295, 61, 146, 0);
INSERT INTO `producto_extra` VALUES (296, 61, 147, 0);
INSERT INTO `producto_extra` VALUES (297, 61, 148, 0);
INSERT INTO `producto_extra` VALUES (298, 61, 149, 0);
INSERT INTO `producto_extra` VALUES (299, 63, 144, 0);
INSERT INTO `producto_extra` VALUES (300, 63, 145, 0);
INSERT INTO `producto_extra` VALUES (301, 63, 146, 0);
INSERT INTO `producto_extra` VALUES (302, 63, 147, 0);
INSERT INTO `producto_extra` VALUES (303, 63, 148, 0);
INSERT INTO `producto_extra` VALUES (304, 63, 149, 0);
INSERT INTO `producto_extra` VALUES (305, 64, 144, 0);
INSERT INTO `producto_extra` VALUES (306, 64, 145, 0);
INSERT INTO `producto_extra` VALUES (307, 64, 146, 0);
INSERT INTO `producto_extra` VALUES (308, 64, 147, 0);
INSERT INTO `producto_extra` VALUES (309, 64, 148, 0);
INSERT INTO `producto_extra` VALUES (310, 64, 149, 0);
INSERT INTO `producto_extra` VALUES (311, 65, 144, 0);
INSERT INTO `producto_extra` VALUES (312, 65, 145, 0);
INSERT INTO `producto_extra` VALUES (313, 65, 146, 0);
INSERT INTO `producto_extra` VALUES (314, 65, 147, 0);
INSERT INTO `producto_extra` VALUES (315, 65, 148, 0);
INSERT INTO `producto_extra` VALUES (316, 65, 149, 0);
INSERT INTO `producto_extra` VALUES (317, 66, 144, 0);
INSERT INTO `producto_extra` VALUES (318, 66, 145, 0);
INSERT INTO `producto_extra` VALUES (319, 66, 146, 0);
INSERT INTO `producto_extra` VALUES (320, 66, 147, 0);
INSERT INTO `producto_extra` VALUES (321, 66, 148, 0);
INSERT INTO `producto_extra` VALUES (322, 66, 149, 0);
INSERT INTO `producto_extra` VALUES (323, 67, 144, 0);
INSERT INTO `producto_extra` VALUES (324, 67, 145, 0);
INSERT INTO `producto_extra` VALUES (325, 67, 146, 0);
INSERT INTO `producto_extra` VALUES (326, 67, 147, 0);
INSERT INTO `producto_extra` VALUES (327, 67, 148, 0);
INSERT INTO `producto_extra` VALUES (328, 67, 149, 0);
INSERT INTO `producto_extra` VALUES (329, 68, 144, 0);
INSERT INTO `producto_extra` VALUES (330, 68, 145, 0);
INSERT INTO `producto_extra` VALUES (331, 68, 146, 0);
INSERT INTO `producto_extra` VALUES (332, 68, 147, 0);
INSERT INTO `producto_extra` VALUES (333, 68, 148, 0);
INSERT INTO `producto_extra` VALUES (334, 68, 149, 0);
INSERT INTO `producto_extra` VALUES (335, 69, 144, 0);
INSERT INTO `producto_extra` VALUES (336, 69, 145, 0);
INSERT INTO `producto_extra` VALUES (337, 69, 146, 0);
INSERT INTO `producto_extra` VALUES (338, 69, 147, 0);
INSERT INTO `producto_extra` VALUES (339, 69, 148, 0);
INSERT INTO `producto_extra` VALUES (340, 69, 149, 0);
INSERT INTO `producto_extra` VALUES (341, 70, 144, 0);
INSERT INTO `producto_extra` VALUES (342, 70, 145, 0);
INSERT INTO `producto_extra` VALUES (343, 70, 146, 0);
INSERT INTO `producto_extra` VALUES (344, 70, 147, 0);
INSERT INTO `producto_extra` VALUES (345, 70, 148, 0);
INSERT INTO `producto_extra` VALUES (346, 70, 149, 0);
INSERT INTO `producto_extra` VALUES (347, 71, 144, 0);
INSERT INTO `producto_extra` VALUES (348, 71, 145, 0);
INSERT INTO `producto_extra` VALUES (349, 71, 146, 0);
INSERT INTO `producto_extra` VALUES (350, 71, 147, 0);
INSERT INTO `producto_extra` VALUES (351, 71, 148, 0);
INSERT INTO `producto_extra` VALUES (352, 71, 149, 0);
INSERT INTO `producto_extra` VALUES (353, 72, 144, 0);
INSERT INTO `producto_extra` VALUES (354, 72, 145, 0);
INSERT INTO `producto_extra` VALUES (355, 72, 146, 0);
INSERT INTO `producto_extra` VALUES (356, 72, 147, 0);
INSERT INTO `producto_extra` VALUES (357, 72, 148, 0);
INSERT INTO `producto_extra` VALUES (358, 72, 149, 0);
INSERT INTO `producto_extra` VALUES (359, 73, 144, 0);
INSERT INTO `producto_extra` VALUES (360, 73, 145, 0);
INSERT INTO `producto_extra` VALUES (361, 73, 146, 0);
INSERT INTO `producto_extra` VALUES (362, 73, 147, 0);
INSERT INTO `producto_extra` VALUES (363, 73, 148, 0);
INSERT INTO `producto_extra` VALUES (364, 73, 149, 0);
INSERT INTO `producto_extra` VALUES (365, 74, 144, 0);
INSERT INTO `producto_extra` VALUES (366, 74, 145, 0);
INSERT INTO `producto_extra` VALUES (367, 74, 146, 0);
INSERT INTO `producto_extra` VALUES (368, 74, 147, 0);
INSERT INTO `producto_extra` VALUES (369, 74, 148, 0);
INSERT INTO `producto_extra` VALUES (370, 74, 149, 0);
INSERT INTO `producto_extra` VALUES (371, 75, 144, 0);
INSERT INTO `producto_extra` VALUES (372, 75, 145, 0);
INSERT INTO `producto_extra` VALUES (373, 75, 146, 0);
INSERT INTO `producto_extra` VALUES (374, 75, 147, 0);
INSERT INTO `producto_extra` VALUES (375, 75, 148, 0);
INSERT INTO `producto_extra` VALUES (376, 75, 149, 0);
INSERT INTO `producto_extra` VALUES (377, 76, 144, 0);
INSERT INTO `producto_extra` VALUES (378, 76, 145, 0);
INSERT INTO `producto_extra` VALUES (379, 76, 146, 0);
INSERT INTO `producto_extra` VALUES (380, 76, 147, 0);
INSERT INTO `producto_extra` VALUES (381, 76, 148, 0);
INSERT INTO `producto_extra` VALUES (382, 76, 149, 0);
INSERT INTO `producto_extra` VALUES (383, 77, 144, 0);
INSERT INTO `producto_extra` VALUES (384, 77, 145, 0);
INSERT INTO `producto_extra` VALUES (385, 77, 146, 0);
INSERT INTO `producto_extra` VALUES (386, 77, 147, 0);
INSERT INTO `producto_extra` VALUES (387, 77, 148, 0);
INSERT INTO `producto_extra` VALUES (388, 77, 149, 0);
INSERT INTO `producto_extra` VALUES (389, 78, 144, 0);
INSERT INTO `producto_extra` VALUES (390, 78, 145, 0);
INSERT INTO `producto_extra` VALUES (391, 78, 146, 0);
INSERT INTO `producto_extra` VALUES (392, 78, 147, 0);
INSERT INTO `producto_extra` VALUES (393, 78, 148, 0);
INSERT INTO `producto_extra` VALUES (394, 78, 149, 0);
INSERT INTO `producto_extra` VALUES (395, 79, 144, 0);
INSERT INTO `producto_extra` VALUES (396, 79, 145, 0);
INSERT INTO `producto_extra` VALUES (397, 79, 146, 0);
INSERT INTO `producto_extra` VALUES (398, 79, 147, 0);
INSERT INTO `producto_extra` VALUES (399, 79, 148, 0);
INSERT INTO `producto_extra` VALUES (400, 79, 149, 0);
INSERT INTO `producto_extra` VALUES (401, 80, 144, 0);
INSERT INTO `producto_extra` VALUES (402, 80, 145, 0);
INSERT INTO `producto_extra` VALUES (403, 80, 146, 0);
INSERT INTO `producto_extra` VALUES (404, 80, 147, 0);
INSERT INTO `producto_extra` VALUES (405, 80, 148, 0);
INSERT INTO `producto_extra` VALUES (406, 80, 149, 0);
INSERT INTO `producto_extra` VALUES (407, 81, 144, 0);
INSERT INTO `producto_extra` VALUES (408, 81, 145, 0);
INSERT INTO `producto_extra` VALUES (409, 81, 146, 0);
INSERT INTO `producto_extra` VALUES (410, 81, 147, 0);
INSERT INTO `producto_extra` VALUES (411, 81, 148, 0);
INSERT INTO `producto_extra` VALUES (412, 81, 149, 0);
INSERT INTO `producto_extra` VALUES (413, 82, 144, 0);
INSERT INTO `producto_extra` VALUES (414, 82, 145, 0);
INSERT INTO `producto_extra` VALUES (415, 82, 146, 0);
INSERT INTO `producto_extra` VALUES (416, 82, 147, 0);
INSERT INTO `producto_extra` VALUES (417, 82, 148, 0);
INSERT INTO `producto_extra` VALUES (418, 82, 149, 0);
INSERT INTO `producto_extra` VALUES (419, 83, 144, 0);
INSERT INTO `producto_extra` VALUES (420, 83, 145, 0);
INSERT INTO `producto_extra` VALUES (421, 83, 146, 0);
INSERT INTO `producto_extra` VALUES (422, 83, 147, 0);
INSERT INTO `producto_extra` VALUES (423, 83, 148, 0);
INSERT INTO `producto_extra` VALUES (424, 83, 149, 0);
INSERT INTO `producto_extra` VALUES (425, 84, 144, 0);
INSERT INTO `producto_extra` VALUES (426, 84, 145, 0);
INSERT INTO `producto_extra` VALUES (427, 84, 146, 0);
INSERT INTO `producto_extra` VALUES (428, 84, 147, 0);
INSERT INTO `producto_extra` VALUES (429, 84, 148, 0);
INSERT INTO `producto_extra` VALUES (430, 84, 149, 0);
INSERT INTO `producto_extra` VALUES (431, 85, 144, 0);
INSERT INTO `producto_extra` VALUES (432, 85, 145, 0);
INSERT INTO `producto_extra` VALUES (433, 85, 146, 0);
INSERT INTO `producto_extra` VALUES (434, 85, 147, 0);
INSERT INTO `producto_extra` VALUES (435, 85, 148, 0);
INSERT INTO `producto_extra` VALUES (436, 85, 149, 0);
INSERT INTO `producto_extra` VALUES (437, 86, 144, 0);
INSERT INTO `producto_extra` VALUES (438, 86, 145, 0);
INSERT INTO `producto_extra` VALUES (439, 86, 146, 0);
INSERT INTO `producto_extra` VALUES (440, 86, 147, 0);
INSERT INTO `producto_extra` VALUES (441, 86, 148, 0);
INSERT INTO `producto_extra` VALUES (442, 86, 149, 0);
INSERT INTO `producto_extra` VALUES (443, 87, 144, 0);
INSERT INTO `producto_extra` VALUES (444, 87, 145, 0);
INSERT INTO `producto_extra` VALUES (445, 87, 146, 0);
INSERT INTO `producto_extra` VALUES (446, 87, 147, 0);
INSERT INTO `producto_extra` VALUES (447, 87, 148, 0);
INSERT INTO `producto_extra` VALUES (448, 87, 149, 0);
INSERT INTO `producto_extra` VALUES (449, 88, 144, 0);
INSERT INTO `producto_extra` VALUES (450, 88, 145, 0);
INSERT INTO `producto_extra` VALUES (451, 88, 146, 0);
INSERT INTO `producto_extra` VALUES (452, 88, 147, 0);
INSERT INTO `producto_extra` VALUES (453, 88, 148, 0);
INSERT INTO `producto_extra` VALUES (454, 88, 149, 0);
INSERT INTO `producto_extra` VALUES (461, 129, 144, 0);
INSERT INTO `producto_extra` VALUES (462, 129, 145, 0);
INSERT INTO `producto_extra` VALUES (463, 129, 146, 0);
INSERT INTO `producto_extra` VALUES (464, 129, 147, 0);
INSERT INTO `producto_extra` VALUES (465, 129, 148, 0);
INSERT INTO `producto_extra` VALUES (466, 129, 149, 0);
INSERT INTO `producto_extra` VALUES (467, 130, 144, 0);
INSERT INTO `producto_extra` VALUES (468, 130, 145, 0);
INSERT INTO `producto_extra` VALUES (469, 130, 146, 0);
INSERT INTO `producto_extra` VALUES (470, 130, 147, 0);
INSERT INTO `producto_extra` VALUES (471, 130, 148, 0);
INSERT INTO `producto_extra` VALUES (472, 130, 149, 0);
INSERT INTO `producto_extra` VALUES (473, 131, 144, 0);
INSERT INTO `producto_extra` VALUES (474, 131, 145, 0);
INSERT INTO `producto_extra` VALUES (475, 131, 146, 0);
INSERT INTO `producto_extra` VALUES (476, 131, 147, 0);
INSERT INTO `producto_extra` VALUES (477, 131, 148, 0);
INSERT INTO `producto_extra` VALUES (478, 131, 149, 0);
INSERT INTO `producto_extra` VALUES (479, 132, 144, 0);
INSERT INTO `producto_extra` VALUES (480, 132, 145, 0);
INSERT INTO `producto_extra` VALUES (481, 132, 146, 0);
INSERT INTO `producto_extra` VALUES (482, 132, 147, 0);
INSERT INTO `producto_extra` VALUES (483, 132, 148, 0);
INSERT INTO `producto_extra` VALUES (484, 132, 149, 0);
INSERT INTO `producto_extra` VALUES (485, 133, 144, 0);
INSERT INTO `producto_extra` VALUES (486, 133, 145, 0);
INSERT INTO `producto_extra` VALUES (487, 133, 146, 0);
INSERT INTO `producto_extra` VALUES (488, 133, 147, 0);
INSERT INTO `producto_extra` VALUES (489, 133, 148, 0);
INSERT INTO `producto_extra` VALUES (490, 133, 149, 0);
INSERT INTO `producto_extra` VALUES (491, 134, 144, 0);
INSERT INTO `producto_extra` VALUES (492, 134, 145, 0);
INSERT INTO `producto_extra` VALUES (493, 134, 146, 0);
INSERT INTO `producto_extra` VALUES (494, 134, 147, 0);
INSERT INTO `producto_extra` VALUES (495, 134, 148, 0);
INSERT INTO `producto_extra` VALUES (496, 134, 149, 0);
INSERT INTO `producto_extra` VALUES (497, 135, 144, 0);
INSERT INTO `producto_extra` VALUES (498, 135, 145, 0);
INSERT INTO `producto_extra` VALUES (499, 135, 146, 0);
INSERT INTO `producto_extra` VALUES (500, 135, 147, 0);
INSERT INTO `producto_extra` VALUES (501, 135, 148, 0);
INSERT INTO `producto_extra` VALUES (502, 135, 149, 0);
INSERT INTO `producto_extra` VALUES (503, 136, 144, 0);
INSERT INTO `producto_extra` VALUES (504, 136, 145, 0);
INSERT INTO `producto_extra` VALUES (505, 136, 146, 0);
INSERT INTO `producto_extra` VALUES (506, 136, 147, 0);
INSERT INTO `producto_extra` VALUES (507, 136, 148, 0);
INSERT INTO `producto_extra` VALUES (508, 136, 149, 0);
INSERT INTO `producto_extra` VALUES (509, 135, 105, 1);
INSERT INTO `producto_extra` VALUES (510, 135, 123, 1);
INSERT INTO `producto_extra` VALUES (511, 135, 111, 1);
INSERT INTO `producto_extra` VALUES (512, 135, 114, 1);
INSERT INTO `producto_extra` VALUES (513, 135, 110, 1);
INSERT INTO `producto_extra` VALUES (514, 135, 106, 1);
INSERT INTO `producto_extra` VALUES (515, 135, 115, 1);
INSERT INTO `producto_extra` VALUES (516, 135, 108, 1);
INSERT INTO `producto_extra` VALUES (517, 135, 107, 1);
INSERT INTO `producto_extra` VALUES (518, 135, 112, 1);
INSERT INTO `producto_extra` VALUES (519, 135, 113, 1);
INSERT INTO `producto_extra` VALUES (520, 136, 105, 1);
INSERT INTO `producto_extra` VALUES (521, 136, 123, 1);
INSERT INTO `producto_extra` VALUES (522, 136, 111, 1);
INSERT INTO `producto_extra` VALUES (523, 136, 114, 1);
INSERT INTO `producto_extra` VALUES (524, 136, 110, 1);
INSERT INTO `producto_extra` VALUES (525, 136, 106, 1);
INSERT INTO `producto_extra` VALUES (526, 136, 115, 1);
INSERT INTO `producto_extra` VALUES (527, 136, 108, 1);
INSERT INTO `producto_extra` VALUES (528, 136, 107, 1);
INSERT INTO `producto_extra` VALUES (529, 136, 112, 1);
INSERT INTO `producto_extra` VALUES (530, 136, 113, 1);
INSERT INTO `producto_extra` VALUES (531, 134, 105, 1);
INSERT INTO `producto_extra` VALUES (532, 134, 123, 1);
INSERT INTO `producto_extra` VALUES (533, 134, 111, 1);
INSERT INTO `producto_extra` VALUES (534, 134, 114, 1);
INSERT INTO `producto_extra` VALUES (535, 134, 110, 1);
INSERT INTO `producto_extra` VALUES (536, 134, 106, 1);
INSERT INTO `producto_extra` VALUES (537, 134, 115, 1);
INSERT INTO `producto_extra` VALUES (538, 134, 108, 1);
INSERT INTO `producto_extra` VALUES (539, 134, 107, 1);
INSERT INTO `producto_extra` VALUES (540, 134, 112, 1);
INSERT INTO `producto_extra` VALUES (541, 134, 113, 1);
INSERT INTO `producto_extra` VALUES (542, 133, 105, 1);
INSERT INTO `producto_extra` VALUES (543, 133, 123, 1);
INSERT INTO `producto_extra` VALUES (544, 133, 111, 1);
INSERT INTO `producto_extra` VALUES (545, 133, 114, 1);
INSERT INTO `producto_extra` VALUES (546, 133, 110, 1);
INSERT INTO `producto_extra` VALUES (547, 133, 106, 1);
INSERT INTO `producto_extra` VALUES (548, 133, 115, 1);
INSERT INTO `producto_extra` VALUES (549, 133, 108, 1);
INSERT INTO `producto_extra` VALUES (550, 133, 107, 1);
INSERT INTO `producto_extra` VALUES (551, 133, 112, 1);
INSERT INTO `producto_extra` VALUES (552, 133, 113, 1);
INSERT INTO `producto_extra` VALUES (553, 132, 105, 1);
INSERT INTO `producto_extra` VALUES (554, 132, 123, 1);
INSERT INTO `producto_extra` VALUES (555, 132, 111, 1);
INSERT INTO `producto_extra` VALUES (556, 132, 114, 1);
INSERT INTO `producto_extra` VALUES (557, 132, 110, 1);
INSERT INTO `producto_extra` VALUES (558, 132, 106, 1);
INSERT INTO `producto_extra` VALUES (559, 132, 115, 1);
INSERT INTO `producto_extra` VALUES (560, 132, 108, 1);
INSERT INTO `producto_extra` VALUES (561, 132, 107, 1);
INSERT INTO `producto_extra` VALUES (562, 132, 112, 1);
INSERT INTO `producto_extra` VALUES (563, 132, 113, 1);
INSERT INTO `producto_extra` VALUES (564, 131, 105, 1);
INSERT INTO `producto_extra` VALUES (565, 131, 123, 1);
INSERT INTO `producto_extra` VALUES (566, 131, 111, 1);
INSERT INTO `producto_extra` VALUES (567, 131, 114, 1);
INSERT INTO `producto_extra` VALUES (568, 131, 110, 1);
INSERT INTO `producto_extra` VALUES (569, 131, 106, 1);
INSERT INTO `producto_extra` VALUES (570, 131, 115, 1);
INSERT INTO `producto_extra` VALUES (571, 131, 108, 1);
INSERT INTO `producto_extra` VALUES (572, 131, 107, 1);
INSERT INTO `producto_extra` VALUES (573, 131, 112, 1);
INSERT INTO `producto_extra` VALUES (574, 131, 113, 1);
INSERT INTO `producto_extra` VALUES (575, 130, 105, 1);
INSERT INTO `producto_extra` VALUES (576, 130, 123, 1);
INSERT INTO `producto_extra` VALUES (577, 130, 111, 1);
INSERT INTO `producto_extra` VALUES (578, 130, 114, 1);
INSERT INTO `producto_extra` VALUES (579, 130, 110, 1);
INSERT INTO `producto_extra` VALUES (580, 130, 106, 1);
INSERT INTO `producto_extra` VALUES (581, 130, 115, 1);
INSERT INTO `producto_extra` VALUES (582, 130, 108, 1);
INSERT INTO `producto_extra` VALUES (583, 130, 107, 1);
INSERT INTO `producto_extra` VALUES (584, 130, 112, 1);
INSERT INTO `producto_extra` VALUES (585, 130, 113, 1);
INSERT INTO `producto_extra` VALUES (586, 129, 105, 1);
INSERT INTO `producto_extra` VALUES (587, 129, 123, 1);
INSERT INTO `producto_extra` VALUES (588, 129, 111, 1);
INSERT INTO `producto_extra` VALUES (589, 129, 114, 1);
INSERT INTO `producto_extra` VALUES (590, 129, 110, 1);
INSERT INTO `producto_extra` VALUES (591, 129, 106, 1);
INSERT INTO `producto_extra` VALUES (592, 129, 115, 1);
INSERT INTO `producto_extra` VALUES (593, 129, 108, 1);
INSERT INTO `producto_extra` VALUES (594, 129, 107, 1);
INSERT INTO `producto_extra` VALUES (595, 129, 112, 1);
INSERT INTO `producto_extra` VALUES (596, 129, 113, 1);
INSERT INTO `producto_extra` VALUES (597, 49, 105, 1);
INSERT INTO `producto_extra` VALUES (598, 49, 123, 1);
INSERT INTO `producto_extra` VALUES (599, 49, 111, 1);
INSERT INTO `producto_extra` VALUES (600, 49, 114, 1);
INSERT INTO `producto_extra` VALUES (601, 49, 110, 1);
INSERT INTO `producto_extra` VALUES (602, 49, 106, 1);
INSERT INTO `producto_extra` VALUES (603, 49, 115, 1);
INSERT INTO `producto_extra` VALUES (604, 49, 108, 1);
INSERT INTO `producto_extra` VALUES (605, 49, 107, 1);
INSERT INTO `producto_extra` VALUES (606, 49, 112, 1);
INSERT INTO `producto_extra` VALUES (607, 49, 113, 1);
INSERT INTO `producto_extra` VALUES (608, 50, 105, 1);
INSERT INTO `producto_extra` VALUES (609, 50, 123, 1);
INSERT INTO `producto_extra` VALUES (610, 50, 111, 1);
INSERT INTO `producto_extra` VALUES (611, 50, 114, 1);
INSERT INTO `producto_extra` VALUES (612, 50, 110, 1);
INSERT INTO `producto_extra` VALUES (613, 50, 106, 1);
INSERT INTO `producto_extra` VALUES (614, 50, 115, 1);
INSERT INTO `producto_extra` VALUES (615, 50, 108, 1);
INSERT INTO `producto_extra` VALUES (616, 50, 107, 1);
INSERT INTO `producto_extra` VALUES (617, 50, 112, 1);
INSERT INTO `producto_extra` VALUES (618, 50, 113, 1);
INSERT INTO `producto_extra` VALUES (619, 52, 105, 1);
INSERT INTO `producto_extra` VALUES (620, 52, 123, 1);
INSERT INTO `producto_extra` VALUES (621, 52, 111, 1);
INSERT INTO `producto_extra` VALUES (622, 52, 114, 1);
INSERT INTO `producto_extra` VALUES (623, 52, 110, 1);
INSERT INTO `producto_extra` VALUES (624, 52, 106, 1);
INSERT INTO `producto_extra` VALUES (625, 52, 115, 1);
INSERT INTO `producto_extra` VALUES (626, 52, 108, 1);
INSERT INTO `producto_extra` VALUES (627, 52, 107, 1);
INSERT INTO `producto_extra` VALUES (628, 52, 112, 1);
INSERT INTO `producto_extra` VALUES (629, 52, 113, 1);
INSERT INTO `producto_extra` VALUES (630, 53, 105, 1);
INSERT INTO `producto_extra` VALUES (631, 53, 123, 1);
INSERT INTO `producto_extra` VALUES (632, 53, 111, 1);
INSERT INTO `producto_extra` VALUES (633, 53, 114, 1);
INSERT INTO `producto_extra` VALUES (634, 53, 110, 1);
INSERT INTO `producto_extra` VALUES (635, 53, 106, 1);
INSERT INTO `producto_extra` VALUES (636, 53, 115, 1);
INSERT INTO `producto_extra` VALUES (637, 53, 108, 1);
INSERT INTO `producto_extra` VALUES (638, 53, 107, 1);
INSERT INTO `producto_extra` VALUES (639, 53, 112, 1);
INSERT INTO `producto_extra` VALUES (640, 53, 113, 1);
INSERT INTO `producto_extra` VALUES (641, 54, 105, 1);
INSERT INTO `producto_extra` VALUES (642, 54, 123, 1);
INSERT INTO `producto_extra` VALUES (643, 54, 111, 1);
INSERT INTO `producto_extra` VALUES (644, 54, 114, 1);
INSERT INTO `producto_extra` VALUES (645, 54, 110, 1);
INSERT INTO `producto_extra` VALUES (646, 54, 106, 1);
INSERT INTO `producto_extra` VALUES (647, 54, 115, 1);
INSERT INTO `producto_extra` VALUES (648, 54, 108, 1);
INSERT INTO `producto_extra` VALUES (649, 54, 107, 1);
INSERT INTO `producto_extra` VALUES (650, 54, 112, 1);
INSERT INTO `producto_extra` VALUES (651, 54, 113, 1);
INSERT INTO `producto_extra` VALUES (652, 55, 105, 1);
INSERT INTO `producto_extra` VALUES (653, 55, 123, 1);
INSERT INTO `producto_extra` VALUES (654, 55, 111, 1);
INSERT INTO `producto_extra` VALUES (655, 55, 114, 1);
INSERT INTO `producto_extra` VALUES (656, 55, 110, 1);
INSERT INTO `producto_extra` VALUES (657, 55, 106, 1);
INSERT INTO `producto_extra` VALUES (658, 55, 115, 1);
INSERT INTO `producto_extra` VALUES (659, 55, 108, 1);
INSERT INTO `producto_extra` VALUES (660, 55, 107, 1);
INSERT INTO `producto_extra` VALUES (661, 55, 112, 1);
INSERT INTO `producto_extra` VALUES (662, 55, 113, 1);
INSERT INTO `producto_extra` VALUES (663, 56, 105, 1);
INSERT INTO `producto_extra` VALUES (664, 56, 123, 1);
INSERT INTO `producto_extra` VALUES (665, 56, 111, 1);
INSERT INTO `producto_extra` VALUES (666, 56, 114, 1);
INSERT INTO `producto_extra` VALUES (667, 56, 110, 1);
INSERT INTO `producto_extra` VALUES (668, 56, 106, 1);
INSERT INTO `producto_extra` VALUES (669, 56, 115, 1);
INSERT INTO `producto_extra` VALUES (670, 56, 108, 1);
INSERT INTO `producto_extra` VALUES (671, 56, 107, 1);
INSERT INTO `producto_extra` VALUES (672, 56, 112, 1);
INSERT INTO `producto_extra` VALUES (673, 56, 113, 1);
INSERT INTO `producto_extra` VALUES (674, 57, 105, 1);
INSERT INTO `producto_extra` VALUES (675, 57, 123, 1);
INSERT INTO `producto_extra` VALUES (676, 57, 111, 1);
INSERT INTO `producto_extra` VALUES (677, 57, 114, 1);
INSERT INTO `producto_extra` VALUES (678, 57, 110, 1);
INSERT INTO `producto_extra` VALUES (679, 57, 106, 1);
INSERT INTO `producto_extra` VALUES (680, 57, 115, 1);
INSERT INTO `producto_extra` VALUES (681, 57, 108, 1);
INSERT INTO `producto_extra` VALUES (682, 57, 107, 1);
INSERT INTO `producto_extra` VALUES (683, 57, 112, 1);
INSERT INTO `producto_extra` VALUES (684, 57, 113, 1);
INSERT INTO `producto_extra` VALUES (685, 58, 105, 1);
INSERT INTO `producto_extra` VALUES (686, 58, 123, 1);
INSERT INTO `producto_extra` VALUES (687, 58, 111, 1);
INSERT INTO `producto_extra` VALUES (688, 58, 114, 1);
INSERT INTO `producto_extra` VALUES (689, 58, 110, 1);
INSERT INTO `producto_extra` VALUES (690, 58, 106, 1);
INSERT INTO `producto_extra` VALUES (691, 58, 115, 1);
INSERT INTO `producto_extra` VALUES (692, 58, 108, 1);
INSERT INTO `producto_extra` VALUES (693, 58, 107, 1);
INSERT INTO `producto_extra` VALUES (694, 58, 112, 1);
INSERT INTO `producto_extra` VALUES (695, 58, 113, 1);
INSERT INTO `producto_extra` VALUES (696, 59, 105, 1);
INSERT INTO `producto_extra` VALUES (697, 59, 123, 1);
INSERT INTO `producto_extra` VALUES (698, 59, 111, 1);
INSERT INTO `producto_extra` VALUES (699, 59, 114, 1);
INSERT INTO `producto_extra` VALUES (700, 59, 110, 1);
INSERT INTO `producto_extra` VALUES (701, 59, 106, 1);
INSERT INTO `producto_extra` VALUES (702, 59, 115, 1);
INSERT INTO `producto_extra` VALUES (703, 59, 108, 1);
INSERT INTO `producto_extra` VALUES (704, 59, 107, 1);
INSERT INTO `producto_extra` VALUES (705, 59, 112, 1);
INSERT INTO `producto_extra` VALUES (706, 59, 113, 1);
INSERT INTO `producto_extra` VALUES (707, 60, 105, 1);
INSERT INTO `producto_extra` VALUES (708, 60, 123, 1);
INSERT INTO `producto_extra` VALUES (709, 60, 111, 1);
INSERT INTO `producto_extra` VALUES (710, 60, 114, 1);
INSERT INTO `producto_extra` VALUES (711, 60, 110, 1);
INSERT INTO `producto_extra` VALUES (712, 60, 106, 1);
INSERT INTO `producto_extra` VALUES (713, 60, 115, 1);
INSERT INTO `producto_extra` VALUES (714, 60, 108, 1);
INSERT INTO `producto_extra` VALUES (715, 60, 107, 1);
INSERT INTO `producto_extra` VALUES (716, 60, 112, 1);
INSERT INTO `producto_extra` VALUES (717, 60, 113, 1);
INSERT INTO `producto_extra` VALUES (718, 61, 105, 1);
INSERT INTO `producto_extra` VALUES (719, 61, 123, 1);
INSERT INTO `producto_extra` VALUES (720, 61, 111, 1);
INSERT INTO `producto_extra` VALUES (721, 61, 114, 1);
INSERT INTO `producto_extra` VALUES (722, 61, 110, 1);
INSERT INTO `producto_extra` VALUES (723, 61, 106, 1);
INSERT INTO `producto_extra` VALUES (724, 61, 115, 1);
INSERT INTO `producto_extra` VALUES (725, 61, 108, 1);
INSERT INTO `producto_extra` VALUES (726, 61, 107, 1);
INSERT INTO `producto_extra` VALUES (727, 61, 112, 1);
INSERT INTO `producto_extra` VALUES (728, 61, 113, 1);
INSERT INTO `producto_extra` VALUES (729, 62, 105, 1);
INSERT INTO `producto_extra` VALUES (730, 62, 123, 1);
INSERT INTO `producto_extra` VALUES (731, 62, 111, 1);
INSERT INTO `producto_extra` VALUES (732, 62, 114, 1);
INSERT INTO `producto_extra` VALUES (733, 62, 110, 1);
INSERT INTO `producto_extra` VALUES (734, 62, 106, 1);
INSERT INTO `producto_extra` VALUES (735, 62, 115, 1);
INSERT INTO `producto_extra` VALUES (736, 62, 108, 1);
INSERT INTO `producto_extra` VALUES (737, 62, 107, 1);
INSERT INTO `producto_extra` VALUES (738, 62, 112, 1);
INSERT INTO `producto_extra` VALUES (739, 62, 113, 1);
INSERT INTO `producto_extra` VALUES (740, 63, 105, 1);
INSERT INTO `producto_extra` VALUES (741, 63, 123, 1);
INSERT INTO `producto_extra` VALUES (742, 63, 111, 1);
INSERT INTO `producto_extra` VALUES (743, 63, 114, 1);
INSERT INTO `producto_extra` VALUES (744, 63, 110, 1);
INSERT INTO `producto_extra` VALUES (745, 63, 106, 1);
INSERT INTO `producto_extra` VALUES (746, 63, 115, 1);
INSERT INTO `producto_extra` VALUES (747, 63, 108, 1);
INSERT INTO `producto_extra` VALUES (748, 63, 107, 1);
INSERT INTO `producto_extra` VALUES (749, 63, 112, 1);
INSERT INTO `producto_extra` VALUES (750, 63, 113, 1);
INSERT INTO `producto_extra` VALUES (751, 64, 105, 1);
INSERT INTO `producto_extra` VALUES (752, 64, 123, 1);
INSERT INTO `producto_extra` VALUES (753, 64, 111, 1);
INSERT INTO `producto_extra` VALUES (754, 64, 114, 1);
INSERT INTO `producto_extra` VALUES (755, 64, 110, 1);
INSERT INTO `producto_extra` VALUES (756, 64, 106, 1);
INSERT INTO `producto_extra` VALUES (757, 64, 115, 1);
INSERT INTO `producto_extra` VALUES (758, 64, 108, 1);
INSERT INTO `producto_extra` VALUES (759, 64, 107, 1);
INSERT INTO `producto_extra` VALUES (760, 64, 112, 1);
INSERT INTO `producto_extra` VALUES (761, 64, 113, 1);
INSERT INTO `producto_extra` VALUES (762, 65, 105, 1);
INSERT INTO `producto_extra` VALUES (763, 65, 123, 1);
INSERT INTO `producto_extra` VALUES (764, 65, 111, 1);
INSERT INTO `producto_extra` VALUES (765, 65, 114, 1);
INSERT INTO `producto_extra` VALUES (766, 65, 110, 1);
INSERT INTO `producto_extra` VALUES (767, 65, 106, 1);
INSERT INTO `producto_extra` VALUES (768, 65, 115, 1);
INSERT INTO `producto_extra` VALUES (769, 65, 108, 1);
INSERT INTO `producto_extra` VALUES (770, 65, 107, 1);
INSERT INTO `producto_extra` VALUES (771, 65, 112, 1);
INSERT INTO `producto_extra` VALUES (772, 65, 113, 1);
INSERT INTO `producto_extra` VALUES (773, 66, 105, 1);
INSERT INTO `producto_extra` VALUES (774, 66, 123, 1);
INSERT INTO `producto_extra` VALUES (775, 66, 111, 1);
INSERT INTO `producto_extra` VALUES (776, 66, 114, 1);
INSERT INTO `producto_extra` VALUES (777, 66, 110, 1);
INSERT INTO `producto_extra` VALUES (778, 66, 106, 1);
INSERT INTO `producto_extra` VALUES (779, 66, 115, 1);
INSERT INTO `producto_extra` VALUES (780, 66, 108, 1);
INSERT INTO `producto_extra` VALUES (781, 66, 107, 1);
INSERT INTO `producto_extra` VALUES (782, 66, 112, 1);
INSERT INTO `producto_extra` VALUES (783, 66, 113, 1);
INSERT INTO `producto_extra` VALUES (784, 67, 105, 1);
INSERT INTO `producto_extra` VALUES (785, 67, 123, 1);
INSERT INTO `producto_extra` VALUES (786, 67, 111, 1);
INSERT INTO `producto_extra` VALUES (787, 67, 114, 1);
INSERT INTO `producto_extra` VALUES (788, 67, 110, 1);
INSERT INTO `producto_extra` VALUES (789, 67, 106, 1);
INSERT INTO `producto_extra` VALUES (790, 67, 115, 1);
INSERT INTO `producto_extra` VALUES (791, 67, 108, 1);
INSERT INTO `producto_extra` VALUES (792, 67, 107, 1);
INSERT INTO `producto_extra` VALUES (793, 67, 112, 1);
INSERT INTO `producto_extra` VALUES (794, 67, 113, 1);
INSERT INTO `producto_extra` VALUES (795, 68, 105, 1);
INSERT INTO `producto_extra` VALUES (796, 68, 123, 1);
INSERT INTO `producto_extra` VALUES (797, 68, 111, 1);
INSERT INTO `producto_extra` VALUES (798, 68, 114, 1);
INSERT INTO `producto_extra` VALUES (799, 68, 110, 1);
INSERT INTO `producto_extra` VALUES (800, 68, 106, 1);
INSERT INTO `producto_extra` VALUES (801, 68, 115, 1);
INSERT INTO `producto_extra` VALUES (802, 68, 108, 1);
INSERT INTO `producto_extra` VALUES (803, 68, 107, 1);
INSERT INTO `producto_extra` VALUES (804, 68, 112, 1);
INSERT INTO `producto_extra` VALUES (805, 68, 113, 1);
INSERT INTO `producto_extra` VALUES (806, 69, 105, 1);
INSERT INTO `producto_extra` VALUES (807, 69, 123, 1);
INSERT INTO `producto_extra` VALUES (808, 69, 111, 1);
INSERT INTO `producto_extra` VALUES (809, 69, 114, 1);
INSERT INTO `producto_extra` VALUES (810, 69, 110, 1);
INSERT INTO `producto_extra` VALUES (811, 69, 106, 1);
INSERT INTO `producto_extra` VALUES (812, 69, 115, 1);
INSERT INTO `producto_extra` VALUES (813, 69, 108, 1);
INSERT INTO `producto_extra` VALUES (814, 69, 107, 1);
INSERT INTO `producto_extra` VALUES (815, 69, 112, 1);
INSERT INTO `producto_extra` VALUES (816, 69, 113, 1);
INSERT INTO `producto_extra` VALUES (817, 70, 105, 1);
INSERT INTO `producto_extra` VALUES (818, 70, 123, 1);
INSERT INTO `producto_extra` VALUES (819, 70, 111, 1);
INSERT INTO `producto_extra` VALUES (820, 70, 114, 1);
INSERT INTO `producto_extra` VALUES (821, 70, 110, 1);
INSERT INTO `producto_extra` VALUES (822, 70, 106, 1);
INSERT INTO `producto_extra` VALUES (823, 70, 115, 1);
INSERT INTO `producto_extra` VALUES (824, 70, 108, 1);
INSERT INTO `producto_extra` VALUES (825, 70, 107, 1);
INSERT INTO `producto_extra` VALUES (826, 70, 112, 1);
INSERT INTO `producto_extra` VALUES (827, 70, 113, 1);
INSERT INTO `producto_extra` VALUES (828, 71, 105, 1);
INSERT INTO `producto_extra` VALUES (829, 71, 123, 1);
INSERT INTO `producto_extra` VALUES (830, 71, 111, 1);
INSERT INTO `producto_extra` VALUES (831, 71, 114, 1);
INSERT INTO `producto_extra` VALUES (832, 71, 110, 1);
INSERT INTO `producto_extra` VALUES (833, 71, 106, 1);
INSERT INTO `producto_extra` VALUES (834, 71, 115, 1);
INSERT INTO `producto_extra` VALUES (835, 71, 108, 1);
INSERT INTO `producto_extra` VALUES (836, 71, 107, 1);
INSERT INTO `producto_extra` VALUES (837, 71, 112, 1);
INSERT INTO `producto_extra` VALUES (838, 71, 113, 1);
INSERT INTO `producto_extra` VALUES (839, 72, 105, 1);
INSERT INTO `producto_extra` VALUES (840, 72, 123, 1);
INSERT INTO `producto_extra` VALUES (841, 72, 111, 1);
INSERT INTO `producto_extra` VALUES (842, 72, 114, 1);
INSERT INTO `producto_extra` VALUES (843, 72, 110, 1);
INSERT INTO `producto_extra` VALUES (844, 72, 106, 1);
INSERT INTO `producto_extra` VALUES (845, 72, 115, 1);
INSERT INTO `producto_extra` VALUES (846, 72, 108, 1);
INSERT INTO `producto_extra` VALUES (847, 72, 107, 1);
INSERT INTO `producto_extra` VALUES (848, 72, 112, 1);
INSERT INTO `producto_extra` VALUES (849, 72, 113, 1);
INSERT INTO `producto_extra` VALUES (850, 73, 105, 1);
INSERT INTO `producto_extra` VALUES (851, 73, 123, 1);
INSERT INTO `producto_extra` VALUES (852, 73, 111, 1);
INSERT INTO `producto_extra` VALUES (853, 73, 114, 1);
INSERT INTO `producto_extra` VALUES (854, 73, 110, 1);
INSERT INTO `producto_extra` VALUES (855, 73, 106, 1);
INSERT INTO `producto_extra` VALUES (856, 73, 115, 1);
INSERT INTO `producto_extra` VALUES (857, 73, 108, 1);
INSERT INTO `producto_extra` VALUES (858, 73, 107, 1);
INSERT INTO `producto_extra` VALUES (859, 73, 112, 1);
INSERT INTO `producto_extra` VALUES (860, 73, 113, 1);
INSERT INTO `producto_extra` VALUES (861, 74, 105, 1);
INSERT INTO `producto_extra` VALUES (862, 74, 123, 1);
INSERT INTO `producto_extra` VALUES (863, 74, 111, 1);
INSERT INTO `producto_extra` VALUES (864, 74, 114, 1);
INSERT INTO `producto_extra` VALUES (865, 74, 110, 1);
INSERT INTO `producto_extra` VALUES (866, 74, 106, 1);
INSERT INTO `producto_extra` VALUES (867, 74, 115, 1);
INSERT INTO `producto_extra` VALUES (868, 74, 108, 1);
INSERT INTO `producto_extra` VALUES (869, 74, 107, 1);
INSERT INTO `producto_extra` VALUES (870, 74, 112, 1);
INSERT INTO `producto_extra` VALUES (871, 74, 113, 1);
INSERT INTO `producto_extra` VALUES (872, 75, 105, 1);
INSERT INTO `producto_extra` VALUES (873, 75, 123, 1);
INSERT INTO `producto_extra` VALUES (874, 75, 111, 1);
INSERT INTO `producto_extra` VALUES (875, 75, 114, 1);
INSERT INTO `producto_extra` VALUES (876, 75, 110, 1);
INSERT INTO `producto_extra` VALUES (877, 75, 106, 1);
INSERT INTO `producto_extra` VALUES (878, 75, 115, 1);
INSERT INTO `producto_extra` VALUES (879, 75, 108, 1);
INSERT INTO `producto_extra` VALUES (880, 75, 107, 1);
INSERT INTO `producto_extra` VALUES (881, 75, 112, 1);
INSERT INTO `producto_extra` VALUES (882, 75, 113, 1);
INSERT INTO `producto_extra` VALUES (883, 76, 105, 1);
INSERT INTO `producto_extra` VALUES (884, 76, 123, 1);
INSERT INTO `producto_extra` VALUES (885, 76, 111, 1);
INSERT INTO `producto_extra` VALUES (886, 76, 114, 1);
INSERT INTO `producto_extra` VALUES (887, 76, 110, 1);
INSERT INTO `producto_extra` VALUES (888, 76, 106, 1);
INSERT INTO `producto_extra` VALUES (889, 76, 115, 1);
INSERT INTO `producto_extra` VALUES (890, 76, 108, 1);
INSERT INTO `producto_extra` VALUES (891, 76, 107, 1);
INSERT INTO `producto_extra` VALUES (892, 76, 112, 1);
INSERT INTO `producto_extra` VALUES (893, 76, 113, 1);
INSERT INTO `producto_extra` VALUES (894, 77, 105, 1);
INSERT INTO `producto_extra` VALUES (895, 77, 123, 1);
INSERT INTO `producto_extra` VALUES (896, 77, 111, 1);
INSERT INTO `producto_extra` VALUES (897, 77, 114, 1);
INSERT INTO `producto_extra` VALUES (898, 77, 110, 1);
INSERT INTO `producto_extra` VALUES (899, 77, 106, 1);
INSERT INTO `producto_extra` VALUES (900, 77, 115, 1);
INSERT INTO `producto_extra` VALUES (901, 77, 108, 1);
INSERT INTO `producto_extra` VALUES (902, 77, 107, 1);
INSERT INTO `producto_extra` VALUES (903, 77, 112, 1);
INSERT INTO `producto_extra` VALUES (904, 77, 113, 1);
INSERT INTO `producto_extra` VALUES (905, 78, 105, 1);
INSERT INTO `producto_extra` VALUES (906, 78, 123, 1);
INSERT INTO `producto_extra` VALUES (907, 78, 111, 1);
INSERT INTO `producto_extra` VALUES (908, 78, 114, 1);
INSERT INTO `producto_extra` VALUES (909, 78, 110, 1);
INSERT INTO `producto_extra` VALUES (910, 78, 106, 1);
INSERT INTO `producto_extra` VALUES (911, 78, 115, 1);
INSERT INTO `producto_extra` VALUES (912, 78, 108, 1);
INSERT INTO `producto_extra` VALUES (913, 78, 107, 1);
INSERT INTO `producto_extra` VALUES (914, 78, 112, 1);
INSERT INTO `producto_extra` VALUES (915, 78, 113, 1);
INSERT INTO `producto_extra` VALUES (916, 79, 105, 1);
INSERT INTO `producto_extra` VALUES (917, 79, 123, 1);
INSERT INTO `producto_extra` VALUES (918, 79, 111, 1);
INSERT INTO `producto_extra` VALUES (919, 79, 114, 1);
INSERT INTO `producto_extra` VALUES (920, 79, 110, 1);
INSERT INTO `producto_extra` VALUES (921, 79, 106, 1);
INSERT INTO `producto_extra` VALUES (922, 79, 115, 1);
INSERT INTO `producto_extra` VALUES (923, 79, 108, 1);
INSERT INTO `producto_extra` VALUES (924, 79, 107, 1);
INSERT INTO `producto_extra` VALUES (925, 79, 112, 1);
INSERT INTO `producto_extra` VALUES (926, 79, 113, 1);
INSERT INTO `producto_extra` VALUES (927, 80, 105, 1);
INSERT INTO `producto_extra` VALUES (928, 80, 123, 1);
INSERT INTO `producto_extra` VALUES (929, 80, 111, 1);
INSERT INTO `producto_extra` VALUES (930, 80, 114, 1);
INSERT INTO `producto_extra` VALUES (931, 80, 110, 1);
INSERT INTO `producto_extra` VALUES (932, 80, 106, 1);
INSERT INTO `producto_extra` VALUES (933, 80, 115, 1);
INSERT INTO `producto_extra` VALUES (934, 80, 108, 1);
INSERT INTO `producto_extra` VALUES (935, 80, 107, 1);
INSERT INTO `producto_extra` VALUES (936, 80, 112, 1);
INSERT INTO `producto_extra` VALUES (937, 80, 113, 1);
INSERT INTO `producto_extra` VALUES (938, 81, 105, 1);
INSERT INTO `producto_extra` VALUES (939, 81, 123, 1);
INSERT INTO `producto_extra` VALUES (940, 81, 111, 1);
INSERT INTO `producto_extra` VALUES (941, 81, 114, 1);
INSERT INTO `producto_extra` VALUES (942, 81, 110, 1);
INSERT INTO `producto_extra` VALUES (943, 81, 106, 1);
INSERT INTO `producto_extra` VALUES (944, 81, 115, 1);
INSERT INTO `producto_extra` VALUES (945, 81, 108, 1);
INSERT INTO `producto_extra` VALUES (946, 81, 107, 1);
INSERT INTO `producto_extra` VALUES (947, 81, 112, 1);
INSERT INTO `producto_extra` VALUES (948, 81, 113, 1);
INSERT INTO `producto_extra` VALUES (949, 154, 104, 1);
INSERT INTO `producto_extra` VALUES (950, 154, 103, 1);
INSERT INTO `producto_extra` VALUES (951, 154, 105, 1);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `refresh`
-- 

CREATE TABLE `refresh` (
  `r_productos` varchar(100) collate utf8_spanish_ci NOT NULL,
  `r_venta` varchar(100) collate utf8_spanish_ci NOT NULL,
  `r_clientes` varchar(100) collate utf8_spanish_ci default NULL,
  `r_actualiza` varchar(100) collate utf8_spanish_ci default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `refresh`
-- 

INSERT INTO `refresh` VALUES ('0501ebda173fbc5f1351522bfdd1adf0', '0501ebda173fbc5f1351522bfdd1adf0', 'AS222FAasdasSF23', 'Faasd222aSA23');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_usuario`
-- 

CREATE TABLE `tipo_usuario` (
  `id_tipo_usuario` smallint(6) NOT NULL,
  `tipo` varchar(20) collate utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `tipo_usuario`
-- 

INSERT INTO `tipo_usuario` VALUES (1, 'Administrador');
INSERT INTO `tipo_usuario` VALUES (2, 'Vendedor');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `unidades`
-- 

CREATE TABLE `unidades` (
  `id_unidad` int(11) NOT NULL auto_increment,
  `unidad` varchar(150) NOT NULL,
  `abreviatura` varchar(6) NOT NULL,
  PRIMARY KEY  (`id_unidad`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Volcar la base de datos para la tabla `unidades`
-- 

INSERT INTO `unidades` VALUES (1, 'GRAMOS', 'G');
INSERT INTO `unidades` VALUES (2, 'PIEZAS', 'PZS');
INSERT INTO `unidades` VALUES (3, 'Unidad', 'uds.');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `usuarios`
-- 

CREATE TABLE `usuarios` (
  `id_usuario` mediumint(2) NOT NULL auto_increment,
  `id_tipo_usuario` int(1) NOT NULL,
  `nombre` varchar(64) collate utf8_spanish_ci NOT NULL default '',
  `usuario` varchar(24) collate utf8_spanish_ci NOT NULL default '',
  `contrasena` varchar(128) collate utf8_spanish_ci NOT NULL default '',
  `cortes` tinyint(1) NOT NULL,
  `ultimo_acceso` datetime default NULL,
  `activo` tinyint(1) NOT NULL default '1',
  `devoluciones` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

-- 
-- Volcar la base de datos para la tabla `usuarios`
-- 

INSERT INTO `usuarios` VALUES (1, 1, 'Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, '2019-09-30 14:21:06', 1, 0);
INSERT INTO `usuarios` VALUES (2, 2, 'Vendedor', 'Vendedor', 'c4ca4238a0b923820dcc509a6f75849b', 1, NULL, 1, 0);
INSERT INTO `usuarios` VALUES (3, 2, 'Yasuri Baeza', 'Caja', '936400f151ba2146a86cfcc342279f57', 1, '2019-09-26 19:19:27', 1, 1);
INSERT INTO `usuarios` VALUES (4, 1, 'Argenis', 'Argenis', '29d1e2357d7c14db51e885053a58ec67', 0, NULL, 1, 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ventas`
-- 

CREATE TABLE `ventas` (
  `id_venta` int(10) unsigned NOT NULL auto_increment,
  `id_usuario` int(2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_corte` int(11) NOT NULL default '0',
  `mesa` varchar(10) collate utf8_spanish_ci NOT NULL default '',
  `abierta` tinyint(1) NOT NULL default '1',
  `pagada` tinyint(1) NOT NULL default '0',
  `fechahora_cerrada` datetime NOT NULL,
  `fechahora_pagada` datetime NOT NULL,
  `id_metodo` int(11) NOT NULL,
  `num_cta` varchar(4) collate utf8_spanish_ci NOT NULL default '',
  `facturado` int(11) NOT NULL,
  `monto_facturado` decimal(10,2) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `reabierta` int(1) NOT NULL default '0',
  `codigo` varchar(30) collate utf8_spanish_ci NOT NULL,
  `metodo_txt` varchar(30) collate utf8_spanish_ci NOT NULL,
  `recibe_txt` decimal(10,2) NOT NULL,
  `cambio_txt` decimal(10,2) NOT NULL,
  `pendiente_facturar` tinyint(4) NOT NULL,
  `pendiente_monto` decimal(10,2) NOT NULL,
  `descuento_txt` int(2) NOT NULL,
  `DescEfec_txt` decimal(10,2) NOT NULL,
  `pagarOriginal` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=72 ;

-- 
-- Volcar la base de datos para la tabla `ventas`
-- 

INSERT INTO `ventas` VALUES (50, 1, '2019-10-02', '14:53:49', 1, 'BARRA', 0, 1, '2019-10-02 14:53:49', '2019-10-02 15:09:49', 1, '', 1, 8.80, 63.80, 0, '', 'EFECTIVO', 100.00, 36.20, 0, 0.00, 0, 0.00, 55.00);
INSERT INTO `ventas` VALUES (51, 1, '2019-10-02', '14:58:59', 1, 'BARRA', 0, 1, '2019-10-02 14:58:59', '2019-10-02 15:13:08', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 100.00, 47.80, 0, 0.00, 1, 9.00, 45.00);
INSERT INTO `ventas` VALUES (52, 1, '2019-10-02', '15:00:49', 1, 'BARRA', 0, 1, '2019-10-02 15:00:49', '2019-10-02 15:13:20', 1, '', 1, 8.80, 63.80, 0, '', 'EFECTIVO', 200.00, 136.20, 0, 0.00, 2, 13.75, 55.00);
INSERT INTO `ventas` VALUES (53, 1, '2019-10-02', '15:01:32', 1, 'BARRA', 0, 1, '2019-10-02 15:01:32', '2019-10-02 15:01:37', 1, '', 1, 8.80, 63.80, 0, '', 'EFECTIVO', 100.00, 36.20, 0, 0.00, 0, 0.00, 55.00);
INSERT INTO `ventas` VALUES (54, 1, '2019-10-02', '15:20:51', 2, 'BARRA', 0, 1, '2019-10-02 15:20:51', '2019-10-02 15:22:13', 1, '', 0, 0.00, 44.00, 0, '', 'EFECTIVO', 100.00, 56.00, 0, 0.00, 1, 11.00, 55.00);
INSERT INTO `ventas` VALUES (55, 1, '2019-10-02', '15:22:32', 2, 'BARRA', 0, 1, '2019-10-02 15:22:32', '2019-10-02 15:22:55', 1, '', 1, 5.76, 41.76, 0, '', 'EFECTIVO', 100.00, 58.24, 0, 0.00, 1, 9.00, 45.00);
INSERT INTO `ventas` VALUES (56, 1, '2019-10-02', '15:43:12', 0, 'BARRA', 0, 1, '2019-10-02 15:43:12', '2019-10-02 15:43:19', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 100.00, 47.80, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (57, 1, '2019-10-02', '15:46:01', 0, 'BARRA', 0, 1, '2019-10-02 15:46:01', '2019-10-02 15:46:15', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 100.00, 47.80, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (58, 1, '2019-10-02', '15:46:45', 0, 'BARRA', 0, 1, '2019-10-02 15:46:45', '2019-10-02 15:46:50', 1, '', 0, 0.00, 18.00, 0, '', 'EFECTIVO', 100.00, 82.00, 0, 0.00, 0, 0.00, 18.00);
INSERT INTO `ventas` VALUES (59, 1, '2019-10-02', '15:48:58', 0, '10', 0, 1, '0000-00-00 00:00:00', '2019-10-02 15:49:02', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 100.00, 47.80, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (60, 1, '2019-10-02', '15:50:33', 0, 'BARRA', 0, 1, '2019-10-02 15:50:33', '2019-10-02 15:50:40', 1, '', 0, 0.00, 55.00, 0, '', 'EFECTIVO', 100.00, 45.00, 0, 0.00, 0, 0.00, 55.00);
INSERT INTO `ventas` VALUES (61, 1, '2019-10-02', '15:51:20', 0, 'BARRA', 0, 1, '2019-10-02 15:51:20', '2019-10-02 15:51:23', 1, '', 0, 0.00, 45.00, 0, '', 'EFECTIVO', 100.00, 55.00, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (62, 1, '2019-10-02', '15:52:09', 0, '10', 0, 1, '0000-00-00 00:00:00', '2019-10-02 15:52:12', 1, '', 0, 0.00, 18.00, 0, '', 'EFECTIVO', 100.00, 82.00, 0, 0.00, 0, 0.00, 18.00);
INSERT INTO `ventas` VALUES (63, 1, '2019-10-02', '15:54:35', 0, '100', 0, 1, '0000-00-00 00:00:00', '2019-10-02 15:54:59', 1, '', 1, 2.88, 20.88, 0, '', 'EFECTIVO', 21.00, 0.12, 0, 0.00, 0, 0.00, 18.00);
INSERT INTO `ventas` VALUES (64, 1, '2019-10-02', '15:55:56', 0, 'BARRA', 0, 1, '2019-10-02 15:55:56', '2019-10-02 15:56:11', 1, '', 1, 2.88, 20.88, 0, '', 'EFECTIVO', 22.00, 1.12, 0, 0.00, 0, 0.00, 18.00);
INSERT INTO `ventas` VALUES (65, 1, '2019-10-02', '15:56:50', 0, 'BARRA', 0, 1, '2019-10-02 15:56:50', '2019-10-02 15:56:54', 1, '', 0, 0.00, 45.00, 0, '', 'EFECTIVO', 50.00, 5.00, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (66, 1, '2019-10-02', '15:58:31', 0, 'BARRA', 0, 1, '2019-10-02 15:58:31', '2019-10-02 15:58:38', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 100.00, 47.80, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (67, 1, '2019-10-02', '16:01:38', 0, 'BARRA', 0, 1, '2019-10-02 16:01:38', '2019-10-02 16:01:47', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 100.00, 47.80, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (68, 1, '2019-10-02', '16:03:05', 0, 'BARRA', 0, 1, '2019-10-02 16:03:05', '2019-10-02 16:03:17', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 54.00, 1.80, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (69, 1, '2019-10-02', '16:04:53', 0, 'BARRA', 0, 1, '2019-10-02 16:04:53', '2019-10-02 16:04:58', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 100.00, 47.80, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (70, 1, '2019-10-02', '16:09:24', 0, 'BARRA', 0, 1, '2019-10-02 16:09:24', '2019-10-02 16:09:29', 1, '', 1, 7.20, 52.20, 0, '', 'EFECTIVO', 100.00, 47.80, 0, 0.00, 0, 0.00, 45.00);
INSERT INTO `ventas` VALUES (71, 1, '2019-10-02', '16:10:07', 0, 'BARRA', 0, 1, '2019-10-02 16:10:07', '2019-10-02 16:10:13', 1, '', 1, 8.80, 63.80, 0, '', 'EFECTIVO', 100.00, 36.20, 0, 0.00, 0, 0.00, 55.00);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ventas_cancelaciones`
-- 

CREATE TABLE `ventas_cancelaciones` (
  `id_venta` int(11) NOT NULL,
  `id_usuario_cancelador` int(11) NOT NULL,
  `fechahora_cancelacion` datetime NOT NULL,
  `motivo` varchar(255) collate utf8_spanish_ci default NULL,
  `id_venta_cancelacion` int(11) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id_venta_cancelacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `ventas_cancelaciones`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ventas_cancelaciones_detalle`
-- 

CREATE TABLE `ventas_cancelaciones_detalle` (
  `id_detalle` int(11) unsigned NOT NULL auto_increment,
  `id_venta_cancelacion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` varchar(10) collate utf8_spanish_ci NOT NULL default '',
  `precio_venta` decimal(10,2) NOT NULL,
  `id_venta` int(11) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `ventas_cancelaciones_detalle`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `venta_detalle`
-- 

CREATE TABLE `venta_detalle` (
  `id_detalle` bigint(20) NOT NULL auto_increment,
  `id_venta` int(10) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` decimal(10,0) NOT NULL,
  `precio_venta` decimal(8,2) NOT NULL,
  `comentarios` varchar(255) collate utf8_spanish_ci NOT NULL,
  `tipo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_detalle`),
  KEY `id_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=180 ;

-- 
-- Volcar la base de datos para la tabla `venta_detalle`
-- 

INSERT INTO `venta_detalle` VALUES (1, 1, 87, 1, 38.00, '', 0);
INSERT INTO `venta_detalle` VALUES (2, 1, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (3, 2, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (4, 2, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (5, 3, 151, 1, 155.00, '', 0);
INSERT INTO `venta_detalle` VALUES (6, 3, 90, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (7, 3, 91, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (8, 3, 152, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (9, 3, 26, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (10, 4, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (11, 4, 107, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (12, 4, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (13, 5, 151, 1, 155.00, '', 0);
INSERT INTO `venta_detalle` VALUES (14, 5, 90, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (15, 5, 91, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (16, 5, 152, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (17, 5, 26, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (18, 6, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (19, 6, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (20, 7, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (21, 7, 112, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (22, 7, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (23, 8, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (24, 8, 107, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (25, 8, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (26, 9, 18, 1, 18.00, '', 0);
INSERT INTO `venta_detalle` VALUES (27, 10, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (28, 10, 113, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (29, 10, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (30, 11, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (31, 11, 111, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (32, 11, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (33, 12, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (34, 12, 108, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (35, 12, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (36, 13, 151, 1, 155.00, '', 0);
INSERT INTO `venta_detalle` VALUES (37, 13, 90, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (38, 13, 91, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (39, 13, 152, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (40, 13, 26, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (41, 14, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (42, 14, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (43, 15, 151, 1, 155.00, '', 0);
INSERT INTO `venta_detalle` VALUES (44, 15, 90, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (45, 15, 91, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (46, 15, 152, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (47, 15, 26, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (48, 16, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (49, 16, 112, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (50, 16, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (51, 17, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (52, 17, 111, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (53, 17, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (54, 18, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (55, 18, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (56, 19, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (57, 19, 112, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (58, 19, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (59, 20, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (60, 20, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (61, 21, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (62, 21, 115, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (63, 21, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (64, 22, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (65, 22, 107, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (66, 22, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (67, 23, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (68, 23, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (69, 24, 151, 1, 155.00, '', 0);
INSERT INTO `venta_detalle` VALUES (70, 24, 90, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (71, 24, 91, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (72, 24, 152, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (73, 24, 26, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (74, 25, 151, 2, 155.00, '', 0);
INSERT INTO `venta_detalle` VALUES (75, 25, 90, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (76, 25, 91, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (77, 25, 152, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (78, 25, 26, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (79, 26, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (80, 26, 115, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (81, 26, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (82, 27, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (83, 27, 115, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (84, 27, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (85, 28, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (86, 28, 112, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (87, 28, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (88, 29, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (89, 29, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (90, 30, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (91, 30, 113, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (92, 30, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (93, 31, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (94, 31, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (95, 32, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (96, 32, 107, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (97, 32, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (98, 33, 135, 1, 99.00, '', 0);
INSERT INTO `venta_detalle` VALUES (99, 33, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (100, 34, 135, 1, 99.00, '', 0);
INSERT INTO `venta_detalle` VALUES (101, 34, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (102, 35, 135, 1, 99.00, '', 0);
INSERT INTO `venta_detalle` VALUES (103, 35, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (104, 36, 135, 1, 99.00, '', 0);
INSERT INTO `venta_detalle` VALUES (105, 36, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (106, 37, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (107, 37, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (108, 38, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (109, 38, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (110, 39, 132, 1, 95.00, '', 0);
INSERT INTO `venta_detalle` VALUES (111, 39, 107, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (112, 39, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (113, 40, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (114, 40, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (115, 41, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (116, 41, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (117, 42, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (118, 42, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (119, 43, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (120, 43, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (121, 44, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (122, 44, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (123, 45, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (124, 45, 107, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (125, 45, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (126, 46, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (127, 46, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (128, 47, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (129, 47, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (130, 48, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (131, 48, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (132, 49, 134, 1, 89.00, '', 0);
INSERT INTO `venta_detalle` VALUES (133, 49, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (134, 50, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (135, 50, 113, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (136, 50, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (137, 51, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (138, 51, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (139, 52, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (140, 52, 112, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (141, 52, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (142, 53, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (143, 53, 113, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (144, 53, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (145, 54, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (146, 54, 113, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (147, 54, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (148, 55, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (149, 55, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (150, 56, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (151, 56, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (152, 57, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (153, 57, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (154, 58, 17, 1, 18.00, '', 0);
INSERT INTO `venta_detalle` VALUES (155, 59, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (156, 59, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (157, 60, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (158, 60, 113, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (159, 60, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (160, 61, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (161, 61, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (162, 62, 17, 1, 18.00, '', 0);
INSERT INTO `venta_detalle` VALUES (163, 63, 18, 1, 18.00, '', 0);
INSERT INTO `venta_detalle` VALUES (164, 64, 17, 1, 18.00, '', 0);
INSERT INTO `venta_detalle` VALUES (165, 65, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (166, 65, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (167, 66, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (168, 66, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (169, 67, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (170, 67, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (171, 68, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (172, 68, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (173, 69, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (174, 69, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (175, 70, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (176, 70, 0, 1, 0.00, '', 0);
INSERT INTO `venta_detalle` VALUES (177, 71, 79, 1, 45.00, '', 0);
INSERT INTO `venta_detalle` VALUES (178, 71, 113, 1, 10.00, '', 0);
INSERT INTO `venta_detalle` VALUES (179, 71, 0, 1, 0.00, '', 0);
