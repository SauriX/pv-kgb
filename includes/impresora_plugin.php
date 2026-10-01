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

function impresion_plugin_para_llevar_efectivo($para_llevar, $domicilio = 0)
{
    return intval($para_llevar) == 1 || intval($domicilio) == 1 ? 1 : 0;
}

function impresion_plugin_destino($impresora_mesa, $impresora_para_llevar, $para_llevar, $comandain, $impresora_cuentas, $impresora_cuentas_para_llevar, $domicilio = 0, $impresora_sd_para_llevar = '', $impresora_sd = '')
{
    $es_para_llevar = impresion_plugin_para_llevar_efectivo($para_llevar, $domicilio) == 1;
    $destino = '';

    if ($comandain == 1) {
        $destino = $es_para_llevar ? trim($impresora_cuentas_para_llevar) : trim($impresora_cuentas);
    }

    if ($destino == '' && $es_para_llevar) {
        if (trim($impresora_para_llevar) != '') {
            $destino = trim($impresora_para_llevar);
        } elseif (trim($impresora_cuentas_para_llevar) != '') {
            $destino = trim($impresora_cuentas_para_llevar);
        } elseif (intval($domicilio) == 1) {
            $destino = trim($impresora_sd_para_llevar) != '' ? trim($impresora_sd_para_llevar) : trim($impresora_sd);
        }
    }

    if ($destino == '' && !$es_para_llevar) {
        $destino = trim($impresora_mesa);
        if ($destino == '' || $comandain == 1) {
            $destino = trim($impresora_cuentas);
        }
    }

    if ($destino == '' && $es_para_llevar) {
        $destino = trim($impresora_mesa);
    }

    return $destino;
}

function impresion_plugin_parse_trabajo($trabajo)
{
    $trabajo = trim($trabajo);
    if ($trabajo === '') {
        return array('destino' => '', 'grupo' => '', 'id_categoria' => 0);
    }
    if (strpos($trabajo, '::') !== false) {
        $partes = explode('::', $trabajo, 2);
        $grupo = trim($partes[1]);
        return array(
            'destino' => trim($partes[0]),
            'grupo' => $grupo,
            'id_categoria' => is_numeric($grupo) ? intval($grupo) : 0
        );
    }
    return array('destino' => $trabajo, 'grupo' => '', 'id_categoria' => 0);
}

function impresion_plugin_trabajo_key($destino, $grupo = '')
{
    $destino = trim($destino);
    if ($destino === '') {
        return '';
    }
    $grupo = trim($grupo);
    if ($grupo === '') {
        return $destino;
    }
    return $destino . '::' . str_replace('::', '_', $grupo);
}

function impresion_plugin_nombre_impresora_categoria($impresora_mesa, $impresora_para_llevar, $para_llevar, $domicilio = 0)
{
    $es_para_llevar = impresion_plugin_para_llevar_efectivo($para_llevar, $domicilio) == 1;
    if ($es_para_llevar && trim($impresora_para_llevar) != '') {
        return trim($impresora_para_llevar);
    }
    return trim($impresora_mesa);
}

function impresion_plugin_etiqueta_comanda($row)
{
    if (isset($row['categoria_nombre']) && trim($row['categoria_nombre']) != '') {
        return trim($row['categoria_nombre']);
    }
    if (trim($row['impresora']) != '') {
        return trim($row['impresora']);
    }
    return 'COMANDA';
}

