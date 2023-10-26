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

	//Insertamos datos
	$sql="INSERT INTO categorias (nombre,impresora) VALUES ('$nombre','$impresora')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}

?>