<?php
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora.php");

extract($_POST);

if(!$nombre) exit("Debe escribir un nombre.");
if(!$direccion) exit("Debe escribir una dirección.");

$direccion=str_replace('"','-',$direccion);  
$direccion=str_replace("'",'-',$direccion); 
$direccion=limpiaStr($direccion,1,1);
$direccion=str_replace("\\n",'#¢#',$direccion);  
$direccion=str_replace("\\",'',$direccion);  
$direccion=str_replace("#¢#",'\\n',$direccion);  

if($id_domicilio){
	$sql ="UPDATE domicilio SET nombre = '$nombre' WHERE id_domicilio = $id_domicilio";
	$q = mysql_query($sql);
	
}else{
	
	$sql = "INSERT INTO domicilio (numero,nombre)VALUES('$numero','$nombre')";
	$q = mysql_query($sql);
	$id_domicilio = mysql_insert_id();
	
}

	$sql ="INSERT INTO domicilio_direcciones (id_domicilio,direccion)VALUES($id_domicilio,'$direccion')";
	
	if(mysql_query($sql)){
		
		imprimir_domicilio($nombre,$numero,$direccion);
		$sql = "INSERT INTO impresion_domicilio (numero,nombre,direccion,fecha_hora)VALUES('$numero','$id_domicilio','$direccion','$fechahora')";
		$q = mysql_query($sql);	
		
		echo '1';		
		
	}