function impresion_plugin_impresoras_comanda($id_venta, $tipo = 'venta')
{
    $id_venta = intval($id_venta);
    $config = impresion_plugin_configuracion();
    $comandain = intval($config['comandain']);
    $trabajos = array();
    if ($tipo == 'domicilio') {
        $sql = "SELECT productos.id_categoria, categorias.impresora, categorias.impresora_para_llevar, 1 AS para_llevar
            FROM venta_domicilio_detalle
            LEFT JOIN productos ON productos.id_producto = venta_domicilio_detalle.id_producto
            LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
            WHERE venta_domicilio_detalle.id_venta_domicilio = $id_venta
            AND venta_domicilio_detalle.id_producto != 0";
    } else {
        $sql = "SELECT productos.id_categoria, categorias.impresora, categorias.impresora_para_llevar, ventas.para_llevar, ventas.domicilio
        FROM venta_detalle
        LEFT JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
        LEFT JOIN productos ON productos.id_producto = venta_detalle.id_producto
        LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
        WHERE venta_detalle.id_venta = $id_venta
        AND venta_detalle.id_producto != 0 AND venta_detalle.impreso = 0";
    }
    $q = mysql_query($sql);
    while ($row = mysql_fetch_assoc($q)) {
        $domicilio_fila = ($tipo == 'domicilio') ? 1 : (isset($row['domicilio']) ? intval($row['domicilio']) : 0);
        $para_llevar_fila = isset($row['para_llevar']) ? intval($row['para_llevar']) : 0;
        $destino = impresion_plugin_destino(
            $row['impresora'],
            $row['impresora_para_llevar'],
            $para_llevar_fila,
            $comandain,
            $config['impresora_cuentas'],
            $config['impresora_cuentas_para_llevar'],
            $domicilio_fila,
            $config['impresora_sd_para_llevar'],
            $config['impresora_sd']
        );
        if ($destino == '') {
            continue;
        }

        // Impresora única: divide por nombre de impresora de la categoría (como antes).
        // Sin única: una comanda por impresora configurada (caja/barra de la categoría).
        if ($comandain == 1) {
            $grupo = impresion_plugin_nombre_impresora_categoria(
                $row['impresora'],
                $row['impresora_para_llevar'],
                $para_llevar_fila,
                $domicilio_fila
            );
            if ($grupo == '') {
                $grupo = $destino;
            }
            $trabajo = impresion_plugin_trabajo_key($destino, $grupo);
        } else {
            $trabajo = $destino;
        }

        if ($trabajo != '' && !in_array($trabajo, $trabajos)) {
            $trabajos[] = $trabajo;
        }
    }
    return $trabajos;
}

