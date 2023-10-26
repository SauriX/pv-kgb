<?
	/*
$sq_conf="SELECT * FROM configuracion";
$q_conf=mysql_query($sq_conf);
$datos_conf=mysql_fetch_assoc($q_conf);
*/
?>
<!-- Configuración General -->
<div class="modal fade" id="CambiaPassword">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Cambio de Contraseña</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="pass_msg_error"></div>
      	<div class="alert alert-success oculto" role="alert" id="pass_msg_ok"></div>
<!--Formulario -->
		<form id="frm_cambio_pass" class="form-horizontal">
			
			<div class="form-group">
				<label for="establecimiento" class="col-md-4 control-label">Contraseña actual</label>
				<div class="col-md-8">
					<input type="password" maxlength="16" class="form-control" name="pass1" id="pass1" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="representante" class="col-md-4 control-label">Nueva contraseña</label>
				<div class="col-md-8">
					<input type="password" maxlength="16" class="form-control" name="pass2" id="pass2" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="telefono" class="col-md-4 control-label">Confirme la contraseña</label>
				<div class="col-md-8">
					<input type="password" maxlength="16" class="form-control" name="pass3" id="pass3" autocomplete="off">
				</div>
			</div>
			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="pass_load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_pass" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_pass" onclick="CambiaPassword()">Cambiar Contraseña</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
function CambiaPassword(){
	$('#pass_msg_error').hide('Fast');
	$('#pass_msg_ok').hide('Fast');
	$('.btn_pass').hide();
	$('#pass_load').show();
	
	var pass2 = $('#pass2').val();
	var pass3 = $('#pass3').val();
	
	if(pass2 != pass3){
		$('#pass_msg_error').html('La nueva contraseña y la confirmación no coinciden.');
		$('#pass_msg_error').show('Fast');
		$('#pass_load').hide();
		$('.btn_pass').show();
		return false;
	}
	var datos=$('#frm_cambio_pass').serialize();
	$.post('ac/cambia_password.php',datos,function(data){
	    if(data==1){
	    	$('#pass_load').hide();
	    	$('#pass_msg_ok').html('La contraseña se ha cambiado.');
			$('#pass_msg_ok').show('Fast');
			$('.btn_pass').show();
	    }else{
	    	$('#pass_load').hide();
			$('.btn_pass').show();
			$('#pass_msg_error').html(data);
			$('#pass_msg_error').show('Fast');
	    }
	});
};
</script>