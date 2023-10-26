<?
$id_corte = 34;
if(!$id_corte) exit("Ingrese numero de corte.");

include("../includes/db.php");
include("../includes/funciones.php");
set_time_limit(0);
ob_start();
$datos=mysql_fetch_assoc(mysql_query("SELECT * FROM configuracion"));
$establecimiento=$datos['establecimiento'];
$rfc=$datos['rfc'];
$direccion=$datos['direccion'];

$titulo="Reporte del Cortes # ".$id_corte;
$sql="SELECT * FROM cortes
JOIN usuarios ON usuarios.id_usuario=cortes.id_usuario
WHERE id_corte = $id_corte";
$query=mysql_query($sql);
$counter="";
?>
<style>
.titulos{
	background-color: #1596b6;
	color: #FFF;
	/*padding-left: 5px;*/
}
.borde-azul{
	border: #1596b6 1px solid ;
}
.borde-iz{
	border-left: #1596b6 1px solid;
}
.borde-der{
	border-right: #1596b6 1px solid;
}
.borde-bot{
	border-bottom: #1596b6 1px solid;
}
.borde-top{
	border-top: #1596b6 1px solid;
}
b{
	font-family: sfsemi;
}
table{
	font-family: sf;
}
.f11{
	font-size: 11px;
}
.f10{
	font-size: 10px;
}
.no-margin{
	margin-bottom: 0px;
}
.titulo_corte{
	font-family: sfsemi;
	margin-bottom: 5px;
	margin-top: 15px;
	font-weight: normal;
}
tr.odd{
    background-color:white;
}
tr.even{
    background-color:#FAFAFA;
}
</style>

<page backtop="20mm" backbottom="15mm" backleft="0mm" backright="2mm" footer="page">

<page_header>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="580" align="center" valign="middle">
				<h4 class="no-margin"><?=$titulo?></h4>
				<? if($establecimiento){ echo $establecimiento; }?> <? if($rfc){ echo "RFC: ".$rfc; }?><br>
				<? if($direccion){ echo $direccion; }?>
			</td>
			<td width="200" align="center" valign="middle"><img src="logo.png" width="180" /></td>
		</tr>
	</table>
</page_header>

<page_footer>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="780" style="padding-top: 10px;padding-bottom: 16px;"><b>KGB grill</b> Punto de venta</td>
		</tr>
	</table>
</page_footer>

