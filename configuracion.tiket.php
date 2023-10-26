<?
$sq_conf="SELECT * FROM configuracion";
$q_conf=mysql_query($sq_conf,$conexion);
$datos_conf=mysql_fetch_assoc($q_conf);
?>
<!-- Configuración General -->
<div class="modal fade" id="ConfiguracionGeneraltiket">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Configuración del ticked</h4>
      </div>
      <div class="modal-body" >
      	<div class="alert alert-danger oculto" role="alert" id="confi_msg_error"></div>
      	<div class="alert alert-success oculto" role="alert" id="confi_msg_ok"></div>
<!--Formulario -->
		<form id="frm_configuracion_ticked" style="Text-Align:center;" class="form-horizontal">
		<h5 class="modal-title">Encabezado</h5>
		<br>
		
			<div class="form-group " style="position :relative !important; left: 17.5% !important;">
				
				<div class="col-md-8 " >
					<input type="text"  maxlength="160" class="form-control" id="header_1" name="header_1" value="<?=$datos_conf['header_1']?>" placeholder="Linea 1" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_2" name="header_2" value="<?=$datos_conf['header_2']?>" placeholder="Linea 2" autocomplete="off">
				</div>
			</div>

			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_3" name="header_3" value="<?=$datos_conf['header_3']?>" placeholder="Linea 3" autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_4" name="header_4" value="<?=$datos_conf['header_4']?>" placeholder="Linea 4" autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_5" name="header_5" value="<?=$datos_conf['header_5']?>" placeholder="Linea 5" autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_6" name="header_6" value="<?=$datos_conf['header_6']?>" placeholder="Linea 6" autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_7" name="header_7" value="<?=$datos_conf['header_7']?>" placeholder="Linea 7" autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_8" name="header_8" value="<?=$datos_conf['header_8']?>" placeholder="Linea 8" autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_9" name="header_9" value="<?=$datos_conf['header_9']?>" placeholder="Linea 9" autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="header_10" name="header_10" value="<?=$datos_conf['header_10']?>" placeholder="Linea 10" autocomplete="off">
				</div>
			</div>
			
			<br>
			<h5 class="modal-title">Pie de página</h5>
			<br>
			
			
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_1" name="footer_1" value="<?=$datos_conf['footer_1']?>" placeholder="Linea 1"  autocomplete="off">
				</div>
			</div>
			
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_2" name="footer_2" value="<?=$datos_conf['footer_2']?>" placeholder="Linea 2"  autocomplete="off">
				</div>
			</div>

			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_3" name="footer_3" value="<?=$datos_conf['footer_3']?>" placeholder="Linea 3"  autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_4" name="footer_4" value="<?=$datos_conf['footer_4']?>" placeholder="Linea 4"  autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_5" name="footer_5" value="<?=$datos_conf['footer_5']?>"  placeholder="Linea 5" autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_6" name="footer_6" value="<?=$datos_conf['footer_6']?>" placeholder="Linea 6"  autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_7" name="footer_7" value="<?=$datos_conf['footer_7']?>" placeholder="Linea 7"  autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_8" name="footer_8" value="<?=$datos_conf['footer_8']?>" placeholder="Linea 8"  autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
			
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_9" name="footer_9" value="<?=$datos_conf['footer_9']?>" placeholder="Linea 9"  autocomplete="off">
				</div>
			</div>
			<div class="form-group" style="position :relative !important; left: 17.5% !important;" >
				
				<div class="col-md-8">
					<input type="text" maxlength="160" class="form-control" id="footer_10" name="footer_10" value="<?=$datos_conf['footer_10']?>"  placeholder="Linea 10" autocomplete="off">
				</div>
			</div>

		
			
			
			
			
			
			
			
			

			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="confi_load" width="30" class="oculto" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="ActualizaDatosTicked()">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
function ActualizaDatosTicked(){
	console.log("entro");
	$('#confi_msg_error').hide('Fast');
	$('#confi_msg_ok').hide('Fast');
	$('.btn').hide();
	$('#confi_load').show();
	var datos=$('#frm_configuracion_ticked').serialize();
	console.log("datos: "+datos);
	$.post('ac/tic.php',datos,function(data){
		console.log("data: "+data);
	    if(data==1){
			console.log("if: "+data);
			window.location = 'index.php';
	    }else{
	    	$('#confi_load').hide();
			$('.btn').show();
			$('#confi_msg_error').html(data);
			console.log("else: "+data);
			$('#confi_msg_error').show('Fast');
	    }
	});
};
</script>