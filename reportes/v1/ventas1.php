<? include("../includes/db.php");
$fecha=$_POST['calendario1'];
$con=mysql_query("SELECT*FROM configuracion") or die (mysql_error());
$array=mysql_fetch_array($con);
//require_once('html2pdfv4/html2pdf.class.php');
//ob_start();

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
<title>Reporte de Ventas</title>
<style type="text/css">
body { font-family:"Arial", Helvetica, sans-serif; }
.lista_info{ font-size: 11px; }
.lista_info2{ font-size: 14px; }
.titulos{ font-size: 16px; }
</style>
</head>
<body>
<table width="730">
	<tr>	
		<td width="530" align="center" valign="middle">
		<b>Reporte de Ventas del <?=fechaTexto($fecha)?></b><br/>
		<?=$array['establecimiento']?> RFC: <?=$array['rfc']?><br/>
		<?=$array['direccion']?><br/>
		</td>
		<td width="200"><img src="../logo.png" width="205" height="35" /></td>
	</tr>
</table>
<table width="730">
	<tr>
		<td width="730" align="left" valign="middle" height="20" class="titulos"><b>Ventas</b></td>
	</tr>
</table>
<table width="730" bgcolor="#e6e6e6">
	<tr>
		<td width="350" align="left" valign="middle" height="15" class="lista_info"><b>Nombre del producto</b></td>
		<td width="100" align="right" valign="middle" height="15" class="lista_info"><b>Cantidad</b></td>
		<td width="100" align="right" valign="middle" height="15" class="lista_info"><b>Precio/Unitario</b></td>
		<td width="30" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="150" align="right" valign="middle" height="15" class="lista_info"><b>Total</b></td>
	</tr>
</table>
<? $query2=mysql_query("SELECT productos.id_producto,productos.nombre,venta_detalle.precio_venta,venta_detalle.cantidad FROM ventas
						JOIN venta_detalle ON venta_detalle.id_venta=ventas.id_venta
						JOIN productos ON venta_detalle.id_producto=productos.id_producto
						WHERE ventas.activo='1' AND ventas.fecha='$fecha'");
	while($ft2=mysql_fetch_assoc($query2)){  

		$producto[$ft2['id_producto']] += $ft2['cantidad'];
		$prod[$ft2['id_producto']] = $ft2['nombre'];
		$precio[$ft2['id_producto']] = $ft2['precio_venta'];
}
foreach($producto as $id => $cant){ 
?>
<table width="730" style="border-bottom: #e6e6e6 1px solid;">
	<tr>
		<td width="350" align="left" class="lista_info" ><?=$prod[$id]?></td>
		<td width="100" align="right" class="lista_info" ><?=$cant?></td>
		<td width="100" align="right" class="lista_info" ><?=number_format($precio[$id],2)?></td>		
		<? $total_venta=$precio[$id]*$cant;
			$total_totales+=$total_venta;
		?>
		<td width="30" align="left" class="lista_info" ></td>
		<td width="150" align="right" class="lista_info" ><b><?=number_format($total_venta,2)?></b></td>
	</tr>
</table>
<? } ?>
<!-- Imprime totales -->
<table width="730" bgcolor="#e6e6e6">
	<tr>
		<td width="350" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="100" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="30" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="170" align="right" valign="middle" height="15" class="lista_info2"><b>Total:</b></td>
		<td width="80" align="right" valign="middle" height="15" class="lista_info2"><b><?=number_format($total_totales,2)?></b></td>
	</tr>
</table>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table width="730">
	<tr>
		<td width="730" align="left" valign="middle" height="20" class="titulos"><b>Devoluciones</b></td>
	</tr>
</table>
<table width="730" bgcolor="#e6e6e6">
	<tr>
		<td width="350" align="left" valign="middle" height="15" class="lista_info"><b>Nombre del producto</b></td>
		<td width="100" align="right" valign="middle" height="15" class="lista_info"><b>Cantidad</b></td>
		<td width="100" align="right" valign="middle" height="15" class="lista_info"><b>Precio/Unitario</b></td>
		<td width="30" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="150" align="right" valign="middle" height="15" class="lista_info"><b>Total</b></td>
	</tr>
</table>
<? 
unset($producto);
unset($prod);
unset($precio);
unset($total_totales);
$query3=mysql_query("SELECT productos.id_producto,productos.nombre,cancelaciones_detalle.precio_venta,cancelaciones_detalle.cantidad FROM cancelaciones
						JOIN cancelaciones_detalle ON cancelaciones_detalle.id_cancelacion=cancelaciones.id_cancelacion
						JOIN productos ON cancelaciones_detalle.id_producto=productos.id_producto
						WHERE cancelaciones.activo='1' AND cancelaciones.fecha='$fecha'");
	while($ft3=mysql_fetch_assoc($query3)){  

		$producto[$ft3['id_producto']] += $ft3['cantidad'];
		$prod[$ft3['id_producto']] = $ft3['nombre'];
		$precio[$ft3['id_producto']] = $ft3['precio_venta'];
}
foreach($producto as $id => $cant){ 
?>
<table width="730" style="border-bottom: #e6e6e6 1px solid;">
	<tr>
		<td width="350" align="left" class="lista_info" ><?=$prod[$id]?></td>
		<td width="100" align="right" class="lista_info" ><?=$cant?></td>
		<td width="100" align="right" class="lista_info" ><?=number_format($precio[$id],2)?></td>		
		<? $total_venta=$precio[$id]*$cant;
			$total_totales+=$total_venta;
		?>
		<td width="30" align="left" class="lista_info" ></td>
		<td width="150" align="right" class="lista_info" ><b><?=number_format($total_venta,2)?></b></td>
	</tr>
</table>
<? } ?>
<!-- Imprime totales -->
<table width="730" bgcolor="#e6e6e6">
	<tr>
		<td width="350" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="100" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="30" align="left" valign="middle" height="15" class="lista_info"></td>
		<td width="170" align="right" valign="middle" height="15" class="lista_info2"><b>Total:</b></td>
		<td width="80" align="right" valign="middle" height="15" class="lista_info2"><b><?=number_format($total_totales,2)?></b></td>
	</tr>
</table>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table width="200">
	<tr>
		<td width="200" align="center" valign="middle" height="30" class="lista_info2" >Ventas por usuario</td>
	</tr>
</table>
<table width="200" >
<?php
$sqlx="SELECT usuarios.usuario,SUM(venta_detalle.cantidad*venta_detalle.precio_venta) as monto FROM ventas
JOIN venta_detalle ON venta_detalle.id_venta = ventas.id_venta
JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
WHERE ventas.fecha = '$fecha'
GROUP BY ventas.id_usuario";
$qx = mysql_query($sqlx);
while($ftx=mysql_fetch_assoc($qx)){ ?>
	<tr>
		<td width="130" align="left" valign="middle" height="15" class="lista_info"><b><?=$ftx['usuario']?></b></td>
		<td width="120" align="right" valign="middle" height="15" class="lista_info"><b><?=number_format($ftx['monto'],2)?></b></td>
	</tr>
<? } ?>
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