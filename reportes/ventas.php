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
	$sql="SELECT * FROM ventas
	JOIN usuarios ON usuarios.id_usuario=ventas.id_usuario
	JOIN metodo_pago ON metodo_pago.id_metodo=ventas.id_metodo
	WHERE pagada=1 AND fecha BETWEEN '$fecha1' AND '$fecha2'";
	$titulo="Reporte de Ventas del día ".fechaLetra($fecha1)." al ".fechaLetra($fecha2);
}else{
	$sql="SELECT * FROM ventas
	JOIN usuarios ON usuarios.id_usuario=ventas.id_usuario
	JOIN metodo_pago ON metodo_pago.id_metodo=ventas.id_metodo
	WHERE fecha='$fecha1'";
	$titulo="Reporte de Ventas del día ".fechaLetraTres($fecha1);
}
$query=mysql_query($sql);
$counter="";


/*
$servidor2="tacoloco.mx";
$usuario2="digmastu_epic";
$clave2="media";
$base2="digmastu_facturacion";
$conexion2 = @mysql_connect ($servidor2,$usuario2,$clave2) or die ("Error en conexi&oacute;n.");
@mysql_select_db($base2) or die ("No BD");
*/


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


<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11">
	<thead>
    	<tr class="titulos">
			<th width="100" height="25" class="f11" style="padding-left: 5px;">Fecha</th>
			<th width="100" height="25" class="f11" style="padding-left: 5px;" align="center">Hora</th>
			<th width="175" height="25" class="f11" align="center">Método de pago</th>
			<th width="125" height="25" class="f11" style="padding-left: 5px;" align="center">Ticket</th>
			<th width="150" height="25" class="f11" align="right" style="padding-right: 5px;">Monto Cuenta</th>
			<!--<th width="85" height="25" class="f11">Mesa</th>
			<th width="155" height="25" class="f11">Cobró</th>
			<th width="50" height="25" class="f11" style="padding-left: 2px;">Factura</th>
			<th width="90" height="25" class="f11" style="padding-left: 5px;">Comprobante</th>
			<th width="100" height="25" class="f11" align="right">Monto Factura</th>-->
		</tr>
	</thead>
	<tbody>
<? while($ft=mysql_fetch_assoc($query)){
	$total_facturado+=$ft['monto_facturado'];
	$total_ticket+=$ft['monto_pagado'];
	$totalDescuento+=$ft['DescEfec_txt'];
$counter++;
$class="";
if($counter%2){
  $class="even";
}else{
  $class="odd";
}
?>
    	<tr class="<?=$class?>">
			<td  height="20" style="padding-left: 5px;"><?=fechaLetraDos($ft['fecha'])?></td>
		    <td  height="20" align="center"><?=$ft['hora']?></td>
		    <td  height="20" align="center"><?=$ft['metodo_pago']?></td>
		    <td  height="20" align="center"><?=$ft['id_venta']?></td>
			<td  height="20" align="right" style="padding-right: 5px;"> $ <?=number_format($ft['monto_pagado'],2)?></td>
		    <!--<td width="85" height="20">Mesa <?=$ft['mesa']?></td>
		    <td width="155" height="20"><?=$ft['nombre']?></td>
		    <td width="50" height="20" align="right"><?=$ft['nombre']?></td>
		    <td width="90" height="20"><? if($ft['facturado']==1){ echo "Factura"; }else{ echo "Ticket"; }?></td>
			<td width="100" height="20" align="right"><? if($ft['facturado']==1){ echo number_format($ft['monto_facturado'],2); }?></td>-->
		</tr>
<? } ?>
		<tr class="odd">
			<td height="20"></td>
			<td height="20"></td>
			<td height="20"></td>
			<td height="20" style="padding-left: 5px;">SUBTOTAL:</td>
			<td height="20" align="right" class="titulos" style="padding-right: 5px;"> $ <?=number_format($total_ticket,2)?></td>
		</tr>
		<tr class="odd">
			<td height="20"></td>
			<td height="20"></td>
			<td height="20"></td>
			<td height="20" style="padding-left: 5px;">DESCUENTOS:</td>
			<td height="20" align="right" class="titulos" style="padding-right: 5px;"> $ <?=number_format($totalDescuento,2)?></td>
		</tr>
		<tr class="odd">
			<td height="20"></td>
			<td height="20"></td>
			<td height="20"></td>
			<td height="20" style="padding-left: 5px;">Total:</td>
			<td height="20" align="right" class="titulos" style="padding-right: 5px;"> $ <?=number_format($total_ticket-$totalDescuento,2)?></td>
		</tr>
	</tbody>
</table>


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
