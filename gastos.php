<?
$sql_gastos="SELECT * FROM gastos 
JOIN usuarios ON usuarios.id_usuario=gastos.id_usuario
WHERE id_corte=0 ORDER BY id_gasto DESC LIMIT 5 ";
$q_gastos=mysql_query($sql_gastos,$conexion);
$valida_gastos=mysql_num_rows($q_gastos);



$sql_g="SELECT COUNT(*) FROM gastos  WHERE id_corte=0";
$q_g=mysql_query($sql_g,$conexion);
$cuantos = @mysql_result($q_g, 0);
?>
<!-- Configuración General -->
<div class="modal fade" id="gastos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Gastos 
          (<span id="cuanto"><?=$cuantos?></span>)</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="gastos_msg_error"></div>
      	<div class="alert alert-success oculto" role="alert" id="gastos_msg_ok"></div>
<!--Formulario -->
		<form id="frm_gastos" class="form-horizontal">
			
			<div class="form-group">
				<label class="col-md-3 control-label">Descripción</label>
				<div class="col-md-9">
					<input type="text" maxlength="160" class="form-control limpia" id="gastos_descripcion" name="gastos_descripcion" autocomplete="off" maxlength="255">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-3 control-label">Monto</label>
				<div class="col-md-9">
					<input type="text" maxlength="18" class="form-control limpia" id="gastos_monto" name="gastos_monto" autocomplete="off" maxlength="6">
				</div>
			</div>
			
		</form>
		<? if($valida_gastos){ ?>
		<hr>
		<h4>Últimos gastos</h4>
		<table class="table table-striped table-hover ">
			<thead>
				<tr>
					<th>Usuario</th>
					<th>Hora</th>
					<th>Descripción</th>
					<th class="text-right">Monto</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="agrega_gasto">
			<? while($ft=mysql_fetch_assoc($q_gastos)){ ?>
				<tr id="gasto_<?=$ft['id_gasto']?>">
					<td><?=$ft['nombre']?></td>
					<td><?=devuelveFechaHora($ft['fecha_hora'])?></td>
					<td><?=$ft['descripcion']?></td>
					<td align="right"><?=number_format($ft['monto'],2)?></td>
					<td align="right">
						<button type="button" class="btn btn-xs btn-gastos btn-danger" onclick="eliminarGasto(<?=$ft['id_gasto']?>)">X</button>
					</td>
				</tr>
			<? } ?>
			</tbody>
		</table>
		<? } ?>      
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="gastos_load" width="30" class="oculto" />
        <button type="button" class="btn btn-gastos btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-gastos btn-primary" onclick="nuevoGasto()">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
$(function(){
	
	$('#gastos_monto').keyup(function () { 
	    var val = $(this).val();
	    if(isNaN(val)){
	         val = val.replace(/[^0-9\.]/g,'');
	         if(val.split('.').length>2) 
	             val =val.replace(/\.+$/,"");
	    }
	    $(this).val(val); 	    
	});
	
/*	$('#gastos_monto').keyup(function(e) {
		if(e.keyCode==13){
			nuevoGasto();

		}
	});
*/	
	$('#gastos_descripcion').keyup(function(e) {
		if(e.keyCode==13){
			$('#gastos_monto').focus();
		}
	});
});
// principio Edicion 
function eliminarGasto(id){
	
	if(confirm('¿Realmente desea eliminar el gasto?')){
		
		$.post('ac/gasto_eliminar.php','id_gasto='+id,function(data) {
		
			if(!isNaN(data)){
				//alert('Gasto eliminado con éxito.');
				$('#gasto_'+id).remove();
				$('#gastos_descripcion').focus();
				$('#cuanto').html(data);

			}else{
				alert('Error al eliminar el gasto. '+data);
			}
			
		
		});
		
	}
	
}

// final Edicion 
function nuevoGasto(){
	
	var descripcion = $('#gastos_descripcion').val();
	var monto 		= $('#gastos_monto').val();
	
	if(!descripcion){ 
		$('#gastos_msg_error').html('Ingrese una descripción para el gasto.').show();;
		return false;
	}
	
	if(!monto){ 
		$('#gastos_msg_error').html('Ingrese un monto para el gasto.').show();;
		return false;
	}
	
	$('#gastos_msg_error').hide('Fast');
	$('#gastos_msg_ok').hide('Fast');
	$('.btn-gastos').hide();
	$('#gastos_load').show();
	var datos=$('#frm_gastos').serialize();
	$.post('ac/nuevo_gasto.php',datos,function(data){
		var dat = data.split("|");
		var valida=dat[0];
		var dato=dat[1];
	    if(valida==1){
		    alert("El gasto se ha agregado");
		    location.reload();
		    /*
		    $('#gastos').modal('hide');
		    $('#agrega_gasto').prepend('<tr><td><?=$s_nombre?></td><td>'+descripcion+'</td><td align="right">'+monto+'</td><td align="right"></td></tr>');

	    	$('#gastos_load').hide();
	    	/*
	    	$('#gastos_msg_ok').html('El gasto se ha agregado.');
			$('#gastos_msg_ok').show('Fast');
			$('.btn-gastos').show();
			$('.limpia').val("");*/
			
			
			//$('#gastos_descripcion').focus();

	    }else{
	    	$('#gastos_load').hide();
			$('.btn-gastos').show();
			$('#gastos_msg_error').html(dato);
			$('#gastos_msg_error').show('Fast');
	    }
	});
};
</script>