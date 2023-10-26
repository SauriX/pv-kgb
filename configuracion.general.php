<?
$sq_conf="SELECT * FROM configuracion";
$q_conf=mysql_query($sq_conf,$conexion);
$datos_conf=mysql_fetch_assoc($q_conf);
?>
<!-- Configuración General -->
<div class="modal fade" id="ConfiguracionGeneral">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Configuración General</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="confi_msg_error"></div>
      	<div class="alert alert-success oculto" role="alert" id="confi_msg_ok"></div>
<!--Formulario -->
		<form id="frm_configuracion_general" class="form-horizontal">
			
			<div class="form-group">
				<label for="establecimiento" class="col-md-4 control-label">Establecimiento</label>
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="establecimiento" name="establecimiento" value="<?=$datos_conf['establecimiento']?>" autocomplete="off">
				</div>
			</div>

			
			<div class="form-group">
				<label for="establecimiento" class="col-md-4 control-label">RFC</label>
				<div class="col-md-8">
					<input type="text" maxlength="18" class="form-control" id="rfc" name="rfc" value="<?=$datos_conf['rfc']?>" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="representante" class="col-md-4 control-label">Representante</label>
				<div class="col-md-8">
					<input type="text" maxlength="120" class="form-control" id="representante" name="representante" value="<?=$datos_conf['representante']?>" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="telefono" class="col-md-4 control-label">Teléfono</label>
				<div class="col-md-8">
					<input type="text" maxlength="10" class="form-control" id="telefono" name="telefono" value="<?=$datos_conf['telefono']?>" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Dirección</label>
				<div class="col-md-8">
					<input type="text" maxlength="255" class="form-control" id="direccion" name="direccion" value="<?=$datos_conf['direccion']?>" autocomplete="off">
				</div>
			</div>

			<div class="form-group">
				<label for="autoprint" class="col-md-4 control-label" style="padding-top:5px;">Paquetes</label>
				<div class="col-md-8">
					<input type="checkbox" class="check" name="paquete" id="paquete" <? if($datos_conf['paquetes']==1){ echo "checked"; } ?> autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="autoprint" class="col-md-4 control-label" style="padding-top:5px;">Comandas Impresora Única:</label>
				<div class="col-md-8">
					<input type="checkbox" class="check" name="comandaim" id="acomandaim" <? if($datos_conf['comandain']==1){ echo "checked"; } ?> autocomplete="off">
				</div>
			</div>

			<div class="form-group">
				<label for="autoprint" class="col-md-4 control-label" style="padding-top:5px;">Autocobro</label>
				<div class="col-md-8">
					<input type="checkbox" class="check" name="autocash" id="autocash" <? if($datos_conf['auto_cobro']==1){ echo "checked"; } ?> autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="autobackup" class="col-md-4 control-label" style="padding-top:5px;">Respaldo en la Nube</label>
				<div class="col-md-8">
					<input type="checkbox" class="check" name="autobackup" id="autobackup" <? if($datos_conf['autobackup']==1){ echo "checked"; } ?> autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="impresora_sd" class="col-md-4 control-label" style="padding-top:5px;">Impresora Servicio a Domicilio</label>
				<div class="col-md-8">
					<input type="text" maxlength="64" class="form-control" id="impresora_sd" name="impresora_sd" value="<?=$datos_conf['impresora_sd']?>" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="impresora_cuentas" class="col-md-4 control-label" style="padding-top:5px;">Impresora de Comandas</label>
				<div class="col-md-8">
					<input type="text" maxlength="64" class="form-control" id="impresora_cuentas" name="impresora_cuentas" value="<?=$datos_conf['impresora_cuentas']?>" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="impresora_cortes" class="col-md-4 control-label" style="padding-top:5px;">Impresora de Cortes</label>
				<div class="col-md-8">
					<input type="text" maxlength="64" class="form-control" id="impresora_cortes" name="impresora_cortes" value="<?=$datos_conf['impresora_cortes']?>" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="impresora_cortes" class="col-md-4 control-label" style="padding-top:5px;">                   </label>
				<div class="col-md-8">
						
							<span class="input-group-addon">Local</span>
							<select name="id" id="id" class="form-control">
								<option value="1" selected>Magisterio</option>
								<option value="2">Carranza</option>
							</select>
						
					<input type="button" maxlength="64" class="form-control" onclick="ceros();"  value="Vaciar existencias"  autocomplete="off">
				</div>
			</div>

			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="confi_load" width="30" class="oculto" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="ActualizaDatosGenerales()">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
function ActualizaDatosGenerales(){
	$('#confi_msg_error').hide('Fast');
	$('#confi_msg_ok').hide('Fast');
	$('.btn').hide();
	$('#confi_load').show();
	var datos=$('#frm_configuracion_general').serialize();
	$.post('ac/configuracion.general.php',datos,function(data){
	    if(data==1){
			window.location = 'index.php';
	    }else{
	    	$('#confi_load').hide();
			$('.btn').show();
			$('#confi_msg_error').html(data);
			$('#confi_msg_error').show('Fast');
	    }
	});
};


function ceros(){
	var datos=$('#frm_configuracion_general').serialize();
	$.post('ac/vaciar_existencias.php',datos,function(data){
	    if(data==1){
			alert('se han restablecido las existencias');
			window.location = 'index.php';
	    }else{
	    	$('#confi_load').hide();
			$('.btn').show();
			$('#confi_msg_error').html(data);
			$('#confi_msg_error').show('Fast');
	    }
	});
};
</script>