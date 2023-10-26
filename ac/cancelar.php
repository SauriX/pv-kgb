<?
	include('../includes/session.php');
	include('../includes/db.php');
	$id_usuario = $s_id_usuario;
	$fecha = date('Y-m-d');
	$hora = date('H:i:s');

	if($_SESSION['s_tipo']!='1'){
		$sql ="SELECT devoluciones FROM usuarios WHERE id_usuario = $id_usuario";
		$q = mysql_query($sql);
		$devs = @mysql_result($q, 0);
		if(!$devs){
			exit('Usted no cuenta con los permisos necesarios para realizar una devolución.');
		}
	}
	mysql_query("BEGIN");
	$sql = "INSERT INTO cancelaciones (id_usuario,fecha,hora)VALUES('$id_usuario','$fecha','$hora')";
	$q = mysql_query($sql);
	if($q) {
		$id_venta = mysql_insert_id(); 
	}else{
		$error = true;		
	}
	foreach($_POST as $p => $v){
		foreach($v as $input_name => $cantidad){
			$item = explode("_",$input_name);
			$id_producto = $item[1];
			$precio = $item[2];
			$sql="INSERT INTO cancelaciones_detalle
			(id_cancelacion,id_producto,cantidad,precio_venta)VALUES('$id_venta','$id_producto','$cantidad','$precio')";
			$query = mysql_query($sql);
			if(!$query){
				$error = true;		
		
			}

		}
	}
	if($error==false){
		mysql_query("COMMIT");
		echo "1";
	}else{
		mysql_query("ROLLBACK");
		echo "\n\nHubo problema, por favor intenta de nuevo";
	}