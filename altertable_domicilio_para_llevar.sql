-- Segundo juego de impresoras para pedidos para llevar
-- Ejecutar sobre la base legacy actual

ALTER TABLE configuracion
ADD impresora_sd_para_llevar VARCHAR(64) NOT NULL DEFAULT '' AFTER impresora_sd,
ADD impresora_cuentas_para_llevar VARCHAR(64) NOT NULL DEFAULT '' AFTER impresora_cuentas,
ADD impresora_cobros_para_llevar VARCHAR(64) NOT NULL DEFAULT '' AFTER impresora_cobros,
ADD impresora_cortes_para_llevar VARCHAR(64) NOT NULL DEFAULT '' AFTER impresora_cortes;

ALTER TABLE categorias
ADD impresora_para_llevar VARCHAR(64) NOT NULL DEFAULT '' AFTER impresora;

UPDATE configuracion
SET impresora_sd_para_llevar = impresora_sd,
	impresora_cuentas_para_llevar = impresora_cuentas,
	impresora_cobros_para_llevar = impresora_cobros,
	impresora_cortes_para_llevar = impresora_cortes
WHERE 1;

UPDATE categorias
SET impresora_para_llevar = impresora
WHERE TRIM(IFNULL(impresora_para_llevar, '')) = '';

ALTER TABLE ventas
ADD para_llevar TINYINT(1) NOT NULL DEFAULT '0' AFTER domicilio;
