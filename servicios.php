<?

if(!$_GET['Tipo']){
	$sql="SELECT id_venta_domicilio,ventas_domicilio.id_domicilio_direccion, fechahora_alta, usuarios.nombre, domicilio.nombre AS cliente, domicilio_direcciones.direccion, comentarios, descuento_cantidad, descuento_porcentaje FROM ventas_domicilio
	LEFT JOIN usuarios ON usuarios.id_usuario=ventas_domicilio.id_usuario
	LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion=ventas_domicilio.id_domicilio_direccion
	LEFT JOIN domicilio ON domicilio.id_domicilio=domicilio_direcciones.id_domicilio
	WHERE ventas_domicilio.id_domicilio_salida=0 AND cancelado !=1  AND ventas_domicilio.id_corte_domicilio=0";
	$q=mysql_query($sql);
	$valida_servicios=mysql_num_rows($q);
}

if($_GET['Tipo']=="Cancelados"){

	$sql="SELECT id_venta_domicilio, fechahora_alta, id_domicilio_salida, fechahora_cancelacion, canceladores.nombre as cancelador, usuarios.nombre, domicilio.nombre AS cliente, domicilio_direcciones.direccion, motivo_cancelacion, descuento_cantidad, descuento_porcentaje FROM ventas_domicilio
	LEFT JOIN usuarios ON usuarios.id_usuario=ventas_domicilio.id_usuario_cancelo
	LEFT JOIN usuarios as canceladores ON usuarios.id_usuario=ventas_domicilio.id_usuario
	LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion=ventas_domicilio.id_domicilio_direccion
	LEFT JOIN domicilio ON domicilio.id_domicilio=domicilio_direcciones.id_domicilio
	WHERE cancelado=1 AND entregado = 0 AND ventas_domicilio.id_corte_domicilio=0";
	$q=mysql_query($sql);
	$valida_servicios_cancelados=@mysql_num_rows($q);
}

if($_GET['Tipo']=="Entregados"){

	$sql="SELECT id_venta_domicilio, fechahora_alta, comentarios, id_domicilio_salida, fechahora_cancelacion, canceladores.nombre as cancelador, usuarios.nombre, domicilio.nombre AS cliente, domicilio_direcciones.direccion, motivo_cancelacion, descuento_cantidad, descuento_porcentaje FROM ventas_domicilio
	LEFT JOIN usuarios ON usuarios.id_usuario=ventas_domicilio.id_usuario_cancelo
	LEFT JOIN usuarios as canceladores ON usuarios.id_usuario=ventas_domicilio.id_usuario
	LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion=ventas_domicilio.id_domicilio_direccion
	LEFT JOIN domicilio ON domicilio.id_domicilio=domicilio_direcciones.id_domicilio
	WHERE cancelado=1 AND entregado = 1 AND ventas_domicilio.id_corte_domicilio=0";
	$q=mysql_query($sql);
	$valida_servicios_entregados=@mysql_num_rows($q);
}


if($_GET['Tipo']=="Transito"){
	/*$sql="SELECT id_venta_domicilio_salida,ventas_domicilio_salidas.id_repartidor,fechahora_salida,nombre FROM ventas_domicilio_salidas
	LEFT JOIN repartidores ON repartidores.id_repartidor=ventas_domicilio_salidas.id_repartidor
	WHERE  cancelado !=1 AND ventas_domicilio_salidas.fechahora_regreso IS NULL";*/
	$sql="SELECT id_domicilio_direccion,id_venta_domicilio_salida,ventas_domicilio_salidas.id_repartidor,fechahora_salida,nombre,fechahora_alta FROM ventas_domicilio
	JOIN ventas_domicilio_salidas ON ventas_domicilio_salidas.id_venta_domicilio_salida=ventas_domicilio.id_domicilio_salida
	LEFT JOIN repartidores ON repartidores.id_repartidor=ventas_domicilio_salidas.id_repartidor
	WHERE cancelado !=1 AND ventas_domicilio.id_domicilio_salida !=0 AND ventas_domicilio_salidas.fechahora_regreso IS NULL GROUP BY ventas_domicilio_salidas.id_venta_domicilio_salida";
	$q=mysql_query($sql);
	while($dat=mysql_fetch_object($q)):
		$datos[] = $dat;
	endwhile;
	$valida_servicios_transito=count($datos);
}

