<div class="modal fade" id="nuevoCodigo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Código de Facturación</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="codigo_msg_error"></div>
      	<div class="alert alert-success oculto" role="alert" id="codigo_msg_ok"></div>
<!--Formulario -->
		<form id="frm_nuevo_codigo" class="form-horizontal">
			
			<div class="form-group">
				<label class="col-md-4 control-label">Monto</label>
				<div class="col-md-8">
					<input type="text" class="form-control limpia total2" id="codigo_monto" name="codigo_monto" autocomplete="off" maxlength="8" autofocus="1">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-4 control-label">Método de Pago</label>
				<div class="col-md-8">
					<select name="codigo_metodo_pago" id="codigo_metodo_pago" class="form-control">
						<option value="1">01 EFECTIVO</option>
						<option value="2">04 TARJETA DE CREDITO</option>
						<option value="5">28 TARJETA DE DEBITO</option>
						<option value="3">03 TRANSFERENCIA ELECTRONICA DE FONDOS</option>
						<option value="4">02 CHEQUE</option>
					</select>
				</div>
			</div>
			
			<div class="form-group oculto" id="codigo_muestra_digitos">
				<label class="col-md-4 control-label">Últimos 4 Digitos</label>
				<div class="col-md-8">
					<input type="text" class="form-control limpia total2" id="codigo_digitos" name="codigo_digitos" autocomplete="off" maxlength="4">
				</div>
			</div>
			
		</form>     
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="codigo_load" width="30" class="oculto" />
        <button type="button" class="btn btn-codigo btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-codigo btn-primary" onclick="nuevoCodigo()">Generar</button>
        <button type="button" class="btn btn-codigo-close oculto btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
$(function(){
	
	$('#codigo_monto').keyup(function () { 
	    var val = $(this).val();
	    if(isNaN(val)){
	         val = val.replace(/[^0-9\.]/g,'');
	         if(val.split('.').length>2) 
	             val =val.replace(/\.+$/,"");
	    }
	    $(this).val(val); 	    
	});
	
	$('#codigo_digitos').keyup(function(e) {
		if(e.keyCode==13){
			
			var metodo_pago = $('#codigo_metodo_pago').val();
			var digitos		= $('#codigo_digitos').val();
			
			if((metodo_pago>1)&&(metodo_pago<5)){
				if(!digitos){ 
					$('#codigo_msg_error').html('Sí tu método de pago es diferente a efectivo es necesario que escribas los últimos 4 digitos de la cuenta.').show();;
					return false;
				}
			}
			
			nuevoCodigo();
		}
	});
	
	$('#codigo_monto').keyup(function(e) {
		if(e.keyCode==13){
			
			if(!$('#codigo_monto').val()){
				return false;	
			}
			$('#codigo_metodo_pago').focus();
		}
	});
	
	$('#codigo_metodo_pago').keyup(function(e) {
		if(e.keyCode==13){
			var metodo_pago = $('#codigo_metodo_pago').val();
			if((metodo_pago>1)&&(metodo_pago<5)){
				$('#codigo_muestra_digitos').show();
				$('#codigo_digitos').focus();
			}else{
				nuevoCodigo();
			}
		}
	});
	
	$('#codigo_metodo_pago').change(function(e) {
		var metodo_pago = $('#codigo_metodo_pago').val();
		if(metodo_pago>1){
			$('#codigo_muestra_digitos').show();
			$('#codigo_digitos').focus();
		}else{
			$('#codigo_muestra_digitos').hide();
		}
	});
});
function nuevoCodigo(){
	
	var monto 		= $('#codigo_monto').val();
	var metodo_pago = $('#codigo_metodo_pago').val();
	var digitos	    = $('#codigo_digitos').val();
	var digitos_cad	= $('#codigo_digitos').val().length;
	
	if(!monto){ 
		$('#codigo_msg_error').html('Ingrese un monto para la factura.').show();;
		return false;
	}
	
	if(!metodo_pago){ 
		$('#codigo_msg_error').html('Seleccione un método de pago.').show();;
		return false;
	}
	
	if((metodo_pago>1)&&(metodo_pago<5)){
		if(!digitos){ 
			$('#codigo_msg_error').html('Sí tu método de pago es diferente a efectivo es necesario que escribas los últimos 4 digitos de la cuenta.').show();;
			return false;
		}

		if(digitos_cad!=4){
			$('#codigo_msg_error').html('Debes escribir los últimos <b>4</b> digitos de la cuenta.').show();;
			return false;
		}
	}
	
	
	
	
	$('#codigo_msg_error').hide('Fast');
	$('#codigo_msg_ok').hide('Fast');
	$('.btn-codigo').hide();
	$('#codigo_load').show();
	var datos=$('#frm_nuevo_codigo').serialize();
	$('#frm_nuevo_codigo').hide();
	$.post('ac/genera_codigo.php',datos,function(data){
		if(isNaN(data)){
			alert(data);
		}else{
			/*
			$('#frm_nuevo_codigo').hide();
			$('#codigo_msg_ok').html('Tu código de facturación es: <b>'+data+'</b>');
			$('#codigo_msg_ok').show();
			$('#codigo_load').hide();
			$('.btn-codigo-close').show()
			*/
			location.reload();
		}
	});
};
</script>