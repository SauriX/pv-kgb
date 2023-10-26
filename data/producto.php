<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_producto']){ exit("Error de ID");}

$id_producto=escapar($_GET['id_producto'],1);

$sql="SELECT * FROM productos WHERE id_producto=$id_producto";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['id_categoria']."|".$ft['codigo']."|".$ft['nombre']."|".$ft['precio_venta']."|".$ft['precio_compra']."|".$ft['sinn']."|".$ft['extra']."|".$ft['paquete'];
}else{
	echo "error";
}
?>