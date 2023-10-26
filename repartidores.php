<?
$sql="SELECT * FROM repartidores ORDER BY activo DESC";
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
		    <h3 class="panel-title">Repartidores</h3>
		  </div>

		  <div class="panel-body">
		  <!-- Confirmación -->
		  <? if($_GET['msg']==1){ ?>
		  		<div class="alert alert-dismissable alert-success">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El repartidor se ha agregado</p>
			  	</div>
		  <? }if($_GET['msg']==2){ ?>
		  		<div class="alert alert-dismissable alert-info">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El repartidor se ha editado</p>
			  	</div>
		  <? } ?>
		  <!-- Contenido -->
		  		<table class="table table-striped table-hover ">
				      <thead>
				        <tr>
				          <th>Nombre</th>
				          <th>Teléfono</th>
				          <th></th>
				        </tr>
				      </thead>
				      <tbody>
				      <? while($ft=mysql_fetch_assoc($q)){ ?>
				        <tr>
				          <td><?=$ft['nombre']?></td>
				          <td><?=$ft['telefono']?></td>
				          <td align="right">
				          		<img src="img/load-azul.gif" border="0" id="load_<?=$ft['id_repartidor']?>" width="19" class="oculto" />
				          	<? if($ft['activo']==1){ ?>
				          		<span class="label label-success link btn_<?=$ft['id_repartidor']?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaUsuario" data-id="<?=$ft['id_repartidor']?>">Editar</span>
				          		<?
					          	if($s_id_repartidor!=$ft['id_repartidor']){
					          	?>
				          		 &nbsp; &nbsp; <span class="label label-danger link btn_<?=$ft['id_repartidor']?>" onclick="javascript:Desactiva(<?=$ft['id_repartidor']?>)">Desactivar</span>
				          		<?
					          	}
					          	?>
				          	<? }else{ ?>
				          		<span class="label label-warning link btn_<?=$ft['id_repartidor']?>" onclick="javascript:Activa(<?=$ft['id_repartidor']?>)">Activar</span>
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
        <h4 class="modal-title">Nuevo Repartidor</h4>
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
				<label for="usuario" class="col-md-3 control-label">Teléfono</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control dat" name="telefono" autocomplete="off">
				</div>
			</div>

		</form>

      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevoUsuario()">Guardar Repartidor</button>
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
        <h4 class="modal-title">Edita Repartidor</h4>
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
				<label for="usuario" class="col-md-3 control-label">Teléfono</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control edit" id="telefono" name="telefono" autocomplete="off">
				</div>
			</div>

			<input type="hidden" name="id_repartidor" id="id_repartidor" />
		</form>

      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load2" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="EditaUsuario()">Actualizar Repartidor</button>
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

		$.getJSON('data/repartidores.php?id_repartidor='+data_id ,function(data){
			console.log(data);

			var nombre		=	data.nombre;
			var telefono	=	data.telefono;
			var resultado	=	data.resultado;
			var id_repartidor	=	data.id_repartidor;

			$('#nombre').val(nombre);
	   		$('#telefono').val(telefono);
	   		$('#id_repartidor').val(data_id);

	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();

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
	$.post('ac/edita_repartidor.php',datos,function(data){
	    if(data==1){
	    	$('#load2').hide();
			$('.btn').show();
			window.open("?Modulo=Repartidores&msg=2", "_self");
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
	$.post('ac/activa_desactiva_repartidor.php', { tipo: "0", id_repartidor: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Repartidores", "_self");
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
	$.post('ac/activa_desactiva_repartidor.php', { tipo: "1", id_repartidor: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Repartidores", "_self");
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
	$.post('ac/nuevo_repartidor.php',datos,function(data){
	    if(data==1){
	    	$('#load').hide();
			$('.btn').show();
			window.open("?Modulo=Repartidores&msg=1", "_self");
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
