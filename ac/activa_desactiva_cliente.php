<?
	include("../includes/session.php");
	include("../includes/dbline.php");
	extract($_POST);
	//print_r($_POST);
	//Validamos datos completos
	//if(!$tipo) exit("No llego el identificador de la operación");
	if(!$id_cliente){
		exit("No llego el identificador del cliente.");
	}
	//Updateamos el estado
	//$sql="UPDATE clientes SET activo='$tipo' WHERE id_cliente=$id_cliente";
	$sql="DELETE FROM clientes WHERE id_cliente=$id_cliente";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error al actualizar el cliente.";
	}
?>