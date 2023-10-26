<?
function return_linea($linea){
	if(strlen($linea)>0):
		return 'esc_pos_line($printer, "'.$linea.'");';
	endif;
}
function imprimir_corte($id_corte){



global $establecimiento_config;

global $autoprint;
global $enviar_sms;

session_start();



$sql_ep ="SELECT*FROM configuracion ";

$q_ep = mysql_query($sql_ep);
$impre = mysql_fetch_assoc($q_ep);
 $impresora_cortes=$impre['impresora_cortes'];

$sql_corte ="SELECT*FROM cortes WHERE id_corte = $id_corte";

$q_corte = mysql_query($sql_corte);
$n = mysql_num_rows($q_corte);
$datos_corte = mysql_fetch_assoc($q_corte);
$fecha_corte =$datos_corte['fh_abierto'];
$fechacierra= $datos_corte['fecha']." ".$datos_corte['hora'];

$efectivoCa = $datos_corte['efectivoCaja'];
$tpvEfec = $datos_corte['tpv'];


$sql ="SELECT id_venta FROM ventas WHERE id_corte = $id_corte";

$qx = mysql_query($sql);

$prod = array();
$nombres = array();
$pu = array();


while($fx = mysql_fetch_assoc($qx)){


    $sql = "SELECT venta_detalle.id_producto,venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta FROM venta_detalle
    JOIN productos ON productos.id_producto = venta_detalle.id_producto
    WHERE id_venta =".$fx['id_venta'] ." AND venta_detalle.precio_venta != 0  ";
  
    $q = mysql_query($sql);
       
    while($ft= mysql_fetch_assoc($q)){
        $prod[$ft['id_producto']]+=$ft['cantidad'];

       $nombres[$ft['id_producto']] = $ft['nombre'];
       $pu[$ft['id_producto']] = $ft['precio_venta'];
       
    }
   


  
 
}  

ksort($nombres);

//array_sort_by($nombres, 'ord', $order = SORT_ASC);
$mesas_ct = 0;
$barra_ct = 0;
$pre_fact_ct = 0;
$no_fact_ct = 0;

$cancelaciones = 0;
$cta_expedidas = 0;

$sqlMetodos = "SELECT id_metodo,metodo_pago FROM metodo_pago";
$qMetodos = mysql_query($sqlMetodos);
while($data_metodos = mysql_fetch_assoc($qMetodos)){
    $me[$data_metodos['id_metodo']] = $data_metodos['metodo_pago'];
}
$sql = "SELECT*FROM ventas WHERE id_corte = $id_corte";

$q = mysql_query($sql);
while($ft = mysql_fetch_assoc($q)){

    $montos_metodo[$ft['id_metodo_pago']]+=$ft['monto_pagado'];

    $cta_expedidas +=1;

}

$sqlcan = "SELECT * FROM cancelaciones WHERE id_corte = $id_corte";
$qcan = mysql_query($sqlcan);

$cancelaciones=mysql_num_rows($qcan);
    $promedio = @($total_totales/$cta_expedidas);
    $mesas_por = @($mesas_ct/$cta_expedidas)*100;
    $barra_por = @($barra_ct/$cta_expedidas)*100;
    $pre_fact_por = @($pre_fact_ct/$cta_expedidas)*100;
    $no_fact_por = @($no_fact_ct/$cta_expedidas)*100;

    $pre_fact_monto_por = @($pre_fact_monto/$total_totales)*100;
    $no_fact_monto_por = @($no_fact_monto/$total_totales)*100;

    $mesas_monto_por = @($mesas_monto/$total_totales)*100;
    $barra_monto_por = @($barra_monto/$total_totales)*100;

$fecha_hora_ticket = $fecha_corte;
$fecha_corte = '
esc_pos_line($printer, " ");
esc_pos_line($printer, "FECHA APERTURA: '.$fecha_hora_ticket.'");

esc_pos_line($printer, "FECHA CORTE: '.$fechacierra.'");
';

$var='
  $printer = esc_pos_open("'.$impresora_cortes.'", "ch-latin-2", false, true);
  esc_pos_align($printer, "center");
  esc_pos_font($printer, "A");
  esc_pos_char_width($printer, "");
  '.$precorteTexto.'
  esc_pos_line($printer, "'.$establecimiento_config.'");
  esc_pos_line($printer, "CORTE DE CAJA #'.$id_corte.'");
  '.$fecha_corte.'
  esc_pos_line($printer, " ");
  

  esc_pos_line($printer, "#################### VENTA #####################");
      esc_pos_align($printer, "left");
  esc_pos_line($printer, "PRODUCTO                    CANT   UNIT     SUBT");';

    $sql ="SELECT SUM(descuento) AS total FROM ventas WHERE id_corte = $id_corte GROUP BY id_corte";
    $sqlDescuento = mysql_fetch_assoc(mysql_query($sql));
    $totalDescuento = $sqlDescuento['total'];
    
    $sql ="SELECT fondo_caja FROM cortes WHERE id_corte = $id_corte";
    $sqlFondo = mysql_fetch_assoc(mysql_query($sql));
    $fondo_caja = $sqlFondo['fondo_caja'];



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


    $efectivo = 0;
  $tarjetas = 0;
  $descuentoTotal = $g_total-$totalDescuento;

    foreach($montos_metodo as $id_m => $monto){
        if ($id_m == '1') {
            $efectivo = floatval($monto);
        }elseif ($id_m == '4' || $id_m == '28') {
            $tarjetas = floatval($tarjetas)+floatval($monto);
        }
    }
  $efectivo2 = number_format($descuentoTotal,2, '.', '');
  $tarjetas2 = number_format($tarjetas,2, '.', '');
 $promedio = ($g_total/$cta_expedidas);
  $mesas_monto = number_format($mesas_monto,2, '.', '');
  $barra_monto = number_format($barra_monto,2, '.', '');
  $pre_fact_monto = number_format($pre_fact_monto,2, '.', '');
  $no_fact_monto = number_format($no_fact_monto,2, '.', '');
  $promedio = number_format($promedio,2, '.', '');
  $no_fact_monto_por = number_format($no_fact_monto_por,2, '.', '');
  $pre_fact_monto_por = number_format($pre_fact_monto_por,2, '.', '');
  $mesas_monto_por = number_format($mesas_monto_por,2, '.', '');
  $barra_monto_por = number_format($barra_monto_por,2, '.', '');
    
    $totalDescuento = number_format($totalDescuento,2, '.', '');
    $descuentoTotal = number_format($descuentoTotal,2, '.', '');

  $var.= 'esc_pos_line($printer, "------------------------------------------------");
  esc_pos_align($printer, "right");
  esc_pos_line($printer, "VENTA SUBTOTAL:'.$spacet.$g_total.'");
        esc_pos_line($printer, "DESCUENTOS: '.$spacet.$totalDescuento.'");
        esc_pos_line($printer, "VENTA TOTAL: '.$spacet.$descuentoTotal.'");
  esc_pos_font($printer, "A");
  esc_pos_align($printer, "left");
  esc_pos_line($printer, "DESGLOSE:");
  esc_pos_line($printer, "EFECTIVO: '.$efectivo.'");
  esc_pos_line($printer, "TARJETAS: '.$tarjetas2.'");
  esc_pos_line($printer, "");
  esc_pos_line($printer, "TICKETS EXPEDIDOS: '.$cta_expedidas.'");
  esc_pos_line($printer, "PROMEDIO POR TICKET: '.$promedio.'");
  esc_pos_line($printer, "CANCELACIONES: '.$cancelaciones.'");
  esc_pos_line($printer, " ");
  esc_pos_line($printer, "#################### GASTOS ####################");
  esc_pos_line($printer, "DESCRIPCION                                MONTO");
';

$sql ="SELECT * FROM gastos WHERE id_corte = $id_corte";
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

        $subtotalVenta = 0;
        $subtotalVenta = floatval($fondo_caja)+floatval($descuentoTotal);
        $subtotalVenta = floatval($subtotalVenta)+floatval($tarjetas);

        $subtotalCaptura = 0;
        $subtotalCaptura = floatval($subtotalCaptura)+floatval($efectivoCa);
        $subtotalCaptura = floatval($subtotalCaptura)+floatval($tpvEfec);


        $ENCAJA = $subtotalVenta;
        $ENCAJA = $ENCAJA-$g_total_g;

        $ENCAJA2 = $subtotalCaptura;
        

        $ventaDetalle = "";
        $restaDetalle = $ENCAJA2-$ENCAJA;
        if ($restaDetalle > 0) {
            $ventaDetalle = "SOBRANTE: $".number_format($restaDetalle,2, '.', '');
        }elseif ($restaDetalle < 0) {
            $ventaDetalle = "FALTANTE: $".number_format(($restaDetalle*-1),2, '.', '');
        }else {
            $ventaDetalle = "DIFERENCIA: $".number_format($restaDetalle,2, '.', '');
        }

        $subtotalVenta = number_format($subtotalVenta,2, '.', '');
        $subtotalCaptura = number_format($subtotalCaptura,2, '.', '');

        $ENCAJA = number_format($ENCAJA,2, '.', '');
        $ENCAJA2 = number_format($ENCAJA2,2, '.', '');

        $efectivoCa = number_format($efectivoCa,2, '.', '');
        $tpvEfec = number_format($tpvEfec,2, '.', '');
        $fondo_caja2 = number_format($fondo_caja,2, '.', '');


        $var.='
  esc_pos_line($printer, " ");
  esc_pos_line($printer, " ");
      esc_pos_line($printer, "################## CORTE CAJA ##################");
  esc_pos_align($printer, "left");
        esc_pos_line($printer, "FONDO DE CAJA: '.$fondo_caja2.'");
        esc_pos_line($printer, "EFECTIVO: '.$efectivo2.'");
  esc_pos_line($printer, "TARJETAS: '.$tarjetas2.'");
  esc_pos_line($printer, "SUBTOTAL: '.$subtotalVenta.'");
        esc_pos_line($printer, "GASTOS: '.$g_total_gastos.'");
     esc_pos_line($printer, "TOTAL: '.$ENCAJA.'");';
     $ajsute=0;
$sql74="SELECT ajuste FROM cortes WHERE id_corte =$id_corte";
    $query74 = mysql_query($sql74);
$ft=mysql_fetch_assoc($query74);

    $ajuste= $ft['ajuste'];

    
     if($ajuste==0){
     $var.='
        esc_pos_line($printer, "################# CORTE CAPTURA ################");
  esc_pos_align($printer, "left");

        esc_pos_line($printer, "EFECTIVO TOTAL: '.$efectivoCa.'");
  esc_pos_line($printer, "TARJETAS: '.$tpvEfec.'");
    
        esc_pos_line($printer, "TOTAL: '.$ENCAJA2.'");
        esc_pos_line($printer, " ");
  esc_pos_line($printer, " ");
        esc_pos_line($printer, "------------------------------------------------");
  esc_pos_line($printer, " ");
        esc_pos_line($printer, " ");
        esc_pos_align($printer, "right");
        esc_pos_line($printer, "TOTAL VENTA: '.$ENCAJA.'");
        esc_pos_line($printer, "TOTAL CAPTURA: '.$ENCAJA2.'");
        esc_pos_line($printer, "'.$ventaDetalle.'");
        esc_pos_line($printer, " ");
  esc_pos_line($printer, " ");
  esc_pos_line($printer, " ");';
     }
   
     if($totalDescuento!=0.00){ 
        
        $var.='
        esc_pos_line($printer, "################# DESCUENTOS #################");
  ';}
 
     $sql45 ="SELECT * FROM ventas
     JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
    JOIN cupones ON cupones.id_cupon = ventas.id_descuento
WHERE id_corte = $id_corte AND id_descuento !=0";

$qx45 = mysql_query($sql45);

while($fx45 = mysql_fetch_assoc($qx45)){
 
  $fecha =$fx45['fechahora_pagada'];
  $folio = $fx45['id_venta'];
  $aux = $fx45['nombre'];

  $consumo = $fx45['monto_pagado']+$fx45['descuento'];
  $consumo= number_format($consumo,2, '.', '');
  $descuento = $fx45['descuento']." (".$fx45['cupon'].")";
  
  $total_cobrado= $fx45['monto_pagado'];
  if($total_cobrado==$fx45['descuento']){
     
     $total_cobrado=0;
  }
  $descuento= number_format($descuento,2, '.', '');
  $total_cobrado= number_format($total_cobrado,2, '.', '');

  
  
$sql90 = "SELECT venta_detalle.id_producto, venta_detalle.cantidad, productos.nombre, venta_detalle.precio_venta
FROM venta_detalle
JOIN productos ON productos.id_producto = venta_detalle.id_producto
WHERE venta_detalle.id_venta =393
AND venta_detalle.precio_venta !=0  ";
;

$q90 = mysql_query($sql90);

$var.='

esc_pos_align($printer, "left");

esc_pos_line($printer, "FECHA: '.$fecha.'");
esc_pos_line($printer, "FOLIO: '.$folio.'");
esc_pos_line($printer, "AUX: '.$aux.'");

esc_pos_line($printer, " ");
esc_pos_line($printer, "PRODUCTOS:");
esc_pos_line($printer, "------------------------------------------------");
';




while($ft=mysql_fetch_assoc($q90)){
  
  $producto=$ft['cantidad']." - ".$ft['nombre'];
  $tot= $ft['cantidad']*$ft['precio_venta'];
  $tot=number_format($tot,2, '.', '');
  $cantidad=number_format($ft['precio_venta'],2, '.', '')." @ ". $tot;
  
   $var.='

  esc_pos_align($printer, "left");
  
     esc_pos_line($printer,"'.$producto.'");
  esc_pos_line($printer, "'.$cantidad.'");
  
  ';

  }
 
  $var.='

  esc_pos_align($printer, "left");
     esc_pos_line($printer, "------------------------------------------------");
  ';
  
  $var.='

  esc_pos_align($printer, "left");
  
     esc_pos_line($printer, "CONSUMO: '.$consumo.'");
  esc_pos_line($printer, "DESCUENTO: '.$descuento.'");
     esc_pos_line($printer, "TOTAL COBRADO: '.$total_cobrado.'");
    
     esc_pos_line($printer, " ");
     esc_pos_line($printer, "##############################################");
  ';
 
 
 
}




  $var.='
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
 

 
      return($var);
//return($nombres);

}

////////////////////////////////////////////////////////////////////////////////////////////////////aqui
function imprimir_mesa($id_venta, $metodo=false){

	global $conexion;
	session_start();

	$fecha_hora_ticket = date('d-m-Y h:i a');

	$headers = "SELECT * FROM configuracion";
	$qh = mysql_query($headers);
   
	while( $ft = mysql_fetch_assoc($qh) ){
        
		$header_1 = return_linea($ft['header_1']);
	  $header_2 = return_linea($ft['header_2']);
	  $header_3 = return_linea($ft['header_3']);
	  $header_4 = return_linea($ft['header_4']);
	  $header_5 = return_linea($ft['header_5']);
	  $header_6 = return_linea($ft['header_6']);
	  $header_7 = return_linea($ft['header_7']);
	  $header_8 = return_linea($ft['header_8']);
	  $header_9 = return_linea($ft['header_9']);
	  $header_10 = return_linea($ft['header_10']);

	  $footer_1= return_linea($ft['footer_1']);
	  $footer_2= return_linea($ft['footer_2']);
	  $footer_3= return_linea($ft['footer_3']);
	  $footer_4= return_linea($ft['footer_4']);
	  $footer_5= return_linea($ft['footer_5']);
	  $footer_6= return_linea($ft['footer_6']);
	  $footer_7= return_linea($ft['footer_7']);
	  $footer_8= return_linea($ft['footer_8']);
	  $footer_9= return_linea($ft['footer_9']);
	  $footer_10= return_linea($ft['footer_10']);
     
		if($ft['direccion']!= null){
			$direccion =return_linea($ft['direccion']);
		}
		if($ft['establecimiento']!= null){
			$establecimiento =return_linea($ft['establecimiento']);
		}
		if($ft['telefono']!= null){
			$telefono =return_linea("Telefono: ".$ft['telefono']);
		}
		if($ft['celular']!= null){
			$celular =return_linea("Celular: ".$ft['celular']);
        }
        

	}

    $var.='
	  $printer = esc_pos_open("EPSON", "ch-latin-2", false, true);
	  esc_pos_drawer($printer);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");';

			$var.= $header_1;
			$var.= $header_2;
			$var.= $header_3;
			$var.= $header_4;
			$var.= $header_5;
			$var.= $header_6;
			$var.= $header_7;
			$var.= $header_8;
			$var.= $header_9;
			$var.= $header_10;
			$var.= ' esc_pos_line($printer,"" );';
            
			$var .= 'esc_pos_line($printer, "'.$fecha_hora_ticket.'");
      esc_pos_line($printer, "FOLIO: #'.$id_venta.'");
      esc_pos_line($printer, "AUX: '.strtoupper($_SESSION['s_nombre']).'");
      esc_pos_line($printer, "------------------------------------------------");
	  	esc_pos_align($printer, "left");
      esc_pos_line($printer, "PRODUCTO                    CANT   UNIT    SUBT");';

	$sql = "SELECT venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta FROM venta_detalle
	LEFT JOIN productos ON productos.id_producto = venta_detalle.id_producto
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

		  $cantidad =$ft['cantidad'];
		  $precio = $ft['precio_venta'];

		  $total= $ft['cantidad']*$ft['precio_venta'];
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

		$sql_descuento = "SELECT descuento FROM ventas WHERE id_venta = $id_venta";
		$q_descuento = mysql_query($sql_descuento);
		$ft_descuento = mysql_fetch_array($q_descuento);
		$descuento = $ft_descuento['descuento'];
        

		$subtotal = number_format($g_total,2);
		$g_total = number_format($g_total-$descuento,2);
		$descuento = number_format($descuento,2);

	  $m = strlen($metodo);
		$s = strlen($subtotal);
		$d = strlen($descuento);
		$t = strlen($g_total);
		$cd = strlen($descuento_t);
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
		switch($s){
	      case 4: $spaces = "           "; break;
	      case 5: $spaces = "          "; break;
	      case 6: $spaces = "         "; break;
	      case 7: $spaces = "        "; break;
	      case 8: $spaces = "       "; break;
	  }
		switch($d){
	      case 4: $spaced = "           "; break;
	      case 5: $spaced = "          "; break;
	      case 6: $spaced = "         "; break;
	      case 7: $spaced = "        "; break;
	      case 8: $spaced = "       "; break;
	  }
		switch($t){
	      case 4: $spacet = "           "; break;
	      case 5: $spacet = "          "; break;
	      case 6: $spacet = "         "; break;
	      case 7: $spacet = "        "; break;
	      case 8: $spacet = "       "; break;
	  }
		switch($cd){
	      case 4: $spacecd = "           "; break;
	      case 5: $spacecd = "          "; break;
	      case 6: $spacecd = "         "; break;
	      case 7: $spacecd = "        "; break;
	      case 8: $spacecd = "       "; break;
	  }
   
		$detalle = '
		esc_pos_line($printer, "METODO:'.$spacem.$metodo.'");
		';

	$var.='
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "right");
	  	esc_pos_emphasize($printer,true);
			esc_pos_line($printer, "SUBTOTAL:'.$spaces.$subtotal.'");
			esc_pos_line($printer, "DESCUENTO:'.$spaced.$descuento.'");
      esc_pos_line($printer, "TOTAL:'.$spacet.$g_total.'");
	  '.$detalle.'
	  	esc_pos_emphasize($printer,false);
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_font($printer, "A");
      esc_pos_align($printer, "center");
      ';

	$var.= $footer_1;
 	$var.= $footer_2;
 	$var.= $footer_3;
 	$var.= $footer_4;
 	$var.= $footer_5;
 	$var.= $footer_6;
 	$var.= $footer_7;
 	$var.= $footer_8;
 	$var.= $footer_9;
 	$var.= $footer_10;
  
	$var.='
	esc_pos_line($printer, "");
	'.$status.'
	esc_pos_cut($printer);
	esc_pos_close($printer);';
    
	  return $var;

}


