<?php
require_once('kescpos.drv.php');

$sql ="SELECT*FROM configuracion";
$datos_config = mysql_fetch_assoc(mysql_query($sql));

$establecimiento_config = $datos_config['establecimiento'];
$autoprint = $datos_config['autoprint'];
$abrir_caja = $datos_config['abrir_caja'];
$enviar_sms = $datos_config['enviar_sms'];
$impresora_sd = $datos_config['impresora_sd'];
$impresora_cuentas = $datos_config['impresora_cuentas'];
$impresora_cortes = $datos_config['impresora_cortes'];
$impresora_cobros = $datos_config['impresora_cobros'];
$dias_para_factura = $datos_config['dias_para_factura'];
$sitio_web_factura = $datos_config['sitio_web_factura'];
$footer_cuenta = $datos_config['footer_cuenta'];
$footer_cobro = $datos_config['footer_cobro'];
$footer_domicilio = $datos_config['footer_domicilio'];
$header_domicilio = $datos_config['header_domicilio'];

function imprimir_corte($id_corte,$nombres,$prod,$pu,$efectivo,$tarjeta,$cheque,$transf,$noide,$cta_expedidas,$mesas_ct,$mesas_por,$mesas_monto,$barra_ct,$barra_por,$barra_monto,$pre_fact_ct,$pre_fact_por,$pre_fact_monto,$no_fact_ct,$no_fact_por,$no_fact_monto,$promedio,$cancelaciones,$pre_fact_monto_por,$no_fact_monto_por,$mesas_monto_por,$barra_monto_por, $fecha_corte = false,$precorte=false){

	include('funciones.php');
	global $establecimiento_config;
	global $impresora_cortes;
	global $autoprint;
	global $enviar_sms;

	session_start();

	$fecha_hora_ticket = date('d-m-Y h:i a');
	if($fecha_corte):
		$fecha_corte = 'esc_pos_line($printer, "FECHA CORTE: '.$fecha_corte.'");';
	endif;
	if($precorte):
		$precorteTexto = 'esc_pos_line($printer, "################################################");';
		$precorteTexto.= 'esc_pos_line($printer, "############### P R E C O R T E ################");';
		$precorteTexto.= 'esc_pos_line($printer, "################################################");';
	endif;

	$var='
	  $printer = esc_pos_open("'.$impresora_cortes.'", "ch-latin-2", false, true);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      '.$precorteTexto.'
      esc_pos_line($printer, "'.$establecimiento_config.'");
      esc_pos_line($printer, "CORTE DE CAJA #'.$id_corte.'");
      esc_pos_line($printer, "IMPRESION: '.$fecha_hora_ticket.'");
      '.$fecha_corte.'
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "#################### VENTA #####################");
	  esc_pos_align($printer, "left");
      esc_pos_line($printer, "PRODUCTO                    CANT   UNIT     SUBT");';



	  foreach($nombres as $id  => $nombre){

		  $producto = $nombre;
		  $producto = substr($producto,0,20);
	      $c_p = strlen($producto);
		  if($c_p<20){
		  	$to_p = 20-$c_p;
			  switch($to_p){
			      case 1: $space0 = " "; break;
			      case 2: $space0 = "  "; break;
			      case 3: $space0 = "   "; break;
			      case 4: $space0 = "    "; break;
			      case 5: $space0 = "     "; break;
			      case 6: $space0 = "      "; break;
			      case 7: $space0 = "       "; break;
			      case 8: $space0 = "        "; break;
			      case 9: $space0 = "         "; break;
			      case 10: $space0 = "          "; break;
			      case 11: $space0 = "           "; break;
			      case 12: $space0 = "            "; break;
			      case 13: $space0 = "             "; break;
			      case 14: $space0 = "              "; break;
 			      case 15: $space0 = "               "; break;
			      case 16: $space0 = "                "; break;
			      case 17: $space0 = "                 "; break;
			      case 18: $space0 = "                  "; break;
			      case 19: $space0 = "                   "; break;
			      case 20: $space0 = "                    "; break;


			  }
		  }

		  $cantidad = $prod[$id];
		  $precio = $pu[$id];

		  $total=$prod[$id]*$pu[$id];
		  $g_total+=$total;
		  $total= number_format($total,2, '.', '');

			  $prec = strlen($precio);
			  $cant = strlen($cantidad);
			  $tot = strlen($total);

			  switch($cant){
			      case 1: $space1 = "          "; break;
			      case 2: $space1 = "         "; break;
			      case 3: $space1 = "        "; break;
			      case 4: $space1 = "       "; break;
			  }
			  switch($prec){
			      case 4: $space2 = "    "; break;
			      case 5: $space2 = "   "; break;
			      case 6: $space2 = "  "; break;
			      case 7: $space2 = " "; break;
			      case 8: $space2 = ""; break;
			  }
			  switch($tot){
			      case 4: $space3 = "     "; break;
			      case 5: $space3 = "    "; break;
			      case 6: $space3 = "   "; break;
			      case 7: $space3 = "  "; break;
			      case 8: $space3 = " "; break;
			  }
	      $var.= 'esc_pos_line($printer, "'.$producto.$space0.$space1.$cantidad.$space2.$precio.$space3.$total.'");';
	      unset($space0);

	  }

	  $g_total = number_format($g_total,2, '.', '');

	  $t = strlen($g_total);


	  switch($t){
	      case 4: $spacet = "           "; break;
	      case 5: $spacet = "          "; break;
	      case 6: $spacet = "         "; break;
	      case 7: $spacet = "        "; break;
	      case 8: $spacet = "       "; break;
	  }


	  $efectivo = number_format($efectivo,2, '.', '');
	  $tarjeta = number_format($tarjeta,2, '.', '');
	  $cheque = number_format($cheque,2, '.', '');
	  $transf = number_format($transf,2, '.', '');
	  $noide = number_format($noide,2, '.', '');

	  $mesas_monto = number_format($mesas_monto,2, '.', '');
	  $barra_monto = number_format($barra_monto,2, '.', '');
	  $pre_fact_monto = number_format($pre_fact_monto,2, '.', '');
	  $no_fact_monto = number_format($no_fact_monto,2, '.', '');
	  $promedio = number_format($promedio,2, '.', '');
	  $no_fact_monto_por = number_format($no_fact_monto_por,2, '.', '');
	  $pre_fact_monto_por = number_format($pre_fact_monto_por,2, '.', '');
	  $mesas_monto_por = number_format($mesas_monto_por,2, '.', '');
	  $barra_monto_por = number_format($barra_monto_por,2, '.', '');

      $var.= 'esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "right");
      esc_pos_line($printer, "VENTA TOTAL:'.$spacet.$g_total.'");
      esc_pos_font($printer, "A");
      esc_pos_align($printer, "left");
      esc_pos_line($printer, "DESGLOSE:");
      esc_pos_line($printer, "EFECTIVO: '.$efectivo.'");
      esc_pos_line($printer, "TARJETA: '.$tarjeta.'");
      esc_pos_line($printer, "CHEQUE: '.$cheque.'");
      esc_pos_line($printer, "TRANSF: '.$transf.'");
      esc_pos_line($printer, "NO IDENT: '.$noide.'");
      esc_pos_line($printer, "");
      esc_pos_line($printer, "CUENTAS EXPEDIDAS: '.$cta_expedidas.'");
      esc_pos_line($printer, "PROMEDIO POR CUENTA: '.$promedio.'");
      esc_pos_line($printer, "CANCELACIONES: '.$cancelaciones.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "MESAS: '.$mesas_ct.' ('.round($mesas_por,2).'%) BARRA: '.$barra_ct.' ('.round($barra_por,2).'%)");
      esc_pos_line($printer, "MESAS: '.$mesas_monto.' ('.round($mesas_monto_por,2).'%) BARRA: '.$barra_monto.' ('.round($barra_monto_por,2).'%)");
      esc_pos_line($printer, "PRFCT: '.$pre_fact_ct.' ('.round($pre_fact_por,2).'%) NOFCT: '.$no_fact_ct.' ('.round($no_fact_por,2).'%)");
      esc_pos_line($printer, "PRFCT: '.$pre_fact_monto.' ('.round($pre_fact_monto_por,2).'%) NOFCT: '.$no_fact_monto.' ('.round($no_fact_monto_por,2).'%)");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "#################### GASTOS ####################");
	  esc_pos_line($printer, "DESCRIPCION                                MONTO");
      ';

      $sql ="SELECT*FROM gastos WHERE id_corte = $id_corte";
      $qq1 = mysql_query($sql);
      while($fx = mysql_fetch_assoc($qq1)){
		  $gasto = $fx['descripcion'];
		  $monto = $fx['monto'];
		  $gasto_ok = substr($gasto,0,25);
	      $c_p = strlen($gasto_ok);
		  if($c_p<30){
		  	$to_p = 30-$c_p;
			  switch($to_p){
			      case 1: $space0 = " "; break;
			      case 2: $space0 = "  "; break;
			      case 3: $space0 = "   "; break;
			      case 4: $space0 = "    "; break;
			      case 5: $space0 = "     "; break;
			      case 6: $space0 = "      "; break;
			      case 7: $space0 = "       "; break;
			      case 8: $space0 = "        "; break;
			      case 9: $space0 = "         "; break;
			      case 10: $space0 = "          "; break;
			      case 11: $space0 = "           "; break;
			      case 12: $space0 = "            "; break;
			      case 13: $space0 = "             "; break;
			      case 14: $space0 = "              "; break;
 			      case 15: $space0 = "               "; break;
			      case 16: $space0 = "                "; break;
			      case 17: $space0 = "                 "; break;
			      case 18: $space0 = "                  "; break;
			      case 19: $space0 = "                   "; break;
			      case 20: $space0 = "                    "; break;
			      case 21: $space0 = "                     "; break;
			      case 22: $space0 = "                      "; break;
			      case 23: $space0 = "                       "; break;
			      case 24: $space0 = "                        "; break;
			      case 25: $space0 = "                         "; break;
			      case 26: $space0 = "                          "; break;
			      case 27: $space0 = "                           "; break;
			      case 28: $space0 = "                            "; break;
			      case 29: $space0 = "                             "; break;
			      case 30: $space0 = "                              "; break;
			      case 31: $space0 = "                               "; break;
			      case 32: $space0 = "                                "; break;
			      case 33: $space0 = "                                 "; break;
			      case 34: $space0 = "                                  "; break;
			      case 35: $space0 = "                                   "; break;


			  }
		  }

		  $g_total_g+=$monto;

		  $monto= number_format($monto,2, '.', '');
		  $mont = strlen($monto);
			  switch($mont){
			      case 4: $spacex = "              "; break;
			      case 5: $spacex = "             "; break;
			      case 6: $spacex = "            "; break;
			      case 7: $spacex = "           "; break;
			      case 8: $spacex = "          "; break;
			  }



		  $var.= 'esc_pos_line($printer, "'.acentos($gasto_ok).$space0.$spacex.$monto.'");';


		}

	  $g_total_gastos = number_format($g_total_g,2, '.', '');

	  $tg = strlen($g_total_gastos);


	  switch($tg){
	      case 4: $spaceg = "           "; break;
	      case 5: $spaceg = "          "; break;
	      case 6: $spaceg = "         "; break;
	      case 7: $spaceg = "        "; break;
	      case 8: $spaceg = "       "; break;
	  }

      $var.= 'esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "right");
      esc_pos_line($printer, "TOTAL DE GASTOS:'.$spaceg.$g_total_gastos.'");';

      $ENCAJA = $efectivo-$g_total_g;
      $ENCAJA = number_format($ENCAJA,2, '.', '');

      $var.='
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
	  esc_pos_line($printer, "################## DIFERENCIA ##################");
      esc_pos_line($printer, " ");
      esc_pos_align($printer, "center");
      esc_pos_line($printer, "EFECTIVO EN CAJA: '.$ENCAJA.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_align($printer, "center");
      esc_pos_line($printer, "RESPONSABLE");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
	  esc_pos_line($printer, "______________________________");
      esc_pos_line($printer, "'.strtoupper($_SESSION['s_nombre']).'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_cut($printer);
      esc_pos_close($printer);';


	if($autoprint){
	      eval($var);
	}
/*
	if($enviar_sms  == 1){//condicion de envio de mensajes si es 1 se envia, si es 0 no se envia
     envio_mensaje($fecha_hora_ticket,
     			$g_total,
     			$efectivo,
     			$tarjeta,
     			$cheque,
     			$transf,
     			$noide,
     			$g_total_gastos,
     			$ENCAJA);
	}
      */
}
// funcion que agregere Komudo
function gastos_imprimir(){

	global $autoprint;
	global $abrir_caja;
	global $impresora_cobros;

    if($abrir_caja){
    	$abrir_caja_cmd = 'esc_pos_drawer($printer);';
    }

	 $var='
	  		$printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true);'.$abrir_caja_cmd.'
			esc_pos_close($printer);';

     if($autoprint){
	 	eval($var);
	 }

}

