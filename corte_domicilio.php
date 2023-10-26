<?

	$sql="SELECT id_repartidor,nombre FROM repartidores WHERE activo=1";
	$q=mysql_query($sql);
	while($dat=mysql_fetch_object($q)):
		$repartidores[] = $dat;
	endwhile;
	$valida_repartidores=count($repartidores);

?>

<div class="row">
	<!--
	<div class="col-md-12">

		<div class="bs-example" data-example-id="navbar-button">
			<nav class="navbar navbar-default">
				<div class="container-fluid">

					<a role="button" class="btn btn-warning navbar-btn" href="#" onclick="checarLlegada();">Reportar Entrega</a>

					<div class="collapse navbar-collapse navbar-right">
						<? if($_GET['Tipo']){ ?>
							<a role="button" class="btn btn-default navbar-btn" href="index.php?Modulo=Servicios">Servicios Pendientes</a>
						&nbsp&nbsp | &nbsp&nbsp
						<? } ?>
						<a role="button" class="btn btn-danger navbar-btn" href="index.php?Modulo=Servicios&Tipo=Facturar">Servicios por Facturar</a>&nbsp&nbsp
						<a role="button" class="btn btn-info navbar-btn" href="index.php?Modulo=Servicios&Tipo=Transito">Servicios en Tránsito</a>
						<? if($valida_servicios){ ?>
							&nbsp&nbsp&nbsp
							<a role="button" class="btn btn-danger navbar-btn" href="index.php?Modulo=Pedidos">Llevar un pedido</a>
						<? } ?>
					</div>
				</div>
			</nav>
		</div>
	</div>
	-->
	<div class="col-md-12">
		<? if($valida_repartidores){ ?>
			<div class="panel panel-info">

				<div class="panel-heading">
					<h3 class="panel-title">Repartidores</h3>
				</div>

				<div class="panel-body">
					<form id="frm-ventas" >
				  		<table class="table table-striped table-hover table-bordered">
							<thead>
						        <tr>
									<th>Repartidor</th>
									<th width="80">Pedidos</th>
									<th width="80">Monto</th>
									<th width="90"></th>
						        </tr>
							</thead>
							<tbody>
								<? foreach($repartidores as $repartidor){ ?>
									<tr class="tr_madre tr_madre_<?=$repartidor->id_repartidor?>">
										<td><?=$repartidor->nombre?></td>
										<td align="center" id="productos_<?=$repartidor->id_repartidor?>"></td>
										<td align="right" id="total_<?=$repartidor->id_repartidor?>"></td>
										<td align="right">
											<a role="button" class="btn btn-info btn-xs" href="#" onclick="muestraPedidos(<?=$repartidor->id_repartidor?>);">Resumen de pedidos</a>
										</td>
									</tr>
	<!-- Tabla emergente -->
									<?
									$id_repartidor=$repartidor->id_repartidor;
									$sql1="SELECT id_venta_domicilio,ventas_domicilio.id_domicilio_direccion, domicilio.nombre AS cliente, direccion, comentarios, ventas_domicilio_salidas.fechahora_salida, ventas_domicilio_salidas.fechahora_regreso,id_domicilio_salida,descuento_cantidad, descuento_porcentaje,fechahora_alta FROM ventas_domicilio
									LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion=ventas_domicilio.id_domicilio_direccion
									LEFT JOIN domicilio ON domicilio.id_domicilio=domicilio_direcciones.id_domicilio
									LEFT JOIN ventas_domicilio_salidas ON ventas_domicilio_salidas.id_venta_domicilio_salida=ventas_domicilio.id_domicilio_salida
									WHERE ventas_domicilio_salidas.id_repartidor=$id_repartidor AND ventas_domicilio.id_corte_domicilio=0";
									$q1=mysql_query($sql1);
									//$valida=mysql_num_rows($q1);
									//if(!$valida){ continue; }
									while($dat=mysql_fetch_object($q1)){

										$id_venta_domicilio=$dat->id_venta_domicilio;
										$descuento=$dat->descuento_cantidad;

										$sq="SELECT SUM(cantidad*precio_venta) AS total FROM venta_domicilio_detalle WHERE id_venta_domicilio=$id_venta_domicilio";
										$qu=mysql_query($sq);
										$dt=mysql_fetch_assoc($qu);

										$dat->hora_salida=horaVista(horaSinFecha($dat->fechahora_salida));
										$dat->hora_regreso=horaVista(horaSinFecha($dat->fechahora_regreso));

										$dat->hora=horaVista(horaSinFecha($dat->fechahora_salida));
										//$dat->transcurrido=substr($dat->fechahora_alta,11,8);
										$dat->total=$dt['total']-$descuento;

										$ventas[] = $dat;

										//echo json_encode($data);
									}
									?>
									<tr style="cursor:default;display:none;" class="trs tr_<?=$id_repartidor?>">
										<td colspan="7">
											<table class="table table-striped table-hover table-bordered">
												<thead>
													<tr class="info">
														<th width="100">F. Venta</th>
														<th width="100">F. Reparto</th>
														<th width="100">En Cocina</th>
														<th width="80">Salió</th>
														<th width="100">Regresó</th>
														<th width="120">Transcurrió</th>
														<th>Dirección</th>
														<th width="80" align="right">Monto</th>

													</tr>
												</thead>
												<tbody>
													<? foreach($ventas as $venta){ ?>
													<input type="hidden" name="ventas[]" class="ventas" value="<?=$venta->id_venta_domicilio?>" />
													<tr class="tr_servicio_<?=$venta->id_venta_domicilio?> pedidos">
													    <td><b>#<?=$venta->id_venta_domicilio?></b></td>
														<td>#<?=$venta->id_domicilio_salida?></td>
														<td><?=hace($venta->fechahora_alta,$venta->fechahora_salida)?></td>
													    <td><?=$venta->hora_salida?></td>
														<td><?=$venta->hora_regreso?></td>
													    <td><?=hace($venta->fechahora_salida,$venta->fechahora_regreso)?></td>
													    <td><?=$venta->direccion?></td>
													    <td align="right"><?=number_format($venta->total,2)?></td>

													</tr>
													<?
														$billete_total+=$venta->total;
													}
													$productos=count($ventas);
													?>
												</tbody>
											</table>
											<button type="button" onclick="javascript:cerrarDetalle();" class="btn btn-warning btn-xs" style="float:right;">Cerrar Pestaña</button>
										</td>
									</tr>
	<!-- Termina tabla emergente -->
								<script>
									$(function(){
										$('#total_<?=$id_repartidor?>').html("<?=number_format($billete_total,2)?>");
										$('#productos_<?=$id_repartidor?>').html(<?=$productos?>);

										var productos = Number(<?=$productos?>);
										if(productos==0){
											$('.tr_madre_<?=$id_repartidor?>').hide();
										}


									});
								</script>
								<? unset($ventas);
								unset($billete_total);
								 } ?>
							</tbody>
						</table>
					</form>
				</div>

				<div class="panel-footer" style="text-align:right;">
					<button type="button" class="btn btn-success btn_ac" onclick="llevarPedido()" id="btn-procesa" data-loading-text="Procesando corte...">Hacer Corte</button>
				</div>

			</div>

			<? }else{ ?>
				<div class="alert alert-info" role="alert">No hay pedidos en tránsito :/</div>
			<? } ?>

	</div>
