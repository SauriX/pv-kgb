<?
include('includes/session.php');




include('includes/impresora.php');

include('includes/postmark.php');


/*$A="SELECT email_notificacion FROM configuracion ";
$B = mysql_query($A,$conexion);
$C = mysql_fetch_assoc($B);
$correo=$C['email_notificacion'];*/
$correo='sauri618@gmail.com';
$id_corte=6;


$sql_corte="SELECT * FROM cortes
LEFT JOIN usuarios ON cortes.id_usuario = usuarios.id_usuario
 where id_corte=$id_corte";
$q=mysql_query($sql_corte); 
$fx=mysql_fetch_assoc($q);

$tags=array() ;

 $sql2 = "SELECT * FROM ventas 
 WHERE id_corte = 6
 ";
 
 $q2=mysql_query($sql2);   
 while($fr=mysql_fetch_assoc($q2)){

    $ventasub+=$fr['pagarOriginal'];
    $descuento+=$fr['DescEfec_txt'];
    $id_venta=$fr['id_venta'];
    $sql="SELECT venta_detalle. * , productos.nombre
    FROM venta_detalle
    LEFT JOIN productos ON venta_detalle.id_producto = productos.id_producto
    WHERE id_venta =$id_venta AND  venta_detalle.id_producto !=0  AND venta_detalle.precio_venta !=0";

    $q=mysql_query($sql);

    
while($ft=mysql_fetch_assoc($q)){

   $id_base=$ft['id_producto'];
   $producto=$ft['nombre'];
   $cantidad=$ft['cantidad'];
   $precio=$ft['precio_venta'];
   $total =$ft['precio_venta']*$ft['cantidad'];
    $index=0;
    for($i=0; $i<count($tags); $i++)
    {
      
     if($tags[$i]['id']==$id_base){
       $index=$id_base;
       
        $tags[$i]['cantidad']+=$cantidad;

        $tags[$i]['total']+=$total;

     }
    }


    if($index==0){
        $tags[]=array(

            "id"=> $id_base,
            "producto" => $producto,
            "cantidad" => $cantidad,
        
            "unitario"=>$precio,
        "total"=>$total,

        );
    }
      





   
}

}


$orig='
<h4>------------- CORTE DE CAJA -------------</h4>

<p><b>FOLIO: #</b>'.$fx['id_corte'].'</p>
<p><b>AUXILIAR: </b>'.$fx['nombre'].'</p>
<p><b>FECHA APERTURA: </b>'.$fx['fh_abierto'].'</p>
<p><b>FECHA CORTE: </b>'.$fx['fecha'].$fx['hora'].'</p>
<div >
<h4 style="text_aling:center;">------------- VENTAS -------------</h4>
</div>
<hr>
';




  


    
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



      
      
        
   



