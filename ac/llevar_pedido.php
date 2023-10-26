<?

include("../includes/session.php");

include("../includes/db.php");

include("../includes/funciones.php");

include('../includes/impresora.php');
//exit('here');
extract($_POST);
//print_r($_POST);
//Validamos datos completos
if(!$id_repartidor) exit("No llegó el identificador del repartidor.");
if(!$id_venta) exit("No llegó ningún pedido.");

$id_repartidor=escapar($id_repartidor,1);

if(!mysql_query("BEGIN")) $error = true;

//Guardamos la salida
$sql = "INSERT INTO ventas_domicilio_salidas (id_repartidor,fechahora_salida)VALUES('$id_repartidor','$fechahora')";
$q = mysql_query($sql);

$id_venta_domicilio_salida = mysql_insert_id();
if(!$q) $error = true;


foreach($id_venta as $id => $venta){
	$sq=@mysql_query("UPDATE ventas_domicilio SET id_domicilio_salida='$id_venta_domicilio_salida' WHERE id_venta_domicilio='$venta'");
	if(!$sq) $error = true;
}

if(!$error){
	if(mysql_query("COMMIT"));
	imprimir_salida($id_venta_domicilio_salida);
	echo "1";
}else{
	if(mysql_query("ROLLBACK"));
	echo "ocurrió un error no se asignaron los pedidos";
}


?>
