<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

if($s_tipo!=1) exit('Usted no tiene los privilegios requeridos para eliminar movimientos.');

extract($_POST);

//$id_mov
//$tipo pago deuda

if($tipo=='pago'){
	
	$sql ="DELETE FROM abonos WHERE id_abono = $id_mov";
	$q = mysql_query($sql);
	if(!$q) exit('Error al eliminar el pago, intente nuevamente.');
	
}else{

	$sql ="SELECT id_venta FROM venta_detalle WHERE id_detalle = $id_mov";
	$q = mysql_query($sql);
	$id_venta = mysql_result($q, 0);
	
	$sql = "SELECT * FROM venta_detalle WHERE id_venta = $id_venta";
	$q = mysql_query($sql);
	$n = mysql_num_rows($q);

	if($n>1){
	
		$sql = "DELETE FROM venta_detalle WHERE id_detalle = $id_mov";
		$q = mysql_query($sql);
		if(!$q) exit('Error al eliminar el cargo, intente nuevamente');
		
	}elseif($n==1){

		$sql = "DELETE FROM venta_detalle WHERE id_venta = $id_venta";
		$q = mysql_query($sql);
		if(!$q) exit('Error al eliminar el cargo, intente nuevamente');
		
		$sql = "DELETE FROM ventas WHERE id_venta = $id_venta";
		$q = mysql_query($sql);
		if(!$q) exit('Error al eliminar el cargo, intente nuevamente');

		$sql = "DELETE FROM creditos WHERE id_venta = $id_venta";
		$q = mysql_query($sql);
		if(!$q) exit('Error al eliminar el cargo, intente nuevamente');
		
	}else{
		exit('Error, contacte a soporte.');
	}
		
	
}

echo "1";


?>
