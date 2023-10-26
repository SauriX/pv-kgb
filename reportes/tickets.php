<? 
	error_reporting(0);
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
set_time_limit(0); 

$sql = "SELECT fecha,mesa,id_venta FROM ventas WHERE fecha = '2017-07-30'";
$q = mysql_query($sql);


while($ft = mysql_fetch_assoc($q)):

	$sql = "
	SELECT venta_detalle.cantidad,venta_detalle.precio_venta,productos.nombre 
	FROM venta_detalle 
	JOIN productos ON venta_detalle.id_producto = productos.id_producto 
	WHERE id_venta =  ".$ft['id_venta'];

	$qx = mysql_query($sql);
	
	unset($multi);
	
	while($fx = mysql_fetch_assoc($qx)):
	
		$multi+= $fx['cantidad']*$fx['precio_venta'];
	
	endwhile;
		
	$totales+=$multi;
	echo $ft['id_venta'].' - FECHA: '.$ft['fecha']." - TOTAL: ".$multi.' - MESA: '.$ft['mesa'].'<hr>';



endwhile;

echo $totales;
/*
exit;
ob_start();
$datos=mysql_fetch_assoc(mysql_query("SELECT * FROM configuracion"));
$establecimiento=$datos['establecimiento'];
$rfc=$datos['rfc'];
$direccion=$datos['direccion'];
extract($_POST);

$fecha1 = '2017-07-30';

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
	
	
	$sql = "SELECT*FROM ventas WHERE id_corte = $id_corte";
	$q = mysql_query($sql);
	while($ft = mysql_fetch_assoc($q)){
		
	
		switch($ft['id_metodo']){
			case '1':
			 $efectivo+= $ft['monto_pagado'];
			 break;
			case '2':
			 $tarjeta+= $ft['monto_pagado'];
			 break;
			case '3':
			 $transf+= $ft['monto_pagado'];
			 break;
			case '4':
			 $cheque+= $ft['monto_pagado'];
			 break;
			case '5':
			 $noide+= $ft['monto_pagado'];
			 break;		
			
		}
		
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
?>	
		<tr class="odd">
			<td width="450" height="20" style="padding-left: 5px;" class="borde-top"><b>DESGLOSE</b></td>
		    <td width="55" height="20" align="center" class="borde-top"></td>
			<td width="130" height="20" align="right" class="titulos"><b>TOTAL:</b></td>
			<td width="125" height="20" align="right" class="titulos"><b><?=number_format($g_total,2)?></b></td>
		</tr>
		<tr class="odd">
			<td width="450" height="20" style="padding-left: 5px;"> EFECTIVO: <b><?=number_format($efectivo,2)?></b></td>
		    <td width="55" height="20" align="center"></td>
			<td width="130" height="20" align="right">MONTO PRE-FACTURADO: <b><?=number_format($pre_fact_monto_por,2)?></b></td>
			<td width="125" height="20" align="right"></td>
		</tr>
		<tr class="odd">
			<td width="450" height="20" style="padding-left: 5px;">TARJETA: <b><?=number_format($tarjeta,2)?></b></td>
		    <td width="55" height="20" align="center"></td>
			<td width="130" height="20" align="right">MONTO NO FACTURADO: <b><?=number_format($no_fact_monto_por,2)?></b></td>
			<td width="125" height="20" align="right"></td>
		</tr>
		<tr class="odd">
			<td width="450" height="20" style="padding-left: 5px;"> CHEQUE: <b><?=number_format($cheque,2)?></b></td>
		    <td width="55" height="20" align="center"></td>
			<td width="130" height="20" align="right"></td>
			<td width="125" height="20" align="right"></td>
		</tr>
		<tr class="odd">
			<td width="450" height="20" style="padding-left: 5px;"> TRANSFERENCIA: <b><?=number_format($transf,2)?></b></td>
		    <td width="55" height="20" align="center"></td>
			<td width="130" height="20" align="right"></td>
			<td width="125" height="20" align="right"></td>
		</tr>
		<tr class="odd">
			<td width="450" height="20" style="padding-left: 5px;"> NO IDENTIFICADO: <b><?=number_format($noide,2)?></b></td>
		    <td width="55" height="20" align="center"></td>
			<td width="130" height="20" align="right"></td>
			<td width="125" height="20" align="right"></td>
		</tr>
	</tbody>
</table>
<? } ?>

</page>


<?php
	exit;
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
*/
?>