if($_GET['Tipo']=="Facturar"){
	$sql="SELECT id_venta_domicilio,ventas_domicilio.id_domicilio_direccion, fechahora_alta, usuarios.nombre, domicilio.nombre AS cliente, domicilio_direcciones.direccion, repartidores.nombre AS repartidor, ventas_domicilio_salidas.fechahora_regreso,descuento_cantidad, descuento_porcentaje FROM ventas_domicilio
	LEFT JOIN usuarios ON usuarios.id_usuario=ventas_domicilio.id_usuario
	LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion=ventas_domicilio.id_domicilio_direccion
	LEFT JOIN domicilio ON domicilio.id_domicilio=domicilio_direcciones.id_domicilio
	LEFT JOIN ventas_domicilio_salidas ON ventas_domicilio_salidas.id_venta_domicilio_salida=ventas_domicilio.id_domicilio_salida
	LEFT JOIN repartidores ON repartidores.id_repartidor=ventas_domicilio_salidas.id_repartidor
	WHERE ventas_domicilio.id_domicilio_salida!=0 AND facturar=1 AND facturado=0 AND cancelado !=1";
	$q=mysql_query($sql);
	while($dat=mysql_fetch_object($q)):
		$datos[] = $dat;
	endwhile;
	$valida_servicios_facturar=count($datos);
}


$impresora_sql="SELECT * FROM configuracion ";
$impresora_q=mysql_query($impresora_sql);
$ix=mysql_fetch_assoc($impresora_q);

if($ix['comandain']){
	$impresora= $ix['impresora_cuentas'];
	$impresora_cuentas=$ix['impresora_cuentas'];
}else{
	$impresora= $ix['impresora_sd'];
	$impresora_cuentas=$ix['impresora_cuentas'];
}




?>

