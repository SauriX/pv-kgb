<?
$id_venta=$_GET['id_venta'];
$mesa=$_GET['mesa'];

$facturado=mysql_result(mysql_query("SELECT facturado FROM ventas WHERE pagada=1 AND id_venta=$id_venta"),0);

$sq="SELECT * FROM venta_detalle 
JOIN productos ON productos.id_producto=venta_detalle.id_producto
WHERE id_venta=$id_venta";
$qu=mysql_query($sq);
$consumo_total=0;
$valida=mysql_num_rows($qu);

$conf="SELECT * FROM configuracion ";
$q_cconf = mysql_query($conf);
$n_cconf= mysql_fetch_assoc($q_cconf);
$reabrir = $n_cconf['pagada'];

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

	/*var message="NoRightClicking";
	function defeatIE() {if (document.all) {(message);return false;}}
	function defeatNS(e) {if 
	(document.layers||(document.getElementById&&!document.all)) {
	if (e.which==2||e.which==3) {(message);return false;}}}
	if (document.layers) 
	{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=defeatNS;}
	else{document.onmouseup=defeatNS;document.oncontextmenu=defeatIE;}
	document.oncontextmenu=new Function("return false")*/
	
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
			<div class="col-md-9"><h3 style="margin-top:6px;">CONSUMO <? if(is_numeric($mesa)){ echo 'MESA '.$mesa; }else{ echo $mesa; }?></h3> </div>
			<div class="col-md-3" style="text-align: right;">
			
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
			<div class="col-md-12" style="text-align: center;">
			<?if($reabrir=="1"){?>
				<a class="btn btn-default btn-sm" role="button" onclick="reabrirMesa(<?=$id_venta?>);">Reabrir Mesa</a>
			<?}?>
				<a type="button" class="btn btn-default" href="#" id="btn_cerrar_mesa" onclick="imprimir_cuenta(<?=$id_venta?>,'<?=$mesa?>');" >REIMPRIMIR</a>
				
			</div>
		</div>
		
		<? }else{ ?>
			<div class="alert alert-danger" role="alert">La mesa que seleccionaste ya no existe.</div>
		<? } ?>
	</div>
</div>
<script>

function refacturar(id_venta){

	$.post('ac/refacturar.php','id_venta='+id_venta,function(data){
		if(data==1){
			alert('Ya puede realizar su factura.');
			window.location = 'index.php?Modulo=NuevaFactura&id_venta='+id_venta;
		}else{
			alert('Error al refacturar, intente de nuevo o contacte a soporte.'+data);
		}

	});

}
function recarga(){
		$('#content_verMesas').load('mesas.php');
		
	}

function imprimir_cuenta(id_venta,mesa){

	$.post('ac/cobrar_pagar.php','&id_venta='+id_venta+'&reimprime=1',function(data) {
		if(data==1){
			window.open('?Modulo=VentaTouchCobradas&id_venta='+id_venta, '_self');
		}else{
			alert(data);
		}
	});

}

function reabrirMesa(){
		
		$('#motivo_apertura').hide();
		$('#reabrir').hide().after('Reabriendo..');
		$.post('ac/reabrir_mesa.php','id_venta=<?=$id_venta?>&motivo='+$('#motivo_apertura').val(),function(data) {
			
			if(data==1){
				
			//	alert('Mesa abierta con éxito');
			//	window.location = 'index.php';
			console.log(data);
			}else{
				alert('Error al abrir mesa. '+data);
			}
			
			
		
		});
}
	
</script>