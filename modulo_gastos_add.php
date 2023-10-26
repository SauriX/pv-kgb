
<script type="text/javascript">

$( document ).ready(function() {
	$('#gastos_descripcion').focus();
	$( "#guardarGasto" ).click(function() {
		var descripcion = $('#gastos_descripcion').val();
		var monto = $('#gastos_monto').val();
		if (descripcion == '') {
				$('#gastos_msg_error').html('Ingrese una descripción para el gasto.');
				$('#gastos_msg_error').show();
				return false;
		}
		if (monto == '') {
				$('#gastos_msg_error').html('Ingrese un monto para el gasto.');
				$('#gastos_msg_error').show();
				return false;
		}
		$('#gastos_msg_error').hide('Fast');
		$('#gastos_msg_ok').hide('Fast');
		$('.btn-gastos').hide();
		$('#gastos_load').show();
		
		var datos=$('#frm_gastos').serialize();
		$.post('ac/nuevo_gasto.php',datos,function(data){
			//$('#mensaje').html(data);
			console.log(data);
			var dat = data.split("|");
			var valida=dat[0];
			var dato=dat[1];
		    if(valida==1){
			    alert("El gasto se ha agregado");
			    gasto();


		    }else{
		    	$('#gastos_load').hide();
				$('.btn-gastos').show();
				$('#gastos_msg_error').html(dato);
				$('#gastos_msg_error').show('Fast');
		    }
		});
	});
});

function nuevo_gasto(){

	window.open("?Modulo=NuevoGastos","_self");
	//window.open("?Modulo=VentaTouchMesa", "_self");
}
function gasto(){

	window.open("?Modulo=MGastos","_self");
	//window.open("?Modulo=VentaTouchMesa", "_self");
}
function provision(){

	window.open("?Modulo=PGastos","_self");
	//window.open("?Modulo=VentaTouchMesa", "_self");
}

function nuevoGasto(){

	var descripcion = $('#gastos_descripcion').val();
	var monto 		= $('#gastos_monto').val();

	if(!descripcion){
		$('#gastos_msg_error').html('Ingrese una descripción para el gasto.').show();;
		return false;
	}

	if(!monto){
		$('#gastos_msg_error').html('Ingrese un monto para el gasto.').show();;
		return false;
	}

	$('#gastos_msg_error').hide('Fast');
	$('#gastos_msg_ok').hide('Fast');
	$('.btn-gastos').hide();
	$('#gastos_load').show();
	var datos=$('#frm_gastos').serialize();
	$.post('ac/nuevo_gasto.php',datos,function(data){
		//$('#mensaje').html(data);
		var dat = data.split("|");
		var valida=dat[0];
		var dato=dat[1];
	    if(valida==1){
		    alert("El gasto se ha agregado");
		    gasto();


	    }else{
	    	$('#gastos_load').hide();
			$('.btn-gastos').show();
			$('#gastos_msg_error').html(dato);
			$('#gastos_msg_error').show('Fast');
	    }
	});
};
</script>
<hr>
<div class="row">
		<div class="col-md-3">
		<a type="button" class="btn btn-default btn-lg btn-block" href="index.php">VENTA</a><br>
		<a href="?Modulo=NuevoGastos" type="button" class="btn btn-default btn-lg btn-block">NUEVO</a><br>
		<a href="?Modulo=MGastos" type="button" class="btn btn-primary btn-lg btn-block"  >GASTOS</a><br>
		<a href="?Modulo=PGastos" type="button" class="btn btn-success btn-lg btn-block" >PROVISIONES</a>
		</div>

	<div class="col-md-9">
	<!--principio -->
		<div class="panel panel-success">
                	<div class="panel-heading">
                	        <h3 class="panel-title"> Nuevo Gasto </h3>
                	</div>
                	<div class="panel-body">
                	<form id="frm_gastos" class="form-horizontal">
										<div id="gastos_msg_error" class="alert alert-warning" role="alert" style="display:none;">
										</div>
										<div id="gastos_msg_ok" class="alert alert-warning" role="alert" style="display:none;">
										</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Descripción</label>
							<div class="col-md-9">
								<input type="text" maxlength="160" class="form-control limpia" id="gastos_descripcion" name="gastos_descripcion" autocomplete="off" maxlength="255">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Monto</label>
							<div class="col-md-9">
								<input type="text" maxlength="18" class="form-control limpia" id="gastos_monto" name="gastos_monto" autocomplete="off" maxlength="6">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 control-label" style="text-align: left;">
							<label class="col-md-6 control-label">Gasto Privisional </label>
							</div>
								<div class="col-md-6" style="padding-top: 5px;">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-default" id="si_requiere" onclick="">
											<input type="radio" name="gasto_s" value="1" id="radio_si" autocomplete="off" checked> &nbsp;&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;&nbsp;
										</label>
										<label class="btn btn-default" id="no_requiere" onclick="">
											<input type="radio" name="gasto_s" value="0" id="radio_no" autocomplete="off"> &nbsp;&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;&nbsp;
										</label>
									</div>
								</div>
						</div>

					</form>
					<div class="modal-footer">
				      	<img src="img/load-verde.gif" border="0" id="gastos_load" width="30" class="oculto" />
				        <button type="button" class="btn btn-gastos btn-default" data-dismiss="modal">Cancelar</button>
				        <button type="button" class="btn btn-gastos btn-primary" id="guardarGasto">Guardar</button>
			      	</div>
                	</div>
            	</div>
            	<div id="mensaje"></div>
	<!-- Final-->
	</div>
</div>
