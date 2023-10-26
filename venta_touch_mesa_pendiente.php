<?
$id_venta=$_GET['id_venta'];
$mesa=$_GET['mesa'];
$sq="SELECT * FROM venta_detalle 
JOIN productos ON productos.id_producto=venta_detalle.id_producto
WHERE id_venta=$id_venta";
$qu=mysql_query($sq);
$consumo_total=0;
$valida=mysql_num_rows($qu);
?>
<hr>
<script>
	function killCopy(e){
		return false
	}
	function reEnable(){
		return true
	}
	
	document.onselectstart=new Function ("return false")
	
	if (window.sidebar){
		document.onmousedown=killCopy
		document.onclick=reEnable
	}

	var message="NoRightClicking";
	function defeatIE() {if (document.all) {(message);return false;}}
	function defeatNS(e) {if 
	(document.layers||(document.getElementById&&!document.all)) {
	if (e.which==2||e.which==3) {(message);return false;}}}
	if (document.layers) 
	{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=defeatNS;}
	else{document.onmouseup=defeatNS;document.oncontextmenu=defeatIE;}
	document.oncontextmenu=new Function("return false")
	
</script>
<div class="row">
	<div class="col-md-3">
	<a type="button" class="btn btn-default btn-lg btn-block" href="index.php">VENTA</a><br>
		<a type="button" class="btn btn-primary btn-lg btn-block" href="?Modulo=VentaTouch" >MESAS</a><br>
		<a type="button" class="btn btn-success btn-lg btn-block" href="?Modulo=VentaTouchCobradas" >MESAS COBRADAS</a><br>
		<a type="button" class="btn btn-default btn-lg btn-block" href="?Modulo=VentaTouchCorte" >CORTE</a>
	</div>
	
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-9"><h3 style="margin-top:6px;">CONSUMO <? if(is_numeric($mesa)){ echo 'MESA '.$mesa; }else{ echo $mesa; }?> <small style="color: red;">** POR COBRAR</small></h3> </div>
			<div class="col-md-3" style="text-align: right;">
				<a type="button" class="btn btn-success" href="?Modulo=VentaTouchCobro&id_venta=<?=$id_venta?>&mesa=<?=$mesa?>" id="btn_cerrar_mesa" >COBRAR MESA</a>
			</div>
		</div>
		<hr>
		<?if($valida){?>
		<table class="table table-striped" style="font-size: 18px;">
			<thead>
				<tr>
					<th>Producto</th>
					<th style="text-align: right" width="80">Precio</th>
					<th style="text-align: center" width="50">Cantidad</th>
					<th style="text-align: right" width="80">Total</th>
					<th style="text-align: right" width="80">Total</th>
				</tr>
			</thead>
			
			<tbody>
				<? while($dat=mysql_fetch_assoc($qu)){
					//Sacamos los productos
					$consumo_total+=$dat['cantidad']*$dat['precio_venta'];
				?>
				<tr id="detalle_<?=$dat['id_detalle']?>">
					<td><?=$dat['nombre']?></td>
					<td align="right"><?=number_format($dat['precio_venta'],2)?></td>
					<td align="center"><?=$dat['cantidad']?></td>
					<td align="right"><?=number_format($dat['cantidad']*$dat['precio_venta'],2)?></td>
				</tr>
				<? } ?>
				
				<!-- Total -->
				<tr>
					<td></td>
					<td align="right"></td>
					<td align="right">TOTAL: </td>
					<td align="right" id="consumo_total_mesa"><strong><?=number_format($consumo_total,2)?></strong></td>
				</tr>
			</tbody>
		</table>
		<hr>
		<div class="row">
			<div class="col-md-6" style="text-align: right;">
				<a type="button" class="btn btn-default" href="#" id="btn_cerrar_mesa" onclick="imprimir_cuenta(<?=$id_venta?>,'<?=$mesa?>');" >REIMPRIMIR</a>
			</div>
			<?
			$sql = "SELECT * FROM ventas WHERE id_venta = $id_venta AND pagada = 1";
			$q = mysql_query($sql);
			$hay = mysql_num_rows($q);
			if(!$hay){
			?>
			<div class="col-md-6">
				<a type="button" class="btn btn-default" href="#" id="btn_cerrar_mesa" onclick="reabrirMesa(<?=$id_venta?>);" >REABRIR MESA</a>
			</div>
			<?
			}
			?>
		</div>
		
		<? }else{ ?>
			<div class="alert alert-danger" role="alert">La mesa que seleccionaste ya no existe.</div>
		<? } ?>
	</div>
</div>



<script>
function imprimir_cuenta(id_venta,mesa){

	$.post('ac/cerrar_mesa.php','mesa='+mesa+'&id_venta='+id_venta+'&reimprime=1',function(data) {
			if(data==1){
				window.open("?Modulo=VentaTouch", "_self");
			}else{
				alert(data);
			}
	});

}
function reabrirMesa(){
	$.post('ac/reabrir_mesa.php','id_venta=<?=$id_venta?>',function(data) {
		if(data==1){
			window.open("?Modulo=VentaTouch&id_venta=<?=$id_venta?>", "_self");
		}else{
			alert('Error al abrir mesa. '+data);
		}
	});
}
</script>