function impresion_plugin_comanda($id_venta, $impresora, $tipo = 'venta')
{
    $id_venta = intval($id_venta);
    $impresora = trim($impresora);
    $trabajo = impresion_plugin_parse_trabajo($impresora);
    $destino_filtro = $trabajo['destino'];
    $grupo_filtro = $trabajo['grupo'];
    $filtrar_grupo = ($grupo_filtro !== '');
    $config = impresion_plugin_configuracion();
    $comandain = intval($config['comandain']);
    $commands = array();
    if ($tipo == 'domicilio') {
        $sql = "SELECT productos.id_categoria, productos.extra, productos.sinn, venta_domicilio_detalle.cantidad,
            productos.nombre, venta_domicilio_detalle.precio_venta, venta_domicilio_detalle.comentarios, categorias.impresora,
            categorias.nombre AS categoria_nombre, categorias.impresora_para_llevar, '' AS mesa, ventas_domicilio.fechahora_alta AS fecha, '' AS hora,
            1 AS para_llevar, 0 AS domicilio
            FROM venta_domicilio_detalle
            LEFT JOIN ventas_domicilio ON ventas_domicilio.id_venta_domicilio = venta_domicilio_detalle.id_venta_domicilio
            LEFT JOIN productos ON productos.id_producto = venta_domicilio_detalle.id_producto
            LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
            WHERE venta_domicilio_detalle.id_venta_domicilio = $id_venta
            AND venta_domicilio_detalle.id_producto != 0";
    } else {
        $sql = "SELECT productos.id_categoria, productos.extra, productos.sinn, venta_detalle.cantidad,
        productos.nombre, venta_detalle.precio_venta, venta_detalle.comentarios, categorias.impresora,
        categorias.nombre AS categoria_nombre, categorias.impresora_para_llevar, ventas.mesa, ventas.hora, ventas.fecha,
        ventas.para_llevar, ventas.domicilio
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
    $domicilio = 0;
    while ($row = mysql_fetch_assoc($q)) {
        $domicilio_fila = ($tipo == 'domicilio') ? 1 : (isset($row['domicilio']) ? intval($row['domicilio']) : 0);
        $para_llevar_fila = impresion_plugin_para_llevar_efectivo($row['para_llevar'], $domicilio_fila);
        $destino = impresion_plugin_destino(
            $row['impresora'],
            $row['impresora_para_llevar'],
            isset($row['para_llevar']) ? intval($row['para_llevar']) : 0,
            $comandain,
            $config['impresora_cuentas'],
            $config['impresora_cuentas_para_llevar'],
            $domicilio_fila,
            $config['impresora_sd_para_llevar'],
            $config['impresora_sd']
        );
        if ($destino !== $destino_filtro) {
            continue;
        }
        if ($filtrar_grupo) {
            $grupo_fila = impresion_plugin_nombre_impresora_categoria(
                $row['impresora'],
                $row['impresora_para_llevar'],
                isset($row['para_llevar']) ? intval($row['para_llevar']) : 0,
                $domicilio_fila
            );
            if ($grupo_fila == '') {
                $grupo_fila = $destino;
            }
            if ($grupo_fila !== $grupo_filtro) {
                continue;
            }
        }
        $rows[] = $row;
        $mesa = $row['mesa'];
        $fecha = $row['fecha'] . ' ' . $row['hora'];
        $para_llevar = $para_llevar_fila;
        $domicilio = intval($row['domicilio']);
    }

    if (count($rows) == 0) {
        return false;
    }

    if ($filtrar_grupo) {
        $etiqueta = $grupo_filtro;
    } else {
        $etiqueta = $destino_filtro != '' ? $destino_filtro : impresion_plugin_etiqueta_comanda($rows[0]);
    }

    $commands[] = impresion_plugin_comando('initializePrint');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', '');
    $commands[] = impresion_plugin_comando('text', '');
    $commands[] = impresion_plugin_comando('text', $etiqueta);
    $commands[] = impresion_plugin_comando('text', 'COMANDA #' . $id_venta);
    if ($domicilio == 1) {
        $titulo_comanda = '*** DOMICILIO ***';
    } elseif ($para_llevar == 1) {
        $titulo_comanda = '*** PARA LLEVAR ***';
    } else {
        $titulo_comanda = 'MESA: ' . $mesa;
    }
    $commands[] = impresion_plugin_comando('text', $titulo_comanda);
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
        'printerName' => $destino_filtro,
        'commands' => $commands
    );
}

