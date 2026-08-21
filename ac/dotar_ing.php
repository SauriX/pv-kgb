<?

include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');
$id_usuario = $s_id_usuario;

//$hora = date('H:i:s');
 
extract($_POST);
$fecha = date('Y-m-d');

$error = false;
$coment = isset($coment) ? limpiaStr($coment,1,1) : '';
$cobrar_producto = (isset($cobrar_producto) && is_array($cobrar_producto)) ? $cobrar_producto : array();
//$fecha= fechaBase($fecha);
//CHECAMOS SI MANDO LOS DATOS
//if(!$factura)exit("Debe ingresar el número o código de factura de compra");
if(!count($cobrar_producto)) exit("Debe agregar al menos un ingrediente.");

//if(!$id_proveedor)exit("Debe ingresar el proveedor de la compra");
mysql_query("BEGIN");

$sql = "INSERT INTO dotaciones_ingredientes (id_usuario,fecha,comentario)VALUES('$id_usuario','$fecha','$coment')";
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
		if(count($item)<4){
			$error = true;
			continue;
		}
		$id_producto = $item[1];
		$precio = $item[2];
		$cantidad = $item[3];
		$id_producto = intval($id_producto);
		$cantidad = floatval($cantidad);
		if($id_producto<=0 || $cantidad<=0){
			$error = true;
			continue;
		}
		
		$sql="INSERT INTO dotaciones_detalle_ingredientes
		(id_dotacion,id_producto,cantidad)VALUES('$id_venta','$id_producto','$cantidad')";
		$query = mysql_query($sql);

		if(!$query) $error = true;		
			
		
		
	//}
}


if($error==false){
	mysql_query("COMMIT");
	echo "1";
}else{
	mysql_query("ROLLBACK");
	echo "Hubo problema, por favor intenta de nuevo";
}