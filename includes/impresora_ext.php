<?php
session_start();
//@include('db.php');
//@include('session.php');
//@include('funciones.php');
@include('kescpos.drv.php');

/*
QUITAR ACENTOS
imprimir_comandas('venta',183270);
imprimir_comandas('domicilio',3);
imprimir_ticket_domicilio(3);
imprimir_salida(3);
*/

function imprimir_comandas($tipo,$id){

    /*
        imprimir_comandas('venta',183270);
        imprimir_comandas('domicilio',11);
    */

    global $s_nombre;
    global $conexion;

    if($tipo=='venta'){

        $sql = "SELECT  venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta,venta_detalle.id_producto,venta_detalle.comentarios,productos.id_categoria,categorias.impresora,productos.imprimir_solo,ventas.mesa,ventas.hora,ventas.fecha
        FROM venta_detalle
        LEFT JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
        LEFT JOIN productos ON productos.id_producto = venta_detalle.id_producto
        LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
        WHERE venta_detalle.id_venta = $id";

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

        $id_producto = $ft['id_producto'];
        $id_categoria = $ft['id_categoria'];
        $nombre = eliminar_tildes($ft['nombre']);
        $cantidad = $ft['cantidad'];
        $precio = $ft['precio_venta'];
        $comentarios = eliminar_tildes($ft['comentarios']);
        $mesa = $ft['mesa'];
        $impresora = $ft['impresora'];
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

    foreach($impresion as $printer => $v){

        if($tipo=='venta'){
            $tipo_comanda = "MESA: $mesa";
        }elseif('domicilio'){
            $tipo_comanda = "*** PARA LLEVAR ***";
        }




        foreach($v as $print => $data){

//DE LA OTRA FORMA SE BAJAN LOS DATOS ACA.
            $fecha_hoy = fechaHoraVista($fechahoy);
            $escpos = new KEscPos('TM-U220B AFU',$print, true, true);

            $escpos -> Font("A");
            $escpos -> Align("center");
            $escpos -> Double(true);
            $escpos -> Width("double");
            $escpos -> Line("");
            $escpos -> Line($print);
            $escpos -> Line("COMANDA #".$id);
            $escpos -> Line($tipo_comanda);
            $escpos -> Double(false);
            $escpos -> Width(false);
            $escpos -> Line($fecha_hoy);
            $escpos -> Line("AUX: $s_nombre");
            $escpos -> Line("________________________________________________");
            $escpos -> Align("left");

            foreach($data as $val){

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
                            $escpos -> Line("    * $com");
                        }
                    }
                }

            } //foreach $data
                $escpos -> Width(false);

            $escpos -> Line("________________________________________________");

        } // foreach $v

        $escpos -> Line("");
        $escpos -> Line("");
        $escpos -> Line("");
        $escpos -> Cut();
        $escpos -> Close();


    } // foreach $impresion


}

