<?
include("../includes/db.php");
include("../includes/funciones.php");
error_reporting(0);

if(!$_GET['id_venta_domicilio']){ exit("Error de ID");}

$id_venta_domicilio=escapar($_GET['id_venta_domicilio'],1);

$sql = "SELECT id_venta_domicilio FROM ventas_domicilio WHERE id_venta_domicilio=$id_venta_domicilio AND id_domicilio_salida = 0 AND id_corte_domicilio = 0  LIMIT 1";
$q=mysql_query($sql);
$valida=mysql_num_rows($q);
if(!$valida){
	$data->resultado=3;
	echo json_encode($data);
}else{

	$sql="SELECT id_venta_domicilio,ventas_domicilio.id_domicilio_direccion, fechahora_alta, domicilio.nombre AS cliente, direccion, comentarios, descuento_cantidad, descuento_porcentaje FROM ventas_domicilio
	LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion=ventas_domicilio.id_domicilio_direccion
	LEFT JOIN domicilio ON domicilio.id_domicilio=domicilio_direcciones.id_domicilio
	WHERE ventas_domicilio.id_venta_domicilio=$id_venta_domicilio AND id_domicilio_salida=0  LIMIT 1";
	$q=mysql_query($sql);
	$val=mysql_num_rows($q);
	if($val){
		$data = mysql_fetch_object($q);

		$sq="SELECT SUM(cantidad*precio_venta) AS total FROM venta_domicilio_detalle WHERE id_venta_domicilio=$id_venta_domicilio";
		$qu=mysql_query($sq);
		$dt=mysql_fetch_assoc($qu);

		$descuento=$data->descuento_cantidad;
		$data->hora=horaVista(horaSinFecha($data->fechahora_alta));
		$data->transcurrido=substr($data->fechahora_alta,0,10)."T".substr($data->fechahora_alta,11,8);
		$data->resultado=1;
		$data->total=number_format($dt['total']-$descuento,2);
		echo json_encode($data);
	}else{
		$data->resultado=2;
		echo json_encode($data);
	}
}
?>
