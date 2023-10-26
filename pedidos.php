	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<h3 class="panel-title">Llevar pedidos</h3>
				</div>

				<div class="panel-body">

					<div class="row">
						<div class="col-md-8" style="padding-top:20px;">

							<form class="form-inline" onsubmit="return false;">

								<div class="form-group">
		    						<input type="password" class="form-control input-lg" id="id_repartidor" name="id_repartidor" maxlength="128" placeholder="Identificación de Repartidor" style="min-width:600px;">
									<input type="hidden" id="id_repartidor_bueno" value="0" />
		  						</div>
		  						<button type="button" onclick="javascript:verificaMeco()" class="btn btn-success btn-lg" id="btn-verifica" data-loading-text="Verificando">Verificar</button>

								<hr>

								<div class="form-group">
		    						<input type="password" class="form-control input-lg solo_numero" id="agrega_venta" maxlength="128" placeholder="Ingrese el código del pedido" style="min-width:600px;" disabled>
		  						</div>
		  						<button type="button" onclick="javascript:agregaVenta()" class="btn btn-info btn-lg" id="btn-agregar" data-loading-text="Agregando" disabled>Agregar</button>
							</form>

						</div>

						<div class="col-md-4" style="text-align:center;" id="muestra_datos_meco">

						</div>

						<div class="col-md-12 oculto" style="margin-top:35px;" id="vista_pedidos">
							<form id="frm_guarda">
								<h4 style="text-align:center;">Detalle de pedidos agregados</h4>
								<table class="table table-striped table-hover table-bordered">
									<thead>
										<tr>
											<th width="100">Folio</th>
											<th width="80">Hora</th>
											<th width="120">Transcurrido</th>
											<th>Comentarios</th>
											<th>Dirección</th>
											<th width="80" align="right">Monto</th>
											<th width="80"></th>
										</tr>
									</thead>
									<tbody id="agrega">

									</tbody>
								</table>
							</form>

						</div>
					</div>

				</div>
				<div class="panel-footer" style="text-align:right;">
					<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
					<a role="button" class="btn btn-default btn_ac" href="javascript:history.back()">Cancelar</a>&nbsp&nbsp&nbsp
					<button type="button" class="btn btn-info btn_ac" onclick="llevarPedido()" id="btn-procesa" data-loading-text="Procesando pedido...">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

<script>
$(function(){
	$("#id_repartidor").focus();
	$('.solo_numero').numeric({allow:"."});

	//Verificamos el repartidor
	$("#id_repartidor").keypress(function(e){
		if(e.which == 13){
			verificaMeco();
		}
	});

	//Agregamnos el pedido
	$('#agrega_venta').keyup(function(e){
		if(e.keyCode == 13) {
			agregaVenta();
		}

		if(e.keyCode == 27) {
			llevarPedido();
		}
	});

});

function verificaMeco(){
	$('#btn-verifica').button('loading');

	var id_repartidor = $('#id_repartidor').val();

	if(!id_repartidor){
		swal("Ocurrió un error", "Escanea tu código!", "error").then( function(){
			setTimeout(function(){
				$('#id_repartidor').focus();
			}, 500);
		});
		//alert("Escanea tu código meco!");
		//$('#id_repartidor').focus();
		return false;
	}

	$.getJSON('data/repartidores.php', {id_repartidor:id_repartidor} ,function(data){
		console.log(data);
       
		var nombre		=	data.nombre;
		var telefono	=	data.telefono;
		var resultado	=	data.resultado;
		var id_repartidor	=	data.id_repartidor;

		if(resultado==1){
			//OK
			$('#muestra_datos_meco').html('<img src="img/meco.png" border="0" width="70" /><h3>'+nombre+'<small><br>'+telefono+'</small></h3>');
			$('#btn-verifica').button('reset');
			//habilitamos los pedidos
			$('#agrega_venta').removeAttr("disabled").focus();
			$('#btn-agregar').removeAttr("disabled");
			$('#id_repartidor_bueno').val(id_repartidor);
		}else{
			//Error
		
			$("#agrega_venta").attr("disabled", true);
			swal("Ocurrió un error", "No se encontro el repartidor", "error").then( function(){
				setTimeout(function(){
					$('#btn-verifica').button('reset');
					$('#muestra_datos_meco').html("");
					$('#id_repartidor').focus();
				}, 500);
			});
			//alert("No se encontro el repartidor");
			//$("#id_repartidor").val("").focus();
		}
	});
}

