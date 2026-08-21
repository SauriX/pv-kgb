<?php

include('../includes/session.php');
require_once('../includes/db.php');
include('../includes/funciones.php');

include('../includes/impresora.php');
include('../includes/postmark.php');

if(!function_exists('normaliza_monto')){
	function normaliza_monto($valor){
		$valor = str_replace(',', '', trim((string)$valor));
		return floatval($valor);
	}
}

extract($_POST);
$reimprime = isset($reimprime) ? $reimprime : 0;
$id_metodo_pago = isset($id_metodo_pago) ? $id_metodo_pago : 99;

$monto_efectivo = isset($monto_efectivo) ? $monto_efectivo : 0;
$monto_tarjeta = isset($monto_tarjeta) ? $monto_tarjeta : 0;
$monto_transferencia = isset($monto_transferencia) ? $monto_transferencia : 0;

$consumo_txt = isset($consumo_txt) ? $consumo_txt : 0;
$total_txt = isset($total_txt) ? $total_txt : 0;
$req_factura = isset($req_factura) ? $req_factura : 0;
$num_cta_txt = isset($num_cta_txt) ? $num_cta_txt : '';
$iva_total = isset($iva_total) ? $iva_total : 0;
$iva_efect = isset($iva_efect) ? $iva_efect : 0;
$codigo = isset($codigo) ? $codigo : '';
$recibe_txt = isset($recibe_txt) ? $recibe_txt : 0;
$cambio_txt = isset($cambio_txt) ? $cambio_txt : 0;
$descuento_txt = isset($descuento_txt) ? $descuento_txt : 0;
$DescEfec_txt = isset($DescEfec_txt) ? $DescEfec_txt : 0;
$pagarOriginal = isset($pagarOriginal) ? $pagarOriginal : 0;
$tc = isset($tc) ? $tc : 0;
$id_venta_cobrar = isset($id_venta_cobrar) ? $id_venta_cobrar : 0;
$check_imprimir = isset($check_imprimir) ? $check_imprimir : 'false';
// 🔥 NORMALIZAR MONTOS
$monto_efectivo = normaliza_monto($monto_efectivo);
$monto_tarjeta = normaliza_monto($monto_tarjeta);
$monto_transferencia = normaliza_monto($monto_transferencia);
$consumo_txt = normaliza_monto($consumo_txt);
$total_txt = normaliza_monto($total_txt);
$iva_total = normaliza_monto($iva_total);
$iva_efect = normaliza_monto($iva_efect);
$recibe_txt = normaliza_monto($recibe_txt);
$cambio_txt = normaliza_monto($cambio_txt);
$DescEfec_txt = normaliza_monto($DescEfec_txt);
$pagarOriginal = normaliza_monto($pagarOriginal);

if($tc){
	$consumo_txt = $total_txt;
}
$descuento_txt = isset($descuento_txt) ? intval($descuento_txt) : 0;

// 🔥 SUMA DE MÉTODOS
$total_metodos = $monto_efectivo + $monto_tarjeta + $monto_transferencia;
$error = false;
if(!$reimprime){
	
	mysql_query("BEGIN");

	if(!is_numeric($id_metodo_pago) || $id_metodo_pago < 1){
		$id_metodo_pago = 99;
	}

	// 🔥 VALIDACIÓN MULTIPAGO
	$no_efectivo = $monto_tarjeta + $monto_transferencia;
	if($no_efectivo - $consumo_txt > 0.01){
		exit('Tarjeta/transferencia no puede exceder el total');
	}

	$efectivo_requerido = $consumo_txt - $no_efectivo;
	if($efectivo_requerido < 0){
		$efectivo_requerido = 0;
	}

	if($monto_efectivo + 0.01 < $efectivo_requerido){
		exit('Faltante por cubrir');
	}

	switch($id_metodo_pago){
		case '2':
		case '3':
		case '4':
			$req_num = 1;
		break;
		default:
			$req_num = 0;
		break;
	}

	$sql = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo = $id_metodo_pago";
	$metodo_pago = @mysql_result(mysql_query($sql,$conexion), 0);

	if($req_factura==1){
		$facturado = '0';
		$monto_facturado = 0;

	}elseif($req_factura==2){

		$facturado = '1';

		if($req_num){
			if(!$num_cta_txt) exit('Falta número de cuenta');
			if(strlen($num_cta_txt)!=4) exit('Número de cuenta debe ser de 4 dígitos');
		}

	}else{
		$req_factura = 2;
	}

	if($monto_facturado > $consumo_txt) exit('El consumo no puede ser mayor al monto a facturar');

	$fechahora_pagada = date('Y-m-d H:i:s');

	if($iva_total!=0){
		$facturado=1;
	}

	// 🔥 CONSTRUIR TEXTO DE MÉTODOS
	$metodo_pago_txt = "";

	if($monto_efectivo > 0){
		$metodo_pago_txt .= "EFECTIVO:$monto_efectivo ";
	}
	if($monto_tarjeta > 0){
		$metodo_pago_txt .= "TARJETA:$monto_tarjeta ";
	}
	if($monto_transferencia > 0){
		$metodo_pago_txt .= "TRANSFERENCIA:$monto_transferencia ";
	}

	// 🔥 CONTAR MÉTODOS
	$metodos_usados = 0;
	if($monto_efectivo > 0) $metodos_usados++;
	if($monto_tarjeta > 0) $metodos_usados++;
	if($monto_transferencia > 0) $metodos_usados++;

	// 🔥 SI ES MIXTO
	if($metodos_usados > 1){
		$id_metodo_pago = 99; // asegúrate que exista en BD
	}

	// fallback
	if($metodo_pago_txt == ""){
		$metodo_pago_txt = $metodo_pago;
	}
	$num_cta_txt = ($num_cta_txt != '') ? $num_cta_txt : '0000';
$codigo = ($codigo != '') ? $codigo : 'NA';
if($facturado == '') $facturado = 0;
if($recibe_txt == '') $recibe_txt = 0;
if($cambio_txt == '') $cambio_txt = 0;
	$sql="UPDATE ventas SET  
		pagada=1,
		abierta=0,
		id_metodo='$id_metodo_pago',
		num_cta='$num_cta_txt',
		facturado='$facturado',
		monto_facturado='$iva_efect',
		monto_pagado='$consumo_txt',

		monto_efectivo='$monto_efectivo',
		monto_tarjeta='$monto_tarjeta',
		monto_transferencia='$monto_transferencia',

		codigo = '$codigo',
		metodo_txt = '$metodo_pago_txt',
		recibe_txt = '$recibe_txt',
		cambio_txt = '$cambio_txt',
		fechahora_pagada = '$fechahora_pagada',
		descuento_txt = '$descuento_txt',
		DescEfec_txt = '$DescEfec_txt',
		pagarOriginal = '$pagarOriginal'

		WHERE id_venta = '$id_venta_cobrar'";

	$query = mysql_query($sql);
if(!$query){
    die('MYSQL ERROR: '.mysql_error());
}	
	if($error==false){

		mysql_query("COMMIT");

		if($check_imprimir == 'false'){
			$var = imprimir_mesa($id_venta_cobrar,'cobrar',$cliente,$numero);
		}else{
			abrir_caja();
		}

		echo '1|'.$var;

	}else{
		mysql_query("ROLLBACK");
		
		echo "Error al guardar: ".$sql;
	}

}else{
	echo '1';
}