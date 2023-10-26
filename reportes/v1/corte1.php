<? //require_once('html2pdfv4/html2pdf.class.php');
/*ob_start();*/
include("../includes/db.php");
$con=mysql_query("SELECT*FROM configuracion") or die (mysql_error());
$array=mysql_fetch_array($con);
$fecha=$_POST['calendario1'];

$sql="SELECT cortes.*, usuarios.usuario as user FROM cortes 
JOIN usuarios ON usuarios.id_usuario = cortes.id_usuario
WHERE fecha = '$fecha'";
$q = mysql_query($sql) or die(mysql_error());


function fechaTexto($fecha){ 
list($anio,$mes,$dia)=explode("-",$fecha); 
switch($mes){
case 1:$mest="Enero";break;
case 2:$mest="Febrero";break;
case 3:$mest="Marzo";break;
case 4:$mest="Abril";break;
case 5:$mest="Mayo";break;
case 6:$mest="Junio";break;
case 7:$mest="Julio";break;
case 8:$mest="Agosto";break;
case 9:$mest="Septiembre";break;
case 10:$mest="Octubre";break;
case 11:$mest="Noviembre";break;
case 12:$mest="Diciembre";break;
}
return $dia." de ".$mest." del ".$anio; 
} 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte de Cortes</title>
<style type="text/css">
body { font-family:"Arial", Helvetica, sans-serif; }
.lista_info{ font-size: 11px;padding-left: 10px; }
.lista_info2{ font-size: 14px; }
</style>
</head>
<body>
<table width="730">
	<tr>	
		<td width="530" align="center" valign="middle">
		<b>Reporte de Cortes del d&iacute;a <?=fechaTexto($fecha);?></b><br/>
		<?=$array['establecimiento']?> RFC: <?=$array['rfc']?><br/>
		<?=$array['direccion']?><br/>
		</td>
		<td width="200"><img src="../logo.png" width="205" height="35" /></td>
	</tr>
</table>
<?  while($ft=mysql_fetch_assoc($q)){
	
	$id_venta = $ft['id_venta_inicio'];
	$id_venta2 = $ft['id_venta_final'];
	$id_cancelacion = $ft['id_cancelacion_inicio'];
	$id_cancelacion2 = $ft['id_cancelacion_final'];
	$user = $ft['user'];
	$monto = $ft['monto'];
	$totales +=$monto;
	$hora = $ft['hora'];  ?>
<table width="730" bgcolor="#e6e6e6">
	<tr>
		<td width="350" align="left" valign="middle" height="22" class="lista_info2"><b>Corte a las <?=$hora?></b></td>
		<td width="100" align="left" valign="middle" height="22" class="lista_info2"><b>por: <?=$user?></b></td>
		<td width="100" align="left" valign="middle" height="22" class="lista_info2"><b></b></td>
		<td width="30" align="left" valign="middle" height="22" class="lista_info2"></td>
		<td width="150" align="right" valign="middle" height="22" class="lista_info2"><b>Subtotal: <?=number_format($monto,2)?></b></td>	
	</tr>
</table>
<table width="730">
	<tr>
		<td width="350" align="left" valign="middle" height="15" class="lista_info"><b>Nombre del producto</b></td>
		<td width="100" align="left" valign="middle" height="15" class="lista_info"><b></b></td>
		<td width="100" align="left" valign="middle" height="15" class="lista_info"><b></b></td>
		<td width="30" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="150" align="right" valign="middle" height="15" class="lista_info"><b>Cantidad</b></td>
	</tr>
</table>
<table width="730">
	<tr>
		<td width="730" align="center" valign="middle" height="15" class="lista_info"><b>VENTAS</b></td>
	</tr>
</table>
<?  $sql="SELECT venta_detalle.*,productos.nombre as prodnombre FROM venta_detalle 
	JOIN productos ON productos.id_producto = venta_detalle.id_producto
	WHERE (id_venta BETWEEN $id_venta AND $id_venta2) AND venta_detalle.activo=1";
	$qx = mysql_query($sql);
	
	while($fx=mysql_fetch_assoc($qx)){
		$producto[$fx['id_producto']] += $fx['cantidad'];
		$prod[$fx['id_producto']] = $fx['prodnombre'];
	}
	foreach($producto as $id => $cant){ ?>
<table width="730" style="border-bottom: #e6e6e6 1px solid;">
	<tr>
		<td width="350" align="left" class="lista_info"><?=$prod[$id]?></td>
		<td width="100" align="left" class="lista_info"><? // Mostrar Cantidad de vendidos ?></td>
		<td width="100" align="left" class="lista_info"><? // Mostrar Precio unitario ?></td>
		<td width="30" align="left" class="lista_info"></td>
		<td width="150" align="right" class="lista_info"><?=number_format($cant)?></td>
	</tr>
</table>
<? } ?>
<table width="730">
	<tr>
		<td width="730" align="center" valign="middle" height="15" class="lista_info"><b>CANCELACIONES</b></td>
	</tr>
</table>
<?
 unset($producto);
 unset($prod);
 $sql="SELECT cancelaciones_detalle.*,productos.nombre as prodnombre FROM cancelaciones_detalle 
	JOIN productos ON productos.id_producto = cancelaciones_detalle.id_producto
	WHERE (id_cancelacion BETWEEN $id_cancelacion AND $id_cancelacion)";
	$qx = mysql_query($sql);

	while($fx=mysql_fetch_assoc($qx)){
		$producto[$fx['id_producto']] += $fx['cantidad'];
		$prod[$fx['id_producto']] = $fx['prodnombre'];
	}
	foreach($producto as $id => $cant){ ?>
<table width="730" style="border-bottom: #e6e6e6 1px solid;">
	<tr>
		<td width="350" align="left" class="lista_info"><?=$prod[$id]?></td>
		<td width="100" align="left" class="lista_info"><? // Mostrar Cantidad de vendidos ?></td>
		<td width="100" align="left" class="lista_info"><? // Mostrar Precio unitario ?></td>
		<td width="30" align="left" class="lista_info"></td>
		<td width="150" align="right" class="lista_info"><?=number_format($cant)?></td>
	</tr>
</table>
<? } ?>
<?
	unset($producto);
	unset($prod); 
}
?>

<!-- Imprime total de lo que vendio el usuario -->
<table width="730" bgcolor="#e6e6e6">
	<tr>
		<td width="350" align="left" valign="middle" height="15" ></td>
		<td width="100" align="left" valign="middle" height="15"></td>
		<td width="30" align="left" valign="middle" height="15" ></td>
		<td width="170" align="right" valign="middle" height="15" class="lista_info2"><b>Monto de Cortes:</b></td>
		<td width="80" align="right" valign="middle" height="15" class="lista_info2"><b><?=number_format($totales,2)?></b></td>
	</tr>
</table>
</body>
</html>
<?
/*
$html=ob_get_contents(); 
ob_end_clean(); 
$pdf = new HTML2PDF('P','Letter','es',array(7, 0, 5, 10)); 
$pdf->pdf->SetDisplayMode('fullpage');
$pdf->WriteHTML($html); 
$pdf->Output('doc.pdf','I');
*/
?>