<?
$sq="SELECT
	impresion_domicilio.id_impresion_domicilio,
	impresion_domicilio.numero,
impresion_domicilio.nombre,
impresion_domicilio.direccion,
impresion_domicilio.fecha_hora,
domicilio.nombre as nombre_completo
FROM
	impresion_domicilio
join domicilio ON 
impresion_domicilio.nombre = domicilio.id_domicilio

ORDER BY
	id_impresion_domicilio DESC
LIMIT 10";
$q0=mysql_query($sq);
$valida=mysql_num_rows($q0);
?>
<div class="row">		
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Servicio a Domicilio</h3>
			</div>
		  
			<div class="panel-body">
				
				<form class="form-horizontal" onsubmit="return false;">
					<div class="form-group form-group-lg">
						<label class="col-sm-4 control-label" style="font-size: 20px;padding-top: 5px;">Ingrese Teléfono:</label>
						<div class="col-sm-8">
							<div class="input-group">
								<input class="form-control" autocomplete="off" value="" type="text" id="telefono" onkeypress="return isNumber(event)" style="font-size: 34px;font-weight: 500;" maxlength="10" autocomplete="off" >
								<span class="input-group-btn">
									<button class="btn btn-primary btn-lg" type="button" onclick="javascript:consulta();" id="btn-consulta" data-loading-text="Validando...">Buscar</button>
								</span>
							</div>
							
						</div>
					</div>
				</form>
				
				
				<div class="row" id="datos">
					
				</div>
				
			</div>
		</div>	
	</div>
	<? if($valida){ ?>
	<table class="table table-striped" style="font-size: 18px;" id="cobradas">
			<thead>
				<tr>
					<th></th>
					<th>Numero</th>
					<th>Nombre</th>
					<th>Domicilio</th>
					<th>Fecha</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
			<? 
			
			while($ft=mysql_fetch_assoc($q0)){ 
			
	
				
			?>
				<tr>
					<td><?=$ft['id_impresion_domicilio']?> </td>
					<td style=""><?=$ft['numero']?></td>
					<td style=""><?=$ft['nombre_completo']?></td>
					<td style=""><?=$ft['direccion']?></td>
					<td style=""><?=$ft['fecha_hora']?></td>
					<td>
					<a type="button" class="btn btn-success" href="#" 
						onclick="re_imprimir_d(<?=$ft['nombre']?>,'<?=$ft['direccion']?>','<?=$ft['numero']?>');" >IMPRIMIR</a><br></td>
				</tr>
			<? } ?>
			</tbody>
		</table>
		<a id="btn_camb" type="button" class="btn btn-success" href="#" onclick="cambiacat();" >
			VER OTROS PEDIDOS
		</a>
		<? }else{ echo '<center><h2>No tienes ninguna .</h2></center>';} ?>
		
		
	</div>
</div>
<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

$(function(){
	$('#telefono').val('').focus();
	
	$('#telefono').keyup(function(e) {
		if(e.keyCode == 13){
			consulta();
		}	
	});
	
	$('#direccion').keyup(function(e) {
		if(e.keyCode == 13){
			agregaDireccion();
		}	
	});
});
function consulta(){
	var numero = $('#telefono').val();
	if(!numero) return false;
	$('#telefono').attr('readonly','readonly');
	var yo = $('#btn-consulta').html();
	if(yo=='Buscar'){
		$('#btn-consulta').html('Nuevo');
	}else{
		window.location = 'index.php?Modulo=Domicilio';
	}
	$('#datos').load("data/direcciones.php?numero="+numero);
}



 function cambiacat() {
      $.ajax({  
	    	url: 'ac/direccion_domicilio.php',  
	    	success: function(data) {  
	        $('#cobradas').html(data);
	        document.getElementById('btn_camb').style.display = 'none';

    }  
});
   }

function re_imprimir_d(nombre,direccion,numero){
	
	//alert("hola");
	$.get('ac/reimprimir.php?nombre='+nombre+"&direccion="+direcion+"&numero="+numero);
	
}
</script>