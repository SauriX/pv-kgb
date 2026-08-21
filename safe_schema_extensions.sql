-- Safe additive schema extensions for legacy MyISAM POS database.
-- Source reviewed: respaldo 25-03-2026.sql
-- Date: 2026-04-27
--
-- Rules followed:
-- - No DROP statements.
-- - No existing table/column renames.
-- - No changes to existing ventas, venta_detalle, productos, usuarios behavior.
-- - Foreign keys are logical only because the legacy schema uses MyISAM.

SET NAMES utf8;

-- ============================================================
-- 1) Multi-payment system
-- ============================================================
-- Coexists with legacy ventas.id_metodo, ventas.monto_pagado, ventas.metodo_txt.
-- New code should write one row per payment method here and continue filling
-- legacy ventas payment fields during the transition.

CREATE TABLE IF NOT EXISTS `pagos` (
  `id_pago` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_venta` bigint(10) unsigned NOT NULL,
  `tipo_pago` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `referencia` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_pago`),
  KEY `idx_pagos_id_venta` (`id_venta`),
  KEY `idx_pagos_tipo_pago` (`tipo_pago`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Example: one sale paid with cash + card.
-- Replace 32197 with a real ventas.id_venta before executing in production.
/*
INSERT INTO `pagos` (`id_venta`, `tipo_pago`, `monto`, `referencia`, `created_at`) VALUES
  (32197, 'efectivo', 200.00, NULL, NOW()),
  (32197, 'tarjeta', 350.00, 'TPV-123456', NOW());
*/

-- Total paid per sale using the new normalized table.
/*
SELECT
  v.id_venta,
  v.monto_pagado AS monto_pagado_legacy,
  COALESCE(SUM(p.monto), 0.00) AS monto_pagado_nuevo
FROM ventas v
LEFT JOIN pagos p ON p.id_venta = v.id_venta
GROUP BY v.id_venta, v.monto_pagado;
*/

-- Validation rule:
-- SUM(pagos.monto) should match ventas.monto_pagado for migrated sales.
/*
SELECT
  v.id_venta,
  v.monto_pagado,
  COALESCE(SUM(p.monto), 0.00) AS total_pagos,
  v.monto_pagado - COALESCE(SUM(p.monto), 0.00) AS diferencia
FROM ventas v
LEFT JOIN pagos p ON p.id_venta = v.id_venta
GROUP BY v.id_venta, v.monto_pagado
HAVING diferencia <> 0.00;
*/

-- ============================================================
-- 2) Flexible RBAC system
-- ============================================================
-- Coexists with legacy usuarios.id_tipo_usuario and tipo_usuario.
-- Existing login and permission logic can keep working while new screens
-- migrate to these tables.

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_roles_nombre` (`nombre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_permissions_key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_id` mediumint(2) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  KEY `idx_user_roles_role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  KEY `idx_role_permissions_permission_id` (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Initial roles.
INSERT IGNORE INTO `roles` (`nombre`) VALUES
  ('admin'),
  ('cajero');

-- Initial permissions. Add more keys as new modules migrate to RBAC.
INSERT IGNORE INTO `permissions` (`key`, `descripcion`) VALUES
  ('ventas.crear', 'Crear ventas'),
  ('ventas.cancelar', 'Cancelar ventas'),
  ('ventas.cobrar', 'Cobrar ventas'),
  ('cortes.ver', 'Ver cortes'),
  ('productos.editar', 'Editar productos');

-- Admin receives all current permissions.
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.nombre = 'admin';

-- Cajero receives day-to-day POS permissions.
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.`key` IN ('ventas.crear', 'ventas.cobrar')
WHERE r.nombre = 'cajero';

-- Conservative mapping from legacy user types:
-- tipo_usuario 1 = Administrador -> admin
-- tipo_usuario 2 = Vendedor -> cajero
INSERT IGNORE INTO `user_roles` (`user_id`, `role_id`)
SELECT u.id_usuario, r.id
FROM usuarios u
JOIN roles r ON r.nombre = 'admin'
WHERE u.id_tipo_usuario = 1;

INSERT IGNORE INTO `user_roles` (`user_id`, `role_id`)
SELECT u.id_usuario, r.id
FROM usuarios u
JOIN roles r ON r.nombre = 'cajero'
WHERE u.id_tipo_usuario = 2;

-- Example permission check.
/*
SELECT COUNT(*) > 0 AS tiene_permiso
FROM user_roles ur
JOIN role_permissions rp ON rp.role_id = ur.role_id
JOIN permissions p ON p.id = rp.permission_id
WHERE ur.user_id = 2
  AND p.`key` = 'ventas.cobrar';
*/

-- ============================================================
-- 3) Performance indexes
-- ============================================================
-- Run during low traffic. MyISAM can lock tables while adding indexes.
-- These statements check INFORMATION_SCHEMA first so the script can be run
-- more than once without failing on duplicate index names.

SET @schema_name := DATABASE();

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD INDEX `idx_ventas_fecha` (`fecha`)',
    'SELECT ''idx_ventas_fecha already exists'' AS status'
  )
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = @schema_name
    AND TABLE_NAME = 'ventas'
    AND INDEX_NAME = 'idx_ventas_fecha'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD INDEX `idx_ventas_id_usuario` (`id_usuario`)',
    'SELECT ''idx_ventas_id_usuario already exists'' AS status'
  )
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = @schema_name
    AND TABLE_NAME = 'ventas'
    AND INDEX_NAME = 'idx_ventas_id_usuario'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD INDEX `idx_ventas_fecha_usuario` (`fecha`, `id_usuario`)',
    'SELECT ''idx_ventas_fecha_usuario already exists'' AS status'
  )
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = @schema_name
    AND TABLE_NAME = 'ventas'
    AND INDEX_NAME = 'idx_ventas_fecha_usuario'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `productos` ADD INDEX `idx_productos_id_categoria` (`id_categoria`)',
    'SELECT ''idx_productos_id_categoria already exists'' AS status'
  )
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = @schema_name
    AND TABLE_NAME = 'productos'
    AND INDEX_NAME = 'idx_productos_id_categoria'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- venta_detalle already has KEY `id_venta` (`id_venta`) in the reviewed dump,
-- so no duplicate id_venta index is added here.

-- If this script is reused against a database where venta_detalle lacks an
-- id_venta index, run this manually after checking SHOW INDEX:
-- ALTER TABLE `venta_detalle` ADD INDEX `idx_venta_detalle_id_venta` (`id_venta`);

-- ============================================================
-- 4) Documentation-only candidates
-- ============================================================
-- deprecated_candidate:
-- - ventas.id_metodo, ventas.metodo_txt, ventas.monto_pagado after pagos is live.
-- - usuarios.id_tipo_usuario and tipo_usuario after RBAC is fully adopted.
-- - existecias because it appears to overlap with existencia and may be a typo.
--
-- normalization_candidate:
-- - configuracion header/footer/printer/module settings.
-- - productos extra/tiene/sinn/ignorar modifier behavior.
-- - existencia.texto inventory snapshots.
--
-- optimization_candidate:
-- - productos.imagen longblob inside the catalog table.
