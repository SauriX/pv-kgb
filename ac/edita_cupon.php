<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);
if(!$id_cupon) exit("ERROR de ID.");
if(!$cupon) exit("Debe escribir un nombre de cupón.");
if(!$porcentaje) exit("Debe escribir un porcentaje para el cupón.");
if(!is_numeric($porcentaje)) exit("Debe escribir un número");
if($porcentaje>100) exit("Debe escribir un porcentaje menor a 100");

$id_cupon=escapar($id_cupon,1);

$cupon = limpiaStr($cupon,1,1);
$porcentaje = limpiaStr($porcentaje,1,1);

$sql="UPDATE cupones SET cupon = '$cupon', porcentaje = '$porcentaje' WHERE id_cupon = $id_cupon";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error, intente más tarde.";
}
?>
