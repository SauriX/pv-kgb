<?
	include('../includes/session.php');
	include('../includes/db.php');
	include('../includes/impresora.php');
	include('../includes/funciones.php');
	include('../includes/dbline.php');
	include('../includes/postmark.php');
	extract($_POST);
	//print_r($_POST);
	//exit;
	$sql="SELECT * FROM configuracion ";
	$q =mysql_query($sql,$conexion);
	$ft=mysql_fetch_assoc($q);
	$sucursal=$ft['sucursal'];
	if(!$reimprime){
		//exit("No1");
		$mesa = trim($mesa);
		$f = date('Y-m-d H:i:s');
		if($mesa){
			$sql ="UPDATE ventas SET abierta = 0,fechahora_cerrada = '$f', descuento_txt='$id_descuento', DescEfec_txt='$monto_descuento' WHERE id_venta = '$id_venta'";
			$q = mysql_query($sql,$conexion);
		}
	}

$var =imprimir_mesa($id_venta,'cerrar');
echo '1|'.$var;
