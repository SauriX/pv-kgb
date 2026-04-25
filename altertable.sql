ALTER TABLE ventas 
ADD monto_efectivo DECIMAL(10,2) DEFAULT 0,
ADD monto_tarjeta DECIMAL(10,2) DEFAULT 0,
ADD monto_transferencia DECIMAL(10,2) DEFAULT 0;