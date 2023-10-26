<?
	include("../includes/session.php");
	include("../includes/db.php");
	extract($_POST);
	if(!$id_producto){
		exit("No llego el identificador del producto");
	}
	//Updateamos el estado
	$sql="UPDATE productos SET activo='$tipo' WHERE id_producto=$id_producto";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error al actualizar el producto";
	}
?>