foreach($tags as $ft){

$orig.='

<tr>
    <td width="20%" >'.$ft['producto'].'</td>
    <td width="20%" style="text-align: right;" >'.$ft['cantidad'].'</td>
    <td width="20%" style="text-align: right;" >'.$ft['unitario'].'</td>
    <td width="20%"style="text-align: right;" >'.number_format($ft['total']).'</td>
</tr>

';




}



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
    


    foreach($montos_metodo as $id_m => $monto){
        if ($id_m == '1') {
            $efectivo = floatval($monto);
        }elseif ($id_m == '4' || $id_m == '28') {
            $tarjetas = floatval($tarjetas)+floatval($monto);
        }
  }
  
  $tarjetas = number_format($tarjetas,2, '.', '');

  $PROMEDIO = number_format(($ventasub)/$cta_expedidas,2, '.', '');
            $orig.='
             <tr>
             <td width="20%" >  </td>
             <td width="20%" >  </td>
             <td width="20%" >  </td>
                 <td width="20%"  style="text-align: left;" >SUBTOTAL DE VENTAS : '.$ventasub.'</td>
             </tr>
            
             <tr>
             <td width="20%" >  </td>
             <td width="20%" >  </td>
             <td width="20%" >  </td>
                 <td width="20%"  style="text-align: left;" >DESCUENTOS : '.$descuento.'</td>
             </tr>
             

             <tr>
             <td width="20%" >  </td>
             <td width="20%" >  </td>
             <td width="20%" >  </td>
                 <td  width="20%"  style="text-align: left;" >TOTAL : '.($ventasub-$descuento).'</td>
             </tr>
          
   
            </tbody>
        </table>
      <hr>
        <table>
        <thead>
        
        </thead>
        <tbody>       
        <tr>
        <td width="20%" ><b>DESGLOSE</b></td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
       
        </tr>

        <tr>
        <td  width="20%"  style="text-align: left;" >EFECTIVO : '.$efectivo.'</td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
            
        </tr>

        <tr>
        <td  width="20%"  style="text-align: left;" >TARJETAS: '.$tarjetas.'</td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
            
        </tr>

        <tr>
        <td  width="20%"  style="text-align: left;" >'.'</td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
            
        </tr>
        <tr>
        <td  width="20%"  style="text-align: left;" >CUENTAS EXPEDIDAS: '.$cta_expedidas.'</td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
            
        </tr>

        <tr>
        <td  width="20%"  style="text-align: left;" >PROMEDIO POR CUENTA: '.$PROMEDIO.'</td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
            
        </tr>
        <tr>
        <td  width="20%"  style="text-align: left;" >CUENTAS CANCELADAS: '.number_format($cancelaciones,0, '.', '').'</td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
        <td width="20%" >  </td>
            
        </tr>
        </tbody>   
    
    
    ';
    $orig.='
    
    <h4 style="text_aling:center;">------------- GASTOS-------------</h4>
    <hr>
    ';



     $orig.='
     <table>
         <thead>
             <tr>
                 <th width="20%">Descripcion</th>
                 <th width="20%"> </th>
                 <th width="20%"> </th>
                 <th width="20%">SUBT</th>
             </tr>
         </thead>
         <tbody>';



         $sql ="SELECT * FROM gastos WHERE id_corte = $id_corte";
         $qq1 = mysql_query($sql);
         $total2=0.00;
         while($fxt = mysql_fetch_assoc($qq1)){
             
              $total2+=$fxt['monto'];
              
         $orig.='

         <tr>
             <td width="20%" >'.$fxt['descripcion'].'</td>
             <td width="20%" style="text-align: right;" > </td>
             <td width="20%" style="text-align: right;" > </td>
             <td width="20%"style="text-align: right;" >'.number_format($fxt['monto']).'</td>
         </tr>
         
         ';
         }



$orig.='
<tr>
<td width="20%" >  </td>


    <td  width="80%"  style="text-align: left;" >GASTOS TOTALES :'.number_format($total2,0, '.', '').'</td>
</tr>

</tbody> 

</table>
';
  

$orig.='
<h4 style="text_aling:center;">------------- CORTE CAJA-------------</h4>
<hr>
<table>
<thead>

</thead>
<tbody>       

<tr>
<td  width="80%"  style="text-align: left;" >FONDO DE CAJA : '.$fx['fondo_caja'].'</td>

<td width="20%" >  </td>
    
</tr>

<tr>
<td  width="20%"  style="text-align: left;" >EFECTIVO: '.$efectivo.'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>

<tr>
<td  width="20%"  style="text-align: left;"  >TARJETAS: '.$tarjetas.'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>
<tr>
<td  width="20%"  style="text-align: left;" >SUBTOTAL: '.($efectivo+$tarjetas+$fx['fondo_caja']).'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>

<tr>
<td  width="20%"  style="text-align: left;" >GASTOS: '.$total2.'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>
<tr>
<td  width="20%"  style="text-align: left;" >TOTAL: '.number_format(($efectivo+$tarjetas+$fx['fondo_caja'])-$total2,2, '.', '').'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>
</tbody>   
</table>

';


$orig.='
<h4 style="text_aling:center;">-------- CORTE CAPTURA --------</h4>
<hr>
<table>
<thead>

</thead>
<tbody>       

<tr>
<td  width="80%"  style="text-align: left;" >EFECTIVO TOTAL : '.$fx['efectivoCaja'].'</td>

<td width="20%" >  </td>
    
</tr>

<tr>
<td  width="20%"  style="text-align: left;" >TARJETAS: '.$fx['tpv'].'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>

<tr>
<td  width="20%"  style="text-align: left;"  >TOTAL: '.number_format(($fx['efectivoCaja']+$fx['tpv']),2, '.', '').'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>

</tbody>   
</table>

';

$falta =($fx['efectivoCaja']+$fx['tpv'])-($efectivo+$tarjetas+$fx['fondo_caja']-$total2);

