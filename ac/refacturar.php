<?php 
include('../includes/session.php');
include('../includes/db.php');

extract($_POST);

if($id_venta){
    $sql ="UPDATE ventas SET facturado = 0 AND pendiente_facturar = 0 AND pendiente_monto = 0 WHERE id_venta = $id_venta";
    $q = mysql_query($sql);
    echo "1";
}else{
    echo 'Falta id_venta';
}