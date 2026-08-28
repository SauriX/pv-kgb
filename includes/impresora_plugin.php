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

function impresion_plugin_impresoras_comanda($id_venta)
{
    $id_venta = intval($id_venta);
    $config = impresion_plugin_configuracion();
    $comandain = intval($config['comandain']);
    $impresoras = array();
    $sql = "SELECT categorias.impresora, categorias.impresora_para_llevar, ventas.para_llevar
        FROM venta_detalle
        LEFT JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
        LEFT JOIN productos ON productos.id_producto = venta_detalle.id_producto
        LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
        WHERE venta_detalle.id_venta = $id_venta
        AND venta_detalle.id_producto != 0 AND venta_detalle.impreso = 0";
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

function impresion_plugin_comanda($id_venta, $impresora)
{
    $id_venta = intval($id_venta);
    $impresora = trim($impresora);
    $config = impresion_plugin_configuracion();
    $comandain = intval($config['comandain']);
    $commands = array();
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
