<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!isset($_GET['id_usuario']) || !$_GET['id_usuario']){ exit("error|id");}

$id_usuario=escapar($_GET['id_usuario'],1);

$sql="SELECT * FROM usuarios WHERE id_usuario=$id_usuario";
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
	$cortes = isset($ft['cortes']) ? $ft['cortes'] : '';
	$devoluciones = isset($ft['devoluciones']) ? $ft['devoluciones'] : '';
	echo $nombre."|".$usuario."|".$id_tipo_usuario."|".$cortes."|".$devoluciones;
}else{
	echo "error|not_found";
}
?>