<? while($ft=mysql_fetch_assoc($query)){
	
	$id_corte = $ft['id_corte'];
	$fondo_caja = $ft['fondo_caja'];
	$efectivoCa = $ft['efectivoCaja'];
   $tpvEfec = $ft['tpv'];
	$sq0="SELECT * FROM ventas WHERE id_corte=$id_corte";
	$q0=mysql_query($sq0);
	$val=mysql_num_rows($q0);
	if(!$val){
		continue;
	}
	$mesas_ct = 0;
	$barra_ct = 0;
	$pre_fact_ct = 0;
	$no_fact_ct = 0;

	$cancelaciones = 0;
	$cta_expedidas = 0;

	$efectivo = 0;
	$tarjeta = 0;
	$transf = 0;
	$cheque = 0;
	$noide = 0;
	while($ft = mysql_fetch_assoc($q0)){

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
//exit($id_corte);
$fecha_hora_ticket = $fecha_corte;

?>


<h4 class="titulo_corte">Corte <? if($fecha2){ echo fechaLetraTres($ft['fecha'])." "; }?>a las <?=horaVista($ft['hora'])?> <br><small>Usuario: <?=$ft['nombre']?></small></h4>
<br>
<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11">
	<thead>
    	<tr class="titulos">
			<th width="450" height="25" class="f11" style="padding-left: 5px;">Producto</th>
			<th width="55" height="25" class="f11">Cantidad</th>
			<th width="130" height="25" class="f11" align="right">Precio</th>
			<th width="125" height="25" class="f11" align="right" style="padding-right: 5px;">Importe</th>
		</tr>
	</thead>
	<tbody>


<?
$sq1="SELECT MIN(id_venta) AS venta_inicio, MAX(id_venta) AS venta_final FROM ventas WHERE id_corte=$id_corte";
$q1=mysql_query($sq1);
$dat=mysql_fetch_assoc($q1);
$id_venta_inicio = $dat['venta_inicio'];
$id_venta_final = $dat['venta_final'];
$sq2 = "SELECT 	venta_detalle.id_producto,
				venta_detalle.cantidad,
				venta_detalle.precio_venta,
				productos.nombre
FROM venta_detalle
JOIN productos ON productos.id_producto = venta_detalle.id_producto
WHERE id_venta BETWEEN $id_venta_inicio AND $id_venta_final";

$g_total=0;
$q2 = mysql_query($sq2);
$prod = array();
$nombres = array();
$pu = array();
while($ft=mysql_fetch_assoc($q2)){

	$prod[$ft['id_producto']]+=$ft['cantidad'];
	$nombres[$ft['id_producto']] = $ft['nombre'];
	$pu[$ft['id_producto']] = $ft['precio_venta'];

}
	  foreach($nombres as $id  => $nombre){

		  $producto = $nombre;
		  $cantidad = $prod[$id];
		  $precio = $pu[$id];

		  $total=$prod[$id]*$pu[$id];
		  $g_total+=$total;
		  $total= number_format($total,2);
		  $efectivo2 = number_format($efectivo,2, '.', '');
		  $tarjetas2 = number_format($tarjetas,2, '.', '');
	
		  $mesas_monto = number_format($mesas_monto,2, '.', '');
		  $barra_monto = number_format($barra_monto,2, '.', '');
		  $pre_fact_monto = number_format($pre_fact_monto,2, '.', '');
		  $no_fact_monto = number_format($no_fact_monto,2, '.', '');
		  $promedio = number_format($promedio,2, '.', '');
		  $no_fact_monto_por = number_format($no_fact_monto_por,2, '.', '');
		  $pre_fact_monto_por = number_format($pre_fact_monto_por,2, '.', '');
		  $mesas_monto_por = number_format($mesas_monto_por,2, '.', '');
		  $barra_monto_por = number_format($barra_monto_por,2, '.', '');
			$descuentoTotal = $g_total-$totalDescuento;
			$totalDescuento = number_format($totalDescuento,2, '.', '');
			$descuentoTotal = number_format($descuentoTotal,2, '.', '');
		  $counter++;
		  $class="";
		  if($counter%2){
		  	$class="even";
		  }else{
		  	$class="odd";
		  }

?>
<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;"><?= $producto ?></td>
	<td width="55" height="20" align="center"><?= $cantidad ?></td>
	<td width="130" height="20" align="right"><?= $precio ?></td>
	<td width="125" height="20" align="right"><?= $total ?></td>
</tr>
<?php } ?>

<tr class="<?=$class?>" >

	<td width="450" height="20" style="padding-left: 5px; font-weight: bold;" >VENTA SUBTOTAL</td>
	<td width="55" height="20" align="center"></td>
	<td width="55" height="20" align="center"></td>
	<td width="55" height="20" align="right"><?=number_format($g_total,2, '.', '');?></td>

	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">DESCUENTO</td>
	<td width="55" height="20" align="center"></td>
	<td width="55" height="20" align="center"></td>
	<td width="55" height="20" align="right"><?= $totalDescuento ?></td>
	
	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;"><b>VENTA TOTAL</b></td>
	<td width="55" height="20" align="center"></td>
	<td width="55" height="20" align="center"></td>
	
	<td width="55" height="20" align="right"><b><?= $descuentoTotal ?></b></td>
	
</tr>

<?php

$sqlCorte = "SELECT efectivoCaja,tpv,otrosMet FROM cortes WHERE id_corte = $id_corte";
$qCorte = mysql_query($sqlCorte);
while($datosCorte = mysql_fetch_assoc($qCorte)){
	
  $capturaEfec = $datosCorte['efectivoCaja'];
  $montoCapturado = floatval($montoCapturado)+floatval($capturaEfec);

  $capturaTpv = $datosCorte['tpv'];
  $montoCapturado = floatval($montoCapturado)+floatval($capturaTpv);

  $capturaOtrosMet = $datosCorte['otrosMet'];
  $montoCapturado = floatval($montoCapturado)+floatval($capturaOtrosMet);

}

?>

	</tbody>
</table>


<h4> Desglose</h4>
<!---aqui-->
<?
		$efectivo = 0;
		$tarjetas = 0;
		foreach($montos_metodo as $id_m => $monto){
			if ($id_m == '1') {
				$efectivo = floatval($monto);
			}elseif ($id_m == '4' || $id_m == '28') {
				$tarjetas = floatval($tarjetas)+floatval($monto);
			}
		}
	  $efectivo2 = number_format($efectivo,2, '.', '');
	  $tarjetas2 = number_format($tarjetas,2, '.', '');
?>
<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11">
	<thead>
    	<tr class="titulos">
			<th width="450" height="25" class="f11" style="padding-left: 5px;">Concepto</th>
			<th width="55" height="25" class="f11">Monto</th>
			
		
		</tr>
	</thead>
	<tbody>



	
<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">EFECTIVO</td>
	<td width="55" height="20" align="center"><?=$efectivo2?></td>

	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">TARJETAS</td>
	<td width="55" height="20" align="center"><?= $tarjetas2 ?></td>
	
	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">CUENTAS EXPEDIDA</td>
	<td width="55" height="20" align="CENTER"><?= $cta_expedidas ?></td>
	
</tr>
<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">PROMEDIO POR CUENTA</td>
	<td width="55" height="20" align="CENTER"><?= $promedio ?></td>
	
</tr>



	</tbody>
</table>


<page pageset="old">
</page>
<h4> Gastos</h4>
<!---aqui-->

<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11">
	<thead>
    	<tr class="titulos">
			<th width="450" height="25" class="f11" style="padding-left: 5px;">Concepto</th>
			<th width="55" height="25" class="f11">Monto</th>
			
		
		</tr>
	</thead>
	<tbody>


	<?
		    $sql ="SELECT * FROM gastos WHERE id_corte = $id_corte";
			$qq1 = mysql_query($sql);
			$numero = mysql_num_rows($qq1);
			if($numero>0){
			while($fx = mysql_fetch_assoc($qq1)){
				
				$monto = $fx['monto'];
				
				
	  
				$g_total_g+=$monto;
	  
				$monto= number_format($monto,2, '.', '');
				$mont = strlen($monto);
				
				
	  ?>
	  <tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;"><?=$fx['descripcion'];?></td>
	<td width="55" height="20" align="center"><?=$fx['monto'];?></td>

	
</tr>
	  <?
			  }
?>
<?$g_total_gastos = number_format($g_total_g,2, '.', '');?>
	
	<tr class="<?=$class?>">
		<td width="450" height="20" style="padding-left: 5px;"><b>GASTO TOTAL</b></td>
		<td width="55" height="20" align="center"><b><?=$g_total_gastos?></b></td>
	
		
	</tr>
	<?}else{?>
		<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">NO SE HICIERON GASTOS</td>
	

	
</tr>
<?$g_total_gastos = number_format($g_total_g,2, '.', '');?>
	
<tr class="<?=$class?>">
<td width="450" height="20" style="padding-left: 5px;"><b>GASTO TOTAL</b></td>
		<td width="55" height="20" align="center"><b><?=$g_total_gastos?></b></td>
	
</tr>

	<?}?>





	</tbody>
</table>


<page pageset="old">
</page>
<h4> Corte de caja</h4>
<!---aqui-->

<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11">
	<thead>
    	<tr class="titulos">
			<th width="450" height="25" class="f11" style="padding-left: 5px;">Concepto</th>
			<th width="55" height="25" class="f11">Monto</th>
			
		
		</tr>
	</thead>
	<tbody>


<?
$subtotalVenta = 0;
$subtotalVenta = floatval($subtotalVenta)+floatval($efectivo);
$subtotalVenta = floatval($subtotalVenta)+floatval($tarjetas);

$subtotalCaptura = 0;
$subtotalCaptura = floatval($subtotalCaptura)+floatval($efectivoCa);
$subtotalCaptura = floatval($subtotalCaptura)+floatval($tpvEfec);


$ENCAJA = $subtotalVenta+$fondo_caja;
$ENCAJA = $ENCAJA-$g_total_g;

$ENCAJA2 = $efectivoCa;
$ENCAJA2 = $ENCAJA2-$g_total_g;

$ventaDetalle = "";
$restaDetalle = $ENCAJA-$ENCAJA2;
if ($restaDetalle > 0) {
	$ventaDetalle = "SOBRANTE: $".number_format($restaDetalle,2, '.', '');
}elseif ($restaDetalle < 0) {
	$ventaDetalle = "FALTANTE: $".number_format($restaDetalle,2, '.', '');
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


?>
	
<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">FONDO DE CAJA</td>
	<td width="55" height="20" align="center"><?=$fondo_caja2?></td>

	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">EFECTIVO</td>
	<td width="55" height="20" align="center"><?=$efectivo2?></td>

	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">TARJETAS</td>
	<td width="55" height="20" align="center"><?=$tarjetas2?></td>

	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">SUBTOTAL</td>
	<td width="55" height="20" align="center"><?=$subtotalVenta?></td>

	
</tr>



<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">GASTOS</td>
	<td width="55" height="20" align="center"><?=$g_total_gastos?></td>

	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;"><b>TOTAL</b></td>
	<td width="55" height="20" align="center"><b><?=$ENCAJA?></b></td>

	
</tr>

	</tbody>
</table>

<?
$sql74="SELECT ajuste FROM cortes WHERE id_corte =$id_corte";
$query74 = mysql_query($sql74);
$ft=mysql_fetch_assoc($query74);

$ajuste= $ft['ajuste'];
if($ajuste==0){
?>


<h4> Corte captura</h4>
<!---aqui-->
<?
	
	  $tarjetas2 = number_format($tarjetas,2, '.', '');
?>
<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11">
	<thead>
    	<tr class="titulos">
			<th width="450" height="25" class="f11" style="padding-left: 5px;">Concepto</th>
			<th width="55" height="25" class="f11">Monto</th>
			
		
		</tr>
	</thead>
	<tbody>



	
<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">EFECTIVO TOTAL</td>
	<td width="55" height="20" align="center"><?=$efectivoCa?></td>

	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">TARJETAS</td>
	<td width="55" height="20" align="center"><?= $tpvEfec ?></td>
	
	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">SUBTOTAL</td>
	<td width="55" height="20" align="CENTER"><?= $subtotalCaptura ?></td>
	
</tr>
<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">GASTOS</td>
	<td width="55" height="20" align="CENTER"><?= $g_total_gastos?></td>
	
</tr>
<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;"><b>TOTAL</b></td>
	<td width="55" height="20" align="CENTER"><b><?= $ENCAJA2?></b></td>
	
</tr>
<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">TOTAL VENTA</td>
	<td width="55" height="20" align="CENTER"><?= $ENCAJA2?></td>
	
</tr>

<tr class="<?=$class?>">
	<td width="450" height="20" style="padding-left: 5px;">TOTAL CAPTURA</td>
	<td width="55" height="20" align="CENTER"><?= $ENCAJA2?></td>
	
</tr>



	</tbody>
</table>
<h5><?=$ventaDetalle?></h5>
<?}?>
<? } ?>

</page>


<?php
	$content_html = ob_get_clean();

	// initialisation de HTML2PDF
	@mkdir('pdfs_mail');
	require_once(dirname(__FILE__).'/pdf/html2pdf.class.php');
	try
	{

		$html2pdf = new HTML2PDF('P','Letter','es', true, 'UTF-8', array(2, 0, 0, 0));
		//$html2pdf->setDefaultFont('Arial');
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->addFont("sf");
		$html2pdf->addFont("sfsemi");
		//$html2pdf = new HTML2PDF('L','A4','es', false, 'utf-8', array(0, 0, 0, 0));
		$html2pdf->writeHTML($content_html, isset($_GET['vuehtml']));
//		$html2pdf->createIndex('Sommaire', 25, 12, false, true, 1);
		$html2pdf->Output('pdfs_mail/vendefacil_corte_'.$id_corte.'.pdf','P');
	}
	catch(HTML2PDF_exception $e) { echo $e; }

?>
