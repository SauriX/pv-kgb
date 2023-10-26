<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_categoria']){ exit("Error de ID");}

$id_categoria=escapar($_GET['id_categoria'],1);

$sql="SELECT * FROM categorias WHERE id_categoria=$id_categoria";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['nombre']."|".$ft['usuario']."|".$ft['id_tipo_usuario']."|".$ft['impresora'];
}else{
	echo "error";
}
?>