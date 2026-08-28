<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre para la categoría.");

//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);
$impresora=limpiaStr($impresora,1,1);
$impresora_para_llevar=limpiaStr($impresora_para_llevar,1,1);

	//Insertamos datos
	$sql="INSERT INTO categorias (nombre,impresora,impresora_para_llevar) VALUES ('$nombre','$impresora','$impresora_para_llevar')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}

?>