function abrir_caja(){


	global $impresora_cobros;

	 $var=	'

	  		$printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true); esc_pos_drawer($printer); esc_pos_close($printer);

	  		';

	 eval($var);

}
//final de la funcion


function imprimir_mesa($id_venta,$op,$tipo,$factura=false,$codigo=false,$monto=false,$metodo=false,$efectivo=false,$cambio=false){

	global $establecimiento_config;
	global $autoprint;
	global $abrir_caja;
	global $impresora_cobros;
	global $impresora_cuentas;
	global $dias_para_factura;
	global $sitio_web_factura;
	global $footer_cuenta;
	global $footer_cobro;

	session_start();

	$fecha_hora_ticket = date('d-m-Y h:i a');

	if($op=='BARRA'){
		$add = ' - BARRA';
	}else{
		$add =" - MESA: ".$op;
	}

	if($tipo=="cerrar"){
		//cuenta
		$var_print = '$printer = esc_pos_open("'.$impresora_cuentas.'", "ch-latin-2", false, true);';
	}else{
		//cobro

	    if($abrir_caja){
	    	$abrir_caja_cmd = 'esc_pos_drawer($printer);';
	    }


		$var_print = '
		 $printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true); '.$abrir_caja_cmd;
	}

	$var = $var_print;
#      esc_pos_line($printer, "'.$establecimiento_config.'");
    $var.='

	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      esc_pos_line($printer, "MARISQUERIA EL TACO LOCO");
      esc_pos_line($printer, "FUNDADA EN 1979 SA DE CV");
      esc_pos_line($printer, "AV. JOSE MA. MORELOS 87");
      esc_pos_line($printer, "C.P. 77000 CHETUMAL, Q.ROO");
      esc_pos_line($printer, "RFC: MTL1010278B1");
	  esc_pos_line($printer, "REGIMEN GENERAL DE LEY");
	  esc_pos_line($printer, "DE PERSONAS MORALES");
      esc_pos_line($printer, "'.$fecha_hora_ticket.'");
      esc_pos_line($printer, "FOLIO: #'.$id_venta.$add.'");
      esc_pos_line($printer, "AUX: '.strtoupper($_SESSION['s_nombre']).'");
      esc_pos_line($printer, "------------------------------------------------");
	  esc_pos_align($printer, "left");
      esc_pos_line($printer, "PRODUCTO                    CANT   UNIT    SUBT");';

	$sql = "SELECT venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta  FROM venta_detalle
	JOIN productos ON productos.id_producto = venta_detalle.id_producto
	WHERE id_venta = '$id_venta'";
	$q = mysql_query($sql);
	while($ft=mysql_fetch_assoc($q)){

		  $producto = $ft['nombre'];
		  $producto = substr($producto,0,20);

	      $c_p = strlen($producto);
		  if($c_p<20){
		  	$to_p = 20-$c_p;
			  switch($to_p){
			      case 1: $space0 = " "; break;
			      case 2: $space0 = "  "; break;
			      case 3: $space0 = "   "; break;
			      case 4: $space0 = "    "; break;
			      case 5: $space0 = "     "; break;
			      case 6: $space0 = "      "; break;
			      case 7: $space0 = "       "; break;
			      case 8: $space0 = "        "; break;
			      case 9: $space0 = "         "; break;
			      case 10: $space0 = "          "; break;
			      case 11: $space0 = "           "; break;
			      case 12: $space0 = "            "; break;
			      case 13: $space0 = "             "; break;
			      case 14: $space0 = "              "; break;
 			      case 15: $space0 = "               "; break;
			      case 16: $space0 = "                "; break;
			      case 17: $space0 = "                 "; break;
			      case 18: $space0 = "                  "; break;
			      case 19: $space0 = "                   "; break;
			      case 20: $space0 = "                    "; break;

			  }
		  }

		  $cantidad = $ft['cantidad'];
		  $precio = $ft['precio_venta'];

		  $total=$ft['cantidad']*$ft['precio_venta'];
		  $g_total+=$total;
		  $total= number_format($total,2, '.', '');

			  $prec = strlen($precio);
			  $cant = strlen($cantidad);
			  $tot = strlen($total);

			  switch($cant){
			      case 1: $space1 = "          "; break;
			      case 2: $space1 = "         "; break;
			      case 3: $space1 = "        "; break;
			      case 4: $space1 = "       "; break;
			  }
			  switch($prec){
			      case 4: $space2 = "    "; break;
			      case 5: $space2 = "   "; break;
			      case 6: $space2 = "  "; break;
			      case 7: $space2 = " "; break;
			      case 8: $space2 = ""; break;
			  }
			  switch($tot){
			      case 4: $space3 = "     "; break;
			      case 5: $space3 = "    "; break;
			      case 6: $space3 = "   "; break;
			      case 7: $space3 = "  "; break;
			      case 8: $space3 = " "; break;
			  }
	      $var.= 'esc_pos_line($printer, "'.$producto.$space0.$space1.$cantidad.$space2.$precio.$space3.$total.'");';
	      unset($space0);
	  }

	  $total_final_clean = $g_total;

	  $g_total = number_format($g_total,2, '.', '');
	  $efectivo = number_format($efectivo,2, '.', '');
	  $cambio = number_format($cambio,2, '.', '');

	  $t = strlen($g_total);
	  $e = strlen($efectivo);
	  $c = strlen($cambio);
	  $m = strlen($metodo);
	  switch($t){
	      case 4: $spacet = "           "; break;
	      case 5: $spacet = "          "; break;
	      case 6: $spacet = "         "; break;
	      case 7: $spacet = "        "; break;
	      case 8: $spacet = "       "; break;
	  }
	  switch($m){
	      case 4: $spacem = "           "; break;
	      case 5: $spacem = "          "; break;
	      case 6: $spacem = "         "; break;
	      case 7: $spacem = "        "; break;
	      case 8: $spacem = "       "; break;
	      case 9: $spacem = "      "; break;
	      case 10: $spacem = "    "; break;
	      case 11: $spacem = "   "; break;
	      case 12: $spacem = "  "; break;
	      case 13: $spacem = " "; break;
	  }

	  switch($e){
	      case 4: $spacee = "           "; break;
	      case 5: $spacee = "          "; break;
	      case 6: $spacee = "         "; break;
	      case 7: $spacee = "        "; break;
	      case 8: $spacee = "       "; break;
	  }
	  switch($c){
	      case 4: $spacec = "           "; break;
	      case 5: $spacec = "          "; break;
	      case 6: $spacec = "         "; break;
	      case 7: $spacec = "        "; break;
	      case 8: $spacec = "       "; break;

	  }


	if($tipo=='cerrar'){

		$fact = 'esc_pos_line($printer, "SI REQUIERE FACTURA SOLICITELA AL MOMENTO.");';
		$status = 'esc_pos_font($printer, "B");';
		$status.= 'esc_pos_line($printer, "PENDIENTE DE PAGO");';

		$footer_final = $footer_cuenta;

	}else{

		$footer_final = $footer_cobro;

		$detalle = '
		esc_pos_line($printer, "SU PAGO:'.$spacee.$efectivo.'");
		esc_pos_line($printer, "CAMBIO:'.$spacec.$cambio.'");
		esc_pos_line($printer, "METODO:'.$spacem.$metodo.'");
		';

		if($factura==1){

			if($total_final_clean>$monto){
				$add_monto = 'esc_pos_line($printer, "IMPORTE SOLICITADO: $'.number_format($monto,2).'");';
				$status = 'esc_pos_font($printer, "B");';
				$status.= 'esc_pos_line($printer, "EL SALDO DEL TOTAL NO SOLICITADO");';
				$status.= 'esc_pos_line($printer, " SE INTEGRARA A LA FACTURA GLOBAL DIARIA.");';
			}

			$fact = 'esc_pos_line($printer, "GENERE SU FACTURA EN: '.$sitio_web_factura.'");';
			$fact.= 'esc_pos_line($printer, "CODIGO DE FACTURACION: '.$codigo.'");';
			$fact.= $add_monto;
			$fact.= 'esc_pos_line($printer, "CUENTA CON '.$dias_para_factura.' DIAS A PARTIR DE LA FECHA");';
			$fact.= 'esc_pos_line($printer, "DE ESTE TICKET PARA GENERAR SU FACTURA.");';


		}else{

			$status = 'esc_pos_font($printer, "B");';
			$status.= 'esc_pos_line($printer, "FACTURA NO REQUERIDA POR EL CLIENTE.");';
			$status.= 'esc_pos_line($printer, "ESTA VENTA SE INTEGRARA A LA FACTURA GLOBAL DIARIA.");';

		}
	}


	$var.='
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "right");
	  esc_pos_emphasize($printer,true);
      esc_pos_line($printer, "TOTAL:'.$spacet.$g_total.'");
	  '.$detalle.'
	  esc_pos_emphasize($printer,false);
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_font($printer, "A");
      esc_pos_align($printer, "center");
      '.$fact.'
      esc_pos_align($printer, "center");
     esc_pos_line($printer, "");';
      $var.=$footer_final;
      $var.='
      esc_pos_line($printer, "");
	  '.$status.'
	  esc_pos_cut($printer);
      esc_pos_close($printer);

      ';

	if($autoprint){
	    eval($var);
	}

}