function imprimir_ticket_domicilio($id, $impresora = 'DOMICILIO2'){

    global $s_nombre;
    global $conexion;
   // $impresora = "EPSON";

    $sql = "SELECT domicilio_direcciones.direccion,
            domicilio_direcciones.costo,
            domicilio.numero,
            domicilio.nombre,
            ventas_domicilio.facturar,
            ventas_domicilio.fechahora_alta,
            ventas_domicilio.comentarios,
            ventas_domicilio.descuento_cantidad,
            ventas_domicilio.nombre_para_llevar
            FROM ventas_domicilio
            LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion = ventas_domicilio.id_domicilio_direccion
            LEFT JOIN domicilio ON domicilio.id_domicilio = domicilio_direcciones.id_domicilio
            WHERE ventas_domicilio.id_venta_domicilio = $id";

    $q = mysql_query($sql);
    $domicilio = mysql_fetch_assoc($q);
    if($domicilio['direccion']){
        $direccion = explode("\n",$domicilio['direccion']);
    }else{
        $direccion = 0;
    }
    $facturar = $domicilio['facturar'];
    $comentarios_generales = trim($domicilio['comentarios']);
    $fechahora = $domicilio['fechahora_alta'];
    $descuento_cantidad = $domicilio['descuento_cantidad'];
    $nombre_para_llevar = $domicilio['nombre_para_llevar'];
    if($nombre_para_llevar){
        $nombre_cliente = $nombre_para_llevar;
    }else{
        $nombre_cliente = $domicilio['nombre'];
    }

    if($facturar==1){
        $fact = "SI";
    }else{
        $fact = "NO";
    }

    $sql = "SELECT venta_domicilio_detalle.cantidad,
            venta_domicilio_detalle.precio_venta,
            venta_domicilio_detalle.comentarios,
            productos.nombre
            FROM venta_domicilio_detalle
            JOIN productos ON productos.id_producto = venta_domicilio_detalle.id_producto
            WHERE venta_domicilio_detalle.id_venta_domicilio = $id";
    $q = mysql_query($sql);

    $barcode = 'barcode_'.$id.'.jpg';
    $data = file_get_contents("http://tacoloco.mx/barcode.php?f=jpg&s=code-128&d=$id&w=500&sf=2");
    file_put_contents($barcode,$data);

    $fecha_hoy = fechaHoraVista($fechahora);
    #$fecha_hoy = strtoupper(fechaLetraDos(date('Y-m-d'))).' '.date('h:i A');
    $escpos = new KEscPos('TM-T88IV AFU',$impresora, true, true);

    $escpos -> Font("A");
    $escpos -> Align("center");
    $escpos -> Double(true);
    $escpos -> Width("double");
    $escpos -> Line("");
    if($nombre_para_llevar){
        $escpos -> Line("SERVICIO A PARA LLEVAR");
    }else{
        $escpos -> Line("SERVICIO A DOMICILIO");
    }
    $escpos -> Line("TICKET #$id");
    $escpos -> Line("");
    $escpos -> Line(horaVista($fechahora));
    $escpos -> Line("");
    $escpos -> Double(false);
    $escpos -> Width(false);
    $escpos -> Line("MARISQUERIA EL TACO LOCO");
    $escpos -> Line("FUNDADA EN 1979 S.A. DE C.V.");
    $escpos -> Line("AV. JOSE MARIA MORELOS 87");
    $escpos -> Line("C.P. 77000, CHETUMAL, QROO.");
    $escpos -> Line("R.F.C.: MTL1010278B1");
    $escpos -> Line("REGIMEN GENERAL");
    $escpos -> Line("DE PERSONAS MORALES");
    $escpos -> Line("------------------------------------------------");
    $escpos -> Line("$fecha_hoy");
    $escpos -> Line("AUX: $s_nombre");
    $escpos -> Line("------------------------------------------------");
    $escpos -> Align("left");
    $escpos -> Line("CLIENTE: ".eliminar_tildes($nombre_cliente));
    if($domicilio['numero']){
        $escpos -> Line("NUMERO: ".$domicilio['numero']);
    }
    if($direccion){
        $escpos -> Line("DIRECCION DE ENTREGA:");
        $escpos -> Double(true);
        foreach($direccion as $d){
            $escpos -> Line(eliminar_tildes($d));
        }
    }
    $escpos -> Double(false);
    if($comentarios_generales){
        $escpos -> Line("------------------------------------------------");
        $escpos -> Align("center");
        $escpos -> Line("*** NOTA: ***");
        $escpos -> Double(true);
        $escpos -> Line(eliminar_tildes($comentarios_generales));
        $escpos -> Double(false);
        $escpos -> Align("left");
    }
    $escpos -> Line("------------------------------------------------");
    $escpos -> Line("REQUIERE FACTURA: $fact");
    $escpos -> Line("------------------------------------------------");

    while($datos = mysql_fetch_assoc($q)){
        $cantidad = $datos['cantidad'];
        $producto = eliminar_tildes($datos['nombre']);
        $precio_u = $datos['precio_venta'];
        $subtotal = $cantidad*$precio_u;
        $total+=$subtotal;
        $comments = trim(eliminar_tildes($datos['comentarios']));
        $comments = explode("\n",$comments);
        $escpos -> Double(true);
        $escpos -> Line("$cantidad - $producto");
        $escpos -> Double(false);
        if(count($comments)>0){
            foreach($comments as $c){
                if($c){
                    $escpos -> Line("    * $c");
                }
            }
        }
        $escpos -> Line("    $precio_u @ ".number_format($subtotal,2));

    }
    $sub_num = number_format($total,2);
    $total_subtotal = "SUB-TOTAL: ".$sub_num;
    $total_desc = "DESCUENTO: ".number_format($descuento_cantidad,2);
    $total_totales = "TOTAL: ".number_format($total-$descuento_cantidad,2);

    $cuantos_subtotal = 48-strlen($total_subtotal);
    $cuantos_desc = 48-strlen($total_desc);
    $cuantos_total = 48-strlen($total_totales);

    $espacios = "";
    for($i=1;$i<=$cuantos_subtotal;$i++){
        $espacios.=" ";
    }

    $total_subtotal = $espacios.$total_subtotal;
    $espacios = "";

    for($i=1;$i<=$cuantos_desc;$i++){
        $espacios.=" ";
    }

    $total_desc = $espacios.$total_desc;
    $espacios = "";

    for($i=1;$i<=$cuantos_total;$i++){
        $espacios.=" ";
    }

    $total = $espacios.$total_totales;
    $espacios = "";


    $escpos -> Line("------------------------------------------------"); //48
    $escpos -> Double(true);
    if($descuento_cantidad>0){
      $escpos -> Line($total_subtotal);
      $escpos -> Line($total_desc);
    }
    $escpos -> Line($total);
    $escpos -> Double(false);
    $escpos -> Align("center");
    $escpos -> Line("");
    $escpos -> Line("QUEJAS, DUDAS O COMENTARIOS:");
    $escpos -> Line("9838321213 - 9831292090");
    $escpos -> Line("");
    $escpos -> Line("GRACIAS POR SU PREFERENCIA");
    $escpos -> Line("");
    $escpos -> Line("Facebook: El Taco Loco Chetumal");
    $escpos -> Line("Visitenos tambien en Bacalar");
    $escpos -> Line("www.tacoloco.mx");
    $escpos -> Line("");
    $escpos -> Image(false,$barcode);
    $escpos -> Line("");
    $escpos -> Cut();
    $escpos -> Close();
    unlink($barcode);

}

