<?php
session_start();
include('../includes/db.php');
include('../includes/funciones.php');
include('../includes/impresora_plugin.php');

header('Content-Type: application/json; charset=utf-8');

$id_venta = isset($_POST['id_venta']) ? intval($_POST['id_venta']) : 0;
$impresora = isset($_POST['impresora']) ? trim($_POST['impresora']) : '';

if ($id_venta <= 0) {
    echo json_encode(array('ok' => false, 'error' => 'id_venta_invalido'));
    exit;
}

if (isset($_POST['listar_impresoras'])) {
    echo json_encode(array(
        'ok' => true,
        'printers' => impresion_plugin_impresoras_comanda($id_venta)
    ));
    exit;
}

if ($impresora == '') {
    echo json_encode(array('ok' => false, 'error' => 'impresora_requerida'));
    exit;
}

$printList = impresion_plugin_comanda($id_venta, $impresora);
if ($printList === false) {
    echo json_encode(array('ok' => false, 'error' => 'comanda_no_encontrada'));
    exit;
}

echo json_encode(array('ok' => true, 'printList' => $printList));