function ticket_factura($UUID,$TotalImpuestosTrasladados,$Serie1,$re_UsoCFDI,$re_rfc,$re_nombre,$emi_Regimen,$emi_nombre,$emi_rfc,$certificado_sat,$sello_cfdi,$sello_sat,$fecha_timbrado,$folio_sat,$folio_interno,$cantidad,$unidad,$descripcion,$importe,$desc_u,$name,$subtotal,$cantidad_iva,$leyenda,$TOTAL,$c_emisor,$c_sat,$forma_pago,$metodo_pago,$tipo_comprobante,$numero_cuenta,$metodo,$total_letra,$re_localidad){

	//global $impresora_cobros;
	global $impresora_cuentas;
	require_once('php_image_magician.php');

	    $escpos = new KEscPos('TM-T88IV AFU', $impresora_cuentas, true, true);
      #$escpos = new KEscPos('TM-T88IV AFU', 'testprinter', true, true);
      #$escpos = new KEscPos('TM-T88IV AFU', 'ip:192.65.33.28', true, true);

      $img2 = "logo.jpg";
      $escpos -> Align("center");
      $escpos -> Image(false, $img2);
      //$escpos -> Cut();
      $escpos -> Close();

	//require "kescpos.drv.php";
	$magicianObj = new imageLib("temp/".$name.".png");
	$magicianObj -> saveImage('temp/'.$name.'.bmp');

	$largura = 55; //Image Width in Pixels
	$altura = 54;  //Image Height in Pixels
	$fator_x = $largura / 72; //Image Resolution (72 ppi)
	$fator_y = $largura / 300; //Printer Resolution (300 dpi)
	$fator_z = $fator_x / $fator_y;

	$largura_f = $largura * $fator_z;
	$altura_f = $altura * $fator_z;

switch($forma_pago){
          case '01': $lforma_pago="01 EFECTIVO";
          break;

          case '02': $lforma_pago="02 CHEQUE";
          break;

          case '03': $lforma_pago= "03 TRANSFERENCIA DE FONDOS";
          break;

          case '04': $lforma_pago="04 TARJETAS DE CREDITO";
          break;

          case '05':
          $lforma_pago= "05 MONEDEROS ELECTRONICOS";
          break;

          case '06':
          $lforma_pago= "06 DINERO ELECTRONICO";
          break;

          case '07':
          $lforma_pago= "07 TARJETAS DIGITALES";
          break;

          case '08':
        $lforma_pago= "08 VALES DE DESPENSA";
          break;

          case '09':
          $lforma_pago=$l_metodo= "09 BIENES";
          break;

          case '10':
          $lforma_pago=$l_metodo= "10 SERVICIO";
          break;

          case '11':
          $lforma_pago=$l_metodo= "11 POR CUENTA DE TERCERO";
          break;

          case '12':
          $lforma_pago=$l_metodo= "12 DACION EN PAGO";
          break;

          case '13':
          $lforma_pago=$l_metodo= "13 PAGO POR SUBROGACION";
          break;

          case '14':
          $lforma_pago=$l_metodo= "14 PAGO POR CONSIGNACION";
          break;

          case '15':
          $lforma_pago=$l_metodo= "15 CONDONACION";
          break;

          case '16':
          $lforma_pago= "16 CANCELACION";
          break;

          case '17':
          $lforma_pago= "17 COMPENSACION";
          break;

          case '28':
          $lforma_pago= "28 TARJETA DE DEBITO";
          break;

          case '29':
          $lforma_pago= "29 TARJETA DE SERVICIO";
          break;

          case '98':
          $lforma_pago= "98 NA";
          break;

          case '99':
          $lforma_pago= "99 OTROS";
          break;
          case '999':
          $lforma_pago= "NA";
          break;

          default:
          $lforma_pago=  $forma_pago;
        }

$prueba = "Cebaños";


	$var='$printer = esc_pos_open("'.$impresora_cuentas.'", "ch-latin-2", false, true);';







       $escpos = new KEscPos('TM-T88IV AFU', $impresora_cuentas, true, true);

       $escpos -> Align("left");
       $escpos -> Font("A");
       $escpos -> Double(true);
       $escpos -> Line("FOLIO FISCAL:");
       $escpos -> Double(false);
       $escpos -> Align("center");
       $escpos -> Line($folio_sat);

       $escpos -> Align("left");
       $escpos -> Font("A");
       $escpos -> Double(true);
       $escpos -> Line("CERTIFICADO EMISOR:");
       $escpos -> Double(false);
       $escpos -> Align("center");
       $escpos -> Line($c_emisor);

       $escpos -> Align("left");
       $escpos -> Font("A");
       $escpos -> Double(true);
       $escpos -> Line("CERTIFICADO SAT:");
       $escpos -> Double(false);
       $escpos -> Align("center");
       $escpos -> Line($c_sat);

       $escpos -> Align("left");
       $escpos -> Font("A");
       $escpos -> Double(true);
       $escpos -> Line("FECHA DE TIMBRADO:");
       $escpos -> Double(false);
       $escpos -> Align("center");
       $escpos -> Line($fecha_timbrado);

       $escpos -> Align("left");
       $escpos -> Double(true);
       $escpos -> Font("A");
       $escpos -> Line("FOLIO:");
       $escpos -> Double(false);
       $escpos -> Align("center");
       $escpos -> Line($folio_interno);

       $escpos -> Align("left");
       $escpos -> Font("A");
       $escpos -> Double(true);
       $escpos -> Line("RECEPTOR:");
       $escpos -> Double(false);
       $escpos -> Align("center");
       $escpos -> Line("$re_nombre");
       $escpos -> Line("R.F.C : $re_rfc");
       $escpos -> Line("UsoCFDI: $re_UsoCFDI");

      $escpos -> Close();

	 $var.='esc_pos_align($printer, "left");
      esc_pos_font($printer, "A");
      esc_pos_emphasize($printer,true);
      esc_pos_line($printer, "EMISOR:");
      esc_pos_font($printer, "A");
      esc_pos_align($printer, "center");
      esc_pos_emphasize($printer,false);
	  esc_pos_line($printer, "'.$emi_nombre.'");
	  esc_pos_line($printer, "R.F.C: '.$emi_rfc.'");
	  esc_pos_line($printer, "RegimenFiscal: '.$emi_Regimen.'");
      esc_pos_line($printer, "");';


$var.='
  esc_pos_align($printer, "left");
      esc_pos_font($printer, "A");
      esc_pos_emphasize($printer,true);
      esc_pos_line($printer, "CONSUMO:");
      esc_pos_align($printer, "center");
      esc_pos_emphasize($printer,false);
      esc_pos_align($printer, "left");
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_line($printer, "DESCRIPCION           CANT   P.UNIT    IMPORTE");
';
 		$producto = substr($desc_u,0,20);

	      $c_p = strlen($producto);
		  if($c_p<20){
		  	$to_p = 20-$c_p;
			  switch($to_p){
			      case 1: $space0 = " "; break;
			      case 2: $space0 = " "; break;
			      case 3: $space0 = "  "; break;
			      case 4: $space0 = "  "; break;
			      case 5: $space0 = "  "; break;
			      case 6: $space0 = "   "; break;
			      case 7: $space0 = "    "; break;
			      case 8: $space0 = "     "; break;
			      case 9: $space0 = "    "; break;
			      case 10: $space0 = "    "; break;
			      case 11: $space0 = "     "; break;
			      case 12: $space0 = "      "; break;
			      case 13: $space0 = "       "; break;
			      case 14: $space0 = "        "; break;
 			      case 15: $space0 = "         "; break;
			      case 16: $space0 = "          "; break;
			      case 17: $space0 = "           "; break;
			      case 18: $space0 = "            "; break;
			      case 19: $space0 = "             "; break;
			      case 20: $space0 = "              "; break;

			  }
		  }


			  if($unidad =="NO APLICA"){
			  	$unidad = "NA";
			  	 $prec = strlen($unidad);
			  }else{
			  	 $prec = strlen($unidad);
			  }
			  $cant = strlen($cantidad);
			  $tot = strlen($importe);

			  switch($cant){
			      case 1: $space1 = "   "; break;
			      case 2: $space1 = "   "; break;
			      case 3: $space1 = "  "; break;
			      case 4: $space1 = " "; break;
			  }

			  switch($prec){
			  	  case 2: $space2 = "       "; break;
			      case 4: $space2 = "      "; break;
			      case 5: $space2 = "      "; break;
			      case 6: $space2 = "     "; break;
			      case 7: $space2 = "   "; break;
			      case 8: $space2 = "  "; break;
			  }
			  switch($tot){
			      case 4: $space3 = "    "; break;
			      case 5: $space3 = "   "; break;
			      case 6: $space3 = "   "; break;
			      case 7: $space3 = "  "; break;
			      case 8: $space3 = " "; break;
			  }


if($unidad =="NO APLICA"){
	$unidad = "NA";
	$var.='
    esc_pos_line($printer, "'.$producto.$space0.$space1.$cantidad.$space2.$unidad.$space2.$importe.'");';

}else{
	$var.='
    esc_pos_line($printer, "'.$producto.$space0.$space1.$cantidad.$space2.$unidad.$space2.$importe.'");';
}



	  $t = strlen($TOTAL);
	  $e = strlen($subtotal);
	  $c = strlen($importe);
	  $m = strlen($cantidad_iva);
	  switch($t){
	      case 4: $spacet = "         "; break;
	      case 5: $spacet = "        "; break;
	      case 6: $spacet = "       "; break;
	      case 7: $spacet = "      "; break;
	      case 8: $spacet = "     "; break;
	  }
	  switch($m){
	      case 4: $spacem = "         "; break;
	      case 5: $spacem = "        "; break;
	      case 6: $spacem = "       "; break;
	      case 7: $spacem = "      "; break;
	      case 8: $spacem = "     "; break;
	      case 9: $spacem = "     "; break;
	      case 10: $spacem = "   "; break;
	      case 11: $spacem = "   "; break;
	      case 12: $spacem = "  "; break;
	      case 13: $spacem = " "; break;
	  }

	  switch($e){
	      case 4: $spacee = "         "; break;
	      case 5: $spacee = "        "; break;
	      case 6: $spacee = "       "; break;
	      case 7: $spacee = "      "; break;
	      case 8: $spacee = "     "; break;
	  }
	  switch($c){
	      case 4: $spacec = "     "; break;
	      case 5: $spacec = "     "; break;
	      case 6: $spacec = "     "; break;
	      case 7: $spacec = "     "; break;
	      case 8: $spacec = "     "; break;

	  }
	$spacio_1 = "  ";
  	$detalle = '
		esc_pos_line($printer, "SUBTOTAL:'.$spacee.$subtotal.$spacio_1.'");
		esc_pos_line($printer, "IVA:'.$spacem.$TotalImpuestosTrasladados.$spacio_1.'");

		';


	$var.='
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "right");
	  esc_pos_emphasize($printer,true);
	  '.$detalle.'
      esc_pos_line($printer, "TOTAL:'.$spacet.$TOTAL.$spacio_1.'");

	  esc_pos_emphasize($printer,false);
      esc_pos_line($printer, "------------------------------------------------");';


