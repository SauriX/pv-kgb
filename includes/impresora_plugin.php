<?php

function impresion_plugin_comando($action, $text = null, $count = 0, $mode = false, $imagePath = null)
{
    return array(
        'action' => $action,
        'text' => $text,
        'count' => $count,
        'mode' => $mode,
        'imagePath' => $imagePath
    );
}

function impresion_plugin_configuracion()
{
    $q = mysql_query("SELECT * FROM configuracion");
    return mysql_fetch_assoc($q);
}

function impresion_plugin_destino($impresora, $impresora_para_llevar, $para_llevar, $comandain, $impresora_cuentas, $impresora_cuentas_para_llevar)
{
    if ($para_llevar == 1) {
        $impresora = trim($impresora_para_llevar) != '' ? $impresora_para_llevar : $impresora;
    }
    if ($comandain == 1 || trim($impresora) == '') {
        $impresora = $para_llevar == 1 ? $impresora_cuentas_para_llevar : $impresora_cuentas;
    }
    return trim($impresora);
}

function impresion_plugin_impresoras_comanda($id_venta, $tipo = 'venta')
{
    $id_venta = intval($id_venta);
    $config = impresion_plugin_configuracion();
    $comandain = intval($config['comandain']);
    $impresoras = array();
    if ($tipo == 'domicilio') {
        $sql = "SELECT categorias.impresora, categorias.impresora_para_llevar, 1 AS para_llevar
            FROM venta_domicilio_detalle
            LEFT JOIN productos ON productos.id_producto = venta_domicilio_detalle.id_producto
            LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
            WHERE venta_domicilio_detalle.id_venta_domicilio = $id_venta
            AND venta_domicilio_detalle.id_producto != 0";
    } else {
        $sql = "SELECT categorias.impresora, categorias.impresora_para_llevar, ventas.para_llevar
        FROM venta_detalle
        LEFT JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
        LEFT JOIN productos ON productos.id_producto = venta_detalle.id_producto
        LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
        WHERE venta_detalle.id_venta = $id_venta
        AND venta_detalle.id_producto != 0 AND venta_detalle.impreso = 0";
    }
    $q = mysql_query($sql);
    while ($row = mysql_fetch_assoc($q)) {
        $destino = impresion_plugin_destino(
            $row['impresora'],
            $row['impresora_para_llevar'],
            intval($row['para_llevar']),
            $comandain,
            $config['impresora_cuentas'],
            $config['impresora_cuentas_para_llevar']
        );
        if ($destino != '' && !in_array($destino, $impresoras)) {
            $impresoras[] = $destino;
        }
    }
    return $impresoras;
}

