-- ============================================================
-- KGB POS - Modern clean database schema
-- Database: zentra_pos
-- Purpose: New normalized restaurant POS schema designed from scratch.
-- Legacy dump used only as business reference, not as structural source.
-- ============================================================

CREATE DATABASE IF NOT EXISTS `zentra_pos`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `zentra_pos`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- Core catalog
-- ============================================================

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_categorias_nombre` (`nombre`),
  KEY `idx_categorias_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `categoria_id` bigint unsigned NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_productos_categoria_nombre` (`categoria_id`, `nombre`),
  KEY `idx_productos_categoria_id` (`categoria_id`),
  KEY `idx_productos_activo` (`activo`),
  CONSTRAINT `fk_productos_categoria`
    FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `chk_productos_precio_no_negativo`
    CHECK (`precio` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Users, roles and permissions
-- ============================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_username` (`username`),
  KEY `idx_users_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_roles_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_permissions_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `role_id`),
  KEY `idx_user_roles_role_id` (`role_id`),
  CONSTRAINT `fk_user_roles_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT `fk_user_roles_role`
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`, `permission_id`),
  KEY `idx_role_permissions_permission_id` (`permission_id`),
  CONSTRAINT `fk_role_permissions_role`
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT `fk_role_permissions_permission`
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Customers
-- ============================================================

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_clientes_nombre` (`nombre`),
  KEY `idx_clientes_telefono` (`telefono`),
  KEY `idx_clientes_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Sales
-- ============================================================

CREATE TABLE IF NOT EXISTS `ventas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `cliente_id` bigint unsigned DEFAULT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('abierta', 'pagada', 'cancelada') NOT NULL DEFAULT 'abierta',
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ventas_usuario_id` (`usuario_id`),
  KEY `idx_ventas_cliente_id` (`cliente_id`),
  KEY `idx_ventas_fecha_hora` (`fecha_hora`),
  KEY `idx_ventas_estado` (`estado`),
  KEY `idx_ventas_estado_fecha` (`estado`, `fecha_hora`),
  CONSTRAINT `fk_ventas_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `fk_ventas_cliente`
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  CONSTRAINT `chk_ventas_total_no_negativo`
    CHECK (`total` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `venta_detalle` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` decimal(12,3) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (ROUND(`cantidad` * `precio_unitario`, 2)) STORED,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_venta_detalle_venta_id` (`venta_id`),
  KEY `idx_venta_detalle_producto_id` (`producto_id`),
  CONSTRAINT `fk_venta_detalle_venta`
    FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT `fk_venta_detalle_producto`
    FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `chk_venta_detalle_cantidad_positiva`
    CHECK (`cantidad` > 0),
  CONSTRAINT `chk_venta_detalle_precio_no_negativo`
    CHECK (`precio_unitario` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Multi-payment system
-- ============================================================

CREATE TABLE IF NOT EXISTS `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` bigint unsigned NOT NULL,
  `tipo` enum('efectivo', 'tarjeta', 'transferencia') NOT NULL,
  -- monto is the amount applied to the sale total.
  -- For cash tendered above the sale total, store the received amount in monto_recibido.
  `monto` decimal(12,2) NOT NULL,
  `monto_recibido` decimal(12,2) DEFAULT NULL,
  `cambio` decimal(12,2) GENERATED ALWAYS AS (
    CASE
      WHEN `monto_recibido` IS NULL THEN 0.00
      WHEN `monto_recibido` > `monto` THEN ROUND(`monto_recibido` - `monto`, 2)
      ELSE 0.00
    END
  ) STORED,
  `referencia` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pagos_venta_id` (`venta_id`),
  KEY `idx_pagos_tipo` (`tipo`),
  KEY `idx_pagos_created_at` (`created_at`),
  CONSTRAINT `fk_pagos_venta`
    FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `chk_pagos_monto_positivo`
    CHECK (`monto` > 0),
  CONSTRAINT `chk_pagos_monto_recibido_no_negativo`
    CHECK (`monto_recibido` IS NULL OR `monto_recibido` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- View for payment validation and reports.
CREATE OR REPLACE VIEW `vw_ventas_pago_resumen` AS
SELECT
  v.id AS venta_id,
  v.estado,
  v.total,
  COALESCE(SUM(p.monto), 0.00) AS total_pagado,
  COALESCE(SUM(COALESCE(p.monto_recibido, p.monto)), 0.00) AS total_recibido,
  COALESCE(SUM(p.cambio), 0.00) AS cambio_entregado,
  v.total - COALESCE(SUM(p.monto), 0.00) AS saldo
FROM ventas v
LEFT JOIN pagos p ON p.venta_id = v.id
GROUP BY v.id, v.estado, v.total;

-- ============================================================
-- Cash register closing
-- ============================================================

CREATE TABLE IF NOT EXISTS `cortes_caja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `fecha_apertura` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_cierre` datetime DEFAULT NULL,
  `estado` enum('abierto', 'cerrado') NOT NULL DEFAULT 'abierto',
  `fondo_inicial` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_ventas` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pagos_efectivo` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pagos_tarjeta` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pagos_transferencia` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_gastos` decimal(12,2) NOT NULL DEFAULT 0.00,
  `efectivo_contado` decimal(12,2) DEFAULT NULL,
  `diferencia` decimal(12,2) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cortes_caja_usuario_id` (`usuario_id`),
  KEY `idx_cortes_caja_estado` (`estado`),
  KEY `idx_cortes_caja_fecha_apertura` (`fecha_apertura`),
  CONSTRAINT `fk_cortes_caja_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `chk_cortes_caja_fondo_no_negativo`
    CHECK (`fondo_inicial` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `gastos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `corte_caja_id` bigint unsigned DEFAULT NULL,
  `usuario_id` bigint unsigned NOT NULL,
  `concepto` varchar(150) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_gastos_corte_caja_id` (`corte_caja_id`),
  KEY `idx_gastos_usuario_id` (`usuario_id`),
  KEY `idx_gastos_created_at` (`created_at`),
  CONSTRAINT `fk_gastos_corte_caja`
    FOREIGN KEY (`corte_caja_id`) REFERENCES `cortes_caja` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  CONSTRAINT `fk_gastos_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `chk_gastos_monto_positivo`
    CHECK (`monto` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Optional seed data
-- ============================================================

INSERT INTO `roles` (`nombre`) VALUES
  ('admin'),
  ('cajero'),
  ('gerente')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

INSERT INTO `permissions` (`key`, `descripcion`) VALUES
  ('ventas.crear', 'Crear ventas'),
  ('ventas.cobrar', 'Cobrar ventas'),
  ('ventas.cancelar', 'Cancelar ventas'),
  ('productos.ver', 'Ver productos'),
  ('productos.editar', 'Editar productos'),
  ('cortes.abrir', 'Abrir corte de caja'),
  ('cortes.cerrar', 'Cerrar corte de caja'),
  ('reportes.ver', 'Ver reportes')
ON DUPLICATE KEY UPDATE `descripcion` = VALUES(`descripcion`);

INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.nombre = 'admin'
ON DUPLICATE KEY UPDATE `role_id` = VALUES(`role_id`);

INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.`key` IN ('ventas.crear', 'ventas.cobrar', 'productos.ver', 'cortes.abrir')
WHERE r.nombre = 'cajero'
ON DUPLICATE KEY UPDATE `role_id` = VALUES(`role_id`);

INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.`key` IN (
  'ventas.crear',
  'ventas.cobrar',
  'ventas.cancelar',
  'productos.ver',
  'productos.editar',
  'cortes.abrir',
  'cortes.cerrar',
  'reportes.ver'
)
WHERE r.nombre = 'gerente'
ON DUPLICATE KEY UPDATE `role_id` = VALUES(`role_id`);

-- ============================================================
-- Business-rule triggers
-- ============================================================

DELIMITER $$

CREATE TRIGGER `trg_pagos_before_insert`
BEFORE INSERT ON `pagos`
FOR EACH ROW
BEGIN
  DECLARE venta_total decimal(12,2);
  DECLARE venta_estado varchar(20);
  DECLARE pagado_actual decimal(12,2);

  SELECT total, estado
    INTO venta_total, venta_estado
  FROM ventas
  WHERE id = NEW.venta_id;

  IF venta_estado = 'cancelada' THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'No se pueden registrar pagos en una venta cancelada';
  END IF;

  SELECT COALESCE(SUM(monto), 0.00)
    INTO pagado_actual
  FROM pagos
  WHERE venta_id = NEW.venta_id;

  IF pagado_actual + NEW.monto > venta_total THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'El total de pagos no puede exceder el total de la venta';
  END IF;

  IF NEW.tipo = 'efectivo'
     AND NEW.monto_recibido IS NOT NULL
     AND NEW.monto_recibido < NEW.monto THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'En efectivo, monto_recibido no puede ser menor al monto aplicado';
  END IF;

  IF NEW.tipo <> 'efectivo'
     AND NEW.monto_recibido IS NOT NULL
     AND NEW.monto_recibido <> NEW.monto THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Solo pagos en efectivo pueden tener monto_recibido diferente al monto aplicado';
  END IF;
END$$

CREATE TRIGGER `trg_pagos_before_update`
BEFORE UPDATE ON `pagos`
FOR EACH ROW
BEGIN
  DECLARE venta_total decimal(12,2);
  DECLARE venta_estado varchar(20);
  DECLARE pagado_actual decimal(12,2);

  SELECT total, estado
    INTO venta_total, venta_estado
  FROM ventas
  WHERE id = NEW.venta_id;

  IF venta_estado = 'cancelada' THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'No se pueden modificar pagos de una venta cancelada';
  END IF;

  SELECT COALESCE(SUM(monto), 0.00)
    INTO pagado_actual
  FROM pagos
  WHERE venta_id = NEW.venta_id
    AND id <> OLD.id;

  IF pagado_actual + NEW.monto > venta_total THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'El total de pagos no puede exceder el total de la venta';
  END IF;

  IF NEW.tipo = 'efectivo'
     AND NEW.monto_recibido IS NOT NULL
     AND NEW.monto_recibido < NEW.monto THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'En efectivo, monto_recibido no puede ser menor al monto aplicado';
  END IF;

  IF NEW.tipo <> 'efectivo'
     AND NEW.monto_recibido IS NOT NULL
     AND NEW.monto_recibido <> NEW.monto THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Solo pagos en efectivo pueden tener monto_recibido diferente al monto aplicado';
  END IF;
END$$

CREATE TRIGGER `trg_ventas_before_update`
BEFORE UPDATE ON `ventas`
FOR EACH ROW
BEGIN
  DECLARE pagado_actual decimal(12,2);

  IF NEW.estado = 'pagada' THEN
    SELECT COALESCE(SUM(monto), 0.00)
      INTO pagado_actual
    FROM pagos
    WHERE venta_id = NEW.id;

    IF pagado_actual <> NEW.total THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Para marcar una venta como pagada, SUM(pagos.monto) debe ser igual a ventas.total';
    END IF;
  END IF;
END$$

DELIMITER ;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Useful validation queries
-- ============================================================

-- Permission check example:
-- SELECT COUNT(*) > 0 AS tiene_permiso
-- FROM users u
-- JOIN user_roles ur ON ur.user_id = u.id
-- JOIN role_permissions rp ON rp.role_id = ur.role_id
-- JOIN permissions p ON p.id = rp.permission_id
-- WHERE u.id = 1
--   AND p.`key` = 'ventas.cobrar';

-- Payment validation report:
-- SELECT *
-- FROM vw_ventas_pago_resumen
-- WHERE estado = 'pagada'
--   AND saldo <> 0.00;

-- Cash change example:
-- Sale total: 700.00, customer gives 1000.00, POS applies 700.00 and returns 300.00.
-- INSERT INTO pagos (venta_id, tipo, monto, monto_recibido, referencia)
-- VALUES (1, 'efectivo', 700.00, 1000.00, NULL);
