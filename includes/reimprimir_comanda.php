<?
include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');
include('../includes/impresora.php');

$id_venta = isset($_POST['id_venta']) ? (int) $_POST['id_venta'] : 0;
if($id_venta <= 0){
	exit('error|id');
}

$q_reset = mysql_query("UPDATE venta_detalle SET impreso=0 WHERE id_venta=$id_venta");
if(!$q_reset){
	exit('error|reset');
}

$result = imprimir_comandas('venta',$id_venta);
if($result !== '1'){
	exit($result);
}

$q_mark = mysql_query("UPDATE venta_detalle SET impreso=1 WHERE id_venta=$id_venta");
if(!$q_mark){
	exit('error|mark');
}

echo '1';