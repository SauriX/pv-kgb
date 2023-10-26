<?
	include("../includes/session.php");
	include("../includes/db.php");
	include("../includes/funciones.php");
	extract($_GET);
	if(!$id_venta_domicilio){
		exit("No llegó el identificador.");
	}
	if(!$motivo){
		exit("Por favor mencionanos el motivo por el que estas cancelando este pedido.");
	}
	$id_venta_domicilio=escapar($id_venta_domicilio,1);
	$sq=@mysql_query("UPDATE ventas_domicilio SET cancelado='1', id_usuario_cancelo='$s_id_usuario', fechahora_cancelacion='$fechahora', motivo_cancelacion='$motivo' WHERE id_venta_domicilio='$id_venta_domicilio'");
	if(!$sq){
		$error = true;
	}
	if(!$error){
		echo "1";
	}else{
		echo "Ocurrió un error al cancelar.";
	}
