<?
	include("../includes/session.php");
	include("../includes/db.php");
	extract($_POST);
	if(!$id_cupon){
		exit("No llego el identificador del cupon");
	}
	//Updateamos el estado
	$sql="UPDATE cupones SET activo = '$tipo' WHERE id_cupon = $id_cupon";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error al actualizar el cupon";
	}
?>