</div>





<script>
function muestraPedidos(id){
	$('.trs').hide();
	$('.tr_'+id).show();
	$('.tr_madre').removeClass('info');
	$('.tr_madre_'+id).addClass('info');
}

function cerrarDetalle(){
	$('.trs').hide();
	$('.tr_madre').removeClass('info');
}

function llevarPedido(){
	$('#btn-procesa').button('loading');
	$('.btn-elimina').prop("disabled", true);
	var pedidos = $('.ventas').length;

	if(pedidos<=1){
		var msg = "la venta?";
	}else{
		var msg = "las "+pedidos+" ventas?";
	}
	swal({
		title: "Corte de Ventas",
		text: "¿Estas seguro de hacer el corte por "+msg,
		icon: "warning",
		buttons: true,
    	buttons: ['No, Cancelar', 'Si, hacer Corte']
	})
	.then((willDelete) => {
		if(willDelete){
			var datos = $('#frm-ventas').serialize();
			$.post('ac/corte_servicio_domicilio.php',datos,function(data){
				if(data==1){
					swal("El se ha hecho", {
		      			icon: "success",
					}).then(function(result){
    					if(result){
        					setTimeout(function(){
            					window.open("?Modulo=Servicios", "_self");
        					}, 500);
    					}
					});
				}else{
					swal(data, {
		      			icon: "error",
					}).then(function(result){
    					if(result){
        					setTimeout(function(){
            					$('#agrega_venta').focus();
								$('#btn-procesa').button('reset');
								$('.btn-elimina').removeAttr("disabled");
        					}, 500);
    					}
					});
				}
			});
		}else{
			swal("Operación Cancelada", {
				icon: "info",
			}).then(function(result){
				if(result){
					setTimeout(function(){
						$('#agrega_venta').focus();
						$('#btn-procesa').button('reset');
						$('.btn-elimina').removeAttr("disabled");
					}, 500);
				}
			});
		}
	});
}
</script>
