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

$sql="SELECT productos.*, categorias.nombre AS categoria, existencia FROM productos
JOIN categorias ON categorias.id_categoria=productos.id_categoria
LEFT JOIN existencias ON existencias.id_producto=productos.id_producto
ORDER BY categoria,nombre ASC";
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

<page backtop="20mm" backbottom="10mm" backleft="0mm" backright="2mm" footer="page">

<page_header>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="580" align="center" valign="middle">
				<h4 class="no-margin">Existencias al día <?=fechaLetraTres($fecha_actual)?></h4>
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
			<th width="145" height="25" class="f11" style="padding-left: 5px;">Categoría</th>
			<th width="75" height="25" class="f11">Código</th>
			<th width="340" height="25" class="f11">Producto</th>
			<th width="75" height="25" class="f11">Existencias</th>
			<th width="125" height="25" class="f11" align="right" style="padding-right: 5px;">Precio</th>
		</tr>
	</thead>
	<tbody>
<? while($ft=mysql_fetch_assoc($query)){ 
	$counter++;
	$class="";
	if($counter%2){
		$class="even";
	}else{
		$class="odd";
	}
?>
    	<tr class="<?=$class?>" >
			<td width="145" height="20" style="padding-left: 5px;"><?=$ft['categoria']?></td>
		    <td width="75" height="20"><?=$ft['codigo']?></td>
		    <td width="340" height="20"><?=$ft['nombre']?></td>
			<td width="75" height="20"><?=fnum($existencia,1)?></td>
			<td width="125" height="20" align="right" style="padding-right: 5px;"><?=fnum($ft['precio_venta'])?></td>
		</tr>
<? } ?>
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