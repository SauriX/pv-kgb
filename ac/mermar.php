<?php 
/* guardar_adeudo.php maneja una estructura similar (guarda una venta con moroso)*/
include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');
$id_usuario = $s_id_usuario;
//$fecha = date('Y-m-d');
//$hora = date('H:i:s');

$total_totales = 0;

extract($_POST);

mysql_query("BEGIN");

$sql = "INSERT INTO merma (id_usuario,fecha,observaciones)VALUES('$id_usuario','$fecha','$coment')";

$q = mysql_query($sql);
if($q) {
	$id_venta = mysql_insert_id(); 
}else{
	$error = true;		
}

unset($_POST['id_cliente']);
unset($_POST['abono']);

foreach($cobrar_producto as $p => $v){
	//foreach($v as $input_name => $cantidad){
		
		$item = explode("_",$p);
		$id_producto = $item[1];
		$precio = $item[2];
		$cantidad = $item[3];
		
		$sql="INSERT INTO merma_detalle
		(id_merma,id_producto,cantidad)VALUES('$id_venta','$id_producto','$cantidad')";
		$query = mysql_query($sql);

		if(!$query) {$error = true;	
		}
		
			
		
		$total_totales+=$precio*$cantidad;
		
	//}
}


if($error==false){
	mysql_query("COMMIT");
	echo "1";
}else{
	mysql_query("ROLLBACK");
	echo "Hubo problema, por favor intenta de nuevo";
}