if(($fx['efectivoCaja']+$fx['tpv'])>($efectivo+$tarjetas+$fx['fondo_caja']-$total2)){

    $falta=number_format($falta,2, '.', '');
    $res='SOBRANTE: '.$falta;

}else{
    $falta *=-1;
    $falta=number_format($falta,2, '.', '');
    $res='FALTANTE: '.$falta;

}

$orig.='

<hr>
<table>
<thead>

</thead>
<tbody>       

<tr>
<td  width="80%"  style="text-align: left;" >TOTAL DE VENTAS: '.number_format(($efectivo+$tarjetas+$fx['fondo_caja'])-$total2,0, '.', '').'</td>

<td width="20%" >  </td>
    
</tr>

<tr>
<td  width="20%"  style="text-align: left;" >TOTAL CAPTURA '.number_format(($fx['efectivoCaja']+$fx['tpv']),2, '.', '').'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>

<tr>
<td  width="20%"  style="text-align: left;"  >'.$res.'</td>
<td width="20%" >  </td>
<td width="20%" >  </td>
<td width="20%" >  </td>
    
</tr>

</tbody>   

</table>
';


if($totalDescuento!=0.00){ 
            
    $orig.='
    <h4 style="text_aling:center;">-------- DESCUENTOSS--------</h4>
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
$descuento= number_format($descuento,2, '.', '');
$total_cobrado= number_format($total_cobrado,2, '.', '');



$sql90 = "SELECT venta_detalle.id_producto,venta_detalle.cantidad,productos.nombre,venta_detalle.precio_venta,productos.extra,productos.sinn,productos.paquete FROM venta_detalle
JOIN productos ON productos.id_producto = venta_detalle.id_producto

WHERE venta_detalle.id_venta =".$fx45['id_venta'] ." AND venta_detalle.precio_venta != 0  ";
;
$q90 = mysql_query($sql90);

$orig.='
<hr>
<p><b>FECHA : </b>'.$fecha.'</p>
<p><b>FOLIO: #</b>'.$folio.'</p>
<p><b>AUXILIAR: </b>'.$aux.'</p>

<table>
<thead>
<tr>
<th width="20%">PRODUCTO</th>
<th width="20%">CANT</th>
<th width="20%">UNIT</th>
<th width="20%">SUBT</th>
</tr>
</thead>
<tbody>       
';




while($ft=mysql_fetch_assoc($q90)){

$producto=$ft['cantidad']." - ".$ft['nombre'];
$tot= $ft['cantidad']*$ft['precio_venta'];
$tot=number_format($tot,2, '.', '');
$cantidad=number_format($ft['precio_venta'],2, '.', '')." @ ". $tot;

$orig.='

<tr>
    <td width="25%" >'.$ft['nombre'].'</td>
    <td width="25%" style="text-align: right;" >'.$ft['cantidad'].'</td>
    <td width="25%" style="text-align: right;" >'.$ft['precio_venta'].'</td>
    <td width="25%"style="text-align: right;" >'.$tot.'</td>
</tr>

';

}

$orig.='


<hr>
<tr>


    <td width="90%"  style="text-align: left;" >SUBTOTAL : '.$fx45['pagarOriginal'].'</td>
</tr>

<tr>


    <td width="90%"  style="text-align: left;" >DESCUENTO : '.$fx45['DescEfec_txt'].'</td>
</tr>


<tr>


    <td  width="90%"  style="text-align: left;" >TOTAL : '.$fx45['monto_pagado'].'</td>
</tr>

</tbody>   
</table>
';





}




    $html= $orig;
$remite = "VendeFacil <bot@adminus.mx>";
$dato = $html;
$postmark = new Postmark(null,$remite);
$postmark->to($correo);
$postmark->subject('Corte de caja');
$postmark->html_message($dato);
$postmark->send();



?>
<input type="checkbox" class="hi" name="vehicle1" value="Bike" > I have a bike<br>
<input type="checkbox" class="hi" name="vehicle2" value="Car" > I have a car<br>
<input type="checkbox" class="hi" name="vehicle3" value="Boat" > I have a boat<br>

<input type="button" value="hola" onclick="hola2();">





 

<script>



    function hola2(){
        $('.hi').prop('checked', true);
  
var diasSeleccionados = new Array();

$('input[type=checkbox]:checked').each(function() {
    diasSeleccionados.push($(this).val());
    console.log($(this).val());
});

alert("Dias seleccionados => " + diasSeleccionados);
 
    }
</script>