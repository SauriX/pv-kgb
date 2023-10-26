<?
$sq_productos="SELECT * FROM  `cupones`";
$q_productos=mysql_query($sq_productos);
?>
<div class="row mb10">
	<div class="col-md-12 text-right">
	    <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#AgregaProducto">Agregar Cupon Nuevo</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		    <h3 class="panel-title">Cupones</h3>
		  </div>

		  <div class="panel-body">
		  <!-- Confirmación -->
		  <? if($_GET['msg']==1){ ?>
		  		<div class="alert alert-dismissable alert-success">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El cupón se ha agregado</p>
			  	</div>
		  <? }if($_GET['msg']==2){ ?>
		  		<div class="alert alert-dismissable alert-info">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El cupón se ha editado</p>
			  	</div>
		  <? } ?>
		  <!-- Contenido -->
			  <table class="table table-bordered table-hover" id="tabla">
			  <thead>
			    <tr>
			      <th width="10">ID</th>
			      <th>Cupón</th>
			      <th width="20">Porcentaje</th>
			      <th width="80"></th>
			    </tr>
			  </thead>
			  <tbody>
			  <? while($ft=mysql_fetch_assoc($q_productos)){ ?>
			    <tr>
			      <td><?=$ft['id_cupon']?></td>
			      <td><?=$ft['cupon']?></td>
			      <td align="right"><?=fnum($ft['porcentaje'])?> %</td>
				  	<td align="center">
							<img src="img/load-azul.gif" border="0" id="load_<?=$ft['id_cupon']?>" width="19" class="oculto" />
				    <? if($ft['activo'] == 1){ ?>
						<div class="btn-group btn_<?=$proyecto->id_proyecto?>">
							<a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
								<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="javascript:;"  data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaProducto" data-id="<?=$ft['id_cupon']?>"> Editar </a>
								</li>
								<li>
									<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" onclick="javascript:Desactiva(<?=$ft['id_cupon']?>)" >Descativar</a>
								</li>
							</ul>
						</div>
				    <? }else{ ?>
				    	<span class="label label-warning link btn_<?=$ft['id_cupon']?>" onclick="javascript:Activa(<?=$ft['id_cupon']?>)">Activar</span>
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
<div class="modal fade" id="AgregaProducto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Nuevo Cupón</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
				<form id="frm" class="form-horizontal">

					<div class="form-group">
						<label for="precio_venta" class="col-md-3 control-label">Cupón</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="cupon" autocomplete="off">
						</div>
					</div>

					<div class="form-group">
						<label for="precio_venta" class="col-md-3 control-label">Porcentaje</label>
						<div class="col-md-9">
							<input type="text" class="form-control solo_numero" name="porcentaje" maxlength="3" autocomplete="off">
						</div>
					</div>

				</form>
			</div>

			<div class="modal-footer">
				<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn-modal" onclick="GuardaProducto()">Guardar Cupón</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="EditaProducto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Edita Producto</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error2"></div>
				<div class="row oculto" id="load_big">
					<div class="col-md-12 text-center" >
						<img src="img/load-verde.gif" border="0" width="50" />
					</div>
				</div>

				<form id="frm_edita" class="form-horizontal">
					<input type="hidden" name="id_cupon" id="id_cupon" />

					<div class="form-group">
						<label for="" class="col-md-3 control-label">Cupon</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="cupon" id="cupon" autocomplete="off">
						</div>
					</div>

					<div class="form-group">
						<label for="" class="col-md-3 control-label">Porcentaje</label>
						<div class="col-md-9">
							<input type="text" class="form-control solo_numero" name="porcentaje" id="porcentaje" maxlength="3" autocomplete="off">
						</div>
					</div>

				</form>
			</div>

			<div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load2" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn-modal" onclick="EditaProducto()">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!--- Js -->
<script>
$(document).ready(function() {
    $('#tabla').dataTable({
        "order": [[ 0, "asc" ]],
        "iDisplayLength": 100

    });
});
function GuardaProducto(){
	$('#msg_error').hide('Fast');
	$('.btn').hide();
	$('#load').show();
	var datos=$('#frm').serialize();
	$.post('ac/nuevo_cupon.php',datos,function(data){
	    if(data==1){
	    	$('#msg_error').hide('Fast');
			window.open("?Modulo=Cupones&msg=1", "_self");
	    }else{
				$('#load').hide();
				$('.btn').show();
				$('#msg_error').html(data);
				$('#msg_error').show('Fast');
	    }
	});
};
function EditaProducto(){
	$('#msg_error2').hide('Fast');
	$('.btn').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_cupon.php',datos,function(data){
	    if(data==1){
	    	$('#msg_error2').hide('Fast');
			window.open("?Modulo=Cupones&msg=2", "_self");
	    }else{
	    	$('#load2').hide();
			$('.btn').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
};


$(function(){
	$(document).on('click', '[data-id]', function () {
		//Precargamos
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	  var data_id = $(this).attr('data-id');
		$.ajax({
			url: "data/cupones.php",
			data: 'id_cupon='+data_id,
			success: function(data){
				var datos = data.split('|');

				$('#cupon').val(datos[1]);
				$('#porcentaje').val(datos[2]);
	   		$('#id_cupon').val(data_id);

				$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
			},
			cache: false
		});
	});

	$('#EditaProducto').on('hidden.bs.modal', function (e) {
			$('#msg_error2').hide();
			$('#msg_error').hide();
			$('.btn').show();
			$('#msg_error').hide('Fast');

			$('#categoria_nueva').hide('Fast');
			$('#frm').show('Fast');
			$('.btn-modal').show('Fast');
			$('#nombre_categoria').val("");
  	});

		$('.solo_numero').keyup(function () {
			this.value = this.value.replace(/[^0-9\.]/g,'');
		});

});


function Desactiva(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_cupones.php', { tipo: "0", id_cupon: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Cupones", "_self");
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
	$.post('ac/activa_desactiva_cupones.php', { tipo: "1", id_cupon: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Cupones", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
</script>