function impresion_plugin_impresora_ticket($config, $tipo = 'cobrar', $para_llevar = 0, $domicilio = 0)
{
    $tipo = $tipo == 'cerrar' ? 'cerrar' : 'cobrar';
    $usa_para_llevar = intval($para_llevar) == 1 || intval($domicilio) == 1;
    $candidatas = array();
    if ($tipo == 'cerrar') {
        if ($usa_para_llevar) {
            $candidatas[] = $config['impresora_cuentas_para_llevar'];
            $candidatas[] = $config['impresora_cobros_para_llevar'];
        }
        $candidatas[] = $config['impresora_cuentas'];
        $candidatas[] = $config['impresora_cobros'];
    } else {
        if ($usa_para_llevar) {
            $candidatas[] = $config['impresora_cobros_para_llevar'];
            $candidatas[] = $config['impresora_cuentas_para_llevar'];
        }
        $candidatas[] = $config['impresora_cobros'];
        $candidatas[] = $config['impresora_cuentas'];
    }
    if ($domicilio) {
        $candidatas[] = $config['impresora_sd_para_llevar'];
        $candidatas[] = $config['impresora_sd'];
    }
    foreach ($candidatas as $nombre) {
        if (trim($nombre) != '') {
            return trim($nombre);
        }
    }
    $q = mysql_query("SELECT impresora FROM categorias WHERE impresora IS NOT NULL AND TRIM(impresora) != '' LIMIT 1");
    if ($q && mysql_num_rows($q)) {
        $row = mysql_fetch_assoc($q);
        if (trim($row['impresora']) != '') {
            return trim($row['impresora']);
        }
    }
    return '';
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
    $domicilio = intval($venta['domicilio']) == 1;
    $impresora = impresion_plugin_impresora_ticket($config, $tipo, $para_llevar, $domicilio);
    if ($impresora == '') {
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
    if ($domicilio) {
        $commands[] = impresion_plugin_comando('text', 'SERVICIO A DOMICILIO');
    } elseif ($para_llevar) {
        $commands[] = impresion_plugin_comando('text', 'PARA LLEVAR');
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('left');
    $commands[] = impresion_plugin_comando('text', 'PRODUCTO          CANT    UNIT     SUBT');

    $q = mysql_query("SELECT venta_detalle.cantidad, productos.nombre, venta_detalle.precio_venta, venta_detalle.comentarios
        FROM venta_detalle
        JOIN productos ON productos.id_producto = venta_detalle.id_producto
        WHERE venta_detalle.id_venta = $id_venta");
    $total = 0;
    while ($detalle = mysql_fetch_assoc($q)) {
        $subtotal = floatval($detalle['cantidad']) * floatval($detalle['precio_venta']);
        $total += $subtotal;
        $nombre = substr(eliminar_tildes($detalle['nombre']), 0, 16);
        $linea = str_pad($nombre, 17);
        $linea .= str_pad(strval(intval($detalle['cantidad'])), 5, ' ', STR_PAD_LEFT);
        $linea .= str_pad(number_format(floatval($detalle['precio_venta']), 2, '.', ''), 9, ' ', STR_PAD_LEFT);
        $linea .= str_pad(number_format($subtotal, 2, '.', ''), 11, ' ', STR_PAD_LEFT);
        $commands[] = impresion_plugin_comando('text', $linea);
        $comentario_producto = isset($detalle['comentarios']) ? $detalle['comentarios'] : '';
        if (strpos($comentario_producto, '[[DESC100]]') !== false) {
            $commands[] = impresion_plugin_comando('text', '  * DESC. 100%');
        }
    }

    $descuento = floatval($venta['DescEfec_txt']);
    $nombre_cupon = '';
    $id_descuento = intval($venta['descuento_txt']);
    if ($id_descuento > 0) {
        $q_cupon = mysql_query("SELECT cupon FROM cupones WHERE id_cupon = $id_descuento LIMIT 1");
        if ($q_cupon && ($cupon = mysql_fetch_assoc($q_cupon)) && trim($cupon['cupon']) != '') {
            $nombre_cupon = trim($cupon['cupon']);
        }
    }
    $total_cobrar = $total - $descuento;
    if ($total_cobrar < 0) {
        $total_cobrar = 0;
    }

    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('right');
    if ($descuento > 0.009) {
        $commands[] = impresion_plugin_comando('text', 'CONSUMO: $' . number_format($total, 2));
        $texto_descuento = 'DESCUENTO: $' . number_format($descuento, 2);
        if ($nombre_cupon != '') {
            $texto_descuento .= ' (' . eliminar_tildes($nombre_cupon) . ')';
        }
        $commands[] = impresion_plugin_comando('text', $texto_descuento);
    }
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'TOTAL: $' . number_format($descuento > 0.009 ? $total_cobrar : $total, 2));
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
    $commands[] = impresion_plugin_comando('text', 'FECHA APERTURA: ' . $corte['fh_abierto']);
    $commands[] = impresion_plugin_comando('text', 'FECHA CORTE: ' . $corte['fecha'] . ' ' . $corte['hora']);
    $commands[] = impresion_plugin_comando('text', '');
    $commands[] = impresion_plugin_comando('text', '################# VENTA ##################');
    $commands[] = impresion_plugin_comando('left');
    $commands[] = impresion_plugin_comando('text', 'PRODUCTO              CANT   UNIT     SUBT');

    $productos_total = 0;
    $q = mysql_query("SELECT productos.nombre, productos.extra, productos.paquete,
        SUM(venta_detalle.cantidad) AS cantidad,
        AVG(venta_detalle.precio_venta) AS precio_unit,
        SUM(venta_detalle.cantidad * venta_detalle.precio_venta) AS total
        FROM venta_detalle
        JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
        JOIN productos ON productos.id_producto = venta_detalle.id_producto
        WHERE ventas.id_corte = $id_corte AND venta_detalle.precio_venta != 0
        GROUP BY venta_detalle.id_producto, productos.nombre, productos.extra, productos.paquete
        ORDER BY productos.nombre");
    while ($producto = mysql_fetch_assoc($q)) {
        $productos_total += floatval($producto['total']);
        $nombre = eliminar_tildes($producto['nombre']);
        if (intval($producto['extra']) == 1) {
            $nombre = '(EXTRA)' . $nombre;
        } elseif (intval($producto['paquete']) == 1) {
            $nombre = '(PAQ)' . $nombre;
        }
        $nombre = substr($nombre, 0, 20);
        $cantidad = floatval($producto['cantidad']);
        $unit = number_format(floatval($producto['precio_unit']), 2, '.', '');
        $subt = number_format(floatval($producto['total']), 2, '.', '');
        $linea = str_pad($nombre, 20)
            . str_pad($cantidad, 6, ' ', STR_PAD_LEFT)
            . str_pad($unit, 8, ' ', STR_PAD_LEFT)
            . str_pad($subt, 8, ' ', STR_PAD_LEFT);
        $commands[] = impresion_plugin_comando('text', $linea);
    }

    $descuentos_cupon = 0;
    $q = mysql_query("SELECT SUM(DescEfec_txt) AS total FROM ventas WHERE id_corte = $id_corte");
    if ($q && ($row = mysql_fetch_assoc($q))) {
        $descuentos_cupon = floatval($row['total']);
    }
    $venta_total = $productos_total - $descuentos_cupon;

    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('right');
    $commands[] = impresion_plugin_comando('text', 'VENTA SUBTOTAL: ' . number_format($productos_total, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'DESCUENTOS: ' . number_format($descuentos_cupon, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'VENTA TOTAL: ' . number_format($venta_total, 2, '.', ''));
    $commands[] = impresion_plugin_comando('left');

    $efectivo = 0;
    $tarjeta = 0;
    $transferencia = 0;
    $total_cobrado = 0;
    $cta_expedidas = 0;
    $para_llevar_ct = 0;
    $cancelaciones = 0;
    $mesas_ct = 0;
    $barra_ct = 0;
    $mesas_monto = 0;
    $barra_monto = 0;
    $q = mysql_query("SELECT id_metodo, monto_pagado, monto_efectivo, monto_tarjeta,
        monto_transferencia, mesa, para_llevar, reabierta
        FROM ventas WHERE id_corte = $id_corte");
    while ($venta = mysql_fetch_assoc($q)) {
        $cta_expedidas++;
        $pagado = floatval($venta['monto_pagado']);
        $m_efectivo = floatval($venta['monto_efectivo']);
        $m_tarjeta = floatval($venta['monto_tarjeta']);
        $m_transferencia = floatval($venta['monto_transferencia']);
        $total_cobrado += $pagado;
        $id_metodo = intval($venta['id_metodo']);
        $tiene_desglose = ($m_efectivo + $m_tarjeta + $m_transferencia) > 0.009;

        // Efectivo real de la venta (sin el cambio): pagado - tarjeta - transferencia
        if ($tiene_desglose) {
            $parte_efectivo = $pagado - $m_tarjeta - $m_transferencia;
            if ($parte_efectivo < 0) {
                $parte_efectivo = 0;
            }
            $efectivo += $parte_efectivo;
            $tarjeta += $m_tarjeta;
            $transferencia += $m_transferencia;
        } elseif ($id_metodo == 3) {
            $transferencia += $pagado;
        } elseif ($id_metodo == 4 || $id_metodo == 28 || $id_metodo == 2 || $id_metodo == 5) {
            $tarjeta += $pagado;
        } else {
            $efectivo += $pagado;
        }

        if ($venta['mesa'] != 'BARRA') {
            $mesas_ct++;
            $mesas_monto += $pagado;
        } else {
            $barra_ct++;
            $barra_monto += $pagado;
        }
        if (intval($venta['para_llevar']) == 1) {
            $para_llevar_ct++;
        }
        if (intval($venta['reabierta']) == 1) {
            $cancelaciones++;
        }
    }

    $promedio = $cta_expedidas > 0 ? ($productos_total / $cta_expedidas) : 0;

    $commands[] = impresion_plugin_comando('text', 'DESGLOSE:');
    $commands[] = impresion_plugin_comando('text', 'EFECTIVO: ' . number_format($efectivo, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'TARJETAS: ' . number_format($tarjeta, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'TRANSFERENCIAS: ' . number_format($transferencia, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', '');
    $commands[] = impresion_plugin_comando('text', 'CUENTAS EXPEDIDAS: ' . $cta_expedidas);
    $commands[] = impresion_plugin_comando('text', 'VENTAS PARA LLEVAR: ' . $para_llevar_ct);
    $commands[] = impresion_plugin_comando('text', 'PROMEDIO POR CUENTA: ' . number_format($promedio, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'CANCELACIONES: ' . $cancelaciones);
    $commands[] = impresion_plugin_comando('text', '');

    $q = mysql_query("SELECT gastos.descripcion, gastos.monto
        FROM gastos
        WHERE gastos.id_corte = $id_corte
        ORDER BY gastos.id_gasto ASC");
    $gastos_detalle = array();
    $gastos_total = 0;
    while ($gasto = mysql_fetch_assoc($q)) {
        $gastos_detalle[] = $gasto;
        $gastos_total += floatval($gasto['monto']);
    }

    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('text', '################# GASTOS #################');
    $commands[] = impresion_plugin_comando('left');
    $commands[] = impresion_plugin_comando('text', 'DESCRIPCION                          MONTO');
    foreach ($gastos_detalle as $gasto) {
        $desc = substr(eliminar_tildes($gasto['descripcion']), 0, 28);
        $linea = str_pad($desc, 30) . str_pad(number_format($gasto['monto'], 2, '.', ''), 12, ' ', STR_PAD_LEFT);
        $commands[] = impresion_plugin_comando('text', $linea);
    }
    $commands[] = impresion_plugin_comando('text', '------------------------------------------');
    $commands[] = impresion_plugin_comando('right');
    $commands[] = impresion_plugin_comando('text', 'TOTAL DE GASTOS: ' . number_format($gastos_total, 2, '.', ''));
    $commands[] = impresion_plugin_comando('left');

    $q = mysql_query("SELECT venta_detalle.cantidad, venta_detalle.comentarios,
        productos.nombre, productos.precio_venta AS precio_catalogo,
        ventas.id_venta, ventas.mesa
        FROM venta_detalle
        JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
        JOIN productos ON productos.id_producto = venta_detalle.id_producto
        WHERE ventas.id_corte = $id_corte
        AND venta_detalle.comentarios LIKE '%[[DESC100]]%'
        ORDER BY ventas.id_venta ASC, venta_detalle.id_detalle ASC");
    $cortesias = array();
    $cortesias_total = 0;
    while ($row = mysql_fetch_assoc($q)) {
        $precio_original = floatval($row['precio_catalogo']);
        if (preg_match('/\[\[PRECIO_ORIG:([0-9.]+)\]\]/', $row['comentarios'], $matches)) {
            $precio_original = floatval($matches[1]);
        }
        $importe = floatval($row['cantidad']) * $precio_original;
        $cortesias_total += $importe;
        $cortesias[] = array(
            'nombre' => $row['nombre'],
            'cantidad' => $row['cantidad'],
            'importe' => $importe,
            'mesa' => $row['mesa'],
            'id_venta' => $row['id_venta']
        );
    }
    if (count($cortesias) > 0) {
        $commands[] = impresion_plugin_comando('text', '');
        $commands[] = impresion_plugin_comando('center');
        $commands[] = impresion_plugin_comando('text', '############### CORTESIAS ################');
        $commands[] = impresion_plugin_comando('left');
        $commands[] = impresion_plugin_comando('text', 'PRODUCTO                    CANT    VALOR');
        foreach ($cortesias as $cortesia) {
            $linea = substr(eliminar_tildes($cortesia['nombre']), 0, 24);
            $linea = str_pad($linea, 26) . str_pad($cortesia['cantidad'], 5, ' ', STR_PAD_LEFT);
            $linea .= str_pad(number_format($cortesia['importe'], 2), 11, ' ', STR_PAD_LEFT);
            $commands[] = impresion_plugin_comando('text', $linea);
            $mesa = $cortesia['mesa'] == 'BARRA' ? 'BARRA' : 'MESA ' . $cortesia['mesa'];
            $commands[] = impresion_plugin_comando('text', '  Folio #' . $cortesia['id_venta'] . ' - ' . $mesa);
        }
        $commands[] = impresion_plugin_comando('right');
        $commands[] = impresion_plugin_comando('text', 'TOTAL CORTESIAS: $' . number_format($cortesias_total, 2));
        $commands[] = impresion_plugin_comando('left');
    }

    $fondo_caja = floatval($corte['fondo_caja']);
    $efectivo_declarado = floatval($corte['efectivoCaja']);
    $tpv_declarada = floatval($corte['tpv']);
    $ajuste = isset($corte['ajuste']) ? intval($corte['ajuste']) : 0;

    // Como el corte anterior:
    // SUBTOTAL = fondo + venta neta
    // TOTAL (EN CAJA esperado) = SUBTOTAL - gastos
    // CAPTURA = efectivo declarado + TPV declarada
    $subtotal_caja = $fondo_caja + $venta_total;
    $total_caja = $subtotal_caja - $gastos_total;
    $total_captura = $efectivo_declarado + $tpv_declarada;
    $diff_captura = $total_captura - $total_caja;

    $commands[] = impresion_plugin_comando('text', '');
    $commands[] = impresion_plugin_comando('center');
    $commands[] = impresion_plugin_comando('text', '############### CORTE CAJA ###############');
    $commands[] = impresion_plugin_comando('left');
    $commands[] = impresion_plugin_comando('text', 'FONDO DE CAJA: ' . number_format($fondo_caja, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'EFECTIVO: ' . number_format($efectivo, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'TARJETAS: ' . number_format($tarjeta, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'TRANSFERENCIAS: ' . number_format($transferencia, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'SUBTOTAL: ' . number_format($subtotal_caja, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'GASTOS: ' . number_format($gastos_total, 2, '.', ''));
    $commands[] = impresion_plugin_comando('text', 'TOTAL: ' . number_format($total_caja, 2, '.', ''));

    if ($ajuste == 0) {
        $commands[] = impresion_plugin_comando('center');
        $commands[] = impresion_plugin_comando('text', '############## CORTE CAPTURA #############');
        $commands[] = impresion_plugin_comando('left');
        $commands[] = impresion_plugin_comando('text', 'EFECTIVO TOTAL: ' . number_format($efectivo_declarado, 2, '.', ''));
        $commands[] = impresion_plugin_comando('text', 'TARJETAS: ' . number_format($tpv_declarada, 2, '.', ''));
        $commands[] = impresion_plugin_comando('text', 'TOTAL: ' . number_format($total_captura, 2, '.', ''));
        $commands[] = impresion_plugin_comando('text', '');
        $commands[] = impresion_plugin_comando('text', '------------------------------------------');
        $commands[] = impresion_plugin_comando('text', '');
        $commands[] = impresion_plugin_comando('right');
        $commands[] = impresion_plugin_comando('text', 'TOTAL VENTA: ' . number_format($total_caja, 2, '.', ''));
        $commands[] = impresion_plugin_comando('text', 'TOTAL CAPTURA: ' . number_format($total_captura, 2, '.', ''));
        if (abs($diff_captura) < 0.01) {
            $commands[] = impresion_plugin_comando('text', 'DIFERENCIA: $0.00');
        } elseif ($diff_captura > 0) {
            $commands[] = impresion_plugin_comando('text', 'SOBRANTE: $' . number_format($diff_captura, 2, '.', ''));
        } else {
            $commands[] = impresion_plugin_comando('text', 'FALTANTE: $' . number_format(abs($diff_captura), 2, '.', ''));
        }
    }

    $commands[] = impresion_plugin_comando('text', '');
    $commands[] = impresion_plugin_comando('right');
    $commands[] = impresion_plugin_comando('doubleWidth2');
    $commands[] = impresion_plugin_comando('text', 'TOTAL COBRADO: $' . number_format($total_cobrado, 2));
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
