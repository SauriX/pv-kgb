<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora.php");

extract($_POST);
//print_r($_POST);
//exit;
//Validamos datos completos
if(!$ventas) exit("No llegó ningún identificador.");

//Guardamos la salida
$sql = "INSERT INTO cortes_domicilio (id_usuario,fechahora)VALUES('$s_id_usuario','$fechahora')";
$q = mysql_query($sql);
$id_corte_domicilio = mysql_insert_id();
if(!$q) $error = true;


foreach($ventas as $id => $id_venta){
	$sq=@mysql_query("UPDATE ventas_domicilio SET id_corte_domicilio='$id_corte_domicilio' WHERE id_venta_domicilio='$id_venta'");
	if(!$sq) $error = true;
}

if(!$error){
	$sq=@mysql_query("UPDATE ventas_domicilio SET id_corte_domicilio='$id_corte_domicilio' WHERE cancelado = 1");
	imprimir_corte_repartidores($id_corte_domicilio);
	//enviaCorte($id_corte_domicilio);
	echo "1";
}else{
	echo "ocurrió un error no se hizo el corte";
}


?>