function impresion_plugin_comanda($id_venta, $impresora, $tipo = 'venta')
{
    $id_venta = intval($id_venta);
    $impresora = trim($impresora);
    $config = impresion_plugin_configuracion();
    $comandain = intval($config['comandain']);
    $commands = array();
    if ($tipo == 'domicilio') {
        $sql = "SELECT productos.extra, productos.sinn, venta_domicilio_detalle.cantidad,
            productos.nombre, venta_domicilio_detalle.precio_venta, venta_domicilio_detalle.comentarios, categorias.impresora,
            categorias.impresora_para_llevar, '' AS mesa, ventas_domicilio.fechahora_alta AS fecha, '' AS hora,
            1 AS para_llevar
            FROM venta_domicilio_detalle
            LEFT JOIN ventas_domicilio ON ventas_domicilio.id_venta_domicilio = venta_domicilio_detalle.id_venta_domicilio
            LEFT JOIN productos ON productos.id_producto = venta_domicilio_detalle.id_producto
            LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
            WHERE venta_domicilio_detalle.id_venta_domicilio = $id_venta
            AND venta_domicilio_detalle.id_producto != 0";
    } else {
        $sql = "SELECT productos.extra, productos.sinn, venta_detalle.cantidad,
        productos.nombre, venta_detalle.precio_venta, venta_detalle.comentarios, categorias.impresora,
        categorias.impresora_para_llevar, ventas.mesa, ventas.hora, ventas.fecha,
        ventas.para_llevar
        FROM venta_detalle
        LEFT JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
        LEFT JOIN productos ON productos.id_producto = venta_detalle.id_producto
        LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
        WHERE venta_detalle.id_venta = $id_venta
        AND venta_detalle.id_producto != 0 AND venta_detalle.impreso = 0";
    }
    $q = mysql_query($sql);
    if (!$q) {
        return false;
    }

    $rows = array();
    $mesa = '';
    $fecha = '';
    $para_llevar = 0;
    while ($row = mysql_fetch_assoc($q)) {
        $destino = impresion_plugin_destino(
            $row['impresora'],
            $row['impresora_para_llevar'],
            intval($row['para_llevar']),
            $comandain,
            $config['impresora_cuentas'],
            $config['impresora_cuentas_para_llevar']
        );
        if ($destino !== $impresora) {
            continue;
        }
        $rows[] = $row;
        $mesa = $row['mesa'];
        $fecha = $row['fecha'] . ' ' . $row['hora'];
        $para_llevar = intval($row['para_llevar']);
    }

    if (count($rows) == 0) {
        return false;
    }

    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', '');
    $commands[] = impresion_plugin_comando('text', '');
    $commands[] = impresion_plugin_comando('text', $impresora);
    $commands[] = impresion_plugin_comando('text', 'COMANDA #' . $id_venta);
    $commands[] = impresion_plugin_comando('text', $para_llevar == 1 ? '*** PARA LLEVAR ***' : 'MESA: ' . $mesa);
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('text', $fecha);
    $commands[] = impresion_plugin_comando('text', '__________________________________________');
    $commands[] = impresion_plugin_comando('left');

    foreach ($rows as $row) {
        $nombre = eliminar_tildes($row['nombre']);
        $cantidad = $row['cantidad'];
        if (intval($row['extra']) == 1 || intval($row['sinn']) == 1 || floatval($row['precio_venta']) == 0) {
            $cantidad = '  *';
        }
        $commands[] = impresion_plugin_comando('doubleWidth2');
        $commands[] = impresion_plugin_comando('text', '- ' . $cantidad . ' ' . $nombre);
        $commands[] = impresion_plugin_comando('normalWidth');
        $comentarios = explode("\n", (string)$row['comentarios']);
        foreach ($comentarios as $comentario) {
            $comentario = trim($comentario);
            if ($comentario == '[[DESC100]]') {
                $comentario = 'DESC. 100%';
            }
            if ($comentario != '') {
                $commands[] = impresion_plugin_comando('text', '  * ' . eliminar_tildes($comentario));
            }
        }
    }

    $commands[] = impresion_plugin_comando('text', '__________________________________________');
    $commands[] = impresion_plugin_comando('newLines', null, 3);
    $commands[] = impresion_plugin_comando('full');

    return array(
        'printerName' => $impresora,
        'commands' => $commands
    );
}

