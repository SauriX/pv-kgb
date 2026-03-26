<?php

include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');

include('../includes/impresora.php');
include('../includes/postmark.php');

extract($_POST);

// 🔥 NORMALIZAR MONTOS
$monto_efectivo = floatval($monto_efectivo);
$monto_tarjeta = floatval($monto_tarjeta);
$monto_transferencia = floatval($monto_transferencia);

// 🔥 SUMA DE MÉTODOS
$total_metodos = $monto_efectivo + $monto_tarjeta + $monto_transferencia;

if(!$reimprime){

	mysql_query("BEGIN");

	if(!is_numeric($id_metodo_pago)) exit('Falta método de pago');
	if($id_metodo_pago < 1) exit('Falta método de pago');

	// 🔥 VALIDACIÓN MULTIPAGO
	if($total_metodos > 0){
		if(abs($total_metodos - $consumo_txt) > 0.01){
			exit('Los montos no coinciden con el total');
		}
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

	if($tc){
		$consumo_txt = $total_txt;
	}

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

	$query = mysql_query($sql,$conexion);
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