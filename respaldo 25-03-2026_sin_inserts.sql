-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- VersiÃ³n del servidor:         5.0.51b-community-nt-log - MySQL Community Edition (GPL)
-- SO del servidor:              Win32
-- HeidiSQL VersiÃ³n:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;




-- Volcando estructura para tabla vendefacil_5.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` mediumint(3) NOT NULL auto_increment,
  `nombre` varchar(20) collate utf8_spanish_ci NOT NULL default '',
  `impresora` varchar(64) collate utf8_spanish_ci NOT NULL,
  `activo` int(1) NOT NULL default '1',
  `ocultar` int(11) NOT NULL default '1',
  `es_paquete` tinyint(4) NOT NULL default '0',
  `alerta` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_categoria`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.categorias: 42 rows
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(11) unsigned NOT NULL auto_increment,
  `nombre` varchar(255) collate utf8_spanish_ci default NULL,
  `telefono` varchar(10) collate utf8_spanish_ci default NULL,
  `email` varchar(255) collate utf8_spanish_ci default NULL,
  `genero` varchar(2) collate utf8_spanish_ci default NULL,
  `fecha_nacimiento` date default NULL,
  `fechahora_alta` datetime default NULL,
  PRIMARY KEY  (`id_cliente`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.clientes: 3 rows
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.codigospostales
CREATE TABLE IF NOT EXISTS `codigospostales` (
  `CodigoPostal` int(11) NOT NULL,
  `Colonia` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `Municipio` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `Estado` text character set utf8 collate utf8_unicode_ci NOT NULL,
  KEY `CodigoPostal` (`CodigoPostal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.codigospostales: 32,030 rows
/*!40000 ALTER TABLE `codigospostales` DISABLE KEYS */;
/*!40000 ALTER TABLE `codigospostales` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.configuracion
CREATE TABLE IF NOT EXISTS `configuracion` (
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
  `insumos` tinyint(4) NOT NULL default '0',
  `ajustes_facturacion` tinyint(4) NOT NULL default '0',
  `sucursal` int(11) NOT NULL default '1',
  UNIQUE KEY `header_2` (`header_2`),
  UNIQUE KEY `header_2_2` (`header_2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.configuracion: 1 rows
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.cortes
CREATE TABLE IF NOT EXISTS `cortes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=746 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.cortes: 745 rows
/*!40000 ALTER TABLE `cortes` DISABLE KEYS */;
/*!40000 ALTER TABLE `cortes` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.cortes_domicilio
CREATE TABLE IF NOT EXISTS `cortes_domicilio` (
  `id_corte_domicilio` bigint(20) NOT NULL auto_increment,
  `id_usuario` int(11) NOT NULL,
  `fechahora` datetime NOT NULL,
  PRIMARY KEY  (`id_corte_domicilio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.cortes_domicilio: 0 rows
/*!40000 ALTER TABLE `cortes_domicilio` DISABLE KEYS */;
/*!40000 ALTER TABLE `cortes_domicilio` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.cupones
CREATE TABLE IF NOT EXISTS `cupones` (
  `id_cupon` int(2) NOT NULL auto_increment,
  `cupon` varchar(60) NOT NULL,
  `porcentaje` varchar(3) NOT NULL,
  `activo` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_cupon`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.cupones: 8 rows
/*!40000 ALTER TABLE `cupones` DISABLE KEYS */;
/*!40000 ALTER TABLE `cupones` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.domicilio
CREATE TABLE IF NOT EXISTS `domicilio` (
  `id_domicilio` int(11) unsigned NOT NULL auto_increment,
  `numero` varchar(10) character set utf8 collate utf8_spanish_ci default NULL,
  `nombre` varchar(64) character set utf8 collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id_domicilio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.domicilio: 0 rows
/*!40000 ALTER TABLE `domicilio` DISABLE KEYS */;
/*!40000 ALTER TABLE `domicilio` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.domicilio_direcciones
CREATE TABLE IF NOT EXISTS `domicilio_direcciones` (
  `id_domicilio_direccion` int(11) unsigned NOT NULL auto_increment,
  `id_domicilio` int(11) default NULL,
  `direccion` text,
  `costo` varchar(2) default NULL,
  PRIMARY KEY  (`id_domicilio_direccion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.domicilio_direcciones: 0 rows
/*!40000 ALTER TABLE `domicilio_direcciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `domicilio_direcciones` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.dotaciones
CREATE TABLE IF NOT EXISTS `dotaciones` (
  `id_dotacion` bigint(20) NOT NULL auto_increment,
  `id_usuario` int(2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time default NULL,
  `activo` int(1) NOT NULL,
  `comentario` text collate utf8_spanish_ci,
  `id_corte` int(11) default '0',
  `contar` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id_dotacion`)
) ENGINE=MyISAM AUTO_INCREMENT=930 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.dotaciones: 673 rows
/*!40000 ALTER TABLE `dotaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `dotaciones` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.dotaciones_detalle
CREATE TABLE IF NOT EXISTS `dotaciones_detalle` (
  `id_detalle` bigint(20) NOT NULL auto_increment,
  `id_dotacion` bigint(20) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` varchar(300) collate utf8_spanish_ci NOT NULL,
  `activo` int(1) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=MyISAM AUTO_INCREMENT=2946 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.dotaciones_detalle: 2,257 rows
/*!40000 ALTER TABLE `dotaciones_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `dotaciones_detalle` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.existecias
CREATE TABLE IF NOT EXISTS `existecias` (
  `id_base` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_corte` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.existecias: 49 rows
/*!40000 ALTER TABLE `existecias` DISABLE KEYS */;
/*!40000 ALTER TABLE `existecias` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.existencia
CREATE TABLE IF NOT EXISTS `existencia` (
  `id` int(11) default NULL,
  `texto` text,
  `contar` int(11) default '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.existencia: 743 rows
/*!40000 ALTER TABLE `existencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `existencia` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.gastos
CREATE TABLE IF NOT EXISTS `gastos` (
  `id_gasto` bigint(20) NOT NULL auto_increment,
  `id_corte` bigint(20) NOT NULL default '0',
  `id_usuario` int(11) NOT NULL,
  `descripcion` varchar(255) collate utf8_spanish_ci NOT NULL,
  `monto` decimal(8,2) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `provision` varchar(10) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id_gasto`)
) ENGINE=MyISAM AUTO_INCREMENT=10712 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.gastos: 10,358 rows
/*!40000 ALTER TABLE `gastos` DISABLE KEYS */;
/*!40000 ALTER TABLE `gastos` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.merma
CREATE TABLE IF NOT EXISTS `merma` (
  `id_merma` int(11) NOT NULL auto_increment,
  `id_usuario` mediumint(9) NOT NULL,
  `fecha` date NOT NULL,
  `activo` smallint(1) NOT NULL,
  `observaciones` text collate utf8_spanish_ci NOT NULL,
  `id_corte` int(11) default '0',
  `contar` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id_merma`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.merma: 0 rows
/*!40000 ALTER TABLE `merma` DISABLE KEYS */;
/*!40000 ALTER TABLE `merma` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.merma_detalle
CREATE TABLE IF NOT EXISTS `merma_detalle` (
  `id_detalle` int(11) NOT NULL auto_increment,
  `id_merma` int(11) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `activo` smallint(1) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.merma_detalle: 0 rows
/*!40000 ALTER TABLE `merma_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `merma_detalle` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.metodo_pago
CREATE TABLE IF NOT EXISTS `metodo_pago` (
  `id_metodo` int(11) unsigned NOT NULL auto_increment,
  `metodo_pago` varchar(32) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id_metodo`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.metodo_pago: 4 rows
/*!40000 ALTER TABLE `metodo_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `metodo_pago` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.msn
CREATE TABLE IF NOT EXISTS `msn` (
  `id_msn` int(11) NOT NULL auto_increment,
  `id_usuario` varchar(45) default NULL,
  `id_tipo_usuario` varchar(45) default NULL,
  `numero` varchar(45) default NULL,
  `activo` varchar(45) default NULL,
  PRIMARY KEY  (`id_msn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.msn: 0 rows
/*!40000 ALTER TABLE `msn` DISABLE KEYS */;
/*!40000 ALTER TABLE `msn` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.productos
CREATE TABLE IF NOT EXISTS `productos` (
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
  `ingrediente` tinyint(4) NOT NULL default '0',
  `alerta` int(11) default NULL,
  `color` varchar(250) collate utf8_spanish_ci NOT NULL,
  `imagen` longblob,
  `ignorar` bigint(20) default NULL,
  `receta` longtext collate utf8_spanish_ci,
  PRIMARY KEY  (`id_producto`)
) ENGINE=MyISAM AUTO_INCREMENT=674 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.productos: 486 rows
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.productosxbase
CREATE TABLE IF NOT EXISTS `productosxbase` (
  `id_detalle` int(11) NOT NULL auto_increment,
  `id_base` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=MyISAM AUTO_INCREMENT=1018 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.productosxbase: 523 rows
/*!40000 ALTER TABLE `productosxbase` DISABLE KEYS */;
/*!40000 ALTER TABLE `productosxbase` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.productos_base
CREATE TABLE IF NOT EXISTS `productos_base` (
  `id_base` int(11) NOT NULL auto_increment,
  `producto` varchar(250) character set utf8 collate utf8_spanish_ci NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `precio` int(250) NOT NULL,
  `alerta` int(11) default NULL,
  PRIMARY KEY  (`id_base`)
) ENGINE=MyISAM AUTO_INCREMENT=197 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.productos_base: 91 rows
/*!40000 ALTER TABLE `productos_base` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_base` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.productos_paquete
CREATE TABLE IF NOT EXISTS `productos_paquete` (
  `id_detalle` int(11) NOT NULL auto_increment,
  `id_producto` int(11) NOT NULL,
  `id_paquete` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.productos_paquete: 17 rows
/*!40000 ALTER TABLE `productos_paquete` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_paquete` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.producto_extra
CREATE TABLE IF NOT EXISTS `producto_extra` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_producto` int(11) default NULL,
  `id_extra` int(11) default NULL,
  `nivel` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2291 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.producto_extra: 1,285 rows
/*!40000 ALTER TABLE `producto_extra` DISABLE KEYS */;
/*!40000 ALTER TABLE `producto_extra` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.refresh
CREATE TABLE IF NOT EXISTS `refresh` (
  `r_productos` varchar(100) collate utf8_spanish_ci NOT NULL,
  `r_venta` varchar(100) collate utf8_spanish_ci NOT NULL,
  `r_clientes` varchar(100) collate utf8_spanish_ci default NULL,
  `r_actualiza` varchar(100) collate utf8_spanish_ci default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.refresh: 1 rows
/*!40000 ALTER TABLE `refresh` DISABLE KEYS */;
/*!40000 ALTER TABLE `refresh` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.repartidores
CREATE TABLE IF NOT EXISTS `repartidores` (
  `id_repartidor` int(11) unsigned NOT NULL auto_increment,
  `nombre` varchar(64) collate utf8_spanish_ci default NULL,
  `telefono` varchar(10) collate utf8_spanish_ci default NULL,
  `activo` tinyint(1) default '1',
  PRIMARY KEY  (`id_repartidor`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.repartidores: 8 rows
/*!40000 ALTER TABLE `repartidores` DISABLE KEYS */;
/*!40000 ALTER TABLE `repartidores` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.sucursales
CREATE TABLE IF NOT EXISTS `sucursales` (
  `id_sucursal` tinyint(2) unsigned NOT NULL auto_increment,
  `sucursal` varchar(255) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id_sucursal`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.sucursales: 4 rows
/*!40000 ALTER TABLE `sucursales` DISABLE KEYS */;
/*!40000 ALTER TABLE `sucursales` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.tipo_usuario
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `id_tipo_usuario` smallint(6) NOT NULL,
  `tipo` varchar(20) collate utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.tipo_usuario: 3 rows
/*!40000 ALTER TABLE `tipo_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_usuario` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.unidades
CREATE TABLE IF NOT EXISTS `unidades` (
  `id_unidad` int(11) NOT NULL auto_increment,
  `unidad` varchar(150) NOT NULL,
  `abreviatura` varchar(6) NOT NULL,
  PRIMARY KEY  (`id_unidad`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.unidades: 3 rows
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;
/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
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
) ENGINE=MyISAM AUTO_INCREMENT=801 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.usuarios: 6 rows
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.ventas
CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` bigint(10) unsigned NOT NULL auto_increment,
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
  `codigo_activacion` varchar(16) collate utf8_spanish_ci default '0',
  `id_cliente` int(11) default '0',
  `id_sucursal` tinyint(2) default NULL,
  `id_venta_sucursal` bigint(20) default NULL,
  `contar` tinyint(4) NOT NULL default '1',
  `domicilio` tinyint(4) default NULL,
  PRIMARY KEY  (`id_venta`)
) ENGINE=MyISAM AUTO_INCREMENT=32198 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.ventas: 32,153 rows
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.ventas_cancelaciones
CREATE TABLE IF NOT EXISTS `ventas_cancelaciones` (
  `id_venta` int(11) NOT NULL,
  `id_usuario_cancelador` int(11) NOT NULL,
  `fechahora_cancelacion` datetime NOT NULL,
  `motivo` varchar(255) collate utf8_spanish_ci default NULL,
  `id_venta_cancelacion` int(11) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id_venta_cancelacion`)
) ENGINE=MyISAM AUTO_INCREMENT=1608 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.ventas_cancelaciones: 1,607 rows
/*!40000 ALTER TABLE `ventas_cancelaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_cancelaciones` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.ventas_cancelaciones_detalle
CREATE TABLE IF NOT EXISTS `ventas_cancelaciones_detalle` (
  `id_detalle` int(11) unsigned NOT NULL auto_increment,
  `id_venta_cancelacion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` varchar(10) collate utf8_spanish_ci NOT NULL default '',
  `precio_venta` decimal(10,2) NOT NULL,
  `id_venta` int(11) NOT NULL,
  PRIMARY KEY  (`id_detalle`)
) ENGINE=MyISAM AUTO_INCREMENT=9378 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.ventas_cancelaciones_detalle: 9,377 rows
/*!40000 ALTER TABLE `ventas_cancelaciones_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_cancelaciones_detalle` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.ventas_domicilio
CREATE TABLE IF NOT EXISTS `ventas_domicilio` (
  `id_venta_domicilio` bigint(20) NOT NULL auto_increment,
  `id_usuario` int(2) NOT NULL default '0',
  `id_corte_domicilio` int(11) NOT NULL default '0',
  `id_domicilio_direccion` int(11) default '0',
  `id_domicilio_salida` int(11) default '0',
  `fechahora_alta` datetime NOT NULL,
  `facturar` tinyint(1) default '0',
  `facturado` tinyint(1) NOT NULL default '0',
  `comentarios` text character set utf8 collate utf8_spanish_ci,
  `descuento_cantidad` decimal(8,2) default NULL,
  `descuento_porcentaje` decimal(4,0) default NULL,
  `cancelado` tinyint(1) default '0',
  `entregado` tinyint(1) NOT NULL default '0',
  `id_usuario_cancelo` int(11) default '0',
  `fechahora_cancelacion` datetime default NULL,
  `motivo_cancelacion` text character set utf8 collate utf8_spanish_ci,
  `nombre_para_llevar` varchar(64) character set utf8 collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id_venta_domicilio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.ventas_domicilio: 0 rows
/*!40000 ALTER TABLE `ventas_domicilio` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_domicilio` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.ventas_domicilio_salidas
CREATE TABLE IF NOT EXISTS `ventas_domicilio_salidas` (
  `id_venta_domicilio_salida` int(11) unsigned NOT NULL auto_increment,
  `id_repartidor` int(11) default NULL,
  `fechahora_salida` datetime default NULL,
  `fechahora_regreso` datetime default NULL,
  PRIMARY KEY  (`id_venta_domicilio_salida`)
) ENGINE=MyISAM AUTO_INCREMENT=198 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.ventas_domicilio_salidas: 197 rows
/*!40000 ALTER TABLE `ventas_domicilio_salidas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_domicilio_salidas` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.venta_detalle
CREATE TABLE IF NOT EXISTS `venta_detalle` (
  `id_detalle` bigint(20) NOT NULL auto_increment,
  `id_venta` bigint(10) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` decimal(10,0) NOT NULL,
  `precio_venta` decimal(8,2) NOT NULL,
  `comentarios` varchar(255) collate utf8_spanish_ci NOT NULL,
  `tipo` int(11) NOT NULL default '0',
  `nombre` varchar(255) collate utf8_spanish_ci default NULL,
  `categoria` varchar(255) collate utf8_spanish_ci default NULL,
  `impreso` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_detalle`),
  KEY `id_venta` (`id_venta`)
) ENGINE=MyISAM AUTO_INCREMENT=164228 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla vendefacil_5.venta_detalle: 160,468 rows
/*!40000 ALTER TABLE `venta_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `venta_detalle` ENABLE KEYS */;

-- Volcando estructura para tabla vendefacil_5.venta_domicilio_detalle
CREATE TABLE IF NOT EXISTS `venta_domicilio_detalle` (
  `id_domicilio_detalle` bigint(20) NOT NULL auto_increment,
  `id_venta_domicilio` bigint(20) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` varchar(10) character set utf8 collate utf8_spanish_ci NOT NULL default '',
  `precio_venta` decimal(8,2) NOT NULL,
  `comentarios` text character set utf8 collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id_domicilio_detalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla vendefacil_5.venta_domicilio_detalle: 0 rows
/*!40000 ALTER TABLE `venta_domicilio_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `venta_domicilio_detalle` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
