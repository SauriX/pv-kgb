<?
	include('../includes/session.php');
	include('../includes/db.php');
	include('../includes/impresora.php');
	include('../includes/funciones.php');
	include('../includes/dbline.php');
	include('../includes/postmark.php');
	extract($_POST);
	if(!function_exists('normaliza_monto')){
		function normaliza_monto($valor){
			$valor = str_replace(',', '', trim((string)$valor));
			return floatval($valor);
		}
	}
	$sin_imprimir = isset($sin_imprimir) ? intval($sin_imprimir) : 0;
	//print_r($_POST);
	//exit;
	$sql="SELECT * FROM configuracion ";
	$q =mysql_query($sql,$conexion);
	$ft=mysql_fetch_assoc($q);
	$sucursal=$ft['sucursal'];
	if(!$reimprime){
		//exit("No1");
		$mesa = trim($mesa);
		$id_descuento = isset($id_descuento) ? intval($id_descuento) : 0;
		$monto_descuento = isset($monto_descuento) ? normaliza_monto($monto_descuento) : 0;
		$f = date('Y-m-d H:i:s');
		if($mesa){
			$sql ="UPDATE ventas SET abierta = 0,fechahora_cerrada = '$f', descuento_txt='$id_descuento', DescEfec_txt='$monto_descuento' WHERE id_venta = '$id_venta'";
			$q = mysql_query($sql,$conexion);
		}
	}
imprimir_mesa($id_venta,'cerrar');
if(!$sin_imprimir){
	imprimir_mesa($id_venta,'cerrar');
}
echo '1';
