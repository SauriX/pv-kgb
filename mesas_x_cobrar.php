<?
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("includes/db.php");
$sql="SELECT * FROM ventas WHERE abierta = 0 AND pagada = 0 AND id_corte = 0";
$q=mysql_query($sql);
$valida=mysql_num_rows($q);
?>
	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-muted2" id="tituloGeneral1">Mesas Pendientes por Cobrar</h4>
	</div>

	<div class="modal-body">
<? if($valida){ ?>
	<div id="dashboarMesas1">
<!-- Boton para cerrar mesa -->
		<div id="sub-menu">
			<form class="form-horizontal" onsubmit="return false;">
				<div class="form-group has-primary">
					<div class="col-xs-6">
						<div class="input-group">
							<input class="form-control" type="text" id="mesa_x_cobrar" name="mesa_x_cobrar" maxlength="10" placeholder="Cerrar mesa" >
							<span class="input-group-btn">
								<button class="btn btn-primary" onclick="cobrar_mesa();" type="button">Cobrar mesa</button>
							</span>
						</div>

					</div>
				</div>
			</form>

			<hr>
		</div>
<!-- Muestra Mesas -->
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover ">
				    <thead>
				        <tr>
				        	<th width="170">Mesa</th>
							<th width="80" align="right" style="text-align: right;">Consumo</th>
							<th width="150" align="right" style="text-align: right;">Hora Cerrada</th>
							<th width="130" align="right"></th>
				        </tr>
				    </thead>
				    <tbody style="font-size: 15px;">
					    <? while($ft=mysql_fetch_assoc($q)){
						    $id_venta=$ft['id_venta'];
						    $mesa=$ft['mesa'];
						    $sq="SELECT * FROM venta_detalle WHERE id_venta=$id_venta";
						    $qu=mysql_query($sq);
						    $consumo_total=0;
						    while($dat=mysql_fetch_assoc($qu)){
						    	//Sacamos los productos
						    	$consumo_total+=$dat['cantidad']*$dat['precio_venta'];
						    }
					    ?>
				        <tr style="cursor: pointer;">
				        	<td onclick="verConsumo(<?=$id_venta?>,'<?=$mesa?>')"><?
					        	if($mesa!="BARRA"){
						        	 echo 'Mesa '.$mesa;
						       }else{
							       echo $mesa;
						       }
					        	?></td>
							<td onclick="verConsumo(<?=$id_venta?>,'<?=$mesa?>')" align="right">$<?=number_format($consumo_total,2)?></td>
							<td onclick="verConsumo(<?=$id_venta?>,'<?=$mesa?>')" align="right"><?

								$d = explode(' ', $ft['fechahora_cerrada']);
								echo  substr($d[1],0,5);


								?></td>

<td style="text-align: right;cursor: default">

<a class="btn btn-primary btn-xs rojos" id="<?=$mesa?>" href="#pagarMesa" monto ="<?=$consumo_total?>" mesa="<?=$mesa?>" mesa-x-pagar-id="<?=$id_venta?>" role="button" data-toggle="modal" data-dismiss="modal">Cobrar mesa</a>

</td>


				        </tr>
				        <? } ?>
				    </tbody>
				</table>

			</div>
		</div>
	</div><!-- end dashboard mesas -->
<? }else{ ?>
<div class="alert alert-warning" role="alert">Aún no tienes mesas abiertas.</div>
<? } ?>
<!-- Muestra descripción de la mesa	-->
		<div class="row oculto" id="muestraDetalleMesa1">

		</div>
<!-- Termina todo -->
	</div>



<script>

$(function(){

	$('#mesa_x_cobrar').alphanumeric();

	$('#mesa_x_cobrar').keyup(function() {
		var yo = $(this).val();
		$(this).val(yo.toUpperCase());

	});

	$('#mesa_x_cobrar').focus();
	$('.rojos').focus(function() {

		$(this).removeClass('btn-primary').addClass('btn-danger');


	});

	$('.rojos').hover(function() {

		$('.rojos').removeClass('btn-danger').addClass('btn-primary');
		$('#mesa_x_cobrar').focus();

	});

	$('#mesa_x_cobrar').keyup(function(e) {

		if(e.keyCode==13){
			cobrar_mesa();
		}


	});

	$('#mesa').focus();
	$('#btn-regresa').click(function() {
		$('#tituloGeneral').html("Mesas Pendientes por Cobrar");
		$('#muestraDetalleMesa').hide();
		$('#dashboarMesas').show();
	});

	$(document).on('click', '[mesa-x-pagar-id]', function () {

		    var id_venta = $(this).attr('mesa-x-pagar-id');
			var monto = $(this).attr('monto');
			var mesa = $(this).attr('mesa');

			$('#pagar_mesa_titulo').html('Cobrar mesa '+mesa);
			$('#recibe_txt,#consumo_txt,#cambio_txt,#numero_cuenta,#monto_facturado').val('');
			$('#monto_factura_div').hide();
			$('#req_factura,#id_metodo_pago').val('0');
			$('#consumo_txt').val(Number(monto).toFixed(2));
			$('#id_venta_cobrar').val(id_venta);
			$('#cobrar_final').html('Cobrar').removeClass('btn-danger').addClass('btn-success');
			pagarOriginal = Number(monto).toFixed(2);
			document.getElementById("consumo_original_txt").value = pagarOriginal;

	});

});

function verConsumo(id_venta,mesa){
	$('#tituloGeneral1').html("Detalle de Mesa por Cobrar "+mesa);
	$('#muestraDetalleMesa1').load("data/detalle_mesa_pagada.php?id_venta="+id_venta);
	$('#dashboarMesas1').hide();
	$('#muestraDetalleMesa1').show();
}


function cobrar_mesa(){
	var mesa = $('#mesa_x_cobrar').val();

	$('#'+mesa).focus();


}

</script>
