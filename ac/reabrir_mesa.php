<?php 
include('../includes/session.php');
include('../includes/db.php');
include('../includes/impresora.php');

extract($_POST);

$id_venta = trim($id_venta);

$f = date('Y-m-d H:i:s');
$reabrir=0;
$sql74="SELECT pagada FROM configuracion";
		$query74 = mysql_query($sql74);
		while($ft=mysql_fetch_assoc($query74)){
        $reabrir = $ft['pagada'];
		}
/**/
if($reabrir=="0"){
$sql = "SELECT * FROM ventas WHERE id_venta = $id_venta AND pagada = 1";
$q = mysql_query($sql);
$hay = mysql_num_rows($q);

if($hay) exit('Mesa ya pagada');
}
/**/


$sql = "SELECT mesa FROM ventas WHERE id_venta = $id_venta";
$q = mysql_query($sql);
$mesa = mysql_result($q, 0);
$sql = "SELECT * FROM ventas WHERE mesa = '$mesa' AND abierta = 1";
$q = mysql_query($sql);
$n = mysql_num_rows($q);
if($n) exit('Hay otra mesa abierta con el mismo número ('.$mesa.').');


if(!mysql_query("BEGIN")) $error = true;

if($id_venta){


	$sql ="INSERT INTO 
	ventas_cancelaciones (id_venta,id_usuario_cancelador,fechahora_cancelacion,motivo)VALUES('$id_venta','$s_id_usuario','$f','$motivo')";
	if(!mysql_query($sql)) $error = true;
	$id_venta_cancelacion = mysql_insert_id();
	if($reabrir=="0"){
	$sql ="UPDATE ventas SET abierta = 1,reabierta = $id_venta_cancelacion WHERE id_venta = $id_venta";
	if(!mysql_query($sql)) $error = true;
	}else{
		$sql ="UPDATE ventas SET abierta = 1,pagada=0,reabierta = $id_venta_cancelacion WHERE id_venta = $id_venta";
	if(!mysql_query($sql)) $error = true;
	}
		
	$sql ="SELECT*FROM venta_detalle WHERE id_venta = $id_venta";
	$qx = mysql_query($sql);
	if(!$qx) $error = true;

	while($ft = mysql_fetch_assoc($qx)){
		
		$idp = $ft['id_producto'];
		$cant = $ft['cantidad'];
		$precio = $ft['precio_venta'];
		
		$sql_detalle ="INSERT INTO 
		ventas_cancelaciones_detalle 
		(id_venta,id_producto,cantidad,precio_venta,id_venta_cancelacion)
		VALUES
		('$id_venta','$idp','$cant','$precio','$id_venta_cancelacion')";
		if(!mysql_query($sql_detalle)) $error = true;
		
	}
}

if($error){	
	if(mysql_query("ROLLBACK")) exit("ROLLBACK");
}else{
	if(mysql_query("COMMIT")) exit("1");	
	
}
