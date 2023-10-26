<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

if(!$id_domicilio_direccion) exit('Ocurrió un error, no llego el identificador de la dirección, contacta a soporte.');
	
$sql ="DELETE FROM domicilio_direcciones WHERE id_domicilio_direccion = $id_domicilio_direccion";
$q = mysql_query($sql);
if(!$q) exit('Error al eliminar, intente más tarde.');
	

echo "1";


?>
