<?php

$id_corte =	$_GET['id_corte'];

?>
<p><span>Total General: <span id="total_general"></span></span></p>
<p><span>Total Facturado: <span id="total_fact"></span></span></p>
<p><span>Total Tarjetas y Transferencias: <span id="total_tarjetas"></span></span></p>
<p><b><span>Total No Facturado en Efectivo: <span id="total_nofact"></span></span></b></p>
<p><b><span style="color:#156d20">Monto Seleccionado: <span id="total_seleccionado">0.00</span></span></b></p>
<p><b><span style="color:#a50202">Monto a Eliminar: <span id="total_eliminar">0.00</span></span></b></p>

<div class="botones">
<input type="button" id="doit" value="Filtro 1" onclick="dale(2);" />
<input type="button" id="doit" value="Filtro 2" onclick="dale(3);"/>
<input type="button" id="doit" value="Filtro 3" onclick="dale(4);"/>
<input type="button" id="doit" value="Filtro 4" onclick="dale(5);"/>
<input type="button" id="doit" value="Filtro 5" onclick="dale(6);"/>
<input type="button" id="doit" value="Filtro 6" onclick="dale(7);"/>
<input type="button" id="mostrar" style="display: none" value="Mostrar Seleccionados" onclick="limpiar();"/>
<input type="button" id="guardar" style="display: none" value="Guardar" onclick="guardar();"/>
<br>
<br>
<span style="color: red;">Las ventas seleccionadas permanecerán, el resto será eliminado.</span>
</div>
<span id="mensaje" style="display:none">Guardando...</span>
<br/><br/>
<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Tickets del Corte #<?=$id_corte?></h3>
				</div>
				<div class="panel-body">
					<?
						if($_GET['id_corte']){
							$sql="SELECT ventas.*,metodo_pago.metodo_pago FROM ventas
							JOIN metodo_pago ON metodo_pago.id_metodo = ventas.id_metodo
							 WHERE id_corte=$id_corte  ORDER BY id_venta ASC";
							$q= mysql_query($sql);
							$n= mysql_num_rows($q);
					 ?>
					 <form id="form_datos">
						 <input type="hidden" name="id_corte" id="id_corte" value="<?=$id_corte?>"/>
					<table class="table table-striped" id="tbl-tickets">
						<thead>
							<tr>
								<th>Folio</th>
								<th>Mesa</th>
								<th>Monto</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Facturado</th>
								<th>Método de Pago</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
						#	$inicio = 'checked="true"';
							#$nodar = '_nodar';
							while($ft=mysql_fetch_assoc($q)){

								$total_general+=$ft['monto_pagado'];

								if($ft['facturado']==1):
									$total_facturado+=$ft['monto_pagado'];
								endif;

								if($ft['id_metodo']!=1):
									$total_tarjetas+=$ft['monto_pagado'];
								endif;

								if( ($ft['id_metodo']==1) && ($ft['facturado']==0) ):
									$tick = 'tickets_item';
									$disa = '';
									$co = '';
									$paso = 1;
									$total_nofact+=$ft['monto_pagado'];

								else:
									$tick = '';
									$disa = 'disabled="true"';
									$co = 'color:lightgrey';
								endif;

								if($co):
									$cheq = 'chequeado soyotro';
								else:
									$cheq = '';
								endif;
							?>
							<tr style="<?=$co?>" id="tr_<?=$ft['id_venta']?>" class="trs <?=$cheq?>">
								<td><?=$ft['id_venta']?></td>
								<td><?=$ft['mesa']?></td>
								<td><?=$ft['monto_pagado']?></td>
								<td><?=fechaLetra($ft['fecha'])?></td>
								<td><?=horaVista($ft['hora'])?></td>
								<td><? if(!$co){
											echo 'NO';
									   }else{
										   echo '- - -';
									   }
									?>
								</td>
								<td><?=$ft['metodo_pago']?></td>
								<td>
								<? if(!$co): ?>
									<input type="checkbox" <?=$disa?> value="<?=$ft['id_venta']?>" name="tickets[]" <?=$inicio?> id="seleccionar" id-venta="<?=$ft['id_venta']?>" monto="<?=$ft['monto_pagado']?>" class="form-control sumar <?=$tick?><?=$nodar?>" onclick="sumarTodo();">
								<? else: ?>
									<input type="checkbox" checked="true" name="tickets[]" value="<?=$ft['id_venta']?>" class="nodeschecar form-control">
								<? endif; ?>
								</td>
							</tr>
						<?
								if($paso):
									$inicio = '';
									$nodar = '';
								endif;
							}


						?>
						</tbody>

					</table>
					 </form>
					<? } ?>

				</div>
			</div>

</div>
<br/><br/>


<script>

$(function() {

	$('.sumar').click(function() {

		var id = $(this).attr('id-venta');

		if($(this).is(":checked")){
			$('#tr_'+id).addClass('chequeado');
		}else{
			$('#tr_'+id).removeClass('chequeado');
		}

	});

	$('.nodeschecar').click(function(e) {
		if (!$(this).is(":checked")) {
			e.preventDefault();
			return false;
		}
	});

	$('#guardar').click(function() {

		$('.botones').hide();
		$('#mensaje').html('<b>Guardando...</b>').show();
		var datos = $('#form_datos').serialize();
		$.post('ac/corte_procesa.php', datos, function(data) {
			if(data==1){
				alert('HECHO');
				window.open("?Modulo=CortesTicket", "_self");
			}else{
				$('#mensaje').html('<b>ERROR (contacte a soporte): '+data+'.</b>');
			}
		});

	});

	$('#total_general').html('<?=number_format($total_general,2)?>');
	$('#total_fact').html('<?=number_format($total_facturado,2)?>');
	$('#total_nofact').html('<?=number_format($total_nofact,2)?>');
	$('#total_tarjetas').html('<?=number_format($total_tarjetas,2)?>');

});

function limpiar(){

	$('.trs').each(function() {

		if(!$(this).hasClass('chequeado')){
			$(this).hide();
		}

	});

	$('#guardar').show();

}

function dale(num){
			$('#guardar').hide();
			$('#mostrar').show();
			$('.tickets_item').prop('checked',false);
			var redondeo = Number(num);
			var cuenta = Number(num);


	$('.trs').each(function() {

		$(this).show();

		if(!$(this).hasClass('soyotro')){
			$(this).removeClass('chequeado');
		}

	});

			$('.tickets_item').each(function() {
				if(!$(this).is(':checked')){
					if(cuenta==redondeo){
						$(this).prop('checked',true);
						var id_venta = $(this).attr('id-venta');
						$('#tr_'+id_venta).addClass('chequeado');
						cuenta = 1;
					}else{
						cuenta++;
					}
				}else{
						var id_venta = $(this).attr('id-venta');
						$('#tr_'+id_venta).removeClass('chequeado');
				}
			});
			sumarTodo();

}

function sumarTodo(){

	var sumaTotal = 0;
	$('.sumar').each(function() {

		if($(this).is(':checked')){
			var monto = $(this).attr('monto');
			sumaTotal+=Number(monto);
		}
	});

	$('#total_seleccionado').html(addCommas(sumaTotal));
	var resta = Number(<?=$total_nofact?>)-Number(sumaTotal);
	$('#total_eliminar').html(addCommas(resta));
}

function addCommas(nStr){
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

</script>
