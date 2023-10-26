<?
    $id_factura = $_GET['id_factura'];
    echo file_get_contents("http://tacoloco.mx/facturacion/cancelar_factura.php?id_factura=$id_factura");

