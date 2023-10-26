<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");


extract($_POST);

$sql ="DELETE FROM gastos WHERE id_gasto = $id_gasto";
$q = mysql_query($sql);
if(!$q) exit('Error al eliminar, intente nuevamente.');

$sql_g="SELECT COUNT(*) FROM gastos  WHERE id_corte=0";
$q_g=mysql_query($sql_g,$conexion);
$cuantos = @mysql_result($q_g, 0);


echo $cuantos;


?>
