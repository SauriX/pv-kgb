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
	
	$sql ="SELECT mesa FROM ventas WHERE id_venta = $id_venta";
	$mesa = @mysql_result(mysql_query($sql),0);
?>
<div class="col-xs-12">
	<div class="col-xs-6" style="padding-left: 0px;">
		<a class="btn btn-primary btn-sm" role="button" onclick="recarga();"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar</a>
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
	        </tr>
	        <? } ?>
	        <tr>
	        	<td width="240"></td>
				<td width="70" align="right"></td>
				<td width="30" align="center"></td>
				<td width="70" align="right" id="consumo_total_mesa"><b><?=number_format($consumo_total,2)?></b></td>
	        </tr>
	    </tbody>
	</table>
	
	
						
	<div class="col-xs-12 text-center" style="padding-left: 0px;">
		<!--
<p>		<input type="text" class="form-control" style="display:none" value="" id="motivo_apertura" placeholder="Ingrese motivo de reapertura de mesa."/> </p>			
		 <a class="btn btn-default btn-sm" role="button" id="reabrir" style="display:none" onclick="reabrirMesa(<?=$id_venta?>);">Reabrir Mesa Ahora</a>
-->
			
		<div id="botones_accion">
				<a class="btn btn-default btn-sm" role="button" id="reimprimir" onclick="imprimir_cuenta(<?=$id_venta?>,'<?=$mesa?>');">Reimprimir</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				 <a class="btn btn-default btn-sm" role="button" onclick="reabrirMesa(<?=$id_venta?>);">Reabrir Mesa</a>
		</div>
	</div>
	
	
</div>
<? }else{ ?>
<div class="alert alert-warning" role="alert">La mesa que seleccionaste no tiene consumo.</div>
<? } ?>

<script>


	function imprimir_cuenta(id_venta,mesa){

		$.post('ac/cerrar_mesa.php','mesa='+mesa+'&id_venta='+id_venta+'&reimprime=1',function(data) {
				if(data==1){
					$('#content_verMesas').load('mesas.php');
				}else{
					//alert(data);
				}
		});

	}

	function pre_abrirMesa(){
		
		$('#botones_accion').hide();
		$('#reabrir').show();
		$('#motivo_apertura').show().focus();
	
		
	}

	function recarga(){
		$('#content_verMesasxCobrar').load('mesas_x_cobrar.php');
		
	}
	function reabrirMesa(){
		
		$('#motivo_apertura').hide();
		$('#reabrir').hide().after('Reabriendo..');
		$.post('ac/reabrir_mesa.php','id_venta=<?=$id_venta?>&motivo='+$('#motivo_apertura').val(),function(data) {
			
			if(data==1){
				
				alert('Mesa abierta con éxito');
				window.location = 'index.php';
			
			}else{
				alert('Error al abrir mesa. '+data);
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
</script>