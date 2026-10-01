<?
include("../includes/db.php");
$id_venta=$_GET['id_venta'];
$sq="SELECT venta_detalle.*, productos.nombre FROM venta_detalle 
JOIN productos ON productos.id_producto=venta_detalle.id_producto
WHERE id_venta=$id_venta
ORDER BY venta_detalle.id_detalle ASC";
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
	        	<th width="220">Producto</th>
	        	<th width="70" align="right" style="text-align: right;">Precio</th>
	        	<th width="30" align="center" style="text-align: center;">Cantidad</th>
	        	<th width="70" align="right" style="text-align: right;">Importe</th>
				<th width="90" align="right"></th>
	        </tr>
	    </thead>
	    <tbody style="font-size: 15px;" id="detalle_mesa_items">
		    <? while($dat=mysql_fetch_assoc($qu)){
			$tiene_descuento = strpos($dat['comentarios'], '[[DESC100]]') !== false;
			$consumo_total+=$dat['cantidad']*$dat['precio_venta'];
	 		?>
	        <tr id="detalle_<?=$dat['id_detalle']?>" <? if($tiene_descuento){ ?>style="background-color:#fcf8e3;"<? } ?>>
	        	<td width="220">
	        		<?=$dat['nombre']?>
	        		<span id="m_<?=$dat['id_detalle']?>" class="label label-warning" <? if(!$tiene_descuento){ ?>style="display:none;"<? } ?>>CORTESIA</span>
	        	</td>
				<td width="70" align="right" id="u_<?=$dat['id_detalle']?>"><?=number_format($dat['precio_venta'],2)?></td>
				<td width="30" align="center"><?=$dat['cantidad']?></td>
				<td width="70" align="right" id="p_<?=$dat['id_detalle']?>"><?=number_format($dat['cantidad']*$dat['precio_venta'],2)?></td>
				<td width="90" align="right">
					<button type="button" class="btn btn-warning btn-xs" id="d_<?=$dat['id_detalle']?>" onclick="toggle_descuento_detalle(<?=$dat['id_detalle']?>); return false;"><?=$tiene_descuento ? 'Restaurar' : '100%'?></button>
					<a class="btn btn-default btn-xs" href="#" role="button" onclick="eliminar_detalle(<?=$dat['id_detalle']?>); return false;">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</a>
				</td>
	        </tr>
	        <? } ?>
	        <tr>
	        	<td width="220"></td>
				<td width="70" align="right"></td>
				<td width="30" align="center"></td>
				<td width="70" align="right" id="consumo_total_mesa"><b><?=number_format($consumo_total,2)?></b></td>
				<td width="90" align="right"></td>
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
				<a class="btn btn-danger btn-sm" role="button" onclick="eliminar_mesa_completa(<?=$id_venta?>);">Eliminar Mesa Completa</a></div>
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
		Printer.imprimirComandas(id, true).catch(function(error) {
			alert(error.message);
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

	function toggle_descuento_detalle(id_detalle){
		$.post('ac/toggle_descuento_detalle.php', { id_detalle: id_detalle }, function(data) {
			var response = data;
			if(typeof data === 'string'){
				try {
					response = JSON.parse(data);
				} catch (e) {
					alert('Error al aplicar descuento.');
					return;
				}
			}

			if(!response.ok){
				alert(response.error || 'No se pudo aplicar el descuento.');
				return;
			}

			var fila = $('#detalle_'+id_detalle);
			$('#u_'+id_detalle).html(Number(response.precio).toFixed(2));
			$('#p_'+id_detalle).html(Number(response.subtotal).toFixed(2));
			$('#d_'+id_detalle).html(response.btn);
			$('#consumo_total_mesa').html('<b>'+Number(response.total).toFixed(2)+'</b>');

			if(Number(response.descuento) === 1){
				fila.css('background-color', '#fcf8e3');
				$('#m_'+id_detalle).show();
			}else{
				fila.css('background-color', '');
				$('#m_'+id_detalle).hide();
			}
		});
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

	function eliminar_mesa_completa(id_venta){
		if(!confirm('Se eliminaran todos los productos de la mesa. \n\nDeseas continuar?')){
			return;
		}

		$.post('ac/elimina_mesa_detalles.php','id_venta='+id_venta,function(data){
			data = $.trim(data);
			if(data==1){
				alert('La mesa se eliminó correctamente.');
				setTimeout(function(){
					recarga();
				}, 500);
			}else{
				alert('Error: '+data);
			}
		});
	}
</script>
