<?php
include('includes/dbline.php');
$sql="SELECT *  FROM clientes
";

$q=mysql_query($sql);

?>
<div class="row mb10">
		<div class="col-md-12 text-right">
			<a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#Nuevocliente" class="btn btn-success btn-sm">Agregar Nuevo</a>
		</div>
</div>

<div class="row">		
	<div class="col-md-12">
		<div class="panel panel-primary">
		
		  <div class="panel-heading">
		    <h3 class="panel-title">Clientes</h3>
		  </div>
		 
		  <div class="panel-body">
		  <!-- Confirmación -->
		  <? if($_GET['msg']==1){ ?>
		  		<div class="alert alert-dismissable alert-success">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El cliente se ha agregado con éxito.</p>
			  	</div>
		  <? }if($_GET['msg']==2){ ?>
		  		<div class="alert alert-dismissable alert-info">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El cliente se ha editado con éxito.</p>
			  	</div>
		  <? } ?>
		  <!-- Contenido -->
		  		<table class="table table-striped table-hover " id="tabla" cellspacing="0" width="100%">
				      <thead>
				        <tr>
				          <th width="20%">Nombre</th>
				          <th width="20%">Telefono</th>
						  <th width="20%">compras realizadas</th>
						  <th width="20%">Mas cosumido</th>
						  <th width="20%"> </th>
				        </tr>
				      </thead>
				      <tbody>
				      <? while($ft=mysql_fetch_assoc($q)){ ?>
				        <tr>
				          <td><?=acentos($ft['nombre'])?></td>
						  <td><?=$ft['telefono']?></td>
						  
						  <td><?
						  $id=$ft['id_cliente'];
						 $sqlv="SELECT id_cliente, count(*) as maximo FROM ventas WHERE id_cliente = $id GROUP BY id_cliente";
						  $qv=mysql_query($sqlv);
						  $trx=mysql_fetch_assoc($qv);
						  if($trx['maximo']){
						  echo($trx['maximo']);
}else{
	echo(0);
}
						 ?></td>

<td><?
						  
						  $sqlv="SELECT nombre, Count(*) AS Total
						  FROM venta_detalle
						  LEFT JOIN ventas ON ventas.id_venta  = venta_detalle.id_venta
						  WHERE ventas.id_cliente=$id AND nombre != ''
						  GROUP BY nombre 
						  ORDER BY Total DESC";
						   $qv=mysql_query($sqlv);
						   $trx=mysql_fetch_assoc($qv);

						   if($trx['nombre']){
						   echo($trx['nombre']);
                           }else{
							echo('No ha realizado una compra');
						   }

						  ?></td>
				          <td align="right">
				          		<img src="img/load-azul.gif" border="0" id="load_<?=$ft['id_cliente']?>" width="19" class="oculto" />
				          	
				          		<span class="label label-success link btn_<?=$ft['id_cliente']?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaCliente" data-id="<?=$ft['id_cliente']?>">Editar</span> &nbsp; &nbsp; 
				          		<span class="label label-danger link btn_<?=$ft['id_cliente']?>" onclick="javascript:Desactiva(<?=$ft['id_cliente']?>)">Eliminar</span>
				          	
				          	
				          		
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
<div class="modal fade" id="Nuevocliente">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Cliente</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="60" class="form-control dat" name="nombre" id="nombre_nuevo" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="referencia" class="col-md-3 control-label">Telefono</label>
				<div class="col-md-9">
					<input type="number" maxlength="255" class="form-control dat" name="telefono" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="referencia" class="col-md-3 control-label">Correo</label>
				<div class="col-md-9">
					<input type="email" maxlength="255" class="form-control dat" name="mail" autocomplete="off">
				</div>
			</div>
			
		
					
			<div class="form-group">
				<label for="referencia" class="col-md-3 control-label">Genero</label>
				<div class="radio">
				  <label><input type="radio" name="genero" value="H" >Hombre</label> &nbsp; &nbsp; &nbsp;
				  <label><input type="radio" name="genero" value="M" >Mujer</label>
				</div>
				
			</div>
			
			<div class="form-group">
				<label for="referencia" class="col-md-3 control-label">Fecha de nacimiento</label>
				<div class="col-md-9">
					<input type="date" maxlength="255" class="form-control dat" name="fechan" autocomplete="off">
				</div>
			</div>
			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevoCliente()">Guardar Cliente</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal -->
<div class="modal fade" id="EditaCliente">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Cliente</h4>
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
					<input type="text" maxlength="60" class="form-control edit" name="nombre" id="nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="referencia" class="col-md-3 control-label">Telefono</label>
				<div class="col-md-9">
					<input type="number" maxlength="255" class="form-control dat" name="telefono" id="tele" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="referencia" class="col-md-3 control-label">Correo</label>
				<div class="col-md-9">
					<input type="email" maxlength="255" class="form-control dat" name="mail" id="mai" autocomplete="off">
				</div>
			</div>
			
		
					
			<div class="form-group">
				<label for="referencia" class="col-md-3 control-label">Genero</label>
				<div class="radio">
				  <label><input type="radio" name="genero" value="H" id="radio1" >Hombre</label> &nbsp; &nbsp; &nbsp;
				  <label><input type="radio" name="genero" value="M" id="radio2" >Mujer</label>
				</div>
				
			</div>
			
			<div class="form-group">
				<label for="referencia" class="col-md-3 control-label">Fecha de nacimiento</label>
				<div class="col-md-9">
					<input type="date" maxlength="255" class="form-control dat" name="fechan" id="fechan" autocomplete="off">
				</div>
			</div>
			
			
			<input type="hidden" name="id_cliente" id="id_cliente" />
			 
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load2" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="EditaCliente()">Editar Cliente</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--- Js -->
<script>
$(function(){
	$('#tabla').dataTable({
        "order": [[ 0, "asc" ]],
        "iDisplayLength": 50
    });

	
	$(document).on('click', '[data-id]', function () {
		//Precargamos
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');

	    $.ajax({
	   	url: "data/clientes.php",
	   	data: 'id_cliente='+data_id,
		   
	   	success: function(data){
			console.log(data);
	   		var datos = data.split('|');
	   		var nombre=datos[0];
	   		var telefono=datos[1];
	   		var email=datos[2];
	   		var genero=datos[3];
	   		var fechan=datos[4];
	   	
	   		$('#nombre').val(nombre);
	   		$('#tele').val(telefono);
		    $('#mai').val(email);

	   	     if(genero=="H"){
			$("#radio1").prop("checked", true);
			} 
		   	
			if(genero=="M"){
			$("#radio2").prop("checked", true);
			} 
			$('#fechan').val(fechan); 
	   		$('#id_cliente').val(data_id);
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	   	},
	   	cache: false
	   });
	});
	
	//Setear el modal
	$('#EditaCliente').on('hidden.bs.modal',function(e){
		if($('#check_comidas').prop("checked")){
			$('#check_comidas').bootstrapSwitch('toggleState');
		}
		
		if($('#check_credito').prop("checked")){
			$('#check_credito').bootstrapSwitch('toggleState');
		}
		
		if($('#favorito').prop("checked")){
			$('#favorito').bootstrapSwitch('toggleState');
		}
		$('#tipo_cargo').val("0");
		$('#msg_error2').hide();
	});
	
	$('#comidas').on('switchChange.bootstrapSwitch', function(event,state) {
		if(state==true){
			$('#configura_comida').fadeIn('slow');
		}else if(state==false){
			$('#configura_comida').fadeOut('slow');
		}
	});
	
	$('#check_comidas').on('switchChange.bootstrapSwitch', function(event,state) {
		if(state==true){
			$('#configura_comida2').fadeIn('slow');
		}else if(state==false){
			$('#configura_comida2').fadeOut('slow');
		}
	});
	
	$('#Nuevocliente').on('shown.bs.modal',function(e){
		$('#nombre_nuevo').focus();
		$('.input_limite_credito').val('');
	});
	
	$('#Nuevocliente').on('hidden.bs.modal',function(e){
		if($('#comidas').prop("checked")){
			$('#comidas').bootstrapSwitch('toggleState');
		}
		
		if($('#credito').prop("checked")){
			$('#credito').bootstrapSwitch('toggleState');
		}
		
		if($('#nuevo_favorito').prop("checked")){
			$('#nuevo_favorito').bootstrapSwitch('toggleState');
		}
		$('#nuevo_tipo_cargo').val("0");
		$('.dat').val("");
		$('#msg_error2').hide();
	});
});

function EditaCliente(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_cliente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Clientes&msg=2", "_self");
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
	$.post('ac/activa_desactiva_cliente.php', { tipo: "0", id_cliente: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Clientes", "_self");
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
	$.post('ac/activa_desactiva_cliente.php', { tipo: "1", id_cliente: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Clientes", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevoCliente(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_cliente.php',datos,function(data){
	
		var dats = data.split('|');
		var nom = dats[0];
		var id = dats[1];

		if((id>0) && (nom)){
			window.open("?Modulo=Clientes&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>