$var.='
	  esc_pos_line($printer, " ");
      esc_pos_line($printer, "'.$total_letra.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "FORMA DE PAGO: '.$lforma_pago.'");
      esc_pos_line($printer, "METODO DE PAGO: '.$metodo.' ");
      ';

      $var.='esc_pos_line($printer, "TIPO DE COMPROBANTE: '.$tipo_comprobante.'");

      esc_pos_line($printer, " ");
	  esc_pos_align($printer, "left");
      esc_pos_font($printer, "A");
      esc_pos_emphasize($printer,true);
      esc_pos_line($printer, "CERTIFICACION DIGITAL DEL SAT:");
      esc_pos_align($printer, "center");
      esc_pos_emphasize($printer,false);
      esc_pos_line($printer, "'.$certificado_sat.'");

	  esc_pos_align($printer, "left");
      esc_pos_font($printer, "A");
      esc_pos_emphasize($printer,true);
      esc_pos_line($printer, "SELLO DIGITAL DEL EMISOR:");
      esc_pos_align($printer, "center");
      esc_pos_emphasize($printer,false);
      esc_pos_line($printer, "'.$sello_cfdi.'");

	  	esc_pos_align($printer, "left");
      esc_pos_font($printer, "A");
      esc_pos_emphasize($printer,true);
      esc_pos_line($printer, "SELLO DIGITAL SAT, PSECFDI:");
      esc_pos_align($printer, "center");
      esc_pos_emphasize($printer,false);
      esc_pos_line($printer, "'.$sello_sat.'");
	  	esc_pos_line($printer, " ");
	  	esc_pos_line($printer, "'.$leyenda.'");
			esc_pos_line($printer, " ");
			esc_pos_close($printer);';

      eval($var);

      	$handle = printer_open($impresora_cuentas);
		printer_start_doc($handle, "My Document");
		printer_start_page($handle);

		printer_set_option($handle, PRINTER_MODE, "RAW");
		printer_draw_bmp($handle, "temp/".$name.".bmp", 1, 1,  $largura_f, $altura_f);

		printer_end_page($handle);
		printer_end_doc($handle);
		printer_close($handle);

}


