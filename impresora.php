<?php

require_once('kescpos.drv.php');
$sql ="SELECT*FROM configuracion";
$datos_config = mysql_fetch_assoc(mysql_query($sql,$conexion));
$salto = chr(13).chr(10);
$establecimiento_config = $datos_config['establecimiento'];
$autoprint = $datos_config['autoprint'];
$abrir_caja = $datos_config['abrir_caja'];
$enviar_sms = $datos_config['enviar_sms'];
$impresora_sd = $datos_config['impresora_sd'];
$impresora_cuentas = $datos_config['impresora_cuentas'];
$impresora_cortes = $datos_config['impresora_cortes'];
$impresora_cobros = $datos_config['impresora_cobros'];
$comandain = $datos_config['comandain'];

function array_sort_by(&$arrIni, $col, $order = SORT_ASC)
{
    $arrAux = array();
    foreach ($arrIni as $key=> $row)
    {
        $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
        $arrAux[$key] = strtolower($arrAux[$key]);
    }
    array_multisort($arrAux, $order, $arrIni);


}




function imprimir_corte($id_corte,$re=false){

    include('funciones.php');

    global $establecimiento_config;
    global $impresora_cortes;
    global $autoprint;
    global $enviar_sms;




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


        $sql = "SELECT venta_detalle.id_producto,venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta,productos.extra,productos.sinn,productos.paquete FROM venta_detalle
        JOIN productos ON productos.id_producto = venta_detalle.id_producto
        WHERE id_venta =".$fx['id_venta'] ." AND venta_detalle.precio_venta != 0  ";

        $q = mysql_query($sql);

        while($ft=mysql_fetch_assoc($q)){

            $prod[$ft['id_producto']]+=$ft['cantidad'];
            if($ft['extra']==1){
                $nombres[$ft['id_producto']] = "(EXTRA)".$ft['nombre'];


            }elseif($ft['paquete']==1){
                $nombres[$ft['id_producto']] ="(PAQ)". $ft['nombre'];

            }else{
                $nombres[$ft['id_producto']] = $ft['nombre'];

            }
            $pu[$ft['id_producto']] = $ft['precio_venta'];

        }

    }
    $sql_conf="SELECT * FROM configuracion";
    $query_conf=mysql_query($sql_conf);
    $conf=mysql_fetch_assoc($query_conf);
    $insumo=$conf['insumos'];

    if($insumo==1){
        function burbuja($array)
        {
            for($i=1;$i<count($array);$i++)
            {
                for($j=0;$j<count($array)-$i;$j++)
                {
                    if($array[$j]['id']>$array[$j+1]['id'])
                    {
                        $k=$array[$j+1];
                        $array[$j+1]=$array[$j];
                        $array[$j]=$k;
                    }
                }
            }
         
            return $array;
        }

        //insumos
        $tags = array();
        $tags2 = array();
        $tags3 = array();
        //Genera la lista de ingredientes
        $sql_pro= "SELECT * FROM productos_base
        LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad";
    
        $q_pro = mysql_query($sql_pro);
    
        while($pro= mysql_fetch_assoc($q_pro)){
    
            $id_base=$pro['id_base'];
    
            $producto=$pro['producto'];
    
            $abreviatura = $pro['abreviatura'];
    
            $tags[] = array(
                "id"=> $id_base,
                "producto" => $producto,
                "cantidad" => 0,
                "unidad" =>" ".$abreviatura,
                "mermado"=>0,
                "consumido"=>0,
                "dotado"=>0,
                "previa"=>0,
                "consumo_dia"=>0,
                "mermado_dia"=>0,
                "dotado_dia"=>0
            );
        }
        $tags = burbuja($tags);
        //Termina de generar la lista de ingredientes
    
        $sql_existencia="SELECT * FROM existencia WHERE id = $id_corte AND contar = 1";
        $q_existencia=mysql_query($sql_existencia);
        $existencias = mysql_fetch_assoc($q_existencia);
        $existencia = json_decode($existencias['texto'],true);
        $existencia = burbuja($existencia);
        
        for($i=0; $i<count($existencia); $i++){
            if($tags[$i]['id']==$existencia[$i]['id']){
                
                $tags[$i]['dotado_dia']+=$existencia[$i]['cantidad'];
                
                                                                
            }
        } 
      
    
            //Optiene la dotaciones de productos y los añade a existencias
            $sql_dotacion= "SELECT * FROM dotaciones WHERE id_corte=$id_corte";
            $q = mysql_query($sql_dotacion);
            while($dotacion = mysql_fetch_assoc($q)){
                $id_dotacion= $dotacion['id_dotacion'];
                $corte=$dotacion['id_corte'];
                $sql_detalle="SELECT SUM(cantidad) as cantidad,id_producto,producto,abreviatura  FROM dotaciones_detalle
                LEFT JOIN productos_base ON  dotaciones_detalle.id_producto = productos_base.id_base
                LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad 
                WHERE id_dotacion = $id_dotacion GROUP BY id_producto";
                //echo($sql_detalle);
                $detalle_q=mysql_query($sql_detalle);
                while($ft=mysql_fetch_assoc($detalle_q)){
                    $id_base=$ft['id_producto'];
                    $cantidad=$ft['cantidad'];
                    $producto=$ft['producto'];
                    $abreviatura = $ft['abreviatura'];
                    for($i=0; $i<count($tags); $i++){
                            
                        if($tags[$i]['id']==$id_base){
                            $index=$i;
                            $tags[$i]['dotado_dia']+=$cantidad;
                        }
                    }
                }
            }
            // fin de la dotaciones
    
        //mermas
        $sql_merma= "SELECT * FROM merma  WHERE id_corte=$id_corte";
        $q_merma = mysql_query($sql_merma);
        while($merma = mysql_fetch_assoc($q_merma)){
            $id_merma= $merma['id_merma'];
            $corte=$merma['id_corte'];
            $sql_detallem="SELECT SUM(cantidad) as cantidad,id_producto,producto,abreviatura  FROM merma_detalle
            LEFT JOIN productos_base ON  merma_detalle.id_producto = productos_base.id_base
            LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad 
            WHERE id_merma = $id_merma GROUP BY id_producto";
            //echo($sql_detalle);
            $detallem_q=mysql_query($sql_detallem);
            while($ft2=mysql_fetch_assoc($detallem_q)){
                $id_base=$ft2['id_producto'];
                $cantidad=$ft2['cantidad'];
                $producto=$ft2['producto'];
                $abreviatura = $ft['abreviatura'];
                for($i=0; $i<count($tags); $i++){
                    if($tags[$i]['id']==$id_base){
                        $index=$i;
                        $tags[$i]['mermado_dia']+=$cantidad;                                                                
                    }
                } 
            }
        }
    
        $ignorar =0;
        //ventas
        $sql_venta= "SELECT * FROM ventas WHERE  id_corte=$id_corte ";
            $q_venta= mysql_query($sql_venta);
            while($venta= mysql_fetch_assoc($q_venta)){
                $id_venta= $venta['id_venta'];
                $corte = $venta['id_corte'];
                $sql_detallev="SELECT * FROM  venta_detalle WHERE  id_venta =$id_venta AND id_producto!=0";
                $q_ventade= mysql_query($sql_detallev);
                while($ft3=mysql_fetch_assoc($q_ventade)){
                 $precio=$ft3['precio_venta'];

                     if($precio !=0){
                        $ignorar=0;
                    }
                    $id_base=$ft3['id_producto'];
                    $pro = "SELECT * FROM  productos WHERE   id_producto=$id_base";
                    $q_pros= mysql_query($pro);
                    $ft6=mysql_fetch_assoc($q_pros);
                    if($ignorar==0){
                     $id_ignorar = $ft6['ignorar'];
                    }
                    $ignorar =$id_ignorar;    
                    $cantidad=$ft3['cantidad'];
                    $sql_producto="SELECT  * FROM  productosxbase WHERE  id_producto=$id_base";
                    $producto_q=mysql_query($sql_producto);
                    
                    echo("</br>");
                    echo "ignorado".$ignorar;
                    echo "id".$id_base;
                    
                    while($ft4=mysql_fetch_assoc($producto_q)){
                        $id_base=$ft4['id_base'];
                        $cantidad2=$ft4['cantidad']*$cantidad;
                        
                        /* echo($ignorar."p") */;
                        
                            /* S */
                            if($ignorar!=$id_base){  
                         for($i=0; $i<count($tags); $i++){
                             if($tags[$i]['id']==$id_base){
                                 $index=$i;
     
                                 $tags[$i]['consumo_dia']+=$cantidad2;	
                                 
                             }
                         }
                         
                    }
                 }
                   
                }
            }
    
        //fin mermas
        for($i=0; $i<count($tags); $i++){
    
           $gasto=$tags[$i]['consumo_dia']+$tags[$i]['mermado_dia'];
           $existencia = $tags[$i]['dotado_dia'];
           $restante = $existencia - $gasto;
           $tags[$i]['cantidad'] = $restante;
            
        }
    
        $id_corte_siguiente= $id_corte +1;
        $sql_validad_exitencia = "SELECT *  FROM existencia where id = $id_corte_siguiente";
        $q_validad_exitencia = mysql_query($sql_validad_exitencia);
        $datos= mysql_num_rows($q_validad_exitencia);
        if(!($datos>0)){
            $json = json_encode($tags);
            $sql_nueva_existencia = "INSERT INTO existencia (id,texto) values($id_corte_siguiente,'$json')";
            $q = mysql_query($sql_nueva_existencia);
        }









        //fin insumos */










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

        $montos_metodo[$ft['id_metodo']]+=$ft['monto_pagado'];

        $cta_expedidas++;

        if($ft['mesa']!='BARRA'){
            $mesas_ct++;
            $mesas_monto+=$ft['monto_pagado'];
        }else{
            $barra_ct++;
            $barra_monto+=$ft['monto_pagado'];
        }

        $total_totales+=$ft['monto_pagado'];

        if($ft['facturado']){
            $pre_fact_ct++;
            $pre_fact_monto+=$ft['monto_facturado'];
        }else{
            $no_fact_ct++;
            $no_fact_monto+=$ft['monto_pagado'];
        }

        if($ft['reabierta']){
            $cancelaciones++;
        }

    }

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


    esc_pos_line($printer, "################# VENTA ##################");
    esc_pos_align($printer, "left");
    esc_pos_line($printer, "PRODUCTO              CANT   UNIT     SUBT");';

    $sql ="SELECT SUM(DescEfec_txt) AS total FROM ventas WHERE id_corte = $id_corte GROUP BY id_corte";
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
                case 1:  $space0 = "   "; break;
                case 2:  $space0 = "    "; break;
                case 3:  $space0 = "     "; break;
                case 4:  $space0 = "      "; break;
                case 5:  $space0 = "       "; break;
                case 6:  $space0 = "        "; break;
                case 7:  $space0 = "         "; break;
                case 8:  $space0 = "          "; break;
                case 9:  $space0 = "           "; break;
                case 10: $space0 = "            "; break;
                case 11: $space0 = "             "; break;
                case 12: $space0 = "              "; break;
                case 13: $space0 = "               "; break;
                case 14: $space0 = "                "; break;
                case 15: $space0 = "                 "; break;
                case 16: $space0 = "                  "; break;
                case 17: $space0 = "                   "; break;
                case 18: $space0 = "                    "; break;
                case 19: $space0 = "                     "; break;
                case 20: $space0 = "                      "; break;


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
            case 1: $space1 = "      "; break;
            case 2: $space1 = "     "; break;
            case 3: $space1 = "    "; break;
            case 4: $space1 = "   "; break;
        }
        switch($prec){
            case 4: $space2 = "    "; break;
            case 5: $space2 = "   "; break;
            case 6: $space2 = "  "; break;
            case 7: $space2 = " "; break;
            case 8: $space2 = ""; break;
        }
        switch($tot){
            case 4: $space3 = "    "; break;
            case 5: $space3 = "   "; break;
            case 6: $space3 = "  "; break;
            case 7: $space3 = " "; break;
            case 8: $space3 = ""; break;
        }

        $var.= 'esc_pos_line($printer, "'.$producto.$space0.$cantidad.$space1.$precio.$space3.$total.'");'.$salto;
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
    $transferencia=0;
    $descuentoTotal = $g_total-$totalDescuento;
    foreach($montos_metodo as $id_m => $monto){
        if ($id_m == '1') {
            $efectivo = floatval($monto);
        }elseif ($id_m == '4' || $id_m == '28') {
            $tarjetas = floatval($tarjetas)+floatval($monto);
        }
        if($id_m =='3'){
            $transferencia= floatval($transferencia)+floatval($monto);
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

    $var.= 'esc_pos_line($printer, "------------------------------------------");
    esc_pos_align($printer, "right");
    esc_pos_line($printer, "VENTA SUBTOTAL:'.$spacet.$g_total.'");
    esc_pos_line($printer, "DESCUENTOS: '.$spacet.$totalDescuento.'");
    esc_pos_line($printer, "VENTA TOTAL: '.$spacet.$descuentoTotal.'");
    esc_pos_font($printer, "A");
    esc_pos_align($printer, "left");
    esc_pos_line($printer, "DESGLOSE:");
    esc_pos_line($printer, "EFECTIVO: '.$efectivo.'");
    esc_pos_line($printer, "TARJETAS: '.$tarjetas2.'");
    esc_pos_line($printer, "TRANSFERENCIAS: '.$transferencia.'");
    esc_pos_line($printer, "");
    esc_pos_line($printer, "CUENTAS EXPEDIDAS: '.$cta_expedidas.'");
    esc_pos_line($printer, "PROMEDIO POR CUENTA: '.$promedio.'");
    esc_pos_line($printer, "CANCELACIONES: '.$cancelaciones.'");
    esc_pos_line($printer, " ");
    esc_pos_line($printer, "################# GASTOS #################");
    esc_pos_line($printer, "DESCRIPCION                          MONTO");
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

                case 1: $space0 = "   "; break;
                case 2: $space0 = "    "; break;
                case 3: $space0 = "     "; break;
                case 4: $space0 = "      "; break;
                case 5: $space0 = "       "; break;
                case 6: $space0 = "        "; break;
                case 7: $space0 = "         "; break;
                case 8: $space0 = "          "; break;
                case 9: $space0 = "           "; break;
                case 10: $space0 = "            "; break;
                case 11: $space0 = "             "; break;
                case 12: $space0 = "              "; break;
                case 13: $space0 = "               "; break;
                case 14: $space0 = "                "; break;
                case 15: $space0 ="                 "; break;
                case 16: $space0 = "                  "; break;
                case 17: $space0 = "                   "; break;
                case 18: $space0 = "                    "; break;
                case 19: $space0 = "                     "; break;
                case 20: $space0 = "                      "; break;
                case 21: $space0 = "                       "; break;
                case 22: $space0 = "                        "; break;
                case 23: $space0 = "                         "; break;
                case 24: $space0 = "                          "; break;
                case 25: $space0 ="                            "; break;
                case 26: $space0 = "                            "; break;
                case 27: $space0 = "                             "; break;
                case 28: $space0 = "                              "; break;
                case 29: $space0 = "                               "; break;
                case 30: $space0 = "                                "; break;

            }
        }

        $g_total_g+=$monto;

        $monto= number_format($monto,2, '.', '');
        $mont = strlen($monto);
        switch($mont){
            case 4: $spacex = "        "; break;
            case 5: $spacex = "       "; break;
            case 6: $spacex = "      "; break;
            case 7: $spacex = "     "; break;
            case 8: $spacex = "    "; break;
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

    $var.= 'esc_pos_line($printer, "------------------------------------------");
    esc_pos_align($printer, "right");
    esc_pos_line($printer, "TOTAL DE GASTOS:'.$spaceg.$g_total_gastos.'");';

    $subtotalVenta = 0;
    $subtotalVenta = floatval($fondo_caja)+floatval($descuentoTotal);
    //$subtotalVenta = floatval($subtotalVenta)+floatval($tarjetas);

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
    esc_pos_line($printer, "############### CORTE CAJA ###############");
    esc_pos_align($printer, "left");
    esc_pos_line($printer, "FONDO DE CAJA: '.$fondo_caja2.'");
    esc_pos_line($printer, "EFECTIVO: '.$efectivo.'");
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
        esc_pos_line($printer, "############## CORTE CAPTURA #############");
        esc_pos_align($printer, "left");

        esc_pos_line($printer, "EFECTIVO TOTAL: '.$efectivoCa.'");
        esc_pos_line($printer, "TARJETAS: '.$tpvEfec.'");

        esc_pos_line($printer, "TOTAL: '.$ENCAJA2.'");
        esc_pos_line($printer, " ");
        esc_pos_line($printer, " ");
        esc_pos_line($printer, "------------------------------------------");
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
        esc_pos_line($printer, "############## DESCUENTOS ##############");
        ';}

        $sql45 ="SELECT * FROM ventas
        JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
        JOIN cupones ON cupones.id_cupon = ventas.descuento_txt
        WHERE id_corte = $id_corte AND descuento_txt !=0";

        $qx45 = mysql_query($sql45);

        while($fx45 = mysql_fetch_assoc($qx45)){

            $fecha =$fx45['fechahora_pagada'];
            $folio = $fx45['id_venta'];
            $aux = $fx45['nombre'];

            $consumo = $fx45['pagarOriginal'];
            $consumo= number_format($consumo,2, '.', '');
            $descuento = $fx45['DescEfec_txt']." (".$fx45['cupon'].")";

            $total_cobrado= $fx45['monto_pagado'];
            if($total_cobrado==$fx45['DescEfec_txt']){

                $total_cobrado=0;
            }
            // $total_cobrado-=$fx45['DescEfec_txt'];
            $descuento= number_format($descuento,2, '.', '');
            $total_cobrado= number_format($total_cobrado,2, '.', '');



            $sql90 = "SELECT venta_detalle.id_producto,venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta,productos.extra,productos.sinn,productos.paquete FROM venta_detalle
            JOIN productos ON productos.id_producto = venta_detalle.id_producto

            WHERE venta_detalle.id_venta =".$fx45['id_venta'] ." AND venta_detalle.precio_venta != 0  ";
            ;
            $q90 = mysql_query($sql90);

            $var.='

            esc_pos_align($printer, "left");

            esc_pos_line($printer, "FECHA: '.$fecha.'");
            esc_pos_line($printer, "FOLIO: '.$folio.'");
            esc_pos_line($printer, "AUX: '.$aux.'");

            esc_pos_line($printer, " ");
            esc_pos_line($printer, "PRODUCTOS:");
            esc_pos_line($printer, "------------------------------------------");
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
            esc_pos_line($printer, "------------------------------------------");
            ';

            $var.='

            esc_pos_align($printer, "left");

            esc_pos_line($printer, "CONSUMO: '.$consumo.'");
            esc_pos_line($printer, "DESCUENTO: '.$descuento.'");
            esc_pos_line($printer, "TOTAL COBRADO: '.$total_cobrado.'");

            esc_pos_line($printer, " ");
            esc_pos_line($printer, "########################################");
            ';



        }

        $cancelaciones_sql="";

        if($insumo==1){
            $var.='esc_pos_line($printer, "############### INSUMOS ################");';
            
            $var.='esc_pos_line($printer, "PRODUCTO      DOTA    MERM   CONSU  EXIS");';

            foreach($tags as $producto ){
                $nombre=trim($producto['producto']);

                $dota=$producto['dotado_dia'];

                $exis=$producto['cantidad'];

                $cant=$producto['consumo_dia'];

                $merm=$producto['mermado_dia'];
                $prev = $producto['previa'];
                $pro= $nombre;


                $pro = substr($pro,0,19);
                $c_p = strlen($pro);
                if($c_p<=19){
                    $to_p = 19-$c_p;
                    switch($to_p){
                        case 0: $space0 = "  "; break;
                        case 1: $space0 = "   "; break;
                        case 2: $space0 = "    "; break;
                        case 3: $space0 = "     "; break;
                        case 4: $space0 = "      "; break;
                        case 5: $space0 = "       "; break;
                        case 6: $space0 = "        "; break;
                        case 7: $space0 = "         "; break;
                        case 8: $space0 = "          "; break;
                        case 9: $space0 = "           "; break;
                        case 10: $space0 = "            "; break;
                        case 11: $space0 = "             "; break;
                        case 12: $space0 = "              "; break;
                        case 13: $space0 = "               "; break;
                        case 14: $space0 = "                "; break;
                        case 15: $space0 ="                 "; break;
                        case 16: $space0 = "                  "; break;
                        case 17: $space0 = "                   "; break;
                        case 18: $space0 = "                    "; break;
                        case 19: $space0 = "                     "; break;





                    }
                }


                $precio = $dota;

                $prec = strlen($precio);
                switch($prec){

                    case 1: $space1 = "     "; break;
                    case 2: $space1 = "    "; break;
                    case 3: $space1 = "   "; break;
                    case 4: $space1 = "  "; break;
                    case 5: $space1 = " "; break;
                    case 6: $space1 = ""; break;
                }
                $total=$cant;
                //$g_total+=$total;



                $cant = strlen($cantidad);
                $tot = strlen($total);
                $mer= strlen($merm);

                if($exis<0){

                    $tot+=1;
                }
                switch($cant){
                    case 1: $space2 = "      "; break;
                    case 2: $space2 = "     "; break;
                    case 3: $space2 = "    "; break;
                    case 4: $space2 = "   "; break;
                    case 5: $space2 = "  "; break;
                    case 6: $space2 = " "; break;
                }

                switch($tot){

                    case 1: $space3 = "      "; break;
                    case 2: $space3 = "     "; break;
                    case 3: $space3 = "    "; break;
                    case 4: $space3 = "   "; break;
                    case 5: $space3 = "  "; break;
                    case 6: $space3 = " "; break;
                }

                switch($merm){
                    case 1: $space4 = "      "; break;
                    case 2: $space4 = "     "; break;
                    case 3: $space4 = "    "; break;
                    case 4: $space4 = "   "; break;
                    case 5: $space4 = "  "; break;
                    case 6: $space4 = " "; break; 
                }

                /* unset($dota);
                unset($merm);
                unset($exis);
                $dota="  ";
                $exis="  ";
                $merm="  ";*/
                $var.= 'esc_pos_line($printer, "'.$pro.$space0.$dota.$space1.$merm.$space2.$total.$space3.$exis.'");'.$salto;
                //exit($var2);
                // $var.= 'esc_pos_line($printer, "'.$pro.$space0.$dota.$space1.$merm.$space2.$exis.$space3.$total.'");';
                unset($space0);



            }

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


        if($autoprint){

            eval($var);
        }

        return $var;

        if($re){

            foreach($tags as $update){

                $can = $update['cantidad'];
                $i = $update['id'];

                $UP="UPDATE existecias SET cantidad ='$can' WHERE id_base = $i ";
                $qu=mysql_query($UP);
            }
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


    $impresorapapa;
    function imprimir_comandas($tipo,$id){

        /*
        imprimir_comandas('venta',183270);
        imprimir_comandas('domicilio',11);
        */
        global $s_nombre;
        global $conexion;
        global $impresorapapa;
        global $comandain;
        global $impresora_cuentas;
        global $salto;

        if($tipo=='venta'){

            $sql = "SELECT productos.paquete, productos.sinn,productos.extra, venta_detalle.cantidad, productos.nombre, venta_detalle.precio_venta, venta_detalle.id_producto, venta_detalle.comentarios, productos.id_categoria, categorias.impresora, productos.imprimir_solo, ventas.mesa, ventas.hora, ventas.fecha
            FROM venta_detalle
            LEFT JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
            LEFT JOIN productos ON productos.id_producto = venta_detalle.id_producto
            LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
            WHERE venta_detalle.id_venta =$id
            AND venta_detalle.id_producto !=0 AND impreso=0";

            //file_put_contents('x.txt',$sql);

        }elseif('domicilio'){

            $sql = "SELECT venta_domicilio_detalle.cantidad,productos.nombre,venta_domicilio_detalle.precio_venta,venta_domicilio_detalle.id_producto,venta_domicilio_detalle.comentarios,productos.id_categoria,categorias.impresora,productos.imprimir_solo,ventas_domicilio.fechahora_alta
            FROM venta_domicilio_detalle
            LEFT JOIN ventas_domicilio ON ventas_domicilio.id_venta_domicilio = venta_domicilio_detalle.id_venta_domicilio
            LEFT JOIN productos ON productos.id_producto = venta_domicilio_detalle.id_producto
            LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
            WHERE venta_domicilio_detalle.id_venta_domicilio = $id";

        }else{

            return false;

        }

        $q = mysql_query($sql);

        $c  = 10;
        $c2 = 10;

        while($ft=mysql_fetch_assoc($q)){



            if($ft['extra']==0 && $ft['sinn']==0 && $ft['precio_venta'] !=0){
                $id_producto = $ft['id_producto'];
                $id_categoria = $ft['id_categoria'];
                $nombre = eliminar_tildes($ft['nombre']);
                $cantidad = $ft['cantidad'];
                $precio = $ft['precio_venta'];





                $comentarios = $ft['comentarios'];

                $mesa = $ft['mesa'];
                $impresora = $ft['impresora'];

                $impresorapapa = $impresora;
                $imprimir_solo = $ft['imprimir_solo'];

                if($tipo=='venta'){
                    $fechahoy = $ft['fecha'].' '.$ft['hora'];

                }elseif('domicilio'){
                    $fechahoy = $ft['fechahora_alta'];
                }

                if($id_producto==0){
                    $c++;
                }else{
                    if($imprimir_solo==1){
                        $c++;
                    }

                    if($impresora!=$impresora_nueva){
                        $c++;
                    }

                    /*

                    $impresion["$impresora"][$c][$c2]['id_producto'] = $id_producto;
                    $impresion["$impresora"][$c][$c2]['nombre'] = $nombre;
                    $impresion["$impresora"][$c][$c2]['cantidad'] = $cantidad;
                    $impresion["$impresora"][$c][$c2]['precio'] = $precio;
                    $impresion["$impresora"][$c][$c2]['comentarios'] = $comentarios;
                    */

                    $impresion[$c]["$impresora"][$c2]['id_producto'] = $id_producto;
                    $impresion[$c]["$impresora"][$c2]['nombre'] = $nombre;
                    $impresion[$c]["$impresora"][$c2]['cantidad'] = $cantidad;
                    $impresion[$c]["$impresora"][$c2]['precio'] = $precio;
                    $impresion[$c]["$impresora"][$c2]['comentarios'] = $comentarios;




                    $impresora_nueva = $impresora;



                    $c2++;

                    if($imprimir_solo==1){
                        $c++;
                    }


                }

            }//aqui va aterminar el if1



            if($ft['extra']==1 ){
                $id_producto = $ft['id_producto'];
                $id_categoria = $ft['id_categoria'];
                $nombre = eliminar_tildes($ft['nombre']);
                $cantidad = "  *";
                $precio = $ft['precio_venta'];



                $comentarios = 1;

                $mesa = $ft['mesa'];
                $impresora = $impresorapapa;
                $imprimir_solo = $ft['imprimir_solo'];

                if($tipo=='venta'){
                    $fechahoy = $ft['fecha'].' '.$ft['hora'];

                }elseif('domicilio'){
                    $fechahoy = $ft['fechahora_alta'];
                }

                if($id_producto==0){
                    $c++;
                }else{
                    if($imprimir_solo==1){
                        $c++;
                    }

                    if($impresora!=$impresora_nueva){
                        $c++;
                    }

                    /*

                    $impresion["$impresora"][$c][$c2]['id_producto'] = $id_producto;
                    $impresion["$impresora"][$c][$c2]['nombre'] = $nombre;
                    $impresion["$impresora"][$c][$c2]['cantidad'] = $cantidad;
                    $impresion["$impresora"][$c][$c2]['precio'] = $precio;
                    $impresion["$impresora"][$c][$c2]['comentarios'] = $comentarios;
                    */

                    $impresion[$c]["$impresora"][$c2]['id_producto'] = $id_producto;
                    $impresion[$c]["$impresora"][$c2]['nombre'] = $nombre;
                    $impresion[$c]["$impresora"][$c2]['cantidad'] = $cantidad;
                    $impresion[$c]["$impresora"][$c2]['precio'] = $precio;
                    $impresion[$c]["$impresora"][$c2]['comentarios'] = $comentarios;



                    $impresora_nueva = $impresora;



                    $c2++;

                    if($imprimir_solo==1){
                        $c++;
                    }


                }

            }//aqui va aterminar el if2


            if($ft['sinn']==1 ){
                $id_producto = $ft['id_producto'];
                $id_categoria = $ft['id_categoria'];
                $nombre = eliminar_tildes($ft['nombre']);
                $cantidad = "  *";
                $precio = $ft['precio_venta'];



                $comentarios = 1;

                $mesa = $ft['mesa'];
                $impresora = $impresorapapa;
                $imprimir_solo = $ft['imprimir_solo'];

                if($tipo=='venta'){
                    $fechahoy = $ft['fecha'].' '.$ft['hora'];

                }elseif('domicilio'){
                    $fechahoy = $ft['fechahora_alta'];
                }

                if($id_producto==0){
                    $c++;
                }else{
                    if($imprimir_solo==1){
                        $c++;
                    }

                    if($impresora!=$impresora_nueva){
                        $c++;
                    }

                    /*

                    $impresion["$impresora"][$c][$c2]['id_producto'] = $id_producto;
                    $impresion["$impresora"][$c][$c2]['nombre'] = $nombre;
                    $impresion["$impresora"][$c][$c2]['cantidad'] = $cantidad;
                    $impresion["$impresora"][$c][$c2]['precio'] = $precio;
                    $impresion["$impresora"][$c][$c2]['comentarios'] = $comentarios;
                    */

                    $impresion[$c]["$impresora"][$c2]['id_producto'] = $id_producto;
                    $impresion[$c]["$impresora"][$c2]['nombre'] = $nombre;
                    $impresion[$c]["$impresora"][$c2]['cantidad'] = $cantidad;
                    $impresion[$c]["$impresora"][$c2]['precio'] = $precio;
                    $impresion[$c]["$impresora"][$c2]['comentarios'] = $comentarios;




                    $impresora_nueva = $impresora;



                    $c2++;

                    if($imprimir_solo==1){
                        $c++;
                    }


                }

            }//aqui va aterminar el if3

            //aqui termina inicia de paquetes
            if($ft['precio_venta']==0 && $ft['sinn'] !=1  ){
                $id_producto = $ft['id_producto'];
                $id_categoria = $ft['id_categoria'];
                $nombre = eliminar_tildes($ft['nombre']);
                $cantidad = "  *";
                $precio = $ft['precio_venta'];



                $comentarios = 1;

                $mesa = $ft['mesa'];
                $impresora = $impresorapapa;
                $imprimir_solo = $ft['imprimir_solo'];

                if($tipo=='venta'){
                    $fechahoy = $ft['fecha'].' '.$ft['hora'];

                }elseif('domicilio'){
                    $fechahoy = $ft['fechahora_alta'];
                }

                if($id_producto==0){
                    $c++;
                }else{
                    if($imprimir_solo==1){
                        $c++;
                    }

                    if($impresora!=$impresora_nueva){
                        $c++;
                    }

                    /*

                    $impresion["$impresora"][$c][$c2]['id_producto'] = $id_producto;
                    $impresion["$impresora"][$c][$c2]['nombre'] = $nombre;
                    $impresion["$impresora"][$c][$c2]['cantidad'] = $cantidad;
                    $impresion["$impresora"][$c][$c2]['precio'] = $precio;
                    $impresion["$impresora"][$c][$c2]['comentarios'] = $comentarios;
                    */

                    $impresion[$c]["$impresora"][$c2]['id_producto'] = $id_producto;
                    $impresion[$c]["$impresora"][$c2]['nombre'] = $nombre;
                    $impresion[$c]["$impresora"][$c2]['cantidad'] = $cantidad;
                    $impresion[$c]["$impresora"][$c2]['precio'] = $precio;
                    $impresion[$c]["$impresora"][$c2]['comentarios'] = $comentarios;




                    $impresora_nueva = $impresora;



                    $c2++;

                    if($imprimir_solo==1){
                        $c++;
                    }


                }

            }
            //aqui termina if de paquetes


        }//termina el whle

        foreach($impresion as $printer => $v){

            if($tipo=='venta'){
                $tipo_comanda = "MESA: $mesa";
            }elseif('domicilio'){
                $tipo_comanda = "*** PARA LLEVAR ***";
            }





            foreach($v as $print => $data){
                $print_original = $print;
                if($comandain=="1"){
                    $print= $impresora_cuentas;

                }
                //DE LA OTRA FORMA SE BAJAN LOS DATOS ACA.
                $fecha_hoy = fechaHoraVista($fechahoy);
                $escpos = new KEscPos('TM-U220B AFU',$print, true, true);

                $escpos -> Font("A");
                $escpos -> Align("center");
                $escpos -> Double(true);
                $escpos -> Width("double");
                $escpos -> Line("");
                $escpos -> Line();
                $escpos -> Line( $print_original);
                $escpos -> Line("COMANDA #".$id);
                $escpos -> Line($tipo_comanda);
                $escpos -> Double(false);
                $escpos -> Width(false);
                $escpos -> Line($fecha_hoy);
                $escpos -> Line("AUX: $s_nombre");
                $escpos -> Line("__________________________________________");
                $escpos -> Align("left");

                foreach($data as $val){
                    $comentarios = $val['comentarios'];


                    if($comentarios !=1){

                        $nombre = eliminar_tildes($val['nombre']);
                        $cantidad = $val['cantidad'];
                        $comentarios = $val['comentarios'];
                        $comentarios = explode("\n",$comentarios);
                        $escpos -> Line("");

                        $escpos -> Width("double");
                        $escpos -> Line("- $cantidad $nombre");
                        if(count($comentarios)>0){
                            foreach($comentarios as $com){
                                $com = trim($com);
                                if($com){
                                    $escpos -> Line("  * $com");
                                }
                            }
                        }
                    }else{
                        $nombre = trim($val['nombre']);

                        $escpos -> Width("double");
                        $escpos -> Line("  * $nombre");

                    }


                } //foreach $data
                $escpos -> Width(false);

                $escpos -> Line("__________________________________________");

            } // foreach $v

            $escpos -> Line("");
            $escpos -> Line("");
            $escpos -> Line("");
            $escpos -> Cut();
            $escpos -> Close();


        } // foreach $impresion


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
    function return_linea($linea){
        if(strlen($linea)>0):
            return 'esc_pos_line($printer, "'.$linea.'");';
        endif;
    }


    //aqui inicia



    function imprimir_gasto($id){

        global $establecimiento_config;
        global $autoprint;
        global $abrir_caja;
        global $impresora_cobros;
        global $impresora_cuentas;

        //require_once('php_image_magician.php');


        $img2 = "../includes/logo.jpg";
        if(file_exists($img2)){
            $escpos = new KEscPos('TM-T88IV AFU',$impresora_cobros, true, true);
            $escpos -> Align("center");
            $escpos -> Image(false, $img2);

            $escpos -> Close();
        }








        $fecha_hora_ticket = date('d-m-Y h:i a');



        //cuenta
        $var_print = '$printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true);';


        $var = $var_print;
        #      esc_pos_line($printer, "'.$establecimiento_config.'");

        $headers = "SELECT * FROM configuracion";
        $qh = mysql_query($headers);

        //$header_1 = "";
        //$header_2 = return_linea("hola prueba prp");


        while($ft=mysql_fetch_assoc($qh)){

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

        esc_pos_align($printer, "center");
        esc_pos_font($printer, "A");
        esc_pos_char_width($printer, "");

        ';

        $var.= ' esc_pos_line($printer,"" );';

        $var.='
        esc_pos_line($printer, "'.$fecha_hora_ticket.'");

        esc_pos_line($printer, "AUX: '.strtoupper($_SESSION['s_nombre']).'");

        esc_pos_line($printer, "-----------------GASTO #'.$id.' -----------------------");

        esc_pos_align($printer, "left");
        esc_pos_line($printer, "CONCEPTO                         MONTO");';

        $sql = "SELECT * FROM gastos
        JOIN usuarios ON usuarios.id_usuario=gastos.id_usuario
        WHERE  id_gasto=$id ORDER BY id_gasto DESC";
        $q = mysql_query($sql);
        while($ft=mysql_fetch_assoc($q)){

            $producto = $ft['descripcion'];
            $producto = substr($producto,0,20);

            $c_p = strlen($producto);
            if($c_p<20){
                $to_p = 20-$c_p;
                switch($to_p){
                    case 1: $space0 = "  "; break;
                    case 2: $space0 = "   "; break;
                    case 3: $space0 = "                              "; break;
                    case 4: $space0 = "     "; break;
                    case 5: $space0 = "      "; break;
                    case 6: $space0 = "       "; break;
                    case 7: $space0 = "        "; break;
                    case 8: $space0 = "         "; break;
                    case 9: $space0 = "          "; break;
                    case 10: $space0 = "            "; break;
                    case 11: $space0 = "             "; break;
                    case 12: $space0 = "              "; break;
                    case 13: $space0 = "               "; break;
                    case 14: $space0 = "                 "; break;
                    case 15: $space0 = "                   "; break;
                    case 16: $space0 = "                     "; break;
                    case 17: $space0 = "                       "; break;
                    case 18: $space0 = "                          "; break;
                    case 19: $space0 = "                             "; break;
                    case 20: $space0 = "                               "; break;

                }
            }


            $precio = $ft['monto'];



            $prec = strlen($precio);




            switch($prec){
                case 4: $space2 = "    "; break;
                case 5: $space2 = "   "; break;
                case 6: $space2 = "  "; break;
                case 7: $space2 = " "; break;
                case 8: $space2 = ""; break;
            }

            $var.= 'esc_pos_line($printer, "'.$producto.$space0."          ".$precio.'");';
            unset($space0);
        }






        for($i=1;$i<=$cuantos_metodo;$i++){
            $espacios.=" ";
        }
        $metodo = $espacios.$metodo;
        $detalle .= 'esc_pos_line($printer, "'.$metodo.'");';



        /*$detalle .='
        esc_pos_line($printer, "SU PAGO:'.$spacee.$efectivo.'");
        esc_pos_line($printer, "TOTAL: '.$spacec.$totalFin.'");
        esc_pos_line($printer, "CAMBIO:'.$spacec.$cambio.'");
        esc_pos_line($printer, "METODO:'.$spacem.$metodo.'");
        ';*/

        /*if($factura==1){

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

}*/



$var.='
esc_pos_line($printer, "------------------------------------------------");
esc_pos_align($printer, "right");
esc_pos_emphasize($printer,true);
'.$detalle.'
esc_pos_emphasize($printer,false);


esc_pos_font($printer, "A");
esc_pos_align($printer, "center");
'.$fact.'
esc_pos_align($printer, "center");
esc_pos_line($printer, "");';




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







//aqui termina






function imprimir_mesa($id_venta,$tipo,$desc=false){
    include('db.php');

    global $establecimiento_config;
    global $autoprint;
    global $abrir_caja;
    global $impresora_cobros;
    global $impresora_cobros;
    global $salto;

    $sql="SELECT SUM(cantidad*precio_venta) AS total FROM venta_detalle WHERE id_venta=$id_venta";
    $q=mysql_query($sql,$conexion);
    $ft=mysql_fetch_assoc($q);
    $consumo=$ft['total'];

    $sql_venta="SELECT * FROM ventas WHERE id_venta = $id_venta";
    $q_venta=mysql_query($sql_venta,$conexion);
    $ve =mysql_fetch_assoc($q_venta);
    $op = $ve['mesa'];
    $metodo = $ve['metodo_txt'];
    $pagarOriginal = $ve['pagarOriginal'];
    $DescEfec_txt = $ve['DescEfec_txt'];
    $efectivo = $ve['recibe_txt'];
    $cambio = $ve['cambio_txt'];
    $codigo = $ve['codigo_activacion'];
    $id_cliente=$ve['id_cliente'];
    $id_descuento=$ve['descuento_txt'];
    $iva = $ve['facturado'];
    $iva_efectivo = $ve['monto_facturado'];
    //require_once('php_image_magician.php');
    $domicilio = $ve['domicilio'];

    $img2 = "../includes/logo.jpg";
    if(file_exists($img2)){
        $escpos = new KEscPos('TM-T88IV AFU',$impresora_cobros, true, true);
        $escpos -> Align("center");
        $escpos -> Image(false, $img2);
        $escpos -> Close();
    }

    if($tipo=='cerrar'){
        $fecha_hora_ticket = $ve['fecha']." ".$ve['hora'];
    }else{
        $fecha_hora_ticket = $ve['fechahora_pagada'];
    }


    if($op=='BARRA'){
        $add = ' - BARRA';
    }else{
        $add =" - MESA: ".$op;
    }

    if($tipo=="cerrar"){
        //cuenta
        $var_print = '$printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true);';
    }else{
        //cobro

        if($abrir_caja){
            $abrir_caja_cmd = 'esc_pos_drawer($printer);';
        }


        $var_print = '
        $printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true); '.$abrir_caja_cmd;
    }

    $var = $var_print;


    $headers = "SELECT * FROM configuracion";
    $qh = mysql_query($headers);




    while($ft=mysql_fetch_assoc($qh)){

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

    esc_pos_align($printer, "center");
    esc_pos_font($printer, "A");
    esc_pos_char_width($printer, "");

    ';

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
    if($domicilio==1){
        $domi = '
        esc_pos_line($printer, "-----------servicio a domicilio-----------");';
    }
    $var.='
    esc_pos_line($printer, "'.$fecha_hora_ticket.'");
    esc_pos_line($printer, "FOLIO: #'.$id_venta.$add.'");
    esc_pos_line($printer, "AUX: '.strtoupper($_SESSION['s_nombre']).'");'.$domi.'
    esc_pos_line($printer, "------------------------------------------");
    esc_pos_align($printer, "left");
    esc_pos_line($printer, "PRODUCTO               CANT   UNIT    SUBT");';

    $sql = "SELECT venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta  FROM venta_detalle
    JOIN productos ON productos.id_producto = venta_detalle.id_producto
    WHERE id_venta = '$id_venta'";
    $q = mysql_query($sql);
    while($ft=mysql_fetch_assoc($q)){

        $producto = eliminar_tildes($ft['nombre']);
        $producto = substr($producto,0,20);

        $c_p = strlen($producto);
        if($c_p<20){
            $to_p = 20-$c_p;
            switch($to_p){
                case 1: $space0 = "     "; break;
                case 2: $space0 = "      "; break;
                case 3: $space0 = "       "; break;
                case 4: $space0 = "        "; break;
                case 5: $space0 = "         "; break;
                case 6: $space0 = "          "; break;
                case 7: $space0 = "           "; break;
                case 8: $space0 = "            "; break;
                case 9: $space0 = "             "; break;
                case 10: $space0 = "              "; break;
                case 11: $space0 = "               "; break;
                case 12: $space0 = "                "; break;
                case 13: $space0 = "                 "; break;
                case 14: $space0 = "                  "; break;
                case 15: $space0 ="                   "; break;
                case 16: $space0 = "                    "; break;
                case 17: $space0 = "                     "; break;
                case 18: $space0 = "                      "; break;
                case 19: $space0 = "                       "; break;
                case 20: $space0 = "                        "; break;


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
        $var.= 'esc_pos_line($printer, "'.$producto.$space0.$cantidad.$space2.$precio.$space3.$total.'");';
        unset($space0);
    }
    //exit($var2);
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

        $fact = 'esc_pos_line($printer, "");';
        $fact .= 'esc_pos_line($printer, "SI REQUIERE FACTURA SOLICITELA AL MOMENTO.");';
        $status = 'esc_pos_font($printer, "B");';
        $status.= 'esc_pos_line($printer, "PENDIENTE DE PAGO");';

        if($id_descuento==0){
            $descuento = $consumo-$desc;
            if($desc==0){
                $descuento=0;
                $desc=$consumo;
            }
        }else{
            $descuento = $DescEfec_txt;
            $desc = $consumo-$DescEfec_txt;
        }
        $sub_num = number_format($consumo,2);
        $descuento = number_format($descuento,2);
        $desc = number_format($desc,2);
        $total_subtotal = "CONSUMO: ".$sub_num;
        if($iva>0){
            //iva desglose
            $iva_efectivo = number_format($iva_efectivo,2);
            $descuent0 = $iva_efectivo;
            $iva_efectivo = "iva al $iva%: ".$iva_efectivo;
            $cuantos_descuento = 40-strlen($iva_efectivo);
            $espacios = "";
            for($i=1;$i<=$cuantos_descuento;$i++){
                $espacios.=" ";
            }
            $iva_efectivo = $espacios.$iva_efectivo;
            $detalle .= 'esc_pos_line($printer, "'.$iva_efectivo.'");';
        }
        $cuantos_subtotal = 40-strlen($total_subtotal);
        $espacios = "";
        for($i=1;$i<=$cuantos_subtotal;$i++){
            $espacios.=" ";
        }
        $total_subtotal = $espacios.$total_subtotal;
        $detalle = 'esc_pos_line($printer, "'.$total_subtotal.'");';

        $total_subtotal2 = "DESCUENTO: ".$descuento;

        $detalle .= 'esc_pos_line($printer, "'.$total_subtotal2.'");';
                /* aqui va el iva tambien
                $iva = $ve['facturado'];
    $iva_efectivo = $ve['monto_facturado'];
        */
        
        $total_subtotal3 = "TOTAL: ".$desc;

        $detalle .= 'esc_pos_line($printer, "'.$total_subtotal3.'");';




    }else{




        if ($DescEfec_txt>0) {
            $sub_num = number_format($consumo,2);
            $total_subtotal = "CONSUMO: ".$sub_num;
            $cuantos_subtotal = 40-strlen($total_subtotal);
            $espacios = "";
            for($i=1;$i<=$cuantos_subtotal;$i++){
                $espacios.=" ";
            }
            $total_subtotal = $espacios.$total_subtotal;
            $detalle = 'esc_pos_line($printer, "'.$total_subtotal.'");';
            $DescEfec_txt = number_format($DescEfec_txt,2);
            $descuent0 = $DescEfec_txt;
            $DescEfec_txt = "DESCUENTO: ".$DescEfec_txt;
            $cuantos_descuento = 40-strlen($DescEfec_txt);
            $espacios = "";
            for($i=1;$i<=$cuantos_descuento;$i++){
                $espacios.=" ";
            }
            $DescEfec_txt = $espacios.$DescEfec_txt;
            $detalle .= 'esc_pos_line($printer, "'.$DescEfec_txt.'");';
        }
        /* aqui va el iva
                $iva = $ve['facturado'];
                $iva_efectivo = $ve['monto_facturado'];
        */
        if($iva>0){
            $sub_num = number_format($consumo,2);
            $total_subtotal = "CONSUMO: ".$sub_num;
            $cuantos_subtotal = 40-strlen($total_subtotal);
            $espacios = "";
            for($i=1;$i<=$cuantos_subtotal;$i++){
                $espacios.=" ";
            }
            $total_subtotal = $espacios.$total_subtotal;
            $detalle = 'esc_pos_line($printer, "'.$total_subtotal.'");';
            //iva desglose
            $iva_efectivo = number_format($iva_efectivo,2);
            $descuent0 = $iva_efectivo;
            $iva_efectivo = "iva al $iva%: ".$iva_efectivo;
            $cuantos_descuento = 40-strlen($iva_efectivo);
            $espacios = "";
            for($i=1;$i<=$cuantos_descuento;$i++){
                $espacios.=" ";
            }
            $iva_efectivo = $espacios.$iva_efectivo;
            $detalle .= 'esc_pos_line($printer, "'.$iva_efectivo.'");';
        }
        $totalFin = number_format(($pagarOriginal-$descuent0),2);
        $totalFin = "TOTAL: ".$totalFin;
        $cuantos_totalFin = 40-strlen($totalFin);
        $espacios = "";
        for($i=1;$i<=$cuantos_totalFin;$i++){
            $espacios.=" ";
        }
        $totalFin = $espacios.$totalFin;
        $detalle .= 'esc_pos_line($printer, "'.$totalFin.'");';

        $efectivo = number_format($efectivo,2);
        $efectivo = "SU PAGO: ".$efectivo;
        $cuantos_efectivo = 40-strlen($efectivo);
        $espacios = "";
        for($i=1;$i<=$cuantos_efectivo;$i++){
            $espacios.=" ";
        }
        $efectivo = $espacios.$efectivo;
        $detalle .= 'esc_pos_line($printer, "'.$efectivo.'");';

        $cambio = number_format($cambio,2);
        $cambio = "CAMBIO: ".$cambio;
        $cuantos_cambio = 40-strlen($cambio);
        $espacios = "";
        for($i=1;$i<=$cuantos_cambio;$i++){
            $espacios.=" ";
        }
        $cambio = $espacios.$cambio;
        $detalle .= 'esc_pos_line($printer, "'.$cambio.'");';

        $metodo = "METODO: ".$metodo;
        $cuantos_metodo = 40-strlen($metodo);
        $espacios = "";
        for($i=1;$i<=$cuantos_metodo;$i++){
            $espacios.=" ";
        }
        $metodo = $espacios.$metodo;
        $detalle .= 'esc_pos_line($printer, "'.$metodo.'");';



        /*$detalle .='
        esc_pos_line($printer, "SU PAGO:'.$spacee.$efectivo.'");
        esc_pos_line($printer, "TOTAL: '.$spacec.$totalFin.'");
        esc_pos_line($printer, "CAMBIO:'.$spacec.$cambio.'");
        esc_pos_line($printer, "METODO:'.$spacem.$metodo.'");
        ';*/

        /*if($factura==1){

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

}*/




}


$var.='
esc_pos_line($printer, "------------------------------------------");
esc_pos_align($printer, "right");
esc_pos_emphasize($printer,true);
'.$detalle.'
esc_pos_emphasize($printer,false);


esc_pos_font($printer, "A");
esc_pos_align($printer, "center");
'.$fact.'
esc_pos_align($printer, "center");
esc_pos_line($printer, "");';


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




/* if(!$id_cliente && $tipo!='cerrar'  && is_connected() && $consumo >=100 ){
    $var.='
    esc_pos_line($printer, "------------------------------------------");
    esc_pos_line($printer, "Ahora puedes pertenecer a KGB club");
    esc_pos_line($printer, "Registra este codigo: '.$codigo.' en ");
    esc_pos_line($printer, "www.kgbgrill.com");';

} */
$var.='
esc_pos_line($printer, "");
'.$status.'
esc_pos_cut($printer);
esc_pos_close($printer);

';

if($autoprint){
    eval($var);
}
//echo $var;
/* $A="SELECT email_notificacion FROM configuracion ";
$B = mysql_query($A,$conexion);
$C = mysql_fetch_assoc($B);
$correo=$C['email_notificacion'];


$sql = "SELECT * FROM ventas
LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_usuario
WHERE ventas.id_venta = $id_venta
";
$q=mysql_query($sql,$conexion);

$fx=mysql_fetch_assoc($q);

$orig='
<h4>------------- NOTA DE REMISIÓN -------------</h4>
<p><b>FECHA: </b>'.$fx['fechahora_pagada'].'</p>
<p><b>FOLIO: #</b>'.$fx['id_venta'].'</p>
<p><b>AUXILIAR: </b>'.$fx['nombre'].'</p>
<hr>';



$orig.='
<table>
<thead>
<tr>
<th width="20%">PRODUCTO</th>
<th width="20%">CANT</th>
<th width="20%">UNIT</th>
<th width="20%">SUBT</th>
</tr>
</thead>
<tbody>';


$sql="SELECT venta_detalle. * , productos.nombre
FROM venta_detalle
LEFT JOIN productos ON venta_detalle.id_producto = productos.id_producto
WHERE id_venta =$id_venta AND  venta_detalle.id_producto !=0";

$q=mysql_query($sql,$conexion);

while($ft=mysql_fetch_assoc($q)){
$orig.='

<tr>
<td width="20%" >'.$ft['nombre'].'</td>
<td width="20%" style="text-align: right;" >'.$ft['cantidad'].'</td>
<td width="20%" style="text-align: right;" >'.$ft['precio_venta'].'</td>
<td width="20%"style="text-align: right;" >'.number_format($ft['cantidad']*$ft['precio_venta'],2, '.', '').'</td>
</tr>

';
}






$orig.='
<tr>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%"  style="text-align: left;" >SUBTOTAL : '.$fx['pagarOriginal'].'</td>
</tr>

<tr>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%"  style="text-align: left;" >DESCUENTO : '.$fx['DescEfec_txt'].'</td>
</tr>


<tr>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td  width="20%"  style="text-align: left;" >TOTAL : '.$fx['monto_pagado'].'</td>
</tr>
</tbody>
</table>






';

$html= $orig;
$remite = "VendeFacil <bot@adminus.mx>";
$dato = $html;
$postmark = new Postmark(null,$remite);
$postmark->to($correo);
$postmark->subject('Nota de remisión');
$postmark->html_message($dato);
$postmark->send(); */






}





function ticket_factura($re_pais,$re_calle,$re_colonia,$re_municipio,$re_no_exterior,$re_nointerior,$re_codigopostal,$re_rfc,$re_nombre,$emi_pais,$emi_calle,$emi_estado,$emi_colonia,$emi_municipio,$emi_noexterior,$emi_codigopostal,$emi2_pais,$emi_nombre,$emi_rfc,$exp_pais,$exp_calle,$exp_estado,$exp_colonia,$exp_noexterior,$exp_codigopostal,$emi_localidad,$exp_localidad,$exp_municipio,$certificado_sat,$sello_cfdi,$sello_sat,$fecha_timbrado,$folio_sat,$folio_interno,$cantidad,$unidad,$descripcion,$importe,$desc_u,$name,$subtotal,$cantidad_iva,$leyenda,$TOTAL,$c_emisor,$c_sat,$forma_pago,$metodo_pago,$tipo_comprobante,$numero_cuenta,$metodo,$total_letra,$re_localidad,$re_estado){

    global $impresora_cobros;
    //global $impresora_cuentas;
    require_once('php_image_magician.php');

    $escpos = new KEscPos('TM-T88IV AFU', $impresora_cobros, true, true);
    #$escpos = new KEscPos('TM-T88IV AFU', 'testprinter', true, true);
    #$escpos = new KEscPos('TM-T88IV AFU', 'ip:192.65.33.28', true, true);

    $img2 = "logo.jpg";
    if(file_exists($img2)){
        $escpos -> Align("center");
        $escpos -> Image(false, $img2);
        //$escpos -> Cut();
        $escpos -> Close();
    }
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

    switch($metodo){
        case '01': $l_metodo="01 EFECTIVO";
        break;

        case '02': $l_metodo="02 CHEQUE";
        break;

        case '03': $l_metodo= "03 TRANSFERENCIA DE FONDOS";
        break;

        case '04': $l_metodo="04 TARJETAS DE CREDITO";
        break;

        case '05':
        $l_metodo= "05 MONEDEROS ELECTRONICOS";
        break;

        case '06':
        $l_metodo= "06 DINERO ELECTRONICO";
        break;

        case '07':
        $l_metodo= "07 TARJETAS DIGITALES";
        break;

        case '08':
        $l_metodo= "08 VALES DE DESPENSA";
        break;

        case '09':
        $l_metodo=$l_metodo= "09 BIENES";
        break;

        case '10':
        $l_metodo=$l_metodo= "10 SERVICIO";
        break;

        case '11':
        $l_metodo=$l_metodo= "11 POR CUENTA DE TERCERO";
        break;

        case '12':
        $l_metodo=$l_metodo= "12 DACION EN PAGO";
        break;

        case '13':
        $l_metodo=$l_metodo= "13 PAGO POR SUBROGACION";
        break;

        case '14':
        $l_metodo=$l_metodo= "14 PAGO POR CONSIGNACION";
        break;

        case '15':
        $l_metodo=$l_metodo= "15 CONDONACION";
        break;

        case '16':
        $l_metodo= "16 CANCELACION";
        break;

        case '17':
        $l_metodo= "17 COMPENSACION";
        break;

        case '28':
        $l_metodo= "28 TARJETA DE DEBITO";
        break;

        case '29':
        $l_metodo= "29 TARJETA DE SERVICIO";
        break;

        case '98':
        $l_metodo= "98 NA";
        break;

        case '99':
        $l_metodo= "99 OTROS";
        break;
        case '999':
        $l_metodo= "NA";
        break;

        default:
        $l_metodo=  $metodo;
    }

    $prueba = "Cebaños";


    $var='$printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true);';







    $escpos = new KEscPos('TM-T88IV AFU', $impresora_cobros, true, true);

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
    $escpos -> Line("FOLIO INTERNO:");
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
    $escpos -> Line("DIRECCION: $re_calle");
    if($re_nointerior==""){
        $escpos ->Line("NUM EXT: $re_no_exterior");
    }else{
        $escpos ->Line("NUM EXT: $re_no_exterior  NUM INT:$re_nointerior");
    }
    $escpos -> Line("COLONIA: $re_colonia");
    $escpos -> Line("CUIDAD: $re_localidad");
    $escpos -> Line("MUNICIPIO: $re_municipio");
    $escpos -> Line("ESTADO: $re_estado PAIS: $re_pais");
    $escpos -> Line("C.P: $re_codigopostal");

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
    esc_pos_line($printer, "DIRECCION: '.$emi_calle." ".$emi_noexterior.'");
    esc_pos_line($printer, "COLONIA: '.$emi_colonia.' C.P: '.$emi_codigopostal.' ");
    esc_pos_line($printer, "CIUDAD: '.$emi_localidad.'");
    esc_pos_line($printer, "MUNICIPIO: '.$emi_municipio.'");
    esc_pos_line($printer, "ESTADO: '.$emi_estado.'  PAIS: '.$emi2_pais.'");
    esc_pos_line($printer, "");

    esc_pos_align($printer, "left");
    esc_pos_emphasize($printer,true);
    esc_pos_font($printer, "A");
    esc_pos_line($printer, "EXPEDIDO EN:");
    esc_pos_align($printer, "center");
    esc_pos_emphasize($printer,false);
    esc_pos_line($printer, "DIRECCION: '.$exp_calle." ".$exp_noexterior.'");
    esc_pos_line($printer, "COLONIA: '.$exp_colonia.'");
    esc_pos_line($printer, "MUNICIPIO: '.$exp_municipio.'");
    esc_pos_line($printer, "CIUDAD: '.$exp_localidad.",".$exp_estado.'");
    esc_pos_line($printer, "PAIS: '.$exp_pais.'");
    esc_pos_line($printer, "C.P: '.$exp_codigopostal.'");';


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
    esc_pos_line($printer, "IVA:'.$spacem.$cantidad_iva.$spacio_1.'");

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
    esc_pos_line($printer, "'.$forma_pago.'");
    esc_pos_line($printer, "METODO DE PAGO: '.$l_metodo.' ");
    ';
    if($numero_cuenta == ""){
        $numero_cuenta = "NA";
        $var.='esc_pos_line($printer, "NUMERO CUENTA DE PAGO: '.$numero_cuenta.'");';
    }else{
        $var.='esc_pos_line($printer, "NUMERO CUENTA DE PAGO: '.$numero_cuenta.'");';
    }



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

    esc_pos_close($printer);';

    eval($var);

    $handle = printer_open($impresora_cobros);
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
    global $impresora_cobros;
    session_start();

    $fecha_hora_ticket = date('d-m-Y h:i a');


    $sql = "SELECT*FROM ventas_cancelaciones WHERE id_venta = $id_venta";

    $qq = mysql_query($sql);
    $cuantos_cancelados = mysql_num_rows($qq);



    $var='
    $printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true);
    esc_pos_align($printer, "center");
    esc_pos_font($printer, "A");
    esc_pos_char_width($printer, "");
    esc_pos_line($printer, "------------------------------------------------");
    esc_pos_line($printer, "------------- ACUSE DE CANCELACION -------------");
    esc_pos_line($printer, "------------------------------------------------");';

    $html = '
    <h4>------------- ALERTA DE CANCELACION -------------</h4>
    ';

    $html2 = '
    <h4>------------- ACUSE DE CANCELACION -------------</h4>
    ';

    $cont = 0;
    while($fx = mysql_fetch_assoc($qq)){
        $cont++;

        $var.='
        esc_pos_line($printer, "");
        esc_pos_line($printer, "############### CANCELACION #'.$fx['id_venta_cancelacion'].' ################");
        esc_pos_line($printer, "");
        esc_pos_line($printer, "'.$fx['fechahora_cancelacion'].'");
        esc_pos_line($printer, "FOLIO: #'.$id_venta.'");
        esc_pos_line($printer, "CANCELO: '.strtoupper($_SESSION['s_nombre']).'");
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

        $html .= '
        <p>MODIFICACION #'.$cont.': $'.$g_total.'</p>
        <h4>--------------------------------------------</h4>
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

    $html .= '
    <p>ULTIMA MODIFICACION: $'.$g_total.'</p>
    <h4>--------------------------------------------</h4>
    ';


    if($autoprint){
        eval($var);
    }
    return $html;
}

function acuse_corte($id_venta,$id_venta_cancelacion,$motivo){

    global $autoprint;
    global $impresora_cobros;
    session_start();

    $fecha_hora_ticket = date('d-m-Y h:i a');


    $sql = "SELECT*FROM ventas_cancelaciones WHERE id_venta = $id_venta";

    $qq = mysql_query($sql);
    $cuantos_cancelados = mysql_num_rows($qq);



    $var='
    $printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true);
    esc_pos_align($printer, "center");
    esc_pos_font($printer, "A");
    esc_pos_char_width($printer, "");
    esc_pos_line($printer, "------------------------------------------------");
    esc_pos_line($printer, "------------- ACUSE DE CANCELACION -------------");
    esc_pos_line($printer, "------------------------------------------------");';

    $html = '
    <h4>------------- ALERTA DE CANCELACION -------------</h4>
    ';

    $html2 = '
    <h4>------------- ACUSE DE CANCELACION -------------</h4>
    ';

    $cont = 0;
    while($fx = mysql_fetch_assoc($qq)){
        $cont++;

        $var.='
        esc_pos_line($printer, "");
        esc_pos_line($printer, "############### CANCELACION #'.$fx['id_venta_cancelacion'].' ################");
        esc_pos_line($printer, "");
        esc_pos_line($printer, "'.$fx['fechahora_cancelacion'].'");
        esc_pos_line($printer, "FOLIO: #'.$id_venta.'");
        esc_pos_line($printer, "CANCELO: '.strtoupper($_SESSION['s_nombre']).'");
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

        $html .= '
        <p>MODIFICACION #'.$cont.': $'.$g_total.'</p>
        <h4>--------------------------------------------</h4>
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
    esc_pos_line($printer, "");
    esc_pos_line($printer, "");';

    $html .= '
    <p>ULTIMA MODIFICACION: $'.$g_total.'</p>
    <h4>--------------------------------------------</h4>
    ';



    return $var;
}


function imprimir_domicilio($nombre,$telefono,$direccion){

    global $establecimiento_config;
    global $impresora_sd;

    global $autoprint;

    $fecha_hora_ticket = date('d-m-Y h:i a');

    $var='
    $printer = esc_pos_open("'.$impresora_sd.'", "ch-latin-2", false, true);
    esc_pos_align($printer, "center");
    esc_pos_font($printer, "A");
    esc_pos_line($printer, "'.$establecimiento_config.'");
    esc_pos_line($printer, "'.$fecha_hora_ticket.'");';


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
    global $impresora_cobros;


    global $autoprint;
    $fecha_hora = date('d-m-Y - H:i');

    if($num_cta){
        $NM = 'esc_pos_line($printer, "NUM CTA:'.$num_cta.'");';
    }

    $var='
    $printer = esc_pos_open("'.$impresora_cobros.'", "ch-latin-2", false, true);
    esc_pos_align($printer, "center");
    esc_pos_font($printer, "A");
    esc_pos_line($printer, "'.$establecimiento_config.'");
    esc_pos_line($printer, "'.$fecha_hora.'");
    esc_pos_line($printer, "AUX: '.strtoupper($_SESSION['s_nombre']).'");      ';
    $var.= 'esc_pos_line($printer, "--------------------------------------------");
    esc_pos_align($printer, "center");
    esc_pos_font($printer, "A");
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
