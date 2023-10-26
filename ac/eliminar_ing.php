<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");


extract($_POST);

$sql ="DELETE FROM productosxbase WHERE id_base= $producto and  id_producto = $tipo";
$q = mysql_query($sql);


if(!$q) exit('Error al eliminar, intente nuevamente.');



echo "1";


?>