<? include("../includes/db.php");
set_time_limit(0);
$con=mysql_query("SELECT*FROM configuracion") or die (mysql_error());
$array=mysql_fetch_array($con);
/*require_once('html2pdfv4/html2pdf.class.php');
ob_start();
*/
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
<meta content="text/html;" http-equiv="content-type" charset="utf-8" />
<title>Reporte de Existencias</title>
<style type="text/css">
body { font-family:"Arial", Helvetica, sans-serif; }
.lista_info{ font-size: 11px; }
</style>
</head>
<body>
<table width="730">
	<tr>	
		<td width="530" align="center" valign="middle">
		<b>Reporte de Existencias del d&iacute;a <?=fechaTexto(date("Y-m-d"))?></b><br/>
		<?=$array['establecimiento']?> RFC: <?=$array['rfc']?><br/>
		<?=$array['direccion']?><br/>
		</td>
		<td width="200"><img src="../logo.png" width="205" height="35" /></td>
	</tr>
</table>
<table width="730" bgcolor="#e6e6e6">
	<tr>
		<td width="300" align="left" class="lista_info" valign="middle" height="15" class="lista_info"><b>Nombre del producto</b></td>
		<td width="100" align="left" class="lista_info" valign="middle" height="15" class="lista_info"><b>C&oacute;digo</b></td>
		<td width="80" align="left" class="lista_info" valign="middle" height="15" class="lista_info"></td>
		<td width="160" align="left" class="lista_info" valign="middle" height="15" class="lista_info"></td>
		<td width="90" align="right" class="lista_info" valign="middle" height="15" class="lista_info"><b>Existencia</b></td>
	</tr>
</table>
<?  $query2=mysql_query("SELECT productos.nombre,productos.codigo, existencias.existencia,categorias.nombre as cat FROM productos
						JOIN existencias ON productos.id_producto=existencias.id_producto
						JOIN categorias ON categorias.id_categoria = productos.id_categoria
						WHERE productos.activo='1' ORDER BY productos.id_categoria ASC",$conexion);
	while($ft2=mysql_fetch_assoc($query2)){  ?>
<table width="730" style="border-bottom: #e6e6e6 1px solid;">
	<tr>
		<td width="300" align="left" class="lista_info" ><?=$ft2['nombre']?></td>
		<td width="100" align="left" class="lista_info" ><?=$ft2['codigo']?></td>
		<td width="80" align="left" class="lista_info" ></td>
		<td width="160" align="left" class="lista_info" ><?=$ft2['cat']?></td>
		<td width="90" align="right" class="lista_info" ><?=number_format($ft2['existencia'])?></td>
	</tr>
</table>
<? } ?>





</body>
</html>
<?
/*$html=ob_get_contents(); 
ob_end_clean(); 
$pdf = new HTML2PDF('P','Letter','es',array(7, 0, 5, 10)); 
$pdf->pdf->SetDisplayMode('fullpage');
$pdf->WriteHTML($html); 
$pdf->Output('doc.pdf','I');
*/
?>