<?
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("includes/db.php");
$sql="SELECT * FROM ventas
JOIN metodo_pago ON ventas.id_metodo = metodo_pago.id_metodo
WHERE abierta = 0 AND pagada = 1 AND id_corte = 0 ORDER BY fechahora_pagada DESC";
$q=mysql_query($sql);
$valida=mysql_num_rows($q);
?>
	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-muted2" id="tituloGeneral2">Mesas Cobradas</h4>
	</div>

	<div class="modal-body">
<? if($valida){ ?>
	<div id="dashboarMesas2">
<!-- Muestra Mesas -->
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover ">
				    <thead>
				        <tr>
				        	<th width="100">Mesa</th>
							<th width="" align="right" style="text-align: right;">Consumo</th>
							<th width="" align="right" style="text-align: right;">Hora Cobrada</th>
							<th width="" align="right" style="text-align: right;">Pago</th>
							<th width="" align="right" style="text-align: right;">Facturado</th>
							<th width="" align="right" style="text-align: right;">Reabierta</th>


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
				        <tr style="cursor: pointer;" onclick="verConsumo(<?=$id_venta?>,'<?=$mesa?>')">
				        	<td><?
					        	if($mesa!="BARRA"){
						        	 echo 'Mesa '.$mesa;
						       }else{
							       echo $mesa;
						       }
					        	?></td>
							<td align="right">$<?=number_format($consumo_total,2)?></td>


							<td align="right"><?

								$d = explode(' ', $ft['fechahora_pagada']);
								echo  substr($d[1],0,5);


								?></td>


							<td  align="right"><?

								echo $ft['metodo_pago'];


								?></td>


							<td  align="right"><?

								if($ft['facturado']){
									echo 'Si';
								}else{
									echo 'No';
								}


								?></td>

							<td  align="right"><?

								if($ft['reabierta']){
									echo 'Si';
								}else{
									echo 'No';
								}


								?></td>



				        </tr>
				        <? } ?>
				    </tbody>
				</table>

			</div>
		</div>
	</div><!-- end dashboard mesas -->
<? }else{ ?>
<div class="alert alert-warning" role="alert">Aún no tienes mesas cobradas.</div>
<? } ?>
<!-- Muestra descripción de la mesa	-->
		<div class="row oculto" id="muestraDetalleMesa2">

		</div>
<!-- Termina todo -->
	</div>



<script>
function verConsumo(id_venta,mesa){
	$('#tituloGeneral2').html("Detalle de Mesa Cobrada "+mesa);
	$('#muestraDetalleMesa2').load("data/detalle_mesa_cobrada.php?id_venta="+id_venta);
	$('#dashboarMesas2').hide();
	$('#muestraDetalleMesa2').show();
}

</script>