function agregaVenta(){
	var contador = Number(1);
	var id_venta = $('#agrega_venta').val();
	$('#btn-agregar').button('loading');

	if(!id_venta){
		swal("Ocurrió un error", "Escanea el ticket del pedido que llevas!", "error").then( function(){
			setTimeout(function(){
				$('#agrega_venta').focus();
			}, 500);
		});
		//alert("Escanea el ticket del pedido que llevas!");
		//$('#agrega_venta').focus();
		$('#btn-agregar').button('reset');
		return false;
	}

	if($(".tr_"+id_venta).length>0){
		swal("Ocurrió un error", "Ya haz agregado este pedido!", "error").then( function(){
			setTimeout(function(){
				$('#agrega_venta').val("");
				$('#agrega_venta').focus();
			}, 500);
		});
		$('#btn-agregar').button('reset');
		return false;
	}

	$.getJSON('data/venta_domicilio.php', {id_venta_domicilio:id_venta} ,function(data){
		console.log(data);

		var resultado	=	data.resultado;
		var folio		=	data.id_venta_domicilio;
		var total		=	data.total;
		var cliente		=	data.cliente;
		var direccion	=	data.direccion;
		var hora		=	data.hora;
		var transcurrido=	data.transcurrido;

		if(data.comentarios){
			var comentarios	=	data.comentarios;
		}else{
			var comentarios =	"N/A";
		}

		if(resultado==1){
			//OK
			//vista_pedidos
			if($("#vista_pedidos").length>0){
				$("#vista_pedidos").show();
			}
			var cont = '<tr class="tr_'+folio+' pedidos"> <td><b>#'+folio+'</b> <input type="hidden" name="id_venta[]" value="'+folio+'"/> </td><td>'+hora+'</td><td><span data-livestamp="'+transcurrido+'"></span></td><td>'+comentarios+'</td><td>'+direccion+'</td><td style="right">'+total+'</td><td style="right"> <button type="button" onclick="javascript:borraVenta('+folio+');" class="btn btn-danger btn-sm btn-elimina" style="margin-top:1px;">Eliminar</button> </td></tr>';
			$('#agrega').append(cont);
		}else if(resultado==2){
			//Error
			swal("Ocurrió un error", "No se encontro el folio de la venta, verifica de nuevo.", "error").then( function(){
				setTimeout(function(){
					$('#agrega_venta').focus();
				}, 500);
			});
			//alert("No se encontro el folio de la venta, verifica de nuevo.");
			//$("#agrega_venta").val("").focus();
		}else if(resultado==3){
			//Error
			swal("Ocurrió un error", "El pedido ya se ha marcado como envíado.", "error").then( function(){
				setTimeout(function(){
					$('#agrega_venta').focus();
				}, 500);
			});
			//alert("Esta venta ya se ha registrado como salida.");
			//$("#agrega_venta").val("").focus();
		}
		$('#btn-agregar').button('reset');

	});

	//var cont = '<tr class="tr_'+contador+'"><td><input type="text" class="form-control" style="min-width:400px;" name="opcion[]" value="'+opcion+'" readonly></td><td style="text-align:right;"><button type="button" onclick="javascript:borraOpcion('+contador+');" class="btn btn-danger btn-sm" style="margin-top:1px;">Eliminar</button></td></tr>';
	$('#agrega_venta').val("").focus();
}

function borraVenta(id){
	/*
	$.get('ac/categoria_opciones_elimina.php?id='+id,function(data){
		if(data==1){
			$('.tr_'+id).remove();
		}else{
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
		}
	});
	*/
	$('.tr_'+id).remove();
	//alert($(".pedidos").length);
	if($(".pedidos").length==0){
		$("#vista_pedidos").hide();
		$('#agrega_venta').focus();
	}
}

function llevarPedido(){
	$('#btn-procesa').button('loading');
	$('.btn-elimina').prop("disabled", true);
	var pedidos = $('.pedidos').length;

	if(pedidos<=1){
		var msg = "el pedido?";
	}else{
		var msg = "los "+pedidos+" pedidos?";
	}
	swal({
		title: "Pedido Completo",
		text: "¿Estas seguro que vas a llevar "+msg,
		icon: "warning",
		buttons: true,
    	buttons: ['No, Cancelar', 'Si, Llevar Pedidos']
	})
	.then((willDelete) => {
		if(willDelete){
			var id_repartidor = $('#id_repartidor_bueno').val();
			var datos = $('#frm_guarda').serialize()+"&id_repartidor="+id_repartidor;
			$.post('ac/llevar_pedido.php',datos,function(data){
				if(data==1){
					swal("El envío ha sido asignado", {
		      			icon: "success",
					}).then(function(result){
    					if(result){
        					setTimeout(function(){
            					window.open("?Modulo=Servicios&msg=3", "_self");
        					}, 500);
    					}
					});
					//window.open("?Modulo=Servicios&msg=3", "_self");
				}else{
					console.log(data);
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
					/*
					$('#load').hide();
					$('.btn').show();
					$('#msg_error').html(data);
					$('#msg_error').show('Fast');
					*/
				}
			});
			/*
    		swal("Venga Pa", {
      			icon: "success",
			});*/
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





/*

	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var id_repartidor = $('#id_repartidor').val();
	var datos=$('#frm_guarda').serialize()+"&id_repartidor="+id_repartidor;
	$.post('ac/llevar_pedido.php',datos,function(data){
		console.log(data);
		if(data==1){
			window.open("?Modulo=Servicios&msg=3", "_self");
		}else{
			$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
		}
	});
*/
}

</script>