function acuse($id_venta,$id_venta_cancelacion,$motivo){

	global $autoprint;
	global $impresora_cuentas;
	session_start();

	$fecha_hora_ticket = date('d-m-Y h:i a');


	$sql = "SELECT*FROM ventas_cancelaciones WHERE id_venta = $id_venta";

	$qq = mysql_query($sql);
	$cuantos_cancelados = mysql_num_rows($qq);



   $var='
	  $printer = esc_pos_open("'.$impresora_cuentas.'", "ch-latin-2", false, true);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_line($printer, "------------- ACUSE DE CANCELACION -------------");
      esc_pos_line($printer, "------------------------------------------------");';

while($fx = mysql_fetch_assoc($qq)){



    $var.='
      esc_pos_line($printer, "");
	  esc_pos_line($printer, "############### CANCELACION #'.$fx['id_venta_cancelacion'].' ################");
      esc_pos_line($printer, "");
      esc_pos_line($printer, "'.$fx['fechahora_cancelacion'].'");
      esc_pos_line($printer, "FOLIO: #'.$id_venta.'");
      esc_pos_line($printer, "CANCELADOR: '.strtoupper($_SESSION['s_nombre']).'");
      esc_pos_line($printer, "------------------------------------------------");
	  esc_pos_align($printer, "left");
      esc_pos_line($printer, "PRODUCTO                    CANT   UNIT    SUBT");';

	$sql = "SELECT ventas_cancelaciones_detalle.cantidad,productos.nombre,ventas_cancelaciones_detalle.precio_venta FROM ventas_cancelaciones_detalle
	JOIN productos ON productos.id_producto = ventas_cancelaciones_detalle.id_producto
	WHERE id_venta_cancelacion = ".$fx['id_venta_cancelacion'];
	$q = 0;
	$q = mysql_query($sql);
	while($ft=mysql_fetch_assoc($q)){

		  $producto = $ft['nombre'];
		  $producto = substr($producto,0,20);

	      $c_p = strlen($producto);
		  if($c_p<20){
		  	$to_p = 20-$c_p;
			  switch($to_p){
			      case 1: $space0 = " "; break;
			      case 2: $space0 = "  "; break;
			      case 3: $space0 = "   "; break;
			      case 4: $space0 = "    "; break;
			      case 5: $space0 = "     "; break;
			      case 6: $space0 = "      "; break;
			      case 7: $space0 = "       "; break;
			      case 8: $space0 = "        "; break;
			      case 9: $space0 = "         "; break;
			      case 10: $space0 = "          "; break;
			      case 11: $space0 = "           "; break;
			      case 12: $space0 = "            "; break;
			      case 13: $space0 = "             "; break;
			      case 14: $space0 = "              "; break;
 			      case 15: $space0 = "               "; break;
			      case 16: $space0 = "                "; break;
			      case 17: $space0 = "                 "; break;
			      case 18: $space0 = "                  "; break;
			      case 19: $space0 = "                   "; break;
			      case 20: $space0 = "                    "; break;

			  }
		  }

		  $cantidad = $ft['cantidad'];
		  $precio = $ft['precio_venta'];

		  $total=$ft['cantidad']*$ft['precio_venta'];
		  $g_total+=$total;
		  $total= number_format($total,2, '.', '');

			  $prec = strlen($precio);
			  $cant = strlen($cantidad);
			  $tot = strlen($total);

			  switch($cant){
			      case 1: $space1 = "          "; break;
			      case 2: $space1 = "         "; break;
			      case 3: $space1 = "        "; break;
			      case 4: $space1 = "       "; break;
			  }
			  switch($prec){
			      case 4: $space2 = "    "; break;
			      case 5: $space2 = "   "; break;
			      case 6: $space2 = "  "; break;
			      case 7: $space2 = " "; break;
			      case 8: $space2 = ""; break;
			  }
			  switch($tot){
			      case 4: $space3 = "     "; break;
			      case 5: $space3 = "    "; break;
			      case 6: $space3 = "   "; break;
			      case 7: $space3 = "  "; break;
			      case 8: $space3 = " "; break;
			  }
	      $var.= 'esc_pos_line($printer, "'.$producto.$space0.$space1.$cantidad.$space2.$precio.$space3.$total.'");';
	      unset($space0);
	  }

	  $total_final_clean = $g_total;

	  $g_total = number_format($g_total,2, '.', '');

	  $t = strlen($g_total);

	  switch($t){
	      case 4: $spacet = "           "; break;
	      case 5: $spacet = "          "; break;
	      case 6: $spacet = "         "; break;
	      case 7: $spacet = "        "; break;
	      case 8: $spacet = "       "; break;
	  }


		$var.='
	      esc_pos_line($printer, "------------------------------------------------");
	      esc_pos_align($printer, "right");
	      esc_pos_line($printer, "TOTAL:'.$spacet.$g_total.'");
	      esc_pos_align($printer, "center");
	      esc_pos_line($printer, "");
	      esc_pos_line($printer, "MOTIVO:");
	      esc_pos_line($printer, "'.$fx['motivo'].'");
	      esc_pos_line($printer, "");
	     ';


		$g_total = 0;
		$total_final_clean = 0;
		$total = 0;

}


	     $var.='
	      esc_pos_line($printer, "############## TICKET FINAL ###############");
	      esc_pos_line($printer, "");
          esc_pos_line($printer, "'.$fecha_hora_ticket.'");
	     esc_pos_line($printer, "FOLIO: #'.$id_venta.'");
	      esc_pos_line($printer, "CANCELADOR: '.strtoupper($_SESSION['s_nombre']).'");
	      esc_pos_line($printer, "------------------------------------------------");
		  esc_pos_align($printer, "left");
	      esc_pos_line($printer, "PRODUCTO                    CANT   UNIT    SUBT");';

		$g_total = 0;
		$total_final_clean = 0;
		$total = 0;


		$sql = "SELECT venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta FROM venta_detalle
		JOIN productos ON productos.id_producto = venta_detalle.id_producto
		WHERE id_venta = '$id_venta'";
		$q = mysql_query($sql);
		while($ft=mysql_fetch_assoc($q)){

			  $producto = $ft['nombre'];
			  $producto = substr($producto,0,20);

		      $c_p = strlen($producto);
			  if($c_p<20){
			  	$to_p = 20-$c_p;
				  switch($to_p){
			      case 1: $space0 = " "; break;
			      case 2: $space0 = "  "; break;
			      case 3: $space0 = "   "; break;
			      case 4: $space0 = "    "; break;
			      case 5: $space0 = "     "; break;
			      case 6: $space0 = "      "; break;
			      case 7: $space0 = "       "; break;
			      case 8: $space0 = "        "; break;
			      case 9: $space0 = "         "; break;
			      case 10: $space0 = "          "; break;
			      case 11: $space0 = "           "; break;
			      case 12: $space0 = "            "; break;
			      case 13: $space0 = "             "; break;
			      case 14: $space0 = "              "; break;
 			      case 15: $space0 = "               "; break;
			      case 16: $space0 = "                "; break;
			      case 17: $space0 = "                 "; break;
			      case 18: $space0 = "                  "; break;
			      case 19: $space0 = "                   "; break;
			      case 20: $space0 = "                    "; break;

				  }
			  }

			  $cantidad = $ft['cantidad'];
			  $precio = $ft['precio_venta'];

			  $total=$ft['cantidad']*$ft['precio_venta'];
			  $g_total+=$total;
			  $total= number_format($total,2, '.', '');

				  $prec = strlen($precio);
				  $cant = strlen($cantidad);
				  $tot = strlen($total);

				  switch($cant){
				      case 1: $space1 = "          "; break;
				      case 2: $space1 = "         "; break;
				      case 3: $space1 = "        "; break;
				      case 4: $space1 = "       "; break;
				  }
				  switch($prec){
				      case 4: $space2 = "    "; break;
				      case 5: $space2 = "   "; break;
				      case 6: $space2 = "  "; break;
				      case 7: $space2 = " "; break;
				      case 8: $space2 = ""; break;
				  }
				  switch($tot){
				      case 4: $space3 = "     "; break;
				      case 5: $space3 = "    "; break;
				      case 6: $space3 = "   "; break;
				      case 7: $space3 = "  "; break;
				      case 8: $space3 = " "; break;
				  }
		      $var.= 'esc_pos_line($printer, "'.$producto.$space0.$space1.$cantidad.$space2.$precio.$space3.$total.'");';
		      unset($space0);
		  }

		  $total_final_clean = $g_total;

		  $g_total = number_format($g_total,2, '.', '');

		  $t = strlen($g_total);

		  switch($t){
		      case 4: $spacet = "           "; break;
		      case 5: $spacet = "          "; break;
		      case 6: $spacet = "         "; break;
		      case 7: $spacet = "        "; break;
		      case 8: $spacet = "       "; break;
		  }


		$var.='
	      esc_pos_line($printer, "------------------------------------------------");
	      esc_pos_align($printer, "right");
	      esc_pos_line($printer, "TOTAL:'.$spacet.$g_total.'");
	      esc_pos_line($printer, "");
	      esc_pos_line($printer, "");
	      esc_pos_line($printer, "");
	      esc_pos_align($printer, "center");
	      esc_pos_line($printer, "RESPONSABLE:");
	      esc_pos_line($printer, "");
	      esc_pos_line($printer, "");
	      esc_pos_line($printer, "");
	      esc_pos_line($printer, "");
	      esc_pos_line($printer, "______________________________");
	      esc_pos_line($printer, "");
	      esc_pos_line($printer, "");
		  esc_pos_cut($printer);
	      esc_pos_close($printer);';

	if($autoprint){
	    eval($var);
	}

	}


