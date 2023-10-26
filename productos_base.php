<?

include('../includes/db.php');
include('../includes/funciones.php');
 




     //insumos
	 $tags = array();
	 $tags2 = array();
	 $tags3 = array();
	 
	

  //Genera la lista de ingredientes
	 $sql_pro= "SELECT * FROM productos_base 
	 LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad";
	 
	 $q_pro = mysql_query($sql_pro);
	 
	 while($pro= mysql_fetch_assoc($q_pro)){
	   
		$id_base=$pro['id_base'];
	 
		$producto=$pro['producto'];
  
		$abreviatura = $pro['abreviatura'];
  
		$tags[] = array(
		   "id"=> $id_base,
		   "producto" => $producto,
		   "cantidad" => 0,
		   "unidad" =>" ".$abreviatura,
		   "mermado"=>0,
		   "consumido"=>0,
		   "dotado"=>0,
		   "previa"=>0
		   
		);
	 }

  //Termina de generar la lista de ingredientes
  

  
  //Optiene la dotaciones de productos y los añade a existencias
	 $sql_dotacion= "SELECT * FROM dotaciones WHERE contar =1";
	 
	 $q = mysql_query($sql_dotacion);
	 
	 
	 
	 while($dotacion = mysql_fetch_assoc($q)){
	 $id_dotacion= $dotacion['id_dotacion'];
	 $corte=$dotacion['id_corte'];
	 
	 $sql_detalle="SELECT SUM(cantidad) as cantidad,id_producto,producto,abreviatura  FROM dotaciones_detalle
	  LEFT JOIN productos_base ON  dotaciones_detalle.id_producto = productos_base.id_base
	  LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad 
	  WHERE id_dotacion = $id_dotacion GROUP BY id_producto";
	  //echo($sql_detalle);
	 $detalle_q=mysql_query($sql_detalle);
	 while($ft=mysql_fetch_assoc($detalle_q)){
				$id_base=$ft['id_producto'];
				$cantidad=$ft['cantidad'];
				$producto=$ft['producto'];
				$abreviatura = $ft['abreviatura'];
			   
				  
				 for($i=0; $i<count($tags); $i++)
				 {
				   
				  if($tags[$i]['id']==$id_base){
					$index=$i;
				 
				   
					$tags[$i]['cantidad']=$tags[$i]['cantidad']+($cantidad);
					
					$consumo=$tags[$i]['dotado']+$cantidad;
				   
				  if($corte==$id_corte){
				   
					 $tags[$i]['dotado']=$consumo;	
				  }
					
		   
				  }
				 }
				  
					
				 
				   
		   
	 }
	 }
	 // fin de la dotaciones
	 
  
	 //mermas
	 
	 $sql_merma= "SELECT * FROM merma  WHERE contar =1";
	 
	 $q_merma = mysql_query($sql_merma);
	 
	 
	 while($merma = mysql_fetch_assoc($q_merma)){
	 $id_merma= $merma['id_merma'];
	 $corte=$merma['id_corte'];
	 $sql_detallem="SELECT SUM(cantidad) as cantidad,id_producto,producto,abreviatura  FROM merma_detalle
	 LEFT JOIN productos_base ON  merma_detalle.id_producto = productos_base.id_base
	 LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad 
	 WHERE id_merma = $id_merma GROUP BY id_producto";
	 
	 
	 //echo($sql_detalle);
	 $detallem_q=mysql_query($sql_detallem);
	 while($ft2=mysql_fetch_assoc($detallem_q)){
			  $id_base=$ft2['id_producto'];
			  $cantidad=$ft2['cantidad'];
			  $producto=$ft2['producto'];
			  $abreviatura = $ft['abreviatura'];
		
				 
			   for($i=0; $i<count($tags); $i++)
				{
				 
				 if($tags[$i]['id']==$id_base){
				  $index=$i;
			   
				 
				  $tags[$i]['cantidad']=$tags[$i]['cantidad']-($cantidad);
				  
					 
				  
				  
																 
				 }
				}
		   
				 
		 
	 }
	 }
	 
	 //fin mermas
	
	 //ventas
	 
	 $sql_venta= "SELECT * FROM ventas WHERE  contar =1 ";
	 
	 $q_venta= mysql_query($sql_venta);
	 
	 
	 while($venta= mysql_fetch_assoc($q_venta)){
	 $id_venta= $venta['id_venta'];
	 $corte = $venta['id_corte'];
	 $sql_detallev="SELECT * FROM  venta_detalle WHERE  id_venta =$id_venta AND id_producto!=0";
	 $q_ventade= mysql_query($sql_detallev);
	 
	 
	 
	 
	 while($ft3=mysql_fetch_assoc($q_ventade)){
	   $id_base=$ft3['id_producto'];
	   
	   $cantidad=$ft3['cantidad'];
	   
	 $sql_producto="SELECT * FROM  productosxbase WHERE  id_producto=$id_base";
	 
	 $producto_q=mysql_query($sql_producto);
	 
		  while($ft4=mysql_fetch_assoc($producto_q)){
			
			  $id_base=$ft4['id_base'];
			  $cantidad2=$ft4['cantidad']*$cantidad;
		   
				
		
				 
			   for($i=0; $i<count($tags); $i++)
				{
				 
				 if($tags[$i]['id']==$id_base){
				  $index=$i;
			   
				  $cantidad3 =$tags[$i]['cantidad']-($cantidad2);
				  
					$tags[$i]['cantidad']=$cantidad3;
				 
				   
				  $consumo=$tags[$i]['consumido']+$cantidad2;
				 
					 $tags[$i]['consumido']=$consumo;	
				
				  
				 
		  
				 }
				}
  
  
  
		   }
				 
		 
	 }
	 }
	 //fin insumos



