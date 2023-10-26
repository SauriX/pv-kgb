<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
set_time_limit(0);
ob_start();
$datos=mysql_fetch_assoc(mysql_query("SELECT * FROM configuracion"));
$establecimiento=$datos['establecimiento'];
$rfc=$datos['rfc'];
$direccion=$datos['direccion'];
extract($_POST);
if(!$fecha1) exit("Seleccione al menos una fecha.");

if($fecha2){
	$sql="SELECT * FROM cortes
	JOIN usuarios ON usuarios.id_usuario=cortes.id_usuario
	WHERE fecha BETWEEN '$fecha1' AND '$fecha2'";
	$titulo="Reporte de Cortes del día ".fechaLetra($fecha1)." al ".fechaLetra($fecha2);
}else{
	$sql="SELECT * FROM cortes
	JOIN usuarios ON usuarios.id_usuario=cortes.id_usuario
	WHERE fecha='$fecha1'";

	$titulo="Reporte de Cortes del día ".fechaLetraTres($fecha1);
}
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
			<td width="780" style="padding-top: 10px;padding-bottom: 16px;"><b>KGB grill</b> Punto de venta avanzado. <b>digmastudio.com/vendefacil</b></td>
		</tr>
	</table>
</page_footer>
<? while($ft=mysql_fetch_assoc($query)){
	$id_corte=$ft['id_corte'];
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
?>
<h4 class="titulo_corte">Corte <? if($fecha2){ echo fechaLetraTres($ft['fecha'])." "; }?>a las <?=horaVista($ft['hora'])?> <br><small>Usuario: <?=$ft['nombre']?></small></h4>
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
$id_venta_inicio=$dat['venta_inicio'];
$id_venta_final=$dat['venta_final'];
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

		  $counter++;
		  $class="";
		  if($counter%2){
		  	$class="even";
		  }else{
		  	$class="odd";
		  }

?>
    	<tr class="<?=$class?>">
			<td width="450" height="20" style="padding-left: 5px;"><?=$producto?></td>
		    <td width="55" height="20" align="center"><?=$cantidad?></td>
			<td width="130" height="20" align="right"><?=$precio?></td>
			<td width="125" height="20" align="right"><?=$total?></td>
		</tr>
<? }

//Método de pago y etc
$sqlCorte = "SELECT efectivoCaja,tpv,otrosMet FROM cortes WHERE id_corte = $id_corte";
$qCorte = mysql_query($sqlCorte);
//$montoCapturado = 0;
while($datosCorte = mysql_fetch_assoc($qCorte)){

	$capturaEfec = $datosCorte['efectivoCaja'];
	$montoCapturado = floatval($montoCapturado)+floatval($capturaEfec);

	$capturaTpv = $datosCorte['tpv'];
	$montoCapturado = floatval($montoCapturado)+floatval($capturaTpv);

	$capturaOtrosMet = $datosCorte['otrosMet'];
	$montoCapturado = floatval($montoCapturado)+floatval($capturaOtrosMet);
}

$sql ="SELECT SUM(DescEfec_txt) AS total FROM ventas WHERE id_corte = $id_corte GROUP BY id_corte";
$sqlDescuento = mysql_fetch_assoc(mysql_query($sql));
$totalDescuento = $sqlDescuento['total'];


$sqlMetodos = "SELECT id_metodo,metodo_pago FROM metodo_pago";
$qMetodos = mysql_query($sqlMetodos);
while($data_metodos = mysql_fetch_assoc($qMetodos)){
	$me[$data_metodos['id_metodo']] = $data_metodos['metodo_pago'];
}
$sql = "SELECT * FROM ventas WHERE id_corte = $id_corte";
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

	$promedio = $total_totales/$cta_expedidas;
	$mesas_por = ($mesas_ct/$cta_expedidas)*100;
	$barra_por = ($barra_ct/$cta_expedidas)*100;
	$pre_fact_por = ($pre_fact_ct/$cta_expedidas)*100;
	$no_fact_por = ($no_fact_ct/$cta_expedidas)*100;

	$pre_fact_monto_por = ($pre_fact_monto/$total_totales)*100;
	$no_fact_monto_por = ($no_fact_monto/$total_totales)*100;

	$mesas_monto_por = ($mesas_monto/$total_totales)*100;
	$barra_monto_por = ($barra_monto/$total_totales)*100;
	$montoVenta = 0;
