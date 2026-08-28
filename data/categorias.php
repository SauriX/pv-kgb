<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!isset($_GET['id_categoria']) || !$_GET['id_categoria']){ exit("error|id");}

$id_categoria=escapar($_GET['id_categoria'],1);

$sql="SELECT * FROM categorias WHERE id_categoria=$id_categoria";
$query=mysql_query($sql);

if(!$query){
	echo "error|query";
	exit;
}

$ft=mysql_fetch_assoc($query);
if($ft){
	$nombre = isset($ft['nombre']) ? $ft['nombre'] : '';
	$usuario = isset($ft['usuario']) ? $ft['usuario'] : '';
	$id_tipo_usuario = isset($ft['id_tipo_usuario']) ? $ft['id_tipo_usuario'] : '';
	$impresora = isset($ft['impresora']) ? $ft['impresora'] : '';
	$impresora_para_llevar = isset($ft['impresora_para_llevar']) ? $ft['impresora_para_llevar'] : '';
	echo $nombre."|".$usuario."|".$id_tipo_usuario."|".$impresora."|".$impresora_para_llevar;
}else{
	echo "error|not_found";
}
?>