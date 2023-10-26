<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_cupon']){ exit("Error de ID");}

$id_producto=escapar($_GET['id_cupon'],1);

$sql="SELECT * FROM cupones WHERE id_cupon=$id_producto";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['id_cupon']."|".$ft['cupon']."|".$ft['porcentaje'];
}else{
	echo "error";
}
?>
