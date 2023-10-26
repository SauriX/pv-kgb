<?
$sql="SELECT * FROM usuarios ORDER BY activo DESC, id_tipo_usuario ASC";
$q=mysql_query($sql);
if($s_tipo==1){
?>
<div class="row mb10">
		<div class="col-md-12 text-right">
			<a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevoUsuario" class="btn btn-success btn-sm">Agregar Nuevo</a>
		</div>
</div>

<div class="row">		
	<div class="col-md-12">
		<div class="panel panel-primary">
		
		  <div class="panel-heading">
		    <h3 class="panel-title">Usuarios</h3>
		  </div>
		  
		  <div class="panel-body">
		  <!-- Confirmación -->
		  <? if($_GET['msg']==1){ ?>
		  		<div class="alert alert-dismissable alert-success">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El usuario se ha agregado</p>
			  	</div>
		  <? }if($_GET['msg']==2){ ?>
		  		<div class="alert alert-dismissable alert-info">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El usuario se ha editado</p>
			  	</div>
		  <? } ?>
		  <!-- Contenido -->
		  		<table class="table table-striped table-hover ">
				      <thead>
				        <tr>
				          <th>Nombre</th>
				          <th>Usuario</th>
				          <th>Tipo Usuario</th>
				          <th></th>
				        </tr>
				      </thead>
				      <tbody>
				      <? while($ft=mysql_fetch_assoc($q)){ ?>
				        <tr>
				          <td><?=$ft['nombre']?></td>
				          <td><?=$ft['usuario']?></td>
				          <td><?=tipo_usuario($ft['id_tipo_usuario'])?></td>
				          <td align="right">
				          		<img src="img/load-azul.gif" border="0" id="load_<?=$ft['id_usuario']?>" width="19" class="oculto" />
				          	<? if($ft['activo']==1){ ?>
				          		<span class="label label-success link btn_<?=$ft['id_usuario']?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaUsuario" data-id="<?=$ft['id_usuario']?>">Editar</span>
				          		<?
					          	if($s_id_usuario!=$ft['id_usuario']){	
					          	?>
				          		 &nbsp; &nbsp; <span class="label label-danger link btn_<?=$ft['id_usuario']?>" onclick="javascript:Desactiva(<?=$ft['id_usuario']?>)">Desactivar</span>			
				          		<?
					          	}
					          	?>
				          	<? }else{ ?>
				          		<span class="label label-warning link btn_<?=$ft['id_usuario']?>" onclick="javascript:Activa(<?=$ft['id_usuario']?>)">Activar</span>
				          	<? } ?>
				          </td>
				        </tr>
				      <? } ?>
				      </tbody>
				  </table>
		  </div>
		  
		</div>	
	</div>
</div>






<!-- Modal -->
<div class="modal fade" id="NuevoUsuario">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Usuario</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control dat" name="nombre" id="nuevo_nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="usuario" class="col-md-3 control-label">Usuario</label>
				<div class="col-md-9">
					<input type="text" maxlength="24" class="form-control dat" name="usuario" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="password" class="col-md-3 control-label">Contraseña</label>
				<div class="col-md-9">
					<input type="text" maxlength="16" class="form-control dat" name="password" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="id_tipo_usuario" class="col-md-3 control-label">Tipo de usuario</label>
				<div class="col-md-9">
					<select class="form-control" name="id_tipo_usuario" id="id_tipo_usuario">
						<option>Seleccione una</option>
						<? $q=mysql_query("SELECT * FROM tipo_usuario"); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_tipo_usuario']?>"><?=$ft['tipo']?></option>
						<? } ?>
		  			</select>
				</div>
			</div>
			<div id="ver_permisos" class="oculto">
				<hr>
				<h4>Permisos</h4>
				<div class="form-group">
					<label for="devoluciones" class="col-md-4 control-label" style="padding-top:5px;">Permitir devoluciones</label>
					<div class="col-md-8">
						<input type="checkbox" class="check" name="devoluciones" id="devoluciones" >
					</div>
				</div>
				
				<div class="form-group">
					<label for="corte" class="col-md-4 control-label" style="padding-top:5px;">Permitir corte de caja</label>
					<div class="col-md-8">
						<input type="checkbox" class="check" name="cortes" id="corte" >
					</div>
				</div>
			</div>
			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevoUsuario()">Guardar Usuario</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="EditaUsuario">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Usuario</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error2"></div>
<!-- Loader -->
		<div class="row oculto" id="load_big">
			<div class="col-md-12 text-center" >
				<img src="img/load-verde.gif" border="0" width="50" />
			</div>
		</div>
