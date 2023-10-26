<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$establecimiento) exit("Debe especificar un nombre para el establecimiento.");
if(!$representante) exit("Debe especificar el nombre del representante.");
if(!$telefono) exit("Debe escribir un teléfono.");
if(!$direccion) exit("Debe escribir una dirección.");

if($autobackup=="on"){
	$autobackup="1";
}else{
	$autobackup="0";
}

if($comandaim=="on"){
	$comandaim="1";
	
}else{
	$comandaim="0";
	;
	
}

if($autocash=="on"){
	$autocash="1";
}else{
	$autocash="0";
}




if($paquete=="on"){
	$paquete="1";
}else{
	$paquete="0";
}

//Formateamos y validamos los valores
$establecimiento=limpiaStr($establecimiento,1,1);
$representante=limpiaStr($representante,1,1);
$direccion=limpiaStr($direccion,1,1);


	//Insertamos datos
	$sql="UPDATE configuracion SET auto_cobro='$autocash' ,establecimiento='$establecimiento', representante='$representante', rfc='$rfc', telefono='$telefono', direccion='$direccion', comandain='$comandaim', autobackup='$autobackup',impresora_sd='$impresora_sd',impresora_cuentas='$impresora_cuentas',impresora_cortes='$impresora_cortes',paquetes='$paquete'";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>