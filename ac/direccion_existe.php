<?php
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora.php");

extract($_POST);

if(!$nombre) exit("Debe escribir un nombre.");
if(!$direccion) exit("Debe escribir una dirección.");
if(!$id_detalle) exit("id_detalle missing.");
if(!$id_domicilio) exit("id_domicilio missing.");

$direccion=str_replace('"','-',$direccion);  
$direccion=str_replace("'",'-',$direccion); 
$direccion=limpiaStr($direccion,1,1);
$direccion=str_replace("\\n",'#¢#',$direccion);  
$direccion=str_replace("\\",'',$direccion);  
$direccion=str_replace("#¢#",'\\n',$direccion);  

$sql ="UPDATE domicilio SET nombre = '$nombre' WHERE id_domicilio = $id_domicilio";
$q = mysql_query($sql);

$sql ="UPDATE domicilio_direcciones SET direccion = '$direccion' WHERE id_domicilio_direccion = $id_detalle";

if(mysql_query($sql)){
	
	imprimir_domicilio($nombre,$numero,$direccion);
	$sql = "INSERT INTO impresion_domicilio (numero,nombre,direccion,fecha_hora)VALUES('$numero','$id_domicilio','$direccion','$fechahora')";
		$q = mysql_query($sql);	
	echo '1';		

	
}