<div class="row">
	<div class="col-md-12">

		<div class="bs-example" data-example-id="navbar-button">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<? if($s_tipo!=4){ ?>
					<a role="button" class="btn btn-danger navbar-btn" href="index.php?Modulo=Pedidos">Llevar Pedidos</a>
					&nbsp&nbsp&nbsp
					<a role="button" class="btn btn-warning navbar-btn" href="#" onclick="checarLlegada();">Reportar Entrega</a>
					<? } ?>
					<div class="collapse navbar-collapse navbar-right">

							<a role="button" class="btn btn-default navbar-btn" href="index.php?Modulo=Servicios">Pendientes</a>
						&nbsp&nbsp | &nbsp&nbsp

						<? if($q_cconf['facturacion']==1){if($s_tipo!=3){ ?>
						<a role="button" class="btn btn-primary navbar-btn" href="index.php?Modulo=Servicios&Tipo=Facturar">Por Facturar</a>&nbsp&nbsp
						<? } }?>
						<a role="button" class="btn btn-info navbar-btn" href="index.php?Modulo=Servicios&Tipo=Transito">En Tránsito</a>

						<a role="button" class="btn btn-danger navbar-btn" href="index.php?Modulo=Servicios&Tipo=Cancelados">Cancelados</a>

						<a role="button" class="btn btn-danger navbar-btn" href="index.php?Modulo=Servicios&Tipo=Entregados">Entregados</a>


						<? if($cortes==1 || $s_tipo==1 ){ ?>
							&nbsp&nbsp | &nbsp&nbsp
							<a role="button" class="btn btn-success navbar-btn" href="?Modulo=CorteDomicilio" >Corte del día</a>
						<? } ?>
					</div>
				</div>
			</nav>
		</div>
	</div>
	<div class="col-md-12">
		<? if(!$_GET['Tipo']){ ?>
			<? if($valida_servicios){ ?>
			<div class="panel panel-primary">

				<div class="panel-heading">
					<h3 class="panel-title">Servicios Pendientes <?=fechaHoraMeridian($fechahora)?></h3>
				</div>

				<div class="panel-body">

			  		<table class="table table-striped table-hover ">
						<thead>
					        <tr>
								<th width="100"># Venta</th>
								<th width="80">Hora</th>
								<th width="120">Transcurrido</th>
								<th width="110">Atendió</th>
								<th>Comentarios</th>
								<th>Dirección</th>
								<th width="80">Monto</th>
								<th></th>
					        </tr>
						</thead>
						<tbody>
							<? while($ft=mysql_fetch_assoc($q)){
								$id_venta_domicilio=$ft['id_venta_domicilio'];
								$descuento=$ft['descuento_cantidad'];
								$sq1="SELECT SUM(cantidad*precio_venta) AS total FROM venta_domicilio_detalle WHERE id_venta_domicilio=$id_venta_domicilio";
								$q1=mysql_query($sq1);
								$dt=mysql_fetch_assoc($q1);
								$monto=$dt['total']-$descuento;
							?>
								<tr>
									<td><b># <?=$id_venta_domicilio?></b></td>
									<td><?=horaVista(horaSinFecha($ft['fechahora_alta']))?></td>
									<td>
										<span data-livestamp="<?=substr($ft['fechahora_alta'],0,10)?>T<?=substr($ft['fechahora_alta'],11,8)?>"></span>
									</td>
									<td><?=$ft['nombre']?></td>
									<td><?if($ft['comentarios']){ echo $ft['comentarios']; }else{ echo "N/A"; }?></td>
									<td><?
									if($ft['id_domicilio_direccion']){
										if( $ft['facturar']!=1){

										echo $ft['direccion'];
									}else{
											echo "<b style='color:rgb(190, 0, 0)'>Entrega en mostrador</b>";
										}
									}else{
										echo "<b style='color:rgb(190, 0, 0)'>Entrega en mostrador</b>";
									}

									?></td>
									<td align="right"><?=number_format($monto,2)?></td>
									<td align="right">
										<? if($s_tipo!=4){ ?>
										<div class="btn-group">
	  										<button type="button" class="btn btn-warning btn-sm dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones <span class="caret"></span></button>
	  										<ul class="dropdown-menu">
												<li><a href="#" onclick="reimprimir_ticket_venta('<?=$id_venta_domicilio?>','<?=$impresora_cuentas?>')">Imprimir Ticket # <?=$id_venta_domicilio?> (Caja)</a></li>
												<li><a href="#" onclick="reimprimir_ticket_venta('<?=$id_venta_domicilio?>','<?=$impresora?>')">Imprimir Ticket # <?=$id_venta_domicilio?> (Repartidores)</a></li>
												<li><a href="#" onclick="muestraPedido('<?=$id_venta_domicilio?>')">Ver Pedido</a></li>
												<?
												if($s_tipo==1) {
													if(!$ft['id_domicilio_direccion']){
														$btn_cancelar = 1;
														$btn_entregar = 1;
													}else{
														$btn_cancelar = 1;
														$btn_entregar = 0;
													}
												}else{
													$btn_cancelar = 0;
													$btn_entregar = 0;
													if(!$ft['id_domicilio_direccion']){
														$btn_entregar = 1;
													}
												}

												if($btn_cancelar){ ?>
													<li><a href="#" onclick="cancelarPedido(<?=$id_venta_domicilio?>)">Cancelar</a></li>
												<? } ?>

												<? if($btn_entregar){ ?>
													<li><a href="#" onclick="entregarPedido(<?=$id_venta_domicilio?>)">Entregado</a></li>
												<? } ?>
											</ul>
										</div>
										<? } ?>

									</td>
								</tr>
							<? unset($monto);
								} ?>
						</tbody>
					</table>

				</div>

			</div>
			<? }else{ ?>
				<div class="alert alert-info" role="alert">Aún no se han tomado pedidos.</div>
			<? } ?>
		<? } ?>

		<!-- PANEL DE CANCELADOS -->
		<!-- PANEL DE CANCELADOS -->
		<!-- PANEL DE CANCELADOS -->
		<!-- PANEL DE CANCELADOS -->
		<!-- PANEL DE CANCELADOS -->

		<? if($_GET['Tipo']=="Cancelados"){ ?>
			<? if($valida_servicios_cancelados){ ?>
			<div class="panel panel-primary">

				<div class="panel-heading">
					<h3 class="panel-title">Servicios Cancelados <?=fechaHoraMeridian($fechahora)?></h3>
				</div>

				<div class="panel-body">

			  		<table class="table table-striped table-hover ">
						<thead>
					        <tr>
								<th width="100"># Venta</th>
								<th width="80">Fecha/Hora Tomado</th>
								<th width="80">Fecha/Hora Cancelado</th>
								<th width="110">Atendió</th>
								<th width="110">Canceló</th>
								<th>Dirección</th>
								<th>Motivo</th>
								<th width="80">Monto</th>
								<th></th>
					        </tr>
						</thead>
						<tbody>
							<? while($ft=mysql_fetch_assoc($q)){
								$id_venta_domicilio=$ft['id_venta_domicilio'];
								$descuento=$ft['descuento_cantidad'];
								$sq1="SELECT SUM(cantidad*precio_venta) AS total FROM venta_domicilio_detalle WHERE id_venta_domicilio=$id_venta_domicilio";
								$q1=mysql_query($sq1);
								$dt=mysql_fetch_assoc($q1);
								$monto=$dt['total']-$descuento;
							?>
								<tr>
									<td><b># <?=$id_venta_domicilio?></b></td>
									<td><span title="<?=$ft['fechahora_alta']?>"><?=horaVista(horaSinFecha($ft['fechahora_alta']))?></span></td>
									<td><span title="<?=$ft['fechahora_cancelacion']?>"><?=horaVista(horaSinFecha($ft['fechahora_cancelacion']))?></span></td>
									<td><?=$ft['nombre']?></td>
									<td><?=$ft['cancelador']?></td>
									<td><?=$ft['direccion']?></td>
									<td><?if($ft['motivo_cancelacion']){ echo $ft['motivo_cancelacion']; }else{ echo "N/A"; }?></td>
									<td align="right"><?=number_format($monto,2)?></td>
									<td align="right">
										<? if($s_tipo!=4){ ?>
										<div class="btn-group">
	  										<button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones <span class="caret"></span></button>
	  										<ul class="dropdown-menu">
												<?
												/*if($s_tipo==3){
														$impre = "DOMICILIO2";
												}else{
														$impre = "EPSON";
												} */
												?>
	    										<li><a href="#" onclick="reimprimir_ticket_venta('<?=$id_venta_domicilio?>','<?=$impresora_cuentas?>')">Imprimir Ticket # <?=$id_venta_domicilio?> (Caja)</a></li>
												<li><a href="#" onclick="reimprimir_ticket_venta('<?=$id_venta_domicilio?>','<?=$impresora?>')">Imprimir Ticket # <?=$id_venta_domicilio?> (Repartidores)</a></li>
												<? if($ft['id_domicilio_salida']>0){ ?>
												<li><a href="#" onclick="reimprimir_salida('<?=$id_venta_domicilio?>','<?=$impresora_cuentas?>')">Imprimir Salida (Caja)</a></li>
												<li><a href="#" onclick="reimprimir_salida('<?=$id_venta_domicilio?>','<?=$impresora?>')">Imprimir Salida (Repartidores)</a></li>
												<? } ?>
											</ul>
										</div>
										<? } ?>

									</td>
								</tr>
							<? unset($monto);} ?>
						</tbody>
					</table>

				</div>

			</div>
			<? }else{ ?>
				<div class="alert alert-info" role="alert">Aún no se han tomado pedidos.</div>
			<? } ?>
		<? } ?>

		<!-- // PANEL DE CANCELADOS -->
		<!-- // PANEL DE CANCELADOS -->
		<!-- // PANEL DE CANCELADOS -->
		<!-- // PANEL DE CANCELADOS -->
		<!-- // PANEL DE CANCELADOS -->
		<!-- // PANEL DE CANCELADOS -->
		<!-- // PANEL DE CANCELADOS -->
		<!-- // PANEL DE CANCELADOS -->


		<!-- PANEL DE ENTREGADOS -->
		<!-- PANEL DE ENTREGADOS -->
		<!-- PANEL DE ENTREGADOS -->
		<!-- PANEL DE ENTREGADOS -->

		<? if($_GET['Tipo']=="Entregados"){ ?>
			<? if($valida_servicios_entregados){ ?>
			<div class="panel panel-primary">

				<div class="panel-heading">
					<h3 class="panel-title">Servicios Entregados en Mostrador <?=fechaHoraMeridian($fechahora)?></h3>
				</div>

				<div class="panel-body">

			  		<table class="table table-striped table-hover ">
						<thead>
					        <tr>
								<th width="100"># Venta</th>
								<th width="80">Fecha/Hora Tomado</th>
								<th width="80">Fecha/Hora Entregado</th>
								<th width="110">Atendió</th>
								<th width="110">Entregó</th>
								<th>Comentarios</th>
								<th width="80">Monto</th>
								<th></th>
					        </tr>
						</thead>
						<tbody>
							<? while($ft=mysql_fetch_assoc($q)){
								$id_venta_domicilio=$ft['id_venta_domicilio'];
								$descuento=$ft['descuento_cantidad'];
								$sq1="SELECT SUM(cantidad*precio_venta) AS total FROM venta_domicilio_detalle WHERE id_venta_domicilio=$id_venta_domicilio";
								$q1=mysql_query($sq1);
								$dt=mysql_fetch_assoc($q1);
								$monto=$dt['total']-$descuento;
							?>
								<tr>
									<td><b># <?=$id_venta_domicilio?></b></td>
									<td><span title="<?=$ft['fechahora_alta']?>"><?=horaVista(horaSinFecha($ft['fechahora_alta']))?></span></td>
									<td><span title="<?=$ft['fechahora_cancelacion']?>"><?=horaVista(horaSinFecha($ft['fechahora_cancelacion']))?></span></td>
									<td><?=$ft['nombre']?></td>
									<td><?=$ft['cancelador']?></td>
									<td><?=$ft['comentarios']?></td>
									<td align="right"><?=number_format($monto,2)?></td>
									<td align="right">
										<? if($s_tipo!=4){ ?>
										<div class="btn-group">
	  										<button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones <span class="caret"></span></button>
	  										<ul class="dropdown-menu">
												<?
												/*if($s_tipo==3){
														$impre = "DOMICILIO2";
												}else{
														$impre = "EPSON";
												} */
												?>
	    										<li><a href="#" onclick="reimprimir_ticket_venta('<?=$id_venta_domicilio?>','<?=$impresora_cuentas?>')">Imprimir Ticket # <?=$id_venta_domicilio?> (Caja)</a></li>
												<li><a href="#" onclick="reimprimir_ticket_venta('<?=$id_venta_domicilio?>','<?=$impresora?>')">Imprimir Ticket # <?=$id_venta_domicilio?> (Repartidores)</a></li>
												<? if($ft['id_domicilio_salida']>0){ ?>
												<li><a href="#" onclick="reimprimir_salida('<?=$id_venta_domicilio?>','<?=$impresora_cuentas?>')">Imprimir Salida (Caja)</a></li>
												<li><a href="#" onclick="reimprimir_salida('<?=$id_venta_domicilio?>','<?=$impresora?>')">Imprimir Salida (Repartidores)</a></li>
												<? } ?>
											</ul>
										</div>
										<? } ?>

									</td>
								</tr>
							<? unset($monto);} ?>
						</tbody>
					</table>

				</div>

			</div>
			<? }else{ ?>
				<div class="alert alert-info" role="alert">Aún no se han tomado pedidos.</div>
			<? } ?>
		<? } ?>

		<!-- // PANEL DE ENTREGADOS -->
		<!-- // PANEL DE ENTREGADOS -->
		<!-- // PANEL DE ENTREGADOS -->
		<!-- // PANEL DE ENTREGADOS -->
		<!-- // PANEL DE ENTREGADOS -->




		<? if($_GET['Tipo']=="Transito"){ ?>
			<? if($valida_servicios_transito>0){ ?>
			<div class="panel panel-info">

				<div class="panel-heading">
					<h3 class="panel-title">Servicios en Tránsito <?=fechaHoraMeridian($fechahora)?></h3>
				</div>

				<div class="panel-body">

			  		<table class="table table-striped table-hover table-bordered">
						<thead>
					        <tr>
								<th width="100"># Servicio</th>
								<th>Hora Captura</th>
								<th>Tiempo en Cocina</th>
								<th>Hora Salida</th>
								<th>Transcurrido</th>
								<th>Repartidor</th>
								<th width="80">Pedidos</th>
								<th width="80">Monto</th>
								<th width="100"></th>
					        </tr>
						</thead>
						<tbody>
							<? foreach($datos as $dato){ ?>
								<tr class="tr_madre tr_madre_<?=$dato->id_venta_domicilio_salida?>">
									<td><b># <?=$dato->id_venta_domicilio_salida?></b></td>
									<td><?=horaVista(horaSinFecha($dato->fechahora_alta))?></td>
									<td><?=hace($dato->fechahora_alta,$dato->fechahora_salida)?></td>
									<td><?=horaVista(horaSinFecha($dato->fechahora_salida))?></td>
									<td><span data-livestamp="<?=substr($dato->fechahora_salida,0,10)?>T<?=substr($dato->fechahora_salida,11,8)?>"></span></td>
									<td><?=$dato->nombre?></td>
									<td align="center" id="productos_<?=$dato->id_venta_domicilio_salida?>"></td>
									<td align="right" id="total_<?=$dato->id_venta_domicilio_salida?>"></td>
									<td align="right">
										<!--
										<div class="btn-group">
	  										<button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones <span class="caret"></span></button>
	  										<ul class="dropdown-menu">
												<li><a href="#">Descripción Pedido</a></li>
												<li><a href="#">Dirección de envío</a></li>
	    										<li><a href="#">Reimpresión</a></li>
												<li><a href="#">Cancelar</a></li>
											</ul>
										</div>
										-->
										<a role="button" class="btn btn-info btn-xs" href="#" onclick="muestraPedidos(<?=$dato->id_venta_domicilio_salida?>);">Pedidos</a>
									</td>
								</tr>
<!-- Tabla emergente -->
								<?
								$sql1="SELECT id_venta_domicilio,ventas_domicilio.id_domicilio_direccion, fechahora_alta, domicilio.nombre AS cliente, direccion, comentarios, descuento_cantidad, descuento_porcentaje FROM ventas_domicilio
								LEFT JOIN domicilio_direcciones ON domicilio_direcciones.id_domicilio_direccion=ventas_domicilio.id_domicilio_direccion
								LEFT JOIN domicilio ON domicilio.id_domicilio=domicilio_direcciones.id_domicilio
								WHERE ventas_domicilio.id_domicilio_salida=$dato->id_venta_domicilio_salida AND ventas_domicilio.id_corte_domicilio=0";
								$q1=mysql_query($sql1);
								while($dat=mysql_fetch_object($q1)){

									$id_venta_domicilio=$dat->id_venta_domicilio;
									$descuento=$dat->descuento_cantidad;

									$sq="SELECT SUM(cantidad*precio_venta) AS total FROM venta_domicilio_detalle WHERE id_venta_domicilio=$id_venta_domicilio";
									$qu=mysql_query($sq);
									$dt=mysql_fetch_assoc($qu);

									$dat->hora=horaVista(horaSinFecha($dat->fechahora_alta));
									$dat->transcurrido=substr($dat->fechahora_alta,0,10)."T".substr($dat->fechahora_alta,11,8);
									$dat->total=$dt['total']-$descuento;

									$ventas[] = $dat;

									//echo json_encode($data);
								}
								?>
								<tr style="cursor:default;display:none;" class="trs tr_<?=$dato->id_venta_domicilio_salida?>">
									<td colspan="7">
										<table class="table table-striped table-hover table-bordered">
											<thead>
												<tr class="info">
													<th width="100">Venta</th>
													<th width="80">Hora</th>
													<th width="120">Transcurrido</th>
													<th>Comentarios</th>
													<th>Dirección</th>
													<th width="80" align="right">Monto</th>
													<th width="80"></th>
												</tr>
											</thead>
											<tbody>
												<?
												if(count($ventas)>0){
												foreach($ventas as $venta){ ?>
												<tr class="tr_servicio_<?=$venta->id_venta_domicilio?> pedidos">
												    <td><b>#<?=$venta->id_venta_domicilio?></b></td>
												    <td><?=$venta->hora?></td>
												    <td><span data-livestamp="<?=$venta->transcurrido?>"></span></td>
												    <td><? if($venta->comentarios){ echo $venta->comentarios; }else{ echo "N/A"; } ?></td>
												    <td><?=$venta->direccion?></td>
												    <td align="right"><?=number_format($venta->total,2)?></td>
												    <td align="right">
														<div class="btn-group">
														<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones <span class="caret"></span></button>
				  										<ul class="dropdown-menu">
				    										<li><a href="#" onclick="reimprimir_ticket_venta('<?=$id_venta_domicilio?>','<?=$impresora_cuentas?>')">Imprimir Ticket #<?=$venta->id_venta_domicilio?> (Caja)</a></li>
															<li><a href="#" onclick="reimprimir_ticket_venta('<?=$id_venta_domicilio?>','<?=$impresora?>')">Imprimir Ticket #<?=$venta->id_venta_domicilio?> (Repartidores)</a></li>
															<li role="separator" class="divider"></li>
															<li><a href="#" onclick="reimprimir_salida('<?=$id_venta_domicilio?>','<?=$impresora_cuentas?>')">Imprimir Salida #<?=$dato->id_venta_domicilio_salida?> (Caja)</a></li>
															<li><a href="#" onclick="reimprimir_salida('<?=$id_venta_domicilio?>','<?=$impresora?>')">Imprimir Salida #<?=$dato->id_venta_domicilio_salida?> (Repartidores)</a></li>
															<li role="separator" class="divider"></li>
															<li><a href="#" onclick="borraVentaRepartidor(<?=$venta->id_venta_domicilio?>);">Cancelar pedido de esta salida</a></li>
														</ul>
														</div>
												    </td>
												</tr>
												<?
													$billete_total+=$venta->total;
													}
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
									$('#total_<?=$dato->id_venta_domicilio_salida?>').html("<?=number_format($billete_total,2)?>");
									$('#productos_<?=$dato->id_venta_domicilio_salida?>').html(<?=$productos?>);
								});
							</script>
							<? unset($ventas);
							unset($billete_total);
							 } ?>
						</tbody>
					</table>

				</div>

			</div>

			<? }else{ ?>
				<div class="alert alert-info" role="alert">No hay pedidos en tránsito.</div>
			<? } ?>
		<? } ?>

		<? if($_GET['Tipo']=="Facturar"){ ?>

			<? if($valida_servicios_facturar){ ?>
			<div class="panel panel-danger">

				<div class="panel-heading">
					<h3 class="panel-title">Servicios Pendientes por Facturar</h3>
				</div>

				<div class="panel-body">

			  		<table class="table table-striped table-hover ">
						<thead>
					        <tr>
								<th width="100"># Venta</th>
								<th width="80">Fecha/Hora</th>
								<th width="120">Entregó</th>
								<th width="110">Atendió</th>
								<th>Dirección</th>
								<th width="80">Monto</th>
								<th></th>
					        </tr>
						</thead>
						<tbody>
							<? foreach($datos as $dato){
								$id_venta_domicilio=$dato->id_venta_domicilio;
								$descuento=$dato->descuento_cantidad;
								$sq1="SELECT SUM(cantidad*precio_venta) AS total FROM venta_domicilio_detalle WHERE id_venta_domicilio=$id_venta_domicilio";
								$q1=mysql_query($sq1);
								$dt=mysql_fetch_assoc($q1);
								$monto=$dt['total']-$descuento;
							?>
								<tr>
									<td><b># <?=$id_venta_domicilio?></b></td>
									<td><?=fechaHoraVista($dato->fechahora_alta)?></td>
									<td><?=$dato->repartidor?></td>
									<td><?=$dato->nombre?></td>
									<td><?=$dato->direccion?></td>
									<td align="right"><?=number_format($monto,2)?></td>
									<td align="right">
										<button type="button" onclick="javascript:marcarFacturado(<?=$id_venta_domicilio?>);" class="btn btn-danger btn-xs btn-elimina" style="margin-top:1px;">Marcar como Facturado</button>
									</td>
								</tr>
							<? unset($monto);} ?>
						</tbody>
					</table>

				</div>

			</div>
			<? }else{ ?>
				<div class="alert alert-info" role="alert">No hay pedidos pendientes de facturar</div>
			<? } ?>

		<? } ?>

















	</div>


