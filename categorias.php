<?
$sql="SELECT * FROM categorias ORDER BY activo DESC, nombre ASC";
$q=mysql_query($sql);
$valida=mysql_num_rows($q);
?>
<div class="row mb10">
		<div class="col-md-12 text-right">
			<a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevaCategoria" class="btn btn-success btn-sm">Agregar Nueva</a>
		</div>
</div>
<? if($valida){ ?>
<div class="row">		
	<div class="col-md-12">
		<div class="panel panel-primary">
		
		  <div class="panel-heading">
		    <h3 class="panel-title">Categorías</h3>
		  </div>
		  
		  <div class="panel-body">
		  <!-- Confirmación -->
		  <? if($_GET['msg']==1){ ?>
		  		<div class="alert alert-dismissable alert-success">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>La categoría se ha agregado</p>
			  	</div>
		  <? }if($_GET['msg']==2){ ?>
		  		<div class="alert alert-dismissable alert-info">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>La categoría se ha editado</p>
			  	</div>
		  <? } ?>
		  <!-- Contenido -->
		  		<table class="table table-striped table-hover ">
				      <thead>
				        <tr>
				          <th>Nombre</th>
				          <th></th>
				        </tr>
				      </thead>
				      <tbody>
				      <? while($ft=mysql_fetch_assoc($q)){ ?>
				        <tr>
				          <td><?=$ft['nombre']?></td>
				          <td align="right">
				          		<img src="img/load-azul.gif" border="0" id="load_<?=$ft['id_categoria']?>" width="19" class="oculto" />
				          	<? if($ft['activo']==1){ ?>
				          		<span class="label label-success link btn_<?=$ft['id_categoria']?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaCategoria" data-id="<?=$ft['id_categoria']?>">Editar</span> &nbsp; &nbsp; 
				          		<span class="label label-danger link btn_<?=$ft['id_categoria']?>" onclick="javascript:Desactiva(<?=$ft['id_categoria']?>)">Desactivar</span>
				          	<? }else{ ?>
				          		<span class="label label-warning link btn_<?=$ft['id_categoria']?>" onclick="javascript:Activa(<?=$ft['id_categoria']?>)">Activar</span>
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
<? }else{ ?>
<br>
<div class="alert alert-warning" role="alert">Aún no se han creado categorias</div>
<? }?>

<!-- Modal -->
<div class="modal fade" id="NuevaCategoria">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nueva Categoría</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="nombre" id="nuevo_nombre" autocomplete="off" maxlength="20">
				</div>
			</div>
			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Impresora</label>
				
				<div class="col-md-9">
					<input type="text" class="form-control edit" id="nuevo_impresora" name="impresora" autocomplete="off" maxlength="20">
				</div>
			</div>
			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevaCategoria()">Guardar Categoría</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="EditaCategoria">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Categoría</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error2"></div>
<!--Formulario -->
		<form id="frm_edita" class="form-horizontal">
			
			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" class="form-control edit" id="nombre" name="nombre" autocomplete="off" maxlength="20">
				</div>
			</div>


			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Impresora</label>
				<div class="col-md-9">
					<input type="text" class="form-control edit" id="impresora" name="impresora" autocomplete="off" maxlength="20">
				</div>
			</div>
			
			<input type="hidden" name="id_categoria" id="id_categoria" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="img/load-verde.gif" border="0" id="load2" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="EditaCategoria()">Actualizar Categoría</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--- Js -->
<script>
$(function(){
	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");

	    var data_id = $(this).attr('data-id');

	    $.ajax({
	   	url: "data/categorias.php",
	   	data: 'id_categoria='+data_id,
	   	success: function(data){
	   		var datos = data.split('|');
			   $('#nombre').val(datos[0]);
			   $('#impresora').val(datos[3]);
	   		$('#id_categoria').val(data_id);
	   	},
	   	cache: false
	   });
	});
	
	$('#NuevaCategoria').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#NuevaCategoria').on('hidden.bs.modal',function(e){	
		$('#nuevo_nombre').val("");
		$('#msg_error').hide();
	});
});

function EditaCategoria(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_categoria.php',datos,function(data){
	    if(data==1){
	    	$('#load2').hide();
			$('.btn').show();
			window.open("?Modulo=Categorias&msg=2", "_self");
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
	$.post('ac/activa_desactiva_categoria.php', { tipo: "0", id_categoria: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Categorias", "_self");
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
	$.post('ac/activa_desactiva_categoria.php', { tipo: "1", id_categoria: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Categorias", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevaCategoria(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nueva_categoria.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Categorias&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>