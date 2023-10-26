<?
	include("../includes/session.php");
	include("../includes/db.php");
	include("../includes/funciones.php");

	extract($_GET);
	//print_r($_POST);
	//Validamos datos completos
	if(!$id_venta_domicilio_salida) exit("No llegó el identificador del repartidor.");
	//$datos=explode("-",$id_venta_domicilio_salida);
	//$tipo=$datos[0];
	//$id_venta_domicilio_salida=$datos[1];

	//if($tipo!="S") exit("Ocurrió un error verifica el ticket que estas escaneando.");
	//$id_venta_domicilio_salida=substr($id_venta_domicilio_salida,1,10);
	$id_venta_domicilio_salida=$id_venta_domicilio_salida;
	//$id_venta_domicilio_salida=escapar($id_venta_domicilio_salida,1);

	$q=mysql_query("SELECT * FROM ventas_domicilio_salidas WHERE id_venta_domicilio_salida='$id_venta_domicilio_salida' AND fechahora_regreso IS NOT NULL");
	$valida=mysql_num_rows($q);
	if($valida>0){
		exit("El servicio ya se ha checado.");
	}else{
		//echo "SELECT * FROM ventas_domicilio_salidas WHERE id_venta_domicilio_salida='$id_venta_domicilio_salida' AND fechahora_regreso IS NULL";
		$q=mysql_query("SELECT * FROM ventas_domicilio_salidas WHERE id_venta_domicilio_salida='$id_venta_domicilio_salida' AND fechahora_regreso IS NULL");
		$valida=mysql_num_rows($q);
		if(!$valida){ exit("No se encontro la salida del servicio."); }

		$sq=@mysql_query("UPDATE ventas_domicilio_salidas SET fechahora_regreso='$fechahora' WHERE id_venta_domicilio_salida='$id_venta_domicilio_salida' AND fechahora_regreso IS NULL");
		if(!$sq) $error = true;
		if(!$error){
			echo "1";
		}else{
			echo "Ocurrió un error no se checo la llegada";
		}
	}

?>