</div>




<div class="modal fade" id="llevarPedido" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Llevar Pedido</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
				<!--Formulario -->
				<form id="frm_guarda" class="form-horizontal">

					<div id="paso_1">
						<div class="form-group">
					        <label for="inputEmail3" class="col-sm-4 control-label" style="font-size:18px;font-weight:normal;margin-top:5px;">Código de venta</label>
					        <div class="col-sm-8">
					            <input type="text" autocomplete="off" id="id_venta_domicilio" name="id_venta_domicilio" placeholder="Escanea el código men" class="form-control input-lg" maxlength="10">
					        </div>
					    </div>
						<div class="form-group">
					        <label for="inputEmail3" class="col-sm-4 control-label" style="font-size:18px;font-weight:normal;margin-top:5px;"></label>
					        <div class="col-sm-8" style="text-align:right;">
								<a role="button" class="btn btn-primary" href="#" onclick="pasoDos()">Siguiente</a>
					        </div>
					    </div>
					</div>

					<div id="paso_2" class="oculto">
						<div class="form-group">
					        <label for="inputEmail3" class="col-sm-4 control-label" style="font-size:18px;font-weight:normal;margin-top:5px;">Identificación</label>
					        <div class="col-sm-8">
					            <input type="text" autocomplete="off" id="id_repartidor" name="id_repartidor" placeholder="Escanea el código men" class="form-control input-lg" maxlength="10">
					        </div>
					    </div>
						<div class="form-group">
					        <label for="inputEmail3" class="col-sm-4 control-label" style="font-size:18px;font-weight:normal;margin-top:5px;"></label>
							<div class="col-sm-4" style="text-align:left;">
								<a role="button" class="btn btn-default" href="#" onclick="pasoUno()">Atras</a>
					        </div>
					        <div class="col-sm-4" style="text-align:right;">
								<a role="button" class="btn btn-primary" href="#" onclick="pasoTres()">Siguiente</a>
					        </div>
					    </div>
					</div>

					<div id="paso_3" class="oculto2">
						<h3>Pedido
						<p>

						</p>
						<div class="form-group">
							<div class="col-sm-6" style="text-align:left;">
								<a role="button" class="btn btn-default" href="#" onclick="pasoDos()">Atras</a>
					        </div>
					        <div class="col-sm-6" style="text-align:right;">
								<a role="button" class="btn btn-primary" href="#" onclick="Confirma()">Confirmar</a>
					        </div>
					    </div>
					</div>

				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="muestra_cosas_pedido" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Detalle del Pedido</h4>
			</div>
			<div class="modal-body" id="muestra_datos">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){

	$("body").keydown(function(e){
		<? if($valida_servicios){ ?>
		if(e.keyCode == 27){
			window.open("?Modulo=Pedidos", "_self");
		}
		<? } ?>
		/*if(e.keyCode == 32){
			checarLlegada();
		}*/
	});

	<? if($valida_servicios){
		if(!$_GET['Tipo']){?>
			iniciarTimer();
		<? } ?>
	<? } ?>

	document.onmousemove = function(){
	  pararTimer();
	  iniciarTimer();
	}

	document.onkeyup = function(){
	  pararTimer();
	  iniciarTimer();
	}

	var position = $(window).scrollTop();

	$(window).scroll(function() {
	    var scroll = $(window).scrollTop();
	    if(scroll > position) {
		  pararTimer();
	  	  iniciarTimer();
	    } else {
		pararTimer();
		 iniciarTimer();
	    }
	    position = scroll;
	});

});

