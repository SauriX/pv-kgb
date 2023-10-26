<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");


extract($_POST);

$sql ="DELETE FROM venta_detalle WHERE id_detalle = $id_detalle";
$q = mysql_query($sql);
if(!$q) exit('Error al eliminar, intente nuevamente.');



echo "1";


?>
