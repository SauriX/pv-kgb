<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_repartidor) exit("Error al identificar el usuario.");
if(!$nombre) exit("Debe escribir un nombre.");

//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);
$id_repartidor=escapar($id_repartidor,1);

//Insertamos datos
$sql="UPDATE repartidores SET nombre='$nombre', telefono='$telefono' WHERE id_repartidor=$id_repartidor";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error, intente más tarde.";
}
?>