<!--Formulario -->
		<form id="frm_edita" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control edit" id="nombre" name="nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="usuario" class="col-md-3 control-label">Usuario</label>
				<div class="col-md-9">
					<input type="text" maxlength="24" class="form-control edit" id="usuario" name="usuario" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="password" class="col-md-3 control-label">Contraseña</label>
				<div class="col-md-9">
					<input type="text" maxlength="16" class="form-control" name="password" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="id_tipo_usuario" class="col-md-3 control-label">Tipo de Usuario</label>
				<div class="col-md-9">
					<select class="form-control edit" id="id_tipo_usuario2" name="id_tipo_usuario">
						<? $q=mysql_query("SELECT * FROM tipo_usuario"); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_tipo_usuario']?>"><?=$ft['tipo']?></option>
						<? } ?>
		  			</select>
				</div>
			</div>
			
			<div id="ver_permisos2" class="oculto">
				<hr>
				<h4>Permisos</h4>
				<div class="form-group">
					<label for="devoluciones" class="col-md-4 control-label" style="padding-top:5px;">Permitir devoluciones</label>
					<div class="col-md-8">
						<input type="checkbox" class="check" name="devoluciones" id="devoluciones2" >
					</div>
				</div>
				
				<div class="form-group">
					<label for="cortes" class="col-md-4 control-label" style="padding-top:5px;">Permitir corte de caja</label>
					<div class="col-md-8">
						<input type="checkbox" class="check" name="cortes" id="cortes" >
					</div>
				</div>
			</div>
			
			<input type="hidden" name="id_usuario" id="id_usuario" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="img/load-verde.gif" border="0" id="load2" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="EditaUsuario()">Actualizar Usuario</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--- Js -->
<script>
$(function(){
	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');

	    $.ajax({
	   	url: "data/usuarios.php",
	   	data: 'id_usuario='+data_id,
	   	success: function(data){
	   		var datos = data.split('|');
	   		var nombre=datos[0];
	   		var usuario=datos[1];
	   		var id_tipo_usuario=datos[2];
	   		var cortes=datos[3];
	   		var devoluciones=datos[4];
	   		
	   		$('#nombre').val(nombre);
	   		$('#usuario').val(usuario);
	   		$('#id_tipo_usuario2').val(id_tipo_usuario);
	   		$('#id_usuario').val(data_id);
	   		
	   		if(id_tipo_usuario==2){
		   		$('#ver_permisos2').show();

		   		if(devoluciones==1){
			   		$('#devoluciones2').bootstrapSwitch('toggleState');
			   	}
		   		
		   		if(cortes==1){
			   		$('#cortes').bootstrapSwitch('toggleState');
			   	}
			   	
	   		}
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#EditaUsuario').on('hidden.bs.modal', function (e) {
		$('#ver_permisos2').hide();
		if($('#devoluciones2').prop("checked")){
			$('#devoluciones2').bootstrapSwitch('toggleState');
		}
		
		if($('#cortes').prop("checked")){
			$('#cortes').bootstrapSwitch('toggleState');
		}
	});
	
	$('#id_tipo_usuario').change(function(){
		var tipo_usuario	=	$('#id_tipo_usuario').val();
		if(tipo_usuario==2){
			$('#ver_permisos').show('Fast');
		}else{
			$('#ver_permisos').hide('Fast');
		}
	});
	
	$('#id_tipo_usuario2').change(function(){
		var tipo_usuario	=	$('#id_tipo_usuario2').val();
		if(tipo_usuario==2){
			$('#ver_permisos2').show('Fast');
		}else{
			$('#ver_permisos2').hide('Fast');
		}
	});
	
	$('#NuevoUsuario').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#NuevoUsuario').on('hidden.bs.modal',function(e){
		if($('#devoluciones').prop("checked")){
			$('#devoluciones').bootstrapSwitch('toggleState');
		}
		
		if($('#corte').prop("checked")){
			$('#corte').bootstrapSwitch('toggleState');
		}
		
		$('#id_tipo_usuario').val("0");
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
		$('#ver_permisos').hide();
	});
});

function EditaUsuario(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_usuario.php',datos,function(data){
	    if(data==1){
	    	$('#load2').hide();
			$('.btn').show();
			window.open("?Modulo=Usuarios&msg=2", "_self");
	    }else{
	    	$('#load2').hide();
			$('.btn').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
}
function Desactiva(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_usuario.php', { tipo: "0", id_usuario: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Usuarios", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function Activa(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_usuario.php', { tipo: "1", id_usuario: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Usuarios", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevoUsuario(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_usuario.php',datos,function(data){
	    if(data==1){
	    	$('#load').hide();
			$('.btn').show();
			window.open("?Modulo=Usuarios&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>

<? }else{ ?>
<div class="alert alert-dismissable alert-danger">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <h4>Error!</h4>
  <p><?=$s_nombre?> no tienes permisos para este módulo.</p>
</div>
<? } ?>