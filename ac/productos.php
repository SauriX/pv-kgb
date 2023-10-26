<?
$sq_productos="SELECT productos.*, categorias.nombre AS categoria FROM productos
LEFT JOIN categorias ON categorias.id_categoria=productos.id_categoria";
$q_productos=mysql_query($sq_productos);
?>
<div class="row mb10">
	<div class="col-md-12 text-right">
	    <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#AgregaProducto">Agregar Producto Nuevo</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		    <h3 class="panel-title">Productos</h3>
		  </div>

		  <div class="panel-body">
		  <!-- Confirmación -->
		  <? if($_GET['msg']==1){ ?>
		  		<div class="alert alert-dismissable alert-success">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El producto se ha agregado</p>
			  	</div>
		  <? }if($_GET['msg']==2){ ?>
		  		<div class="alert alert-dismissable alert-info">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El producto se ha editado</p>
			  	</div>
		  <? } ?>
		  <!-- Contenido -->
			  <table class="table table-striped table-hover " id="tabla" cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th width="15%">Código</th>
				  <th width="15%">Tipo</th>
			      <th width="45%">Producto</th>
			      <th width="15%">Categoría</th>
			
			      <th width="5%" class="text-right">Precio</th>
			      <th width="15%"></th>
			    </tr>
			  </thead>
			  <tbody>
			  <? while($ft=mysql_fetch_assoc($q_productos)){
			  	 
			  ?>
			    <tr >
			      <td><?=$ft['codigo']?></td>

				  <td>
				  <?
				  if($ft['extra']==1){
					  echo('Extra');
				  }

				  if($ft['sinn']==1){
					echo('Sin');
				}


				if($ft['paquete']==1){
					echo('Paquete');
				}
				  
				
				if($ft['paquete']==0 & $ft['extra']==0 & $ft['sinn']==0){
					echo('Producto');
				}

				  ?>
				  </td>
                       

			      <td><?=$ft['nombre']?></td>
			      <td><?=$ft['categoria']?></td>
			    
			      <td align="right"><?=fnum($ft['precio_venta'])?></td>
				  <td align="right">
				  		<img src="img/load-azul.gif" border="0" id="load_<?=$ft['id_producto']?>" width="19" class="oculto" />
				    <? if($ft['activo']==1){ ?>
						<div class="btn-group btn_<?=$proyecto->id_proyecto?>">
                                        <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
	                                      
                                           
                                            <li>
											<a href="javascript:;"  data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaProducto" data-id="<?=$ft['id_producto']?>"> Editar </a>
											</li>
											<?if($ft['sinn']==0 & $ft['extra']==0 & $ft['paquete']==0){?>
								
											<li>
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" onclick="javascript:EsinEx(<?=$ft['id_producto']?>)" >Ver Extras/Sin</a>
                                            </li>
										    <? $sql_conf="SELECT * FROM configuracion";
						   						$query_conf=mysql_query($sql_conf);
						   						$conf=mysql_fetch_assoc($query_conf);
						  						 $insumo=$conf['insumos'];
												if($insumo==1){?>
											<li>
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" onclick="javascript:Eing(<?=$ft['id_producto']?>)" >Ver Ingredientes</a>
											</li>
												<?}?>
											<?}?>

                                            <?if($ft['paquete']==1 ){?>
										
											
											<li>
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" onclick="javascript:Epaquete(<?=$ft['id_producto']?>)" >Ver productos</a>
                                            </li>
										
											
											
											<?}?>



											<li>
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" onclick="javascript:Desactiva(<?=$ft['id_producto']?>)" >Desactivar</a>
                                            </li>
                                        </ul>
                                    </div>
			
				    <? }else{ ?>
				    	<span class="label label-warning link btn_<?=$ft['id_producto']?>" onclick="javascript:Activa(<?=$ft['id_producto']?>)">Activar</span>
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
        <h4 class="modal-title">Nuevo Producto</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm" class="form-horizontal">
			<div class="form-group">
				<label for="categoria" class="col-md-3 control-label">Categoría</label>
				<div class="col-md-7">
					<select class="form-control" id="id_categoria" name="id_categoria">
						<option selected="selected">Seleccione una</option>
						<? $q=mysql_query("SELECT * FROM categorias WHERE activo=1"); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_categoria']?>"><?=$ft['nombre']?></option>
						<? } ?>
		  			</select>
				</div>
				<div class="col-md-2 text-right">
					<button type="button" class="btn btn-primary" onclick="NuevaCategoria()">
						<span class="glyphicon glyphicon-plus"></span>
  					</button>
				</div>
			</div>

			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Código</label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="codigo2" name="codigo" maxlength="120" autocomplete="off">
				</div>
			</div>

			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="nombre" maxlength="120" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label for="Tipo" class="col-md-3 control-label">Tipo</label>
				<div class="col-md-9">
				<div class="radio">
				<label class="radio-inline"><input name="tipo" id="tipo1" type="radio" value="Producto">Producto</label>
				<label class="radio-inline"><input name="tipo" id="tipo2"  type="radio" value="Extra">Extra</label>
				<label class="radio-inline"><input name="tipo" id="tipo3" 	 type="radio" value="Sine">Sin</label>

				<?
						$pack=0;
						$sql74="SELECT paquetes FROM configuracion";
								$query74 = mysql_query($sql74);
								while($ft=mysql_fetch_assoc($query74)){
								$pack = $ft['paquetes'];
								}
						
						if($pack==1){
				?>
				<label class="radio-inline"><input name="tipo" id="tipo4" 	 type="radio" value="	">paquete</label>
			
			
						<?}?>
				</div>
					
				</div>
			</div>

		       

			<div class="form-group">
				<label for="precio_venta" class="col-md-3 control-label">Precio Venta</label>
				<div class="col-md-9">
					<input type="text" class="form-control solo_numero" name="precio_venta" maxlength="8" autocomplete="off">
				</div>
			</div>
			<!--
			<div class="form-group">
				<label for="precio_compra" class="col-md-3 control-label">Precio Compra</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="precio_compra" maxlength="8">
				</div>
			</div>
			-->
		</form>
<!-- Agrega nueva categoría -->

<form id="categoria_nueva" class="form-horizontal oculto">
			
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
            <div class="col-md-3 "  style="float:right;">
			<button type="button" class="btn btn-danger btn_n_categoria" onclick="CancelaCategoria()">
				    	<span class="glyphicon glyphicon-remove"></span>
				    </button>
			<button type="button" class="btn btn-success btn_n_categoria" onclick="GuardaCategoria()">
				    	<span class="glyphicon glyphicon-ok"></span>
				    </button>
					</div>    
		</form>

<!-- Termina el agregar nueva categoría -->
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn-modal" onclick="GuardaProducto()">Guardar Producto</button>
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
<!--Formulario -->
		<div class="row oculto" id="load_big">
			<div class="col-md-12 text-center" >
				<img src="img/load-verde.gif" border="0" width="50" />
			</div>
		</div>

		<form id="frm_edita" class="form-horizontal">
			<div class="form-group">
				<label for="categoria" class="col-md-3 control-label">Categoría</label>
				<div class="col-md-9">
					<select class="form-control" id="id_categoria2" name="id_categoria">
						<? $q=mysql_query("SELECT * FROM categorias WHERE activo=1"); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_categoria']?>"><?=$ft['nombre']?></option>
						<? } ?>
		  			</select>
				</div>
			</div>

			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Código</label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="codigo" name="codigo" maxlength="120" autocomplete="off">
				</div>
			</div>

			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="nombre" name="nombre" maxlength="120" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label for="Tipo" class="col-md-3 control-label">Tipo</label>
				<div class="col-md-9">
				<div class="radio">
				<label class="radio-inline"><input name="tipo" id="editatipo1" type="radio" value="Producto">Producto</label>
				<label class="radio-inline"><input name="tipo" id="editatipo2"  type="radio" value="Extra">Extra</label>
				<label class="radio-inline"><input name="tipo" id="editatipo3" 	 type="radio" value="Sine">Sin</label>
				<?
				$pack=0;
				$sql74="SELECT paquetes FROM configuracion";
						$query74 = mysql_query($sql74);
						while($ft=mysql_fetch_assoc($query74)){
						$pack = $ft['paquetes'];
						}
				
				if($pack==1){?>
				<label class="radio-inline"><input name="tipo" id="editatipo4" 	 type="radio" value="pack">Paquete</label>
						<?}?>
				</div>
					
				</div>
			</div>


			<div class="form-group">
				<label for="precio_venta" class="col-md-3 control-label">Precio Venta</label>
				<div class="col-md-9">
					<input type="text" class="form-control solo_numero" id="precio_venta" name="precio_venta" maxlength="8" autocomplete="off">
				</div>
			</div>
			<!--
			<div class="form-group">
				<label for="precio_compra" class="col-md-3 control-label">Precio Compra</label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="precio_compra" name="precio_compra" maxlength="8">
				</div>
			</div>
			-->
			<input type="hidden" name="id_producto" id="id_producto" />
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
        "order": [[ 2, "asc" ]],
        "iDisplayLength": 100

    });
});
function NuevaCategoria(){
	$('#msg_error').hide('Fast');
	$('.btn-modal').hide();
	$('#frm').hide('Fast');
	$('#categoria_nueva').show('Fast');
	$('#nombre_categoria').focus();
	$('#nombre_categoria').val("");
	$('#load_categoria').hide()
			$('.btn_n_categoria').show();
};
function CancelaCategoria(){
	$('#msg_error').hide('Fast');
	$('#categoria_nueva').hide('Fast');
	$('#frm').show('Fast');
	$('.btn-modal').show('Fast');
	$('#nombre_categoria').val("");
};
function GuardaCategoria(){
	$('.btn_n_categoria').hide()
	$('#load_categoria').show();
	var nombre = $('#nombre_categoria').val();
	$.post('ac/nueva_categoria2.php', { nombre: ""+nombre+"" },function(data){
		//destripamos el resultado
		var datos = data.split('|');
	   	var tipo = datos[0];
	   	var id_categoria = datos[1];
	   	var categoria = datos[2];
	   	var mensaje = datos[3];

		if(tipo==1){
			$('#id_categoria').append(new Option(categoria, id_categoria, true, true));
			$('#msg_error').hide('Fast');
			$('#categoria_nueva').hide('Fast');
			$('#frm').show('Fast');
			$('.btn-modal').show('Fast');
			$('#nombre_categoria').val("");
			$('#codigo2').focus();
		}else{
			$('#msg_error').html(mensaje);
			$('#msg_error').show('Fast');
			$('#load_categoria').hide()
			$('.btn_n_categoria').show();
		}
	});
};
function GuardaProducto(){
	$('#msg_error').hide('Fast');
	$('.btn').hide();
	$('#load').show();
	var datos=$('#frm').serialize();
	$.post('ac/nuevo_producto.php',datos,function(data){
	    if(data==1){
	    	$('#msg_error').hide('Fast');
			window.open("?Modulo=Productos&msg=1", "_self");
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
	$.post('ac/edita_producto.php',datos,function(data){
	    if(data==1){
	    	$('#msg_error2').hide('Fast');
			window.open("?Modulo=Productos&msg=2", "_self");
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
	   	url: "data/producto.php",
	   	data: 'id_producto='+data_id,
	   	success: function(data){
	   		var datos = data.split('|');
	   		$('#id_categoria2').val(datos[0]);
	   		$('#codigo').val(datos[1]);
	   		$('#nombre').val(datos[2]);
	   		$('#precio_venta').val(datos[3]);
	   		$('#precio_compra').val(datos[4]);
			   if(datos[5]==1){
			   $("#editatipo3").prop("checked", true);   
			   }
			   if(datos[6]==1 ){
				$("#editatipo2").prop("checked", true); 
			   }
               if(datos[7]==1 ){
				$("#editatipo4").prop("checked", true); 
			   }

			   if(datos[5]!=1 && datos[6]!=1  && datos[7]!=1  ){
				$("#editatipo1").prop("checked", true); 
			   }

	   		$('#id_producto').val(data_id);
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

  	$('#AgregaProducto').on('hidden.bs.modal', function (e) {
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
	$.post('ac/activa_desactiva_producto.php', { tipo: "0", id_producto: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Productos", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}


function EsinEx(id){

	window.open("?Modulo=ver&id="+id, "_self");

}

function Eing(id){

window.open("?Modulo=ver_ingrediente&id="+id, "_self");

}




function Epaquete(id){

	window.open("?Modulo=ver_producto&id="+id, "_self");

}



 
function Lista(id){
	
	window.open("lista.php?id="+id);

}

function Activa(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_producto.php', { tipo: "1", id_producto: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Productos", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
</script>