function imprimir_salida($id, $impresora = 'DOMICILIO2'){

    global $conexion;
    #$impresora = "DOMICILIO2";

    $sql = "SELECT repartidores.nombre,ventas_domicilio_salidas.fechahora_salida
            FROM ventas_domicilio_salidas
            JOIN repartidores ON repartidores.id_repartidor = ventas_domicilio_salidas.id_repartidor
            WHERE ventas_domicilio_salidas.id_venta_domicilio_salida = $id ";
    $q = mysql_query($sql);
    $data = mysql_fetch_assoc($q);

    $sql  = "SELECT ventas_domicilio.fechahora_alta, ventas_domicilio.id_venta_domicilio, ventas_domicilio.descuento_cantidad, domicilio_direcciones.direccion,domicilio.numero,domicilio.nombre,ventas_domicilio.comentarios
            FROM ventas_domicilio
            JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion = ventas_domicilio.id_domicilio_direccion
            JOIN domicilio ON domicilio.id_domicilio = domicilio_direcciones.id_domicilio
            WHERE ventas_domicilio.id_domicilio_salida = $id";
    $qf = mysql_query($sql);


    $barcode = 'barcode_'.$id.'.jpg';
    $data_bar = file_get_contents("http://tacoloco.mx/barcode.php?f=jpg&s=code-128&d=S$id&w=500&sf=2");
    file_put_contents($barcode,$data_bar);

    $fecha_hoy = fechaHoraVista($data['fechahora_salida']);
     $escpos = new KEscPos('TM-T88IV AFU',$impresora, true, true);

    $escpos -> Font("A");
    $escpos -> Align("center");
    $escpos -> Double(true);
    $escpos -> Width("double");
    $escpos -> Line("");
    $escpos -> Line("SALIDA #$id");
    $escpos -> Line($fecha_hoy);
    $escpos -> Line(eliminar_tildes($data['nombre']));
    $escpos -> Align("left");
    $escpos -> Double(false);
    $escpos -> Width(false);
    $escpos -> Line("------------------------------------------------");

    while($data = mysql_fetch_assoc($qf)){

        $pedido = $data['id_venta_domicilio'];
        $fecha_pedido = horaVista($data['fechahora_alta']);
        $hace_cuanto = hace($data['fechahora_alta']);
        $nombre = eliminar_tildes($data['nombre']);
        $numero = $data['numero'];
        $direccion = $data['direccion'];
        $comentarios = trim(eliminar_tildes($data['comentarios']));
        $descuento_cantidad = $data['descuento_cantidad'];

        $escpos -> Double(true);
        $escpos -> Line("PEDIDO: #$pedido - $fecha_pedido (HACE $hace_cuanto)");
        $escpos -> Double(false);
        $escpos -> Line("CLIENTE: $nombre");
        $escpos -> Line("NUMERO: $numero");
        $direccion = explode("\n",$direccion);
        if(count($direccion)>0){
            foreach($direccion as $d){
                if($d){
                    $escpos -> Line(eliminar_tildes($d));
                }
            }
        }
        $escpos -> Double(true);
        if($comentarios){
            $comentarios = explode("\n",$comentarios);
            if(count($comentarios)>0){
                $escpos -> Line("*** NOTA:");
                foreach($comentarios as $c){
                    if($d){
                        $escpos -> Line("*** $c");
                    }
                }
            }
        }

        $escpos -> Double(false);
        $sql = "SELECT SUM(cantidad*precio_venta) as total FROM venta_domicilio_detalle WHERE id_venta_domicilio = $pedido";
        $q = mysql_query($sql);
        $total = mysql_fetch_assoc($q);
        $total = $total['total'];
        $subtotal = number_format($total,2);
        $desc = number_format($descuento_cantidad,2);
        $total_ok = number_format($total-$descuento_cantidad,2);

        if($descuento_cantidad>0){
            $escpos -> Line("SUB-TOTAL: ".$subtotal);
            $escpos -> Line("DESCUENTO: ".$desc);
        }
        $escpos -> Line("TOTAL: ".$total_ok);
        $escpos -> Line("------------------------------------------------");

    }

    $escpos -> Line("");
    $escpos -> Line("");
    $escpos -> Align("center");
    $escpos -> Image(false,$barcode);
    $escpos -> Line("");
    $escpos -> Cut();
    $escpos -> Close();
    unlink($barcode);
}

