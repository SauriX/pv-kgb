<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

$id_venta = isset($_POST['id_venta']) ? escapar($_POST['id_venta'],1) : false;
if(!$id_venta){
	exit('ID de venta invalido.');
}

$sql = "DELETE FROM venta_detalle WHERE id_venta = $id_venta";
$q = mysql_query($sql);
if(!$q){
	exit('Error al eliminar la mesa, intente nuevamente.');
}

echo "1";
?>