function imprimir_domicilio($nombre,$telefono,$direccion){

	global $establecimiento_config;
	global $impresora_sd;
	global $footer_domicilio;
	global $autoprint;
	global $header_domicilio;
	$fecha_hora_ticket = date('d-m-Y h:i a');

    $var='
	  $printer = esc_pos_open("'.$impresora_sd.'", "ch-latin-2", false, true);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_line($printer, "'.$establecimiento_config.'");
      esc_pos_line($printer, "'.$fecha_hora_ticket.'");';

    $var.=$header_domicilio;

    $var.='
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "left");
      esc_pos_line($printer, "CLIENTE: '.$nombre.'");
      esc_pos_line($printer, "TELEFONO: '.$telefono.'");
      esc_pos_line($printer, "DIRECCION: '.$direccion.'");
      esc_pos_align($printer, "center");
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "center");
      esc_pos_line($printer, "");';

	  $var.=$footer_domicilio;

	  $var.='
      esc_pos_line($printer, "");
	  esc_pos_cut($printer);
      esc_pos_close($printer);
      ';

	if($autoprint){
	    eval($var);
	}

    unset($var);

	$var='
	  $printer = esc_pos_open("'.$impresora_sd.'", "ch-latin-2", false, true);
      esc_pos_line($printer, "");
      esc_pos_line($printer, "'.$fecha_hora_ticket.'");
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "left");
      esc_pos_line($printer, "CLIENTE: '.$nombre.'");
      esc_pos_line($printer, "TELEFONO: '.$telefono.'");
      esc_pos_line($printer, "DIRECCION: '.$direccion.'");
      esc_pos_line($printer, "");
      esc_pos_line($printer, "");
	  esc_pos_cut($printer);
      esc_pos_close($printer);
      ';

	if($autoprint){
	    eval($var);
	}

}