?>
	<tr class="odd">
		<td width="450" height="20" style="padding-left: 5px;" class="borde-top"><b>DESGLOSE</b></td>
			<td width="55" height="20" align="center" class="borde-top"></td>
		<td width="130" height="20" align="right" class="titulos"><b>TOTAL:</b></td>
		<td width="125" height="20" align="right" class="titulos"><b><?=number_format($g_total,2)?></b></td>
	</tr>
	<?php foreach($montos_metodo as $id_m => $monto){
		$montoVenta = $montoVenta+$monto;
		?>
		<?php if ($me[$id_m] == 'EFECTIVO'){ ?>
			<tr class="odd">
					<td width="450" height="20" style="padding-left: 5px;">VENTA <?= $me[$id_m] ?>: <b><?=number_format($monto,2)?></b></td>
					<td width="55" height="20" align="center"></td>
					<td width="130" height="20" align="right" class="titulos"><b>DESCUENTOS:</b></td>
					<td width="125" height="20" align="right" class="titulos"><b><?=number_format($totalDescuento,2)?></b></td>
			</tr>
		<?php }else{ ?>
			<tr class="odd">
					<td width="450" height="20" style="padding-left: 5px;"> <?= $me[$id_m] ?>: <b><?=number_format($monto,2)?></b></td>
					<td width="55" height="20" align="center"></td>
			</tr>
		<?php } ?>
	<?php
				}
				unset($montos_metodo);
	?>
	<tr class="odd">
		<td width="130" height="20" align="left">SUB TOTAL VENTA: <b><?= number_format($montoVenta,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr class="odd">
		<td width="130" height="20" align="left">SUB TOTAL VENTA: <b><?= number_format($totalDescuento,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr class="odd">
		<td width="450" height="20" style="padding-left: 5px;">EFECTIVO CAPTURADO: <b> <?= number_format($capturaEfec,2) ?> </b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr class="odd">
		<td width="130" height="20" align="left">TPV CAPTURADO: <b><?= number_format($capturaTpv,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr>
		<td width="130" height="20" align="left">OTROS METODOS CAPTURADO: <b><?= number_format($capturaOtrosMet,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr class="odd">
		<td width="130" height="20" align="left">SUB TOTAL CAPTURADO: <b><?= number_format($montoCapturado,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<?php

	$sql ="SELECT * FROM gastos WHERE id_corte = $id_corte";
	$qq1 = mysql_query($sql);
	$count12 = mysql_num_rows($qq1);
	$g_total_g = 0;
	if ($count12 > 0) {
		while($fx = mysql_fetch_assoc($qq1)){
			$monto23 = $fx['monto'];
			$g_total_g+=$monto23;
		}
	}

		$ENCAJA = $montoVenta-$g_total_g;
		$ENCAJA2 = $montoCapturado-$g_total_g;
		$ventaDetalle = "";
		$restaDetalle = $ENCAJA2-$ENCAJA;
		if ($restaDetalle > 0) {
			$ventaDetalle = "SOBRANTE: $<b>".number_format($restaDetalle,2)."</b>";
		}elseif ($restaDetalle < 0) {
			$ventaDetalle = "FALTANTE: $<b>".number_format($restaDetalle,2)."</b>";
		}else {
			$ventaDetalle = "DIFERENCIA: $<b>".number_format($restaDetalle,2)."</b>";
		}
	?>
	<tr class="odd">
		<td width="130" height="20" align="left">TOTAL GASTOS: <b><?= number_format($g_total_g,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr class="odd">
		<td width="130" height="20" align="left">TOTAL DESCUENTOS: <b><?= number_format($totalDescuento,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr class="odd">
		<td width="130" height="20" align="left">TOTAL VENTAS: <b><?= number_format($ENCAJA,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr class="odd">
		<td width="130" height="20" align="left">TOTAL CAPTURADO: <b><?= number_format($ENCAJA2,2) ?></b></td>
		<td width="55" height="20" align="center"></td>
	</tr>
	<tr class="odd">
		<td width="450" height="20" style="padding-left: 5px;"><?= $ventaDetalle ?></td>
		<td width="55" height="20" align="center"></td>
	</tr>
</tbody>
</table>
<?php
unset($ventaDetalle);
unset($ENCAJA2);
unset($ENCAJA);
unset($g_total_g);
unset($restaDetalle);

unset($g_total);
unset($montoVenta);
unset($montoCapturado);
unset($capturaTpv);
unset($montoVenta);
unset($montoCapturado);
?>
<? } ?>

</page>


<?php
	$content_html = ob_get_clean();

	// initialisation de HTML2PDF
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
		$html2pdf->Output('Corte_de_Caja.pdf');
	}
	catch(HTML2PDF_exception $e) { echo $e; }

?>