?>
<style>
.oculto{
	display: none;
}
.link{
	cursor: pointer;
}
</style>
<div class="row mb10">
	<div class="col-md-12 text-right">
	    <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#AgregaProducto">Agregar Producto Nuevo</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		    <h3 class="panel-title">Ingredientes</h3>
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

			      <th width="45%">Producto</th>
			      <th width="15%">Existencia</th>

			      <th width="15%"></th>
			    </tr>
			  </thead>
			  <tbody>
			  <? foreach($tags as $ingrediente){

			  ?>
			    <tr >

			      <td><?=$ingrediente['producto']?></td>
			      <td><?=$ingrediente['cantidad']." ".$ingrediente['unidad']?></td>


				  <td align="right">
				  		<img src="img/load-azul.gif" border="0" id="load_<?=$ft['id_producto']?>" width="19" class="oculto" />

						<div class="btn-group btn_<?=$proyecto->id_proyecto?>">
                                        <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">


                                            <li>
											<a href="javascript:;"  data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaProducto" data-id="<?=$ingrediente['id']?>"> Editar </a>
											</li>


											<li>
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" onclick="javascript:Desactiva(<?=$ingrediente['id']?>)" >Eliminar</a>
                                            </li>
                                        </ul>
                                    </div>


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
				<label for="codigo" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="nombre" name="nombre" maxlength="120" autocomplete="off">
				</div>
			</div>



            <div class="form-group">
				<label for="categoria" class="col-md-3 control-label">Unidad</label>
				<div class="col-md-7">
					<select class="form-control" id="id_unidad" name="id_unidad">
						<option selected="selected">Seleccione una</option>

						<? $q=mysql_query("SELECT * FROM unidades"); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_unidad']?>"><?=$ft['unidad']?></option>
						<? } ?>
		  			</select>
				</div>
			<!--	<div class="col-md-2 text-right">
					<button type="button" class="btn btn-primary" onclick="NuevaCategoria()">
						<span class="glyphicon glyphicon-plus"></span>
  					</button>
				</div>-->
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

<!--<form id="categoria_nueva" class="form-horizontal oculto">

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
		</form>-->

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
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="nombre3" name="nombre" maxlength="120" autocomplete="off">
				</div>
			</div>








			<div class="form-group">
				<label for="categoria" class="col-md-3 control-label">Unidades</label>
				<div class="col-md-9">
					<select class="form-control" id="id_unidad2" name="id_unidad">
                    <option selected="selected">Seleccione una</option>
						<? $q=mysql_query("SELECT * FROM unidades "); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_unidad']?>"><?=$ft['unidad']?></option>
						<? } ?>
		  			</select>
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
	$.post('ac/nuevo_productobase.php',datos,function(data){
	    if(data==1){
	    	$('#msg_error').hide('Fast');
			window.open("?Modulo=ProductosBase&msg=1", "_self");
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
	$.post('ac/editar_productobase.php',datos,function(data){
	    if(data==1){
	    	$('#msg_error2').hide('Fast');
			window.open("?Modulo=ProductosBase&msg=2", "_self");
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
	   	url: "data/producto_base.php",
	   	data: 'id_producto='+data_id,
	   	success: function(data){
	   		var datos = data.split('|');
	   		$('#id_categoria2').val(datos[0]);

	   		$('#nombre3').val(datos[1]);
	   		$('#precio_venta').val(datos[2]);
               $('#id_unidad2').val(datos[3]);



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

	var r = confirm("¿Estas seguro que quieres eliminar el ingrediente?");
	if(r==true){
		$.post('ac/eliminar_productobase.php',{id_producto:id},function(data){
			if(data==1){
				window.open("?Modulo=Productos_base", "_self");
			}else{
				$("#load_"+id+"").hide();
				$(".btn_"+id+"").show();
				alert(data);
			}
		});
	}else{
		return false;
	}


}

function Aextras(id){

	window.open("?Modulo=extras&id="+id, "_self");

}
function Asin(id){

window.open("?Modulo=sin&id="+id, "_self");

}
function EsinEx(id){

	window.open("?Modulo=ver&id="+id, "_self");

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