function reimprimir_ticket_venta(id,impresora){
	$.get('ac/reimprimir_ticket_venta.php',{ id:id, impresora:impresora });
}

function reimprimir_salida(id,impresora){
	$.get('ac/reimprimir_salida.php',{ id:id, impresora:impresora });
}

function iniciarTimer(){
    timer = setInterval(function(){
        location.reload();
	}, 10000);
}
function pararTimer(){
  clearInterval(timer);
}


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
function checarLlegada(){
	swal("Escanea tu código de servicio:", {
  		content: "input"
	})
	.then((value)=>{
		$.get('ac/checar_llegada.php',{id_venta_domicilio_salida:value},function(data){
			console.log(data);
			if(data==1){
				swal('Confirmación','Se ha capturado tu entrada','success');
			}else{
				swal('Error',data,'error');
			}
		});
	});
}
function borraVentaRepartidor(id){
	swal({
		title: "Cancelar Envío de Pedido",
		text: "¿Estas seguro de cancelar el pedido del repartidor?\n\n *Al hacer esta operación otro repartidor podrá tomar ese pedido para enviarlo.",
		icon: "warning",
		buttons: true,
    	buttons: ['No', 'Si, cancelar el envío']
	})
	.then((willDelete) => {
		if(willDelete){
			$.get('ac/quitar_venta.php',{id_venta_domicilio:id},function(data){
				if(data==1){
					swal("El servicio se ha quitado del envío", {
		      			icon: "success",
					}).then(function(result){
    					if(result){
        					setTimeout(function(){
            					$('.tr_servicio_'+id).hide();
        					}, 500);
    					}
					});
				}else{
					swal(data, {
		      			icon: "error",
					});
				}
			});
		}
	});
}
function marcarFacturado(id){
	swal({
		title: "Marcar como Facturado",
		text: "¿Estas segur@ que este pedido se ha facturado?",
		icon: "warning",
		buttons: true,
    	buttons: ['No estoy seguro', 'Si, ya hice la factura']
	})
	.then((willDelete) => {
		if(willDelete){
			$.get('ac/marcar_facturado.php',{id_venta_domicilio:id},function(data){
				if(data==1){
					swal("La venta se ha marcado como facturada", {
		      			icon: "success",
					}).then(function(result){
    					if(result){
        					setTimeout(function(){
            					window.open("?Modulo=Servicios&Tipo=Facturar", "_self");
        					}, 500);
    					}
					});
				}else{
					swal(data, {
		      			icon: "error",
					});
				}
			});
		}
	});
}
function cancelarPedido(id_venta_domicilio){
	swal("Motivo de cancelación:", {
  		content: "input",
	})
	.then((value)=>{
		$.get('ac/cancela_pedido_domicilio.php',{id_venta_domicilio:id_venta_domicilio,motivo:value},function(data){
			console.log(data);
			if(data==1){
				swal('Confirmación','El pedido se ha cancelado','success')
				.then((value) => {
  					window.location.reload();
				});

			}else{
				swal('Error',data,'error');
			}
		});
	});
}

function entregarPedido(id_venta_domicilio){

		$.get('ac/entregar_pedido_domicilio.php',{id_venta_domicilio:id_venta_domicilio},function(data){
			console.log(data);
			if(data==1){
				swal('Confirmación','El pedido se ha marcado como entregado.','success')
				.then((value) => {
  					window.location.reload();
				});
			}else{
				swal('Error',data,'error');
			}
		});
}

function muestraPedido(id_venta_domicilio){
	$.get('data/venta_domicilio_detalle.php',{id_venta_domicilio:id_venta_domicilio},function(data){
		console.log(data);
		$('#muestra_datos').html(data);
		$('#muestra_cosas_pedido').modal('show');
		/*
		if(data==1){
			swal('Confirmación','El pedido se ha marcado como entregado.','success')
			.then((value) => {
				window.location.reload();
			});
		}else{
			swal('Error',data,'error');
		}
		*/
	});

}
</script>