function imprimir_codigo($codigo,$monto_num,$metodo_texto,$num_cta){

	global $establecimiento_config;
	global $impresora_cuentas;
	global $dias_para_factura;
	global $sitio_web_factura;
	global $autoprint;
	$fecha_hora = date('d-m-Y - H:i');

	if($num_cta){
		$NM = 'esc_pos_line($printer, "NUM CTA:'.$num_cta.'");';
	}

	$var='
      $printer = esc_pos_open("'.$impresora_cuentas.'", "ch-latin-2", false, true);
      esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_line($printer, "'.$establecimiento_config.'");
      esc_pos_line($printer, "'.$fecha_hora.'");
      esc_pos_line($printer, "AUX: '.strtoupper($_SESSION['s_nombre']).'");      ';
      $var.= 'esc_pos_line($printer, "--------------------------------------------");
      esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_line($printer, "GENERE SU FACTURA EN LINEA:");
      esc_pos_line($printer, "'.$sitio_web_factura.'");
      esc_pos_line($printer, "CUENTA CON '.$dias_para_factura.' DIAS A PARTIR DE LA FECHA");
      esc_pos_line($printer, "DE ESTE TICKET PARA GENERAR SU CFDI.");
      esc_pos_line($printer, "--------------------------------------------");
      esc_pos_line($printer, "== CODIGO DE FACTURACION ==");
      esc_pos_line($printer, "'.$codigo.'");
      esc_pos_line($printer, "MONTO: '.number_format($monto_num,2).'");
      esc_pos_line($printer, "METODO DE PAGO: '.$metodo_texto.'");
      '.$NM.'
      esc_pos_font($printer, "A");
      esc_pos_align($printer, "center");
      esc_pos_line($printer, "--------------------------------------------");
      esc_pos_align($printer, "center");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "GRACIAS POR SU PREFERENCIA");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_cut($printer);
      esc_pos_close($printer);';

	if($autoprint){
	    eval($var);
	}

}

function imprimir_wifi($password_wifi){

	global $establecimiento_config;
	global $impresora_cortes;
	global $autoprint;
	$fecha_hora = date('d-m-Y - H:i');

	$var='
      $printer = esc_pos_open("'.$impresora_cortes.'", "ch-latin-2", false, true);
      esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_line($printer, "'.$establecimiento_config.'");
      esc_pos_line($printer, "'.$fecha_hora.'");
      esc_pos_line($printer, "AUX: '.strtoupper($_SESSION['s_nombre']).'");      ';
      $var.= 'esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_line($printer, "--------------------------------------------");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "== CONTRASENA WIFI (1 HORA) ==");
      esc_pos_line($printer, "RED: TACO LOCO FREE WIFI");
      esc_pos_line($printer, "'.$password_wifi.'");
      esc_pos_line($printer, " ");
      esc_pos_font($printer, "A");
      esc_pos_align($printer, "center");
      esc_pos_line($printer, "--------------------------------------------");
      esc_pos_align($printer, "center");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_cut($printer);
      esc_pos_close($printer);';

	if($autoprint){
	    eval($var);
	}

}

