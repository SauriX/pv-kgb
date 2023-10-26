<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");


extract($_POST);

$sql = "SELECT SUM((cantidad*precio_venta)) FROM venta_detalle WHERE id_venta = $id_venta";
$q = mysql_query($sql);
$data = @mysql_result($q, 0);
if(!$q) exit('Error al calcular mesa, intente nuevamente.');

echo number_format($data,2);


?>