function imprimir_corte_repartidores($id){

    global $conexion;
    $impresora = "EPSON";

    $sql = "SELECT usuarios.nombre,cortes_domicilio.fechahora
            FROM cortes_domicilio
            LEFT JOIN usuarios ON usuarios.id_usuario = cortes_domicilio.id_usuario
            WHERE cortes_domicilio.id_corte_domicilio = $id";
    $q = mysql_query($sql);
    $data = mysql_fetch_assoc($q);
    $fechahora = $data['fechahora'];
    $usuario = $data['nombre'];

    /* DATA REPARTIDORES */

    /*

    VALIDAR REPARTIDORES ACTIVOS EN ESE CORTE
    METER ID CORTE EN LAS CONSULTAS
    */


    $sql="SELECT id_repartidor,nombre FROM repartidores WHERE activo = 1";
    $q=mysql_query($sql);

    while($dat=mysql_fetch_assoc($q)){
        $repartidores[] = $dat;
    }

    foreach($repartidores as $repartidor){

        $id_repartidor = $repartidor['id_repartidor'];
        $nombre = $repartidor['nombre'];

        $sql = "SELECT id_venta_domicilio_salida FROM ventas_domicilio_salidas WHERE id_repartidor = $id_repartidor";
        $q = mysql_query($sql);

        while($data = mysql_fetch_assoc($q)){
            $id_venta_domicilio_salida = $data['id_venta_domicilio_salida'];
            $sql = "SELECT id_venta_domicilio,descuento_cantidad FROM ventas_domicilio WHERE id_corte_domicilio = $id AND id_domicilio_salida = $id_venta_domicilio_salida AND cancelado = 0";
            $qry = mysql_query($sql);

            while($d = mysql_fetch_assoc($qry)){
                $ventas_repartidor[$id_repartidor][] = $d;
                $ventas_repartidor[$id_repartidor]['nombre'] = $nombre;
            }
        }
    }

    foreach($ventas_repartidor as $id_repartidor => $datos){
        $c = 0;

        $rep[$id_repartidor]['total_envios'] = 0;
        $rep[$id_repartidor]['total_ventas_monto'] = 0;
        $rep[$id_repartidor]['total_envios_monto'] = 0;

        foreach($datos as $data){
            $c++;
            $desc = $data['descuento_cantidad'];
            $rep[$id_repartidor]['nombre'] = $datos['nombre'];

            $total = total_venta_domicilio($data['id_venta_domicilio']) - $desc;
            $rep[$id_repartidor]['total_ventas_monto'] +=$total;

            $total_envio = total_envio_domicilio($data['id_venta_domicilio']);
            $rep[$id_repartidor]['total_envios_monto'] +=$total_envio;

            if($total_envio>0){
                $rep[$id_repartidor]['total_envios']++;
            }

        }
        $c--;
        $rep[$id_repartidor]['total_ventas'] = $c;
    }

    /* END DATA REPARTIDORES */

    $valida_repartidores=count($repartidores);

    $fecha_hoy = fechaHoraVista($fechahora);
    $escpos = new KEscPos('TM-T88IV AFU',$impresora, true, true);

    $escpos -> Font("A");
    $escpos -> Align("center");
    $escpos -> Double(true);
    $escpos -> Width("double");
    $escpos -> Line("");
    $escpos -> Line("CORTE DE REPARTIDOPRES");
    $escpos -> Line("FOLIO #$id");
    $escpos -> Double(false);
    $escpos -> Width(false);
    $escpos -> Line("$fecha_hoy");
    $escpos -> Line("AUX: $usuario");
    $escpos -> Align("left");
    $escpos -> Line("------------------------------------------------");

        foreach($rep as $d){

            $total = $d['total_ventas_monto']+$d['total_envios_monto'];

            $totales_ventas += $d['total_ventas'];
            $totales_ventas_monto += $d['total_ventas_monto'];

            $totales_envios += $d['total_envios'];
            $totales_envios_monto += $d['total_envios_monto'];

            $super_total += $total;

            $escpos -> Double(true);
            $escpos -> Line(eliminar_tildes($d['nombre']));
            $escpos -> Double(false);
            $escpos -> Line("PEDIDOS: ".$d['total_ventas']." / $".number_format($d['total_ventas_monto'],2));
            $escpos -> Line("ENVIOS: ".$d['total_envios']." / $".number_format($d['total_envios_monto'],2));
            $escpos -> Line("TOTAL: $".number_format($total,2));
            $escpos -> Line("------------------------------------------------");
        }


    $escpos -> Line("");
    $escpos -> Line("");
    $escpos -> Double(true);
    $escpos -> Line("RESUMEN:");
    $escpos -> Double(false);
    $escpos -> Align("left");
    $escpos -> Line("TOTAL DE PEDIDOS: ".$totales_ventas." / $".number_format($totales_ventas_monto,2));
    $escpos -> Line("TOTAL DE ENVIOS: ".$totales_envios." / $".number_format($totales_envios_monto,2));
    $escpos -> Line("TOTAL: $".number_format($super_total,2));
    $escpos -> Line("");
    $escpos -> Line("");
    $escpos -> Line("------------------------------------------------");
    $escpos -> Line("");
    $escpos -> Line("");
    $escpos -> Line("");
    $escpos -> Line("");
    $escpos -> Cut();
    $escpos -> Close();

}
