<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre.");
//if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");

//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);

//Verificamos que el usuario no exista

//Insertamos datos
$sql="INSERT INTO repartidores (nombre,telefono) VALUES ('$nombre','$telefono')";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error, intente más tarde.";
}
?>
