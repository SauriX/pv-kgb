<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");


extract($_POST);

$sql ="DELETE FROM productos_base WHERE id_base = $id_producto";
$q = mysql_query($sql);
if(!$q) exit('Error al eliminar, intente nuevamente.');



echo "1";


?>