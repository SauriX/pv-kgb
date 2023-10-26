<style>
.Vigente{
	color:#47c000;
	font-weight: bold;
}
.Cancelado{
	color:#bf0000;
	font-weight: bold;
}
</style>
<?
include('includes/external_db.php');

$id_factura = $_GET['id_factura'];

if(!$_GET['fecha']){
	$fecha = date('Y-m-d');
}else{
	$fecha=trim($_GET['fecha']);
	$value='value="'.$fecha.'"';
}

$sql = "SELECT * FROM facturas WHERE DATE(fecha_hora_timbrado) = '$fecha' ORDER BY fecha_hora_timbrado DESC, id_factura ASC";
$q = mysql_query($sql,$conexion2);
$n1 = mysql_num_rows($q);

?>

<script src="js/modalplug.js"></script>

	<div class="row mb10">
		<div class="col-md-8"></div>
		<div class="col-md-4 text-right">

			<div class="input-group">
				<input type="text" class="form-control fecha" name="fecha_facturacion" id="fecha_facturacion" <?=$value?> >
				<span class="input-group-btn">
					<button class="btn btn-primary" type="button" onclick="javascript:cambiaFecha();">Cambiar Fecha</button>
				</span>
			</div>

		</div>
	</div>

	<div class="row">

		<div class="col-md-12">

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Facturas (<?=$n1?>)</h3>
				</div>

				<div class="panel-body">
					<? if($n1){	?>
						<table class="table table-striped" style="font-size:13px" id="tabla_principal">
							<thead>
								<tr>
									<th width="130">Fecha</th>
									<th width="60">Folio</th>
									<th width="120">RFC</th>
									<th>Razón Social</th>
									<th>Método</th>
									<th width="80">Total</th>
									<th width="80">Estado</th>
									<th width="123"></th>
								</tr>
							</thead>
							<tbody>
								<?
								while($ft = mysql_fetch_assoc($q)){

									$f = $ft['fecha_hora_timbrado'];
									$x = explode('T', $f);

									$hora = substr($x[1],0,5);
									if($ft['estado']=='VIGENTE'){
										$est = "Vigente";
									}else{
										$est = "Cancelado";
									}
								?>
								<tr id="tr_<?=$ft['id_factura']?>" <? if($id_factura==$ft['id_factura']){ ?>class="warning"<? } ?>>
									<td><?=fechaLetra($fecha)?> <?=$hora?></td>
									<td><?=$ft['serie']?><?=$ft['folio']?></td>
									<td><?=$ft['receptor_rfc']?></td>
									<td><?=$ft['receptor_rs']?></td>
									<td>
										<?
											$metodo = $ft['metodo_pago'];
											if(is_numeric($metodo)){
												switch($metodo){
													case '01':
													echo "01 EFECTIVO";
													break;

													case '02':
													echo "02 CHEQUE";
													break;

													case '03':
													echo "03 TRANSFERENCIA DE FONDOS";
													break;

													case '04':
													echo "04 TARJETAS DE CREDITO";
													break;

													case '05':
													echo "05 MONEDEROS ELECTRONICOS";
													break;

													case '06':
													echo "06 DINERO ELECTRONICO";
													break;

													case '07':
													echo "07 TARJETAS DIGITALES";
													break;

													case '08':
													echo "08 VALES DE DESPENSA";
													break;

													case '09':
													echo "09 BIENES";
													break;

													case '10':
													echo "10 SERVICIO";
													break;

													case '11':
													echo "11 POR CUENTA DE TERCERO";
													break;

													case '12':
													echo "12 DACION EN PAGO";
													break;

													case '13':
													echo "13 PAGO POR SUBROGACION";
													break;

													case '14':
													echo "14 PAGO POR CONSIGNACION";
													break;

													case '15':
													echo "15 CONDONACION";
													break;

													case '16':
													echo "16 CANCELACION";
													break;

													case '17':
													echo "17 COMPENSACION";
													break;

													case '28':
													echo "28 TARJETA DE DEBITO";
													break;

													case '29':
													echo "29 TARJETA DE SERVICIO";
													break;

													case '98':
													echo "98 NA";
													break;

													case '99':
													echo "99 OTROS";
													break;
												}
											}else{
												echo $metodo;
											}
											?>
										</td>
										<td><?=number_format($ft['total'],2)?></td>
										<td id="estado_<?=$ft['id_factura']?>"><span class="<?=$est?>"><?=ucfirst(strtolower($ft['estado']))?></span></td>
										<td id="acciones_<?= $ft['id_factura'] ?>" style="text-align: right">
											<div class="btn-group">
												<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones <span class="caret"></span></button>
												<ul class="dropdown-menu pull-left">
													<li><a href="http://tacoloco.mx/facturacion/pdf_kgb_magisterial/<?=$ft['pdf']?>" data-pdf="http://tacoloco.mx/d_kgb_magisterial.php?r=<?=$ft['uuid']?>&t=p" class="view-pdf">PDF</a></li>
													<? if($est=="Vigente"){ ?>
														<li><a href="http://tacoloco.mx/d_kgb_magisterial.php?r=<?=$ft['uuid']?>&t=x">XML</a>
														<? } ?>
														<? if($ft['estado']=='VIGENTE'){ ?>
															<li role="separator" class="divider"></li>
															<li><a href="javascript:;" onclick="cancelar(<?=$ft['id_factura']?>);">Cancelar</a></li>
														<? } ?>
													</ul>
												</div>
											</td>
										</tr>
										<? } ?>
									</tbody>
								</table>
								<? }else{ ?>
									<center>
										<h4>No hay facturas en esa fecha (<?= date('d/m/Y',strtotime($fecha)) ?>).</h4>
									</center>
								<? } ?>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="enviarEmail">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
								<h4 class="modal-title">Envío de Factura</h4>
							</div>
							<div class="modal-body">
								<div class="alert alert-danger oculto" role="alert" id="gastos_msg_error"></div>
								<div class="alert alert-success oculto" role="alert" id="gastos_msg_ok"></div>
								<!--Formulario -->
								<form id="frm_facturas" class="form-horizontal">
									<div class="form-group">
										<label class="col-md-3 control-label">Email</label>
										<div class="col-md-9">
											<input type="text" class="form-control limpia" id="fact_email" name="fact_email" autocomplete="off" maxlength="255">
											<input type="hidden" class="limpia" name="fact_id" id="fact_id" value="">
										</div>
									</div>
								</form>
							</div>

							<div class="modal-footer">
								<img src="img/load-verde.gif" border="0" id="facturacion_load" width="30" class="oculto" />
								<button type="button" class="btn btn-factura btn-default" data-dismiss="modal">Cancelar</button>
								<button type="button" class="btn btn-factura btn-primary" id="btn-envia-factura">Enviar</button>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->

				<script>
				$(function(){
					$(document).on('click', '[data-email]', function () {
						//Precargamos
						$('.limpia').val("");
						var email = $(this).attr('data-email');
						var id_factura = $(this).attr('data-id');
						$('#fact_email').val(email);
						$('#fact_id').val(id_factura);
					});

					$('#btn-envia-factura').click(function() {
						var email = $('#fact_email').val();
						var id_factura = $('#fact_id').val();
						enviarmail(email,id_factura);
					});

					$(document).bind('keydown',function(e){
						if(e.which==27){
							$('#myModal').modal('hide');
						};
					});
				});

				function cancelar(id_fact){
					alert('Cancelación en proceso...');
					return false;
					var uno = Math.floor((Math.random() * 10) + 1);
					var dos = Math.floor((Math.random() * 10) + 1);
					var respuesta = Number(uno)+Number(dos);

					if(prompt('Para cancelar resuelva lo siguiente: '+uno+'+'+dos+'=')!=respuesta){
						alert('Error, reintente por favor.');
						return false;
					}

					$('#id_act_'+id_fact).html('Cancelando..');
					$.get('ac/cancelar_factura.php','id_factura='+id_fact,function(data) {
						alert(data);
						location.reload();
					});
				}


				function enviarmail(email,id_factura){
					$('.btn-factura').hide();
					$('#facturacion_load').show();
					$.post('http://tacoloco.mx/facturacion/enviar_mail.php','email='+email+'&id_factura='+id_factura,function(data) {});
					setTimeout(function() {
						$('#enviarEmail').modal('hide')
						$('.limpia').val("");
						$('.btn-factura').show();
						$('#facturacion_load').hide();
						alert("El correo se ha enviado");
					},5000);
				}

				function cambiaFecha(){
					var fecha = $('#fecha_facturacion').val();
					window.open("?Modulo=VerFacturas&fecha="+fecha, "_self");
				}

				function ticket_factura(id_factura){
					//alert(xml);
					$.post('includes/ticket_factura_Nuevo.php', { id_factura: ""+id_factura+"" },function(data){
						//alert(data);
					});
				}

				function regenerar_factura(rfc,uuid){
					alert('http://tacoloco.mx/formatos/PDF-3-3/factura_33_mangochile.php?rfc='+rfc+'&uuid='+uuid);
					/*$.get("http://tacoloco.mx/formatos/PDF-3-3/factura_33_mangochile.php", {rfc: rfc, uuid: uuid}, function(){

				});
				location.reload(true);*/
			}
		</script>