if(!function_exists("esc_pos_open")){

      // =============================
      //    ESC/POS Printer Driver   =
      // =============================
      //        EPSON TM-U220B       =
      //  Windows Printer Functions  =
      // =============================
      // (c) Markus Fumagalli "Kovu" =
      //   http://www.terrasco.net   =
      //         Feb 02 2009         =
      // =============================
      //    You may edit, use and    =
      //   redistribute this file,   =
      //   but leave the original    =
      //   copyright info intact!    =
      // =============================


      // Translate
      // =========
      // $chr_set: string/false ('translation set to use' or false 'for none')

      function esc_pos_translate($string, $chr_set = "ch-latin-2"){
         switch($chr_set){
            case "ch-latin-2":
                   $patterns = "Ä Ö Ü ä ö ü " .
                               "à â ç é è ê " .
                               "ë ï î ì ô ò";
                   $patterns = explode(" ", $patterns);
               $replacements = array(chr(142), chr(153), chr(154), chr(132), chr(148), chr(129),
                                     chr(133), chr(131), chr(135), chr(130), chr(138), chr(136),
                                     chr(137), chr(139), chr(140), chr(141), chr(147), chr(149));
               break;

            default:
               return $string;
         }

         return str_replace($patterns, $replacements, $string);
      }


      // Open Printer
      // ============
      // $translate_chr_set: string (character set name) optional
      // $esc_chr_table: integer    (ESC P character table) optional
      // $reset: bool               (re-initialize printer) optional

      function esc_pos_open($printer_name, $translate_chr_set = false, $esc_chr_table = false, $reset = true){
         // Detect Operating System Family
         // ==============================

         if(substr(php_uname(), 0, 7) == "Windows"){
            $esc_printer['os_family'] = "windows";
         }else{
            $esc_printer['os_family'] = "unix";
         }

         // Check Opening Method
         // ====================

         switch($printer_name){
            case "PRN":
            case "COM1":
            case "COM2":
               $esc_printer['mode'] = "port_windows";
               break;

            case "ttyS0":
            case "ttyS1":
               $esc_printer['mode'] = "port_unix";
               break;

            default:
               $esc_printer['mode'] = "printer_system";
         }

         if($esc_printer['mode'] == "printer_system"){
            if($esc_printer['os_family'] == "windows"){

               // Open With Windows Printer Functions
               // ===================================

               $esc_printer['handle'] = printer_open($printer_name);
               if($esc_printer['handle'] == false){ return false; }

               printer_set_option($esc_printer['handle'], PRINTER_MODE, "raw");

            }else{

               // Send Document to CUPS lpr later
               // ===============================

               $esc_printer['handle'] = true;
            }
         }else{
            if($esc_printer['mode'] == "port_windows" && $esc_printer['os_family'] == "windows"){

               // Open Port DOS / Windows
               // =======================

               if($printer_name == "PRN"){
                  $device = $printer_name;
               }else{
                  shell_exec("mode " . strtolower($printer_name) . ": BAUD=9600 PARITY=N data=8 stop=1 xon=off");
                  $device = $printer_name;
               }

               $esc_printer['handle'] = fopen($device, 'w');

            }elseif($esc_printer['mode'] == "port_unix" && $esc_printer['os_family'] == "unix"){

               // Open Serial Port Unix
               // =====================
               // -> port setup should
               // be done with external
               // tools.

               $device = "/dev/" . $printer_name;
               $esc_printer['handle'] = fopen($device, 'w');

            }else{
               return false;
            }
         }

         // Check Handle
         // ============

         if($esc_printer['handle'] == false){ return false; }

         // Create Document
         // ===============

         $esc_printer['width'] = 40;
         $esc_printer['document'] = false;

         if($reset == true){

            // Re-Initialize printer (reset buffer)
            // ====================================

            $esc_pos_command = chr(27) . "@";
         }

         if($esc_chr_table !== false){

            // Select ESC/POS Character Code Table
            // ===================================

            if(isset($esc_pos_command)){
               $esc_pos_command .= chr(27) . "t" . chr($esc_chr_table);
            }else{
               $esc_pos_command = chr(27) . "t" . chr($esc_chr_table);
            }

         }

         if(isset($esc_pos_command)){
            $printer['document'] = $esc_pos_command;
         }

         $esc_printer['chr_set'] = $translate_chr_set;
         $esc_printer['name'] = $printer_name;

         return $esc_printer;
      }


      // Select Color
      // ============
      // $printer: array (ESC/POS printer data)
      // $color: integer (color id 0 or 1)

      function esc_pos_color(&$printer, $color = 0){
         if($printer == false){ return false; }

         switch($color){
            case 1:
               $select = chr(49);
               break;

            default:
               $select = chr(48);
         }

         $esc_pos_command = chr(27) . "r" . $select;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Double Strike
      // =============
      // $printer: array (ESC/POS printer data)
      // $double_strike: bool

      function esc_pos_double(&$printer, $double_strike = false){
         if($printer == false){ return false; }

         switch($double_strike){
            case true:
               $set = chr(49);
               break;

            default:
               $set = chr(48);
         }

         $esc_pos_command = chr(27) . "G" . $set;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Emphasize
      // =========
      // $printer: array (ESC/POS printer data)
      // $double_strike: bool

      function esc_pos_emphasize(&$printer, $select = false){
         if($printer == false){ return false; }

         switch($select){
            case true:
               $set = chr(49);
               break;

            default:
               $set = chr(48);
         }

         $esc_pos_command = chr(27) . "E" . $set;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Underline
      // =========
      // $printer: array (ESC/POS printer data)
      // $underline: bool

      function esc_pos_underline(&$printer, $underline = false){
         if($printer == false){ return false; }

         switch($underline){
            case true:
               $set = chr(49);
               break;

            default:
               $set = chr(48);
         }

         $esc_pos_command = chr(27) . "-" . $set;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Select Font
      // ===========
      // $printer: array (ESC/POS printer data)
      // $font: string   (type character A/B/*C)

      function esc_pos_font(&$printer, $font = "B"){
         if($printer == false){ return false; }

         switch($font){
            case "A":
               $set = chr(48);
               break;

            case "C":
               $set = chr(50);
               break;

            default:
               $set = chr(49);
         }

         $esc_pos_command = chr(27) . "M" . $set;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Select Character Height
      // =======================
      // $printer: array (ESC/POS printer data)
      // $size: string   (double / normal)

      function esc_pos_char_height(&$printer, $size = "double"){
         if($printer == false){ return false; }

         switch($size){
            case "double":
               $set = chr(16);
               break;

            default:
               $set = chr(0);
         }

         $esc_pos_command = chr(27) . "!" . $set;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Select Character Width
      // ======================
      // $printer: array (ESC/POS printer data)
      // $size: string   (double / normal)

      function esc_pos_char_width(&$printer, $size = "double"){
         if($printer == false){ return false; }

         switch($size){
            case "double":
               $set = chr(32);
               break;

            default:
               $set = chr(0);
         }

         $esc_pos_command = chr(27) . "!" . $set;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Align
      // =====
      // $printer: array   (ESC/POS printer data)
      // $position: string (left/center/right)

      function esc_pos_align(&$printer, $position = "left"){
         if($printer == false){ return false; }

         switch($position){
            case "right":
               $set = chr(50);
               break;

            case "center":
               $set = chr(49);
               break;

            default:
               $set = chr(48);
         }

         $esc_pos_command = chr(27) . "a" . $set;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // XFeed
      // =====
      // $printer: array (ESC/POS printer data)
      // $lines: integer (lines to feed)

      function esc_pos_xfeed(&$printer, $lines = 1){
         if($printer == false){ return false; }

         if($lines > 1){
            $feed = chr(2);
         }else{
            $feed = chr($lines);
         }

         $esc_pos_command = chr(27) . "d" . $feed;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // XFeed Reverse
      // =============
      // $printer: array (ESC/POS printer data)
      // $lines: integer (lines to feed)

      function esc_pos_xrfeed(&$printer, $lines = 1){
         if($printer == false){ return false; }

         if($lines > 1){
            $feed = chr(2);
         }else{
            $feed = chr($lines);
         }

         $esc_pos_command = chr(27) . "e" . $feed;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Cut
      // ===
      // $printer: array (ESC/POS printer data)
      // $lines: integer (lines to feed before cutting) optional

      function esc_pos_cut(&$printer, $lines = false){
         if($printer == false){ return false; }

         if($lines != false){
            $feed = chr($lines);
         }else{
            $feed = chr(0);
         }

         $esc_pos_command = chr(29) . "V" . chr(65) . $feed;

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Drawer Kick
      // ===========
      // $printer: array (ESC/POS printer data)

      function esc_pos_drawer(&$printer, $pin = 2){
         if($printer == false){ return false; }

         if($pin == 5){
            // Pin 5
            $pin = chr(49);
         }else{
            // Pin 2
            $pin = chr(48);
         }

         $esc_pos_command = chr(27) . "p" . $pin . chr(49) . chr(50);

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Add a Line
      // ==========
      // $printer: array (ESC/POS printer data)
      // $string: string (print data)
      // $feed: bool     (feed after printing line)

      function esc_pos_line(&$printer, $string, $feed = true){
         if($printer == false){ return false; }

         $esc_pos_command = esc_pos_translate($string, $printer['chr_set']);

         if($feed == true){
            $esc_pos_command .= chr(10);
         }

         if($printer['document'] == false){
            $printer['document'] = $esc_pos_command;
         }else{
            $printer['document'] .= $esc_pos_command;
         }
      }


      // Feed one Line
      // =============
      // $printer: array (ESC/POS printer data)

      function esc_pos_nl(&$printer){
         if($printer == false){ return false; }

         if($printer['document'] == false){
            $printer['document'] = chr(10);
         }else{
            $printer['document'] .= chr(10);
         }
      }


      // Print Buffer
      // ============
      // $printer: array (ESC/POS printer data)

      function esc_pos_print(&$printer){
         if($printer == false){ return false; }

         if($printer['document'] != false){
            if($printer['mode'] == "printer_system"){

               // Print Through Printer System
               // ============================

               if($printer['os_family'] == "windows"){

                  // Windows
                  // =======

                  printer_write($printer['handle'], $printer['document']);

               }else{

                  // Unix, CUPS
                  // ==========

                  $command = "lpr -P \"" . $printer['name'] . "\" -o raw ";
                  $pipe = popen("$command" , 'w');
                  if(!$pipe){ print "PRINT ERROR: pipe failed.\n"; return false; }
                  fputs($pipe, $printer['document']);
                  pclose($pipe);
               }

            }else{

               // Print On Port Directly
               // ======================

               fwrite($printer['handle'], $printer['document']);
            }

            $printer['document'] = false;
         }
      }


      // Close Printer
      // =============
      // $printer: array (ESC/POS printer data)

      function esc_pos_close(&$printer){
         if($printer == false){ return false; }

         esc_pos_print($printer);
         if($printer['mode'] == "printer_system" && $printer['os_family'] == "windows"){
            printer_close($printer['handle']);
         }elseif($printer['mode'] == "port_windows" OR $printer['mode'] == "port_unix"){
            fclose($printer['handle']);
         }
         $printer = false;
      }

}
