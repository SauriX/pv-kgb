<?
include("../includes/db.php");
$id_venta=$_GET['id_venta'];
$sq="SELECT venta_detalle.*, productos.nombre FROM venta_detalle 
JOIN productos ON productos.id_producto=venta_detalle.id_producto
WHERE id_venta=$id_venta";
$qu=mysql_query($sq);
$consumo_total=0;
$valida=mysql_num_rows($qu);
if($valida){
?>
<div class="col-xs-12">
	<div class="col-xs-6" style="padding-left: 0px;">
		<a class="btn btn-warning btn-sm" role="button" onclick="recarga();"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar</a>
	</div>
	<div class="col-xs-6">
		<h3 style="text-align: right; margin-top: 0px;color: black;" id="tituloMesa"></h3>
	</div>
	<br>
	<hr>

	<table class="table table-striped table-hover ">
	    <thead>
	        <tr>
	        	<th width="240">Producto</th>
	        	<th width="70" align="right" style="text-align: right;">Precio</th>
	        	<th width="30" align="center" style="text-align: center;">Cantidad</th>
	        	<th width="70" align="right" style="text-align: right;">Precio</th>
				<th width="40" align="right"></th>
	        </tr>
	    </thead>
	    <tbody style="font-size: 15px;">
		    <? while($dat=mysql_fetch_assoc($qu)){
			//Sacamos los productos
	 		$consumo_total+=$dat['cantidad']*$dat['precio_venta'];
	 		?>
	        <tr id="detalle_<?=$dat['id_detalle']?>">
	        	<td width="240"><?=$dat['nombre']?></td>
				<td width="70" align="right"><?=number_format($dat['precio_venta'],2)?></td>
				<td width="30" align="center"><?=$dat['cantidad']?></td>
				<td width="70" align="right"><?=number_format($dat['cantidad']*$dat['precio_venta'],2)?></td>
				<td width="40" align="right">
					<a class="btn btn-default btn-xs" href="#" role="button" onclick="eliminar_detalle(<?=$dat['id_detalle']?>);">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</a>
				</td>
	        </tr>
	        <? } ?>
	        <tr>
	        	<td width="240"></td>
				<td width="70" align="right"></td>
				<td width="30" align="center"></td>
				<td width="70" align="right" id="consumo_total_mesa"><b><?=number_format($consumo_total,2)?></b></td>
				<td width="40" align="right"></td>
	        </tr>
	    </tbody>
	</table>
	
	<div class="col-xs-12 text-center" style="padding-left: 0px;">

<p class="text-center">
<center>
<input type="text" class="form-control solo_numero" style="display:none;width: 43%" value="" id="nuevo_numero" placeholder="Ingrese nuevo número de mesa."/> 
</center>
</p>
		 <a class="btn btn-default btn-sm nuevo_num" role="button" id="cambiar_num" style="display:none" onclick="ejecutar_cambio();">Cambiar</a>

			
		<div id="botones_accion row">
				<a class="btn btn-default btn-sm" style="float: left;" role="button" id="cambiar_numero_mesa" onclick="imprimir(<?=$id_venta?>);">Reimprimir Comandas</a></div>
				<a class="btn btn-default btn-sm" role="button" id="cambiar_numero_mesa" onclick="cambiar_numero_mesa();">Cambiar Número de Mesa</a></div>
	</div>
	
	
	
</div>
<? }else{ ?>
<div class="alert alert-danger" role="alert">La mesa que seleccionaste ya no existe.</div>
<? } ?>


<script>
	
$(function() {

	$('#nuevo_numero').alphanumeric();
	

	$('#nuevo_numero').keyup(function(e) {
		var yo = $(this).val();
		$(this).val(yo.toUpperCase());
		
		if(e.keyCode==13){
			ejecutar_cambio();
		}
	});
});
	
	function ejecutar_cambio(){
		var nuevo_numero = $('#nuevo_numero').val();
		$.post('ac/cambiar_mesa.php','mesa_deseada='+nuevo_numero+'&id_venta=<?=$id_venta?>',function(data) {
		
			if(data==1){
 				alert('Número de mesa cambiado con éxito');
				recarga();
			}else{
				alert(data);
			}
			
		
		});
		
	}

	function imprimir(id){
		
		$.post('includes/reimprimir_comanda.php','id_venta=<?=$id_venta?>',function(data) {
		
			
				//window.open("?Modulo=VentaDomicilio", "_self");
			
			
		
		});
		
	}
	
	function cambiar_numero_mesa(){
		$('#nuevo_numero,#cambiar_num').show();
		$('#cambiar_numero_mesa').hide();
		$('#nuevo_numero').focus();
	}
	function recarga(){
		$('#content_verMesas').load('mesas.php');
		
	}
	
	function eliminar_detalle(id){
		
		$('#detalle_'+id).hide();
		$.post('ac/elimina_detalle.php','id_detalle='+id,function(data) {

			if(data==1){
				$.post('ac/consumo_x_mesa.php','id_venta=<?=$id_venta?>',function(num) {
					$('#consumo_total_mesa').html('<b>'+num+'</b>');
				});
			}else{
				alert('Error: '+data);
			}
			
		
		});
		
	}
</script>