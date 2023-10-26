<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

$fecha=date("Y-n-d");
$hora=date("H:i:s");
//Validamos datos completos
if(!$id_cliente) exit("Debe seleccionar un cliente.");
if(!$tipo_cargo) exit("Debe seleccionar un tipo de cargo.");
if(!$desayunos){
	if(!$almuerzos) exit("Debe comprar comidas o desayunos.");
}


//Creamos los productos y precios
if($tipo_cargo==1){
	$precio_base="25";
	$producto_desayuno=1;
	$producto_almuerzo=2;
}elseif($tipo_cargo==2){
	$precio_base="30";
	$producto_desayuno=3;
	$producto_almuerzo=4;
}else{
	exit("Ocurrió un error con el tipo de cargo, intente más tarde.");
}

//Formateamos y validamos los valores
$id_cliente=escapar($id_cliente,1);
$tipo_cargo=escapar($tipo_cargo,1);

//Obtenemos los créditos actuales
$creditos_sql="SELECT * FROM comidas_creditos WHERE id_Cliente=$id_cliente";
$creditos_q=mysql_query($creditos_sql);
$datos_creditos=mysql_fetch_assoc($creditos_q);
$desayunos_actual=$datos_creditos['creditos_desayuno'];
$almuerzos_actual=$datos_creditos['creditos_almuerzo'];
//Nuevos créditos
$desayunos_nuevo=$desayunos_actual+$desayunos;
$almuerzos_nuevo=$almuerzos_actual+$almuerzos;
//Hacemos la compra de comidas afectamos ventas, ventas_detalle y comidas_creditos_recargas, updateamos comidas_creditos


//Insertamos la venta
$sql="INSERT INTO ventas (id_usuario,fecha,hora) VALUES ('$s_id_usuario','$fecha','$hora')";
$q=mysql_query($sql);
$id_venta=mysql_insert_id();
if($q){
	//Insertamos el detalle de la venta
	if($desayunos>0){
		$sql="INSERT INTO venta_detalle (id_venta,id_producto,cantidad,precio_venta) VALUES ('$id_venta','$producto_desayuno','$desayunos','$precio_base')";
		$q=mysql_query($sql);
	}
	
	if($almuerzos>0){
		$sql="INSERT INTO venta_detalle (id_venta,id_producto,cantidad,precio_venta) VALUES ('$id_venta','$producto_almuerzo','$almuerzos','$precio_base')";
		$q=mysql_query($sql);
	}
	
	//Guardamos la recarga de comidas
	$recarga_sql="INSERT INTO comidas_creditos_recargas (id_cliente,id_venta,creditos_desayuno,creditos_almuerzo) VALUES ('$id_cliente','$id_venta','$desayunos','$almuerzos')";
	$recarga_q=mysql_query($recarga_sql);
	
	//Updateamos los créditos del cliente
	$actualiza_sql="UPDATE comidas_creditos SET creditos_desayuno='$desayunos_nuevo', creditos_almuerzo='$almuerzos_nuevo' WHERE id_cliente='$id_cliente'";
	$actualiza_q=mysql_query($actualiza_sql);

	echo "1";
	
}else{
	echo "Ocurrió un error, intente más tarde.";
}

?>