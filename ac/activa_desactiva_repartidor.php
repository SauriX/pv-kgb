<?
	include("../includes/session.php");
	include("../includes/db.php");
	extract($_POST);
	//print_r($_POST);
	//Validamos datos completos
	//if(!$tipo) exit("No llego el identificador de la operación");
	if(!$id_repartidor){
		exit("No llego el identificador del usuario");
	}
	//Updateamos el estado
	$sql="UPDATE repartidores SET activo='$tipo' WHERE id_repartidor=$id_repartidor";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error al actualizar el ususario";
	}
?>
