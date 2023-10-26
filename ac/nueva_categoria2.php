<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
error_reporting(0);
extract($_POST);
//Validamos datos completos
if(!$nombre) exit("0|0|0|Debe escribir un nombre para la categoría.");

//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);

	//Insertamos datos
	$sql="INSERT INTO categorias (nombre) VALUES ('$nombre')";
	$q=mysql_query($sql);
	$id_categoria=mysql_insert_id();
	if($q){
		echo "1|".$id_categoria."|".$nombre;
	}else{
		echo "0|0|0|Ocurrió un error, intente más tarde.";
	}

?>