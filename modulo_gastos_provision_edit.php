<?php
$id_gasto=$_GET['id_gasto'];

$sql_gastos="SELECT * FROM gastos 
JOIN usuarios ON usuarios.id_usuario=gastos.id_usuario
WHERE id_corte=0  and id_gasto = $id_gasto ";
$q_gastos=mysql_query($sql_gastos,$conexion);

$row = mysql_fetch_array($q_gastos);
$v_corte = $row ['monto'];

?>

<script type="text/javascript">

function gasto(){
	
	window.open("?Modulo=MGastos","_self");
	//window.open("?Modulo=VentaTouchMesa", "_self");
}

	// final Edicion 
function AplicarGasto(){
	
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
	$.post('ac/editar_gasto.php',datos,function(data){
		$('#mensaje').html(data);
		var dat = data.split("|");
		var valida=dat[0];
		var dato=dat[1];
	    if(valida==1){
		    alert("El gasto se ha agregado");
		    gasto();
	    }else{
	    	$('#gastos_load').hide();
			$('.btn-gastos').show();
			$('#gastos_msg_error').html(dato);
			$('#gastos_msg_error').show('Fast');
	    }
	});
};

</script>
<hr>
<div class="row">
	<div class="col-md-3">
	<a type="button" class="btn btn-default btn-lg btn-block" href="index.php">VENTA</a><br>
		<a href="?Modulo=NuevoGastos" type="button" class="btn btn-default btn-lg btn-block">NUEVO</a><br>
		<a href="?Modulo=MGastos" type="button" class="btn btn-primary btn-lg btn-block"  >GASTOS</a><br>
		<a href="?Modulo=PGastos" type="button" class="btn btn-success btn-lg btn-block" >PROVISIONES</a>
	</div>
	
	
	<div class="col-md-9">
	<!--principio -->
		<div class="panel panel-success">
                	<div class="panel-heading">
                	        <h3 class="panel-title"> Editar Provision </h3>  
                	</div>
                	<div class="panel-body">
                	<form id="frm_gastos" class="form-horizontal">
						<input type="hidden" value="<?=$row['id_gasto']?>" name="id_gasto" id="id_gasto">
						<div class="form-group">
							<label class="col-md-3 control-label">Descripción</label>
							<div class="col-md-9">
								<input type="text" maxlength="160" class="form-control limpia" id="gastos_descripcion" name="gastos_descripcion" autocomplete="off" maxlength="255" value ="<?=$row['descripcion']?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Monto</label>
							<div class="col-md-9">
								<input type="text" maxlength="18" class="form-control limpia" id="gastos_monto" name="gastos_monto" autocomplete="off" maxlength="6" value="<?=$row['monto']?>">
							</div>
						</div>
	
					</form>
					<div class="modal-footer">
				      	<img src="img/load-verde.gif" border="0" id="gastos_load" width="30" class="oculto" />
				        <button type="button" class="btn btn-gastos btn-default" data-dismiss="modal">Cancelar</button>
				        <button type="button" class="btn btn-gastos btn-primary" onclick="AplicarGasto()">Aplicar Gasto</button>
			      	</div>
                	</div>
            	</div>
            	<div id="mensaje"></div>
	<!-- Final-->	
	</div>
</div>