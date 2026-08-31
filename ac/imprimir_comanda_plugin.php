<?php
include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');
include('../includes/impresora_plugin.php');

header('Content-Type: application/json; charset=utf-8');

$id_venta = isset($_POST['id_venta']) ? intval($_POST['id_venta']) : 0;
$impresora = isset($_POST['impresora']) ? trim($_POST['impresora']) : '';
$tipo = isset($_POST['tipo']) && $_POST['tipo'] == 'domicilio' ? 'domicilio' : 'venta';

if (isset($_POST['wifi'])) {
    $printList = impresion_plugin_wifi(isset($_POST['password']) ? $_POST['password'] : '');
    if ($printList === false) {
        echo json_encode(array('ok' => false, 'error' => 'impresora_cortes_no_configurada'));
        exit;
    }
    echo json_encode(array('ok' => true, 'printList' => $printList));
    exit;
}

if (isset($_POST['codigo'])) {
    $printList = impresion_plugin_codigo(
        isset($_POST['codigo_valor']) ? $_POST['codigo_valor'] : '',
        isset($_POST['monto']) ? $_POST['monto'] : 0,
        isset($_POST['metodo']) ? $_POST['metodo'] : '',
        isset($_POST['cuenta']) ? $_POST['cuenta'] : ''
    );
    if ($printList === false) {
        echo json_encode(array('ok' => false, 'error' => 'impresora_cuentas_no_configurada'));
        exit;
    }
    echo json_encode(array('ok' => true, 'printList' => $printList));
    exit;
}

if (isset($_POST['comprobante_domicilio'])) {
    $printList = impresion_plugin_comprobante_domicilio(
        isset($_POST['nombre']) ? $_POST['nombre'] : '',
        isset($_POST['telefono']) ? $_POST['telefono'] : '',
        isset($_POST['direccion']) ? $_POST['direccion'] : ''
    );
    if ($printList === false) {
        echo json_encode(array('ok' => false, 'error' => 'impresora_domicilio_no_configurada'));
        exit;
    }
    echo json_encode(array('ok' => true, 'printList' => $printList));
    exit;
}

if ($id_venta <= 0) {
    echo json_encode(array('ok' => false, 'error' => 'id_venta_invalido'));
    exit;
}

if (isset($_POST['factura'])) {
    $printList = impresion_plugin_factura($id_venta);
    if ($printList === false) {
        echo json_encode(array('ok' => false, 'error' => 'factura_no_encontrada_o_no_se_pudo_leer_el_xml'));
        exit;
    }
    echo json_encode(array('ok' => true, 'printList' => $printList));
    exit;
}

if (isset($_POST['gasto'])) {
    $printList = impresion_plugin_gasto($id_venta);
    if ($printList === false) {
        echo json_encode(array('ok' => false, 'error' => 'gasto_no_encontrado_o_impresora_no_configurada'));
        exit;
    }
    echo json_encode(array('ok' => true, 'printList' => $printList));
    exit;
}

if (isset($_POST['corte'])) {
    $printList = impresion_plugin_corte($id_venta);
    if ($printList === false) {
        echo json_encode(array('ok' => false, 'error' => 'corte_no_encontrado_o_impresora_no_configurada'));
        exit;
    }
    echo json_encode(array('ok' => true, 'printList' => $printList));
    exit;
}

if (isset($_POST['ticket_domicilio'])) {
    $printList = impresion_plugin_ticket_domicilio($id_venta, $impresora);
    if ($printList === false) {
        echo json_encode(array('ok' => false, 'error' => 'ticket_domicilio_no_encontrado_o_impresora_no_configurada'));
        exit;
    }
    echo json_encode(array('ok' => true, 'printList' => $printList));
    exit;
}

if (isset($_POST['ticket_mesa'])) {
    $tipo_ticket = isset($_POST['tipo_ticket']) ? $_POST['tipo_ticket'] : 'cobrar';
    $printList = impresion_plugin_ticket_mesa($id_venta, $tipo_ticket);
    if ($printList === false) {
        echo json_encode(array('ok' => false, 'error' => 'ticket_no_encontrado_o_impresora_no_configurada'));
        exit;
    }
    echo json_encode(array('ok' => true, 'printList' => $printList));
    exit;
}

if (isset($_POST['reimprimir']) && $tipo == 'venta') {
    $q = mysql_query("UPDATE venta_detalle SET impreso = 0 WHERE id_venta = $id_venta AND id_producto != 0");
    if (!$q) {
        echo json_encode(array('ok' => false, 'error' => 'no_se_pudo_preparar_reimpresion'));
        exit;
    }
}

if (isset($_POST['marcar_impresa'])) {
    if ($tipo == 'domicilio') {
        echo json_encode(array('ok' => true));
        exit;
    }
    $q = mysql_query("UPDATE venta_detalle SET impreso = 1 WHERE id_venta = $id_venta AND id_producto != 0");
    if (!$q) {
        echo json_encode(array('ok' => false, 'error' => 'no_se_pudo_marcar_impresa'));
        exit;
    }
    echo json_encode(array('ok' => true));
    exit;
}

if (isset($_POST['listar_impresoras'])) {
    echo json_encode(array(
        'ok' => true,
        'printers' => impresion_plugin_impresoras_comanda($id_venta, $tipo)
    ));
    exit;
}

if ($impresora == '') {
    echo json_encode(array('ok' => false, 'error' => 'impresora_requerida'));
    exit;
}

$printList = impresion_plugin_comanda($id_venta, $impresora, $tipo);
if ($printList === false) {
    echo json_encode(array('ok' => false, 'error' => 'comanda_no_encontrada'));
    exit;
}

echo json_encode(array('ok' => true, 'printList' => $printList));