function impresion_plugin_ticket_mesa($id_venta, $tipo)
{
    $id_venta = intval($id_venta);
    $tipo = $tipo == 'cerrar' ? 'cerrar' : 'cobrar';
    $config = impresion_plugin_configuracion();
    $q = mysql_query("SELECT * FROM ventas WHERE id_venta = $id_venta");
    $venta = mysql_fetch_assoc($q);
    if (!$venta) {
        return false;
    }

    $para_llevar = intval($venta['para_llevar']) == 1;
    $impresora = $tipo == 'cerrar' ? $config['impresora_cuentas'] : $config['impresora_cobros'];
    if ($para_llevar) {
        $alternativa = $tipo == 'cerrar' ? $config['impresora_cuentas_para_llevar'] : $config['impresora_cobros_para_llevar'];
        if (trim($alternativa) != '') {
            $impresora = $alternativa;
        }
    }
    if (trim($impresora) == '') {
        return false;
    }

    $commands = array();
    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    for ($index = 1; $index <= 10; $index++) {
        $encabezado = trim($config['header_' . $index]);
        if ($encabezado != '') {
            $commands[] = impresion_plugin_comando('text', eliminar_tildes($encabezado));
        }
    }
    $fecha = $tipo == 'cerrar' ? $venta['fecha'] . ' ' . $venta['hora'] : $venta['fechahora_pagada'];
    $mesa = $venta['mesa'] == 'BARRA' ? 'BARRA' : 'MESA: ' . $venta['mesa'];
    $commands[] = impresion_plugin_comando('text', $fecha);
    $commands[] = impresion_plugin_comando('text', 'FOLIO: #' . $id_venta . ' - ' . $mesa);
    if (intval($venta['domicilio']) == 1) {
        $commands[] = impresion_plugin_comando('text', 'SERVICIO A DOMICILIO');
    } elseif ($para_llevar) {
        $commands[] = impresion_plugin_comando('text', 'PARA LLEVAR');
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('left');
    $commands[] = impresion_plugin_comando('text', 'PRODUCTO               CANT   UNIT    SUBT');

    $q = mysql_query("SELECT venta_detalle.cantidad, productos.nombre, venta_detalle.precio_venta
        FROM venta_detalle
        JOIN productos ON productos.id_producto = venta_detalle.id_producto
        WHERE venta_detalle.id_venta = $id_venta");
    $total = 0;
    while ($detalle = mysql_fetch_assoc($q)) {
        $subtotal = floatval($detalle['cantidad']) * floatval($detalle['precio_venta']);
        $total += $subtotal;
        $linea = substr(eliminar_tildes($detalle['nombre']), 0, 20);
        $linea = str_pad($linea, 22) . str_pad($detalle['cantidad'], 5, ' ', STR_PAD_LEFT);
        $linea .= str_pad(number_format($detalle['precio_venta'], 2), 8, ' ', STR_PAD_LEFT);
        $linea .= str_pad(number_format($subtotal, 2), 8, ' ', STR_PAD_LEFT);
        $commands[] = impresion_plugin_comando('text', $linea);
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('right');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'TOTAL: $' . number_format($total, 2));
    $commands[] = impresion_plugin_comando('normalWidth');
    if ($tipo == 'cobrar' && trim($venta['metodo_txt']) != '') {
        $commands[] = impresion_plugin_comando('text', 'PAGO: ' . eliminar_tildes($venta['metodo_txt']));
        $commands[] = impresion_plugin_comando('text', 'CAMBIO: $' . number_format(floatval($venta['cambio_txt']), 2));
    }
    $commands[] = impresion_plugin_comando('center');
    for ($index = 1; $index <= 10; $index++) {
        $pie = trim($config['footer_' . $index]);
        if ($pie != '') {
            $commands[] = impresion_plugin_comando('text', eliminar_tildes($pie));
        }
    }
    if ($tipo == 'cobrar') {
        $commands[] = impresion_plugin_comando('openDrawer');
    }
    $commands[] = impresion_plugin_comando('newLines', null, 3);
    $commands[] = impresion_plugin_comando('full');

    return array('printerName' => trim($impresora), 'commands' => $commands);
}

function impresion_plugin_ticket_domicilio($id_venta, $impresora = '')
{
    $id_venta = intval($id_venta);
    $config = impresion_plugin_configuracion();
    if (trim($impresora) == '') {
        $impresora = trim($config['impresora_sd_para_llevar']) != '' ? $config['impresora_sd_para_llevar'] : $config['impresora_sd'];
    }
    if (trim($impresora) == '') {
        return false;
    }

    $q = mysql_query("SELECT domicilio_direcciones.direccion, domicilio.numero, domicilio.nombre,
        ventas_domicilio.facturar, ventas_domicilio.fechahora_alta, ventas_domicilio.comentarios,
        ventas_domicilio.descuento_cantidad, ventas_domicilio.nombre_para_llevar
        FROM ventas_domicilio
        LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion = ventas_domicilio.id_domicilio_direccion
        LEFT JOIN domicilio ON domicilio.id_domicilio = domicilio_direcciones.id_domicilio
        WHERE ventas_domicilio.id_venta_domicilio = $id_venta");
    $venta = mysql_fetch_assoc($q);
    if (!$venta) {
        return false;
    }

    $commands = array();
    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', $venta['nombre_para_llevar'] ? 'SERVICIO PARA LLEVAR' : 'SERVICIO A DOMICILIO');
    $commands[] = impresion_plugin_comando('text', 'TICKET #' . $id_venta);
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('text', $venta['fechahora_alta']);
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('left');
    $nombre = $venta['nombre_para_llevar'] ? $venta['nombre_para_llevar'] : $venta['nombre'];
    $commands[] = impresion_plugin_comando('text', 'CLIENTE: ' . eliminar_tildes($nombre));
    if (trim($venta['numero']) != '') {
        $commands[] = impresion_plugin_comando('text', 'NUMERO: ' . $venta['numero']);
    }
    if (trim($venta['direccion']) != '') {
        $commands[] = impresion_plugin_comando('text', 'DIRECCION DE ENTREGA:');
        foreach (explode("\n", $venta['direccion']) as $direccion) {
            $commands[] = impresion_plugin_comando('text', eliminar_tildes(trim($direccion)));
        }
    }
    if (trim($venta['comentarios']) != '') {
        $commands[] = impresion_plugin_comando('text', '------------------------------------------');
        $commands[] = impresion_plugin_comando('text', 'NOTA: ' . eliminar_tildes($venta['comentarios']));
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('text', 'REQUIERE FACTURA: ' . (intval($venta['facturar']) == 1 ? 'SI' : 'NO'));
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');

    $q = mysql_query("SELECT venta_domicilio_detalle.cantidad, venta_domicilio_detalle.precio_venta,
        venta_domicilio_detalle.comentarios, productos.nombre
        FROM venta_domicilio_detalle
        JOIN productos ON productos.id_producto = venta_domicilio_detalle.id_producto
        WHERE venta_domicilio_detalle.id_venta_domicilio = $id_venta");
    $total = 0;
    while ($detalle = mysql_fetch_assoc($q)) {
        $subtotal = floatval($detalle['cantidad']) * floatval($detalle['precio_venta']);
        $total += $subtotal;
        $commands[] = impresion_plugin_comando('doubleWidth2');
        $commands[] = impresion_plugin_comando('text', $detalle['cantidad'] . ' - ' . eliminar_tildes($detalle['nombre']));
        $commands[] = impresion_plugin_comando('normalWidth');
        foreach (explode("\n", trim($detalle['comentarios'])) as $comentario) {
            if (trim($comentario) != '') {
                $commands[] = impresion_plugin_comando('text', '    * ' . eliminar_tildes(trim($comentario)));
            }
        }
        $commands[] = impresion_plugin_comando('text', '    ' . number_format($detalle['precio_venta'], 2) . ' @ ' . number_format($subtotal, 2));
    }
    $descuento = floatval($venta['descuento_cantidad']);
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('right');
    if ($descuento > 0) {
        $commands[] = impresion_plugin_comando('text', 'SUB-TOTAL: $' . number_format($total, 2));
        $commands[] = impresion_plugin_comando('text', 'DESCUENTO: $' . number_format($descuento, 2));
    }
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'TOTAL: $' . number_format($total - $descuento, 2));
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('newLines', null, 3);
    $commands[] = impresion_plugin_comando('full');

    return array('printerName' => trim($impresora), 'commands' => $commands);
}

function impresion_plugin_corte($id_corte)
{
    $id_corte = intval($id_corte);
    $config = impresion_plugin_configuracion();
    $impresora = trim($config['impresora_cortes']);
    if ($impresora == '') {
        return false;
    }
    $q = mysql_query("SELECT * FROM cortes WHERE id_corte = $id_corte");
    $corte = mysql_fetch_assoc($q);
    if (!$corte) {
        return false;
    }

    $commands = array();
    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', eliminar_tildes($config['establecimiento']));
    $commands[] = impresion_plugin_comando('text', 'CORTE DE CAJA #' . $id_corte);
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('text', 'APERTURA: ' . $corte['fh_abierto']);
    $commands[] = impresion_plugin_comando('text', 'CORTE: ' . $corte['fecha'] . ' ' . $corte['hora']);
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('text', 'VENTA POR PRODUCTO');
    $commands[] = impresion_plugin_comando('left');

    $q = mysql_query("SELECT productos.nombre, SUM(venta_detalle.cantidad) AS cantidad,
        SUM(venta_detalle.cantidad * venta_detalle.precio_venta) AS total
        FROM venta_detalle
        JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
        JOIN productos ON productos.id_producto = venta_detalle.id_producto
        WHERE ventas.id_corte = $id_corte AND venta_detalle.precio_venta != 0
        GROUP BY venta_detalle.id_producto, productos.nombre ORDER BY productos.nombre");
    while ($producto = mysql_fetch_assoc($q)) {
        $linea = substr(eliminar_tildes($producto['nombre']), 0, 25);
        $linea = str_pad($linea, 26) . str_pad($producto['cantidad'], 5, ' ', STR_PAD_LEFT);
        $linea .= str_pad(number_format($producto['total'], 2), 11, ' ', STR_PAD_LEFT);
        $commands[] = impresion_plugin_comando('text', $linea);
    }

    $q = mysql_query("SELECT COUNT(*) AS cuentas, COALESCE(SUM(monto_pagado), 0) AS total,
        COALESCE(SUM(monto_efectivo), 0) AS efectivo, COALESCE(SUM(monto_tarjeta), 0) AS tarjeta,
        COALESCE(SUM(monto_transferencia), 0) AS transferencia
        FROM ventas WHERE id_corte = $id_corte");
    $ventas = mysql_fetch_assoc($q);
    $q = mysql_query("SELECT COALESCE(SUM(monto), 0) AS total FROM gastos WHERE id_corte = $id_corte");
    $gastos = mysql_fetch_assoc($q);

    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('text', 'CUENTAS: ' . $ventas['cuentas']);
    $commands[] = impresion_plugin_comando('text', 'EFECTIVO VENTAS: $' . number_format($ventas['efectivo'], 2));
    $commands[] = impresion_plugin_comando('text', 'TARJETA: $' . number_format($ventas['tarjeta'], 2));
    $commands[] = impresion_plugin_comando('text', 'TRANSFERENCIA: $' . number_format($ventas['transferencia'], 2));
    $commands[] = impresion_plugin_comando('text', 'GASTOS: $' . number_format($gastos['total'], 2));
    $commands[] = impresion_plugin_comando('text', 'EFECTIVO EN CAJA: $' . number_format($corte['efectivoCaja'], 2));
    $commands[] = impresion_plugin_comando('text', 'TPV DECLARADA: $' . number_format($corte['tpv'], 2));
    $commands[] = impresion_plugin_comando('right');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'TOTAL VENTAS: $' . number_format($ventas['total'], 2));
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('newLines', null, 4);
    $commands[] = impresion_plugin_comando('full');

    return array('printerName' => $impresora, 'commands' => $commands);
}

function impresion_plugin_gasto($id_gasto)
{
    $id_gasto = intval($id_gasto);
    $config = impresion_plugin_configuracion();
    $impresora = trim($config['impresora_cuentas']);
    if ($impresora == '') {
        return false;
    }
    $q = mysql_query("SELECT gastos.*, usuarios.nombre FROM gastos
        LEFT JOIN usuarios ON usuarios.id_usuario = gastos.id_usuario
        WHERE gastos.id_gasto = $id_gasto");
    $gasto = mysql_fetch_assoc($q);
    if (!$gasto) {
        return false;
    }

    $commands = array();
    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    for ($index = 1; $index <= 10; $index++) {
        $encabezado = trim($config['header_' . $index]);
        if ($encabezado != '') {
            $commands[] = impresion_plugin_comando('text', eliminar_tildes($encabezado));
        }
    }
    $commands[] = impresion_plugin_comando('text', $gasto['fecha_hora']);
    $commands[] = impresion_plugin_comando('text', 'AUX: ' . strtoupper(eliminar_tildes($gasto['nombre'])));
    $commands[] = impresion_plugin_comando('text', '--------------- GASTO #' . $id_gasto . ' ---------------');
    $commands[] = impresion_plugin_comando('left');
    $commands[] = impresion_plugin_comando('text', 'CONCEPTO                         MONTO');
    $linea = substr(eliminar_tildes($gasto['descripcion']), 0, 30);
    $linea = str_pad($linea, 32) . str_pad('$' . number_format($gasto['monto'], 2), 10, ' ', STR_PAD_LEFT);
    $commands[] = impresion_plugin_comando('text', $linea);
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('right');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'TOTAL: $' . number_format($gasto['monto'], 2));
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('newLines', null, 3);
    $commands[] = impresion_plugin_comando('full');

    return array('printerName' => $impresora, 'commands' => $commands);
}

function impresion_plugin_atributo_xml($nodo, $nombre)
{
    foreach ($nodo->attributes() as $atributo => $valor) {
        if (strtolower($atributo) == strtolower($nombre)) {
            return (string)$valor;
        }
    }
    return '';
}

function impresion_plugin_comprobante_domicilio($nombre, $telefono, $direccion)
{
    $config = impresion_plugin_configuracion();
    $impresora = trim($config['impresora_sd']);
    if ($impresora == '') {
        return false;
    }
    $commands = array();
    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('text', eliminar_tildes($config['establecimiento']));
    $commands[] = impresion_plugin_comando('text', date('d-m-Y h:i a'));
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('left');
    $commands[] = impresion_plugin_comando('text', 'CLIENTE: ' . eliminar_tildes($nombre));
    $commands[] = impresion_plugin_comando('text', 'TELEFONO: ' . eliminar_tildes($telefono));
    $commands[] = impresion_plugin_comando('text', 'DIRECCION: ' . eliminar_tildes($direccion));
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('newLines', null, 2);
    $commands[] = impresion_plugin_comando('full');
    return array('printerName' => $impresora, 'commands' => $commands);
}

function impresion_plugin_encabezado_domicilio($nombre, $telefono, $direccion)
{
    return rawurlencode(json_encode(array(
        'nombre' => $nombre,
        'telefono' => $telefono,
        'direccion' => $direccion
    )));
}

function impresion_plugin_codigo($codigo, $monto, $metodo, $cuenta)
{
    $config = impresion_plugin_configuracion();
    $impresora = trim($config['impresora_cuentas']);
    if ($impresora == '') {
        return false;
    }
    $commands = array();
    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('text', eliminar_tildes($config['establecimiento']));
    $commands[] = impresion_plugin_comando('text', date('d-m-Y - H:i'));
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('text', 'DE ESTE TICKET PARA GENERAR SU CFDI.');
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'CODIGO DE FACTURACION');
    $commands[] = impresion_plugin_comando('text', $codigo);
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('text', 'MONTO: $' . number_format(floatval($monto), 2));
    $commands[] = impresion_plugin_comando('text', 'METODO DE PAGO: ' . eliminar_tildes($metodo));
    if (trim($cuenta) != '') {
        $commands[] = impresion_plugin_comando('text', 'NUM CTA: ' . $cuenta);
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('text', 'GRACIAS POR SU PREFERENCIA');
    $commands[] = impresion_plugin_comando('newLines', null, 3);
    $commands[] = impresion_plugin_comando('full');
    return array('printerName' => $impresora, 'commands' => $commands);
}

function impresion_plugin_wifi($password)
{
    $config = impresion_plugin_configuracion();
    $impresora = trim($config['impresora_cortes']);
    if ($impresora == '') {
        return false;
    }
    $commands = array();
    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('text', eliminar_tildes($config['establecimiento']));
    $commands[] = impresion_plugin_comando('text', date('d-m-Y - H:i'));
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'CONTRASENA WIFI (1 HORA)');
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('text', 'RED: TACO LOCO FREE WIFI');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', $password);
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('text', 'GRACIAS POR SU PREFERENCIA');
    $commands[] = impresion_plugin_comando('newLines', null, 3);
    $commands[] = impresion_plugin_comando('full');
    return array('printerName' => $impresora, 'commands' => $commands);
}

function impresion_plugin_factura($id_factura)
{
    $id_factura = intval($id_factura);
    $config = impresion_plugin_configuracion();
    $impresora = trim($config['impresora_cuentas']);
    if ($impresora == '') {
        return false;
    }

    include(dirname(__FILE__) . '/external_db.php');
    $q = mysql_query("SELECT xml FROM facturas WHERE id_factura = $id_factura", $conexion2);
    $factura = mysql_fetch_assoc($q);
    if (!$factura || trim($factura['xml']) == '') {
        return false;
    }
    $xml = @simplexml_load_file('http://tacoloco.mx/facturacion/archs_cfdi/' . rawurlencode($factura['xml']));
    if (!$xml) {
        return false;
    }
    $namespaces = $xml->getNamespaces(true);
    $cfdi = isset($namespaces['cfdi']) ? $xml->children($namespaces['cfdi']) : $xml;
    $tfd = isset($namespaces['tfd']) ? $xml->children($namespaces['tfd']) : $xml;
    $comprobante = $xml;
    $receptor = isset($cfdi->Receptor) ? $cfdi->Receptor : null;
    $emisor = isset($cfdi->Emisor) ? $cfdi->Emisor : null;
    $conceptos = isset($cfdi->Conceptos) ? $cfdi->Conceptos->children($namespaces['cfdi']) : array();
    $complemento = isset($cfdi->Complemento) ? $cfdi->Complemento->children($namespaces['tfd']) : null;
    $timbre = $complemento && isset($complemento->TimbreFiscalDigital) ? $complemento->TimbreFiscalDigital : null;

    $commands = array();
    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'FACTURA');
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('text', 'FOLIO: ' . impresion_plugin_atributo_xml($comprobante, 'Serie') . impresion_plugin_atributo_xml($comprobante, 'Folio'));
    $commands[] = impresion_plugin_comando('text', 'FECHA: ' . impresion_plugin_atributo_xml($comprobante, 'Fecha'));
    if ($timbre) {
        $commands[] = impresion_plugin_comando('text', 'UUID: ' . impresion_plugin_atributo_xml($timbre, 'UUID'));
        $commands[] = impresion_plugin_comando('text', 'CERT. SAT: ' . impresion_plugin_atributo_xml($timbre, 'NoCertificadoSAT'));
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('left');
    if ($receptor) {
        $commands[] = impresion_plugin_comando('text', 'RECEPTOR: ' . eliminar_tildes(impresion_plugin_atributo_xml($receptor, 'Nombre')));
        $commands[] = impresion_plugin_comando('text', 'RFC: ' . impresion_plugin_atributo_xml($receptor, 'Rfc'));
        $commands[] = impresion_plugin_comando('text', 'USO CFDI: ' . impresion_plugin_atributo_xml($receptor, 'UsoCFDI'));
    }
    if ($emisor) {
        $commands[] = impresion_plugin_comando('text', 'EMISOR: ' . eliminar_tildes(impresion_plugin_atributo_xml($emisor, 'Nombre')));
        $commands[] = impresion_plugin_comando('text', 'RFC: ' . impresion_plugin_atributo_xml($emisor, 'Rfc'));
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('text', 'DESCRIPCION              CANT      IMPORTE');
    foreach ($conceptos as $concepto) {
        $descripcion = substr(eliminar_tildes(impresion_plugin_atributo_xml($concepto, 'Descripcion')), 0, 26);
        $commands[] = impresion_plugin_comando('text', $descripcion);
        $linea = '  ' . impresion_plugin_atributo_xml($concepto, 'Cantidad');
        $linea .= str_pad('$' . number_format(floatval(impresion_plugin_atributo_xml($concepto, 'Importe')), 2), 39 - strlen($linea), ' ', STR_PAD_LEFT);
        $commands[] = impresion_plugin_comando('text', $linea);
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('right');
    $commands[] = impresion_plugin_comando('text', 'SUBTOTAL: $' . number_format(floatval(impresion_plugin_atributo_xml($comprobante, 'SubTotal')), 2));
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'TOTAL: $' . number_format(floatval(impresion_plugin_atributo_xml($comprobante, 'Total')), 2));
    $commands[] = impresion_plugin_comando('normalWidth');
    $commands[] = impresion_plugin_comando('text', 'FORMA PAGO: ' . impresion_plugin_atributo_xml($comprobante, 'FormaPago'));
    $commands[] = impresion_plugin_comando('text', 'METODO PAGO: ' . impresion_plugin_atributo_xml($comprobante, 'MetodoPago'));
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('text', 'Representacion impresa de un CFDI');
    $commands[] = impresion_plugin_comando('newLines', null, 4);
    $commands[] = impresion_plugin_comando('full');

    return array('printerName' => $impresora, 'commands' => $commands);
}
