<?
include("../includes/db.php");
include("../includes/funciones.php");

$numero=$_GET['numero'];
$sql ="SELECT * FROM domicilio WHERE numero = '$numero'";
$q = mysql_query($sql);
$hay_numeros = mysql_num_rows($q);
$data = mysql_fetch_assoc($q);

$id_domicilio = $data['id_domicilio'];

if($hay_numeros){
	$sql = "SELECT*FROM domicilio_direcciones WHERE id_domicilio = ".$id_domicilio;	
	$dom = mysql_query($sql);
	$cuantas_dir = mysql_num_rows($dom);	
}


if($cuantas_dir){
	$nuevaDir = 'hidden';	
}else{
	$existe = 'hidden';
}

?>
<hr>
<div class="col-md-7 direcciones_precargadas <?=$existe?>" id="muestraDirecciones">
	<h4>Nombre</h4>
	<div class="form-group">
		<input type="text" class="form-control input-lg" autocomplete="off" id="nombre" value="<?=$data['nombre']?>" onkeypress="return validar(event)">
	</div>
	<h4>Direcciones</h4>
	<div class="list-group">
<?
	while($fx = @mysql_fetch_assoc($dom)){
		$direccion = $fx['direccion'];
		$id_domicilio_direccion = $fx['id_domicilio_direccion'];
?>
		<a href="#" class="list-group-item" onclick="mostrar(<?=$id_domicilio_direccion?>,'<?=base64_encode($direccion)?>')">
			<p class="list-group-item-text" style="font-size: 20px;"><?=$direccion?></p>
		</a>
<?
	}

?>
	</div>
<div class="text-right">
	<a href="#" class="btn btn-warning btn-nueva" onclick="nuevo()">Agregar Nueva Dirección</a>
</div>
</div>

<div class="col-md-5 direcciones_precargadas <?=$existe?>">
<br/><br/>
	<form id="frm_dat" onsubmit="return false;">
		<div id="nuevaDireccion" class="oculto">
		<div class="form-group ">
			<textarea class="form-control hidden" rows="4" id="direccion" name="direccion" style="font-size: 20px;"></textarea>
		</div>
		
		<div class="form-group ">
			<input type="hidden" name="id_domicilio_existente" id="id_domicilio_existente" value="0" />
			
			<div class="col-md-6" style="padding-left: 0px;">
				<span id="cargando_nuevo_2" style="display:none"><h4>Imprimiendo...</h4></span>
				
				<a href="#" class="btn btn-primary hidden edita_direccion" id="agregaDireccion" onclick="imprimirExistente();">Imprimir y Guardar</a>
			</div>
			<div class="col-md-6 text-right hidden edita_direccion" id="muestra_eliminar" style="padding-right: 0px;">
				<a href="#" class="btn btn-danger" onclick="borrar()">Borrar</a>
			</div>
		</div>
		</div>
		
	</form>
</div>

<div class="col-md-12 <?=$nuevaDir?>" id="nueva_direccion_div">
	<form id="frm_datos_nuevo" class="form-horizontal" onsubmit="return false;">
		
		<div class="form-group">
			<label class="col-sm-2 control-label" style="font-size: 20px;padding-top: 5px;" for="nombre_nuevo">Nombre:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control input-lg" id="nombre_nuevo" autocomplete="off" name="nombre_nuevo" style="font-size: 20px;" onkeypress="return validar(event)">
			</div>
		</div>
		
		
		<div class="form-group ">
			<label class="col-sm-2 control-label" style="font-size: 20px;padding-top: 5px;" for="direccion_nuevo">Dirección:</label>
			<div class="col-sm-10">
				<textarea class="form-control input-lg" rows="4" id="direccion_nuevo" name="direccion_nuevo" style="font-size: 20px;"></textarea>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-md-8 col-md-offset-4">
				<span id="cargando_nuevo" style="display: none"><h4>Imprimiendo...</h4></span>
				<a href="#" class="btn btn-primary btn-lg imprimir_nuevo" onclick="imprimirNuevo();">Imprimir y Guardar</a> &nbsp;&nbsp;&nbsp;
				<a href="#" class="btn btn-default btn-lg imprimir_nuevo <?=$existe?>" id="" onclick="recargar();">Regresar</a><br/>
			</div>
			
		</div>
		
	</form>
</div>


<script>
	
//inicio
$(document).ready(function()
	{
	$("#direccion").focus(function(){
    		$(this).css("background-color", "#FFFFCC");
    		window.onbeforeunload = function(){
			  return '¿Estás seguro que quieres irte?';
			};
	});
 
	

});
//final

$(function() {

	$('#nombre_nuevo').focus();

});
	
	function imprimirNuevo(){
		$('.imprimir_nuevo').hide();
		$('#cargando_nuevo').show();
		var nombre = $('#nombre_nuevo').val();
		var direccion = $('#direccion_nuevo').val();
		
		//alert(nombre.length);

		if(nombre.length <= 4 || direccion.length <= 4 || nombre.length == 0 || direccion.length == 0 ){
			alert("El nombre o la Direccion tiene menos de 4 letras");
			$('.imprimir_nuevo').show();
			$('#cargando_nuevo').hide();
		}else {
				$.post('ac/direccion_nueva.php','nombre='+nombre+'&direccion='+direccion+'&id_domicilio=<?=$id_domicilio?>&numero=<?=$numero?>',function(data) {
			
			if(data==1){
				window.location = 'index.php?Modulo=Domicilio';
			}else{
				alert('Error '+data);
				window.location = 'index.php?Modulo=Domicilio';

			}
			
		});
		}//final de la condicion
	
		
		
	}
	
	function borrar(){
		var id = $('#id_domicilio_existente').val();
		$.post('ac/direccion_elimina.php','id_domicilio_direccion='+id,function(data) {
			
			if(data==1){
				$('#datos').load("data/direcciones.php?numero=<?=$numero?>");
			}
			
		
		});
	
	
	}
	function imprimirExistente(){
		
		$('.edita_direccion').hide();
		$('#cargando_nuevo_2').show();


		var id = $('#id_domicilio_existente').val();
		var nombre = $('#nombre').val();
		var direccion = $('#direccion').val();


		//inicio replazo de caracteres
		  var specialChars = "!@#$^&%*()+=-[]\/{}|:<>?,.";

		   // Los eliminamos todos
		   for (var i = 0; i < specialChars.length; i++) {
		       nombre= nombre.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
		   }
		   //final de limpia de caracteres
		if(nombre.length <= 4 || direccion.length <= 4 || nombre.length == 0 || direccion.length == 0 ){
			alert("El nombre o la Direccion tiene menos de 4 letras o contiene Caracteres invalidos");
			$('.edita_direccion').show();
			$('#cargando_nuevo_2').hide();
		}else{
		$.post('ac/direccion_existe.php','nombre='+nombre+'&direccion='+direccion+'&id_detalle='+id+'&id_domicilio=<?=$id_domicilio?>&numero=<?=$numero?>',function(data) {
			if(data==1){
				window.location = 'index.php?Modulo=Domicilio';
			}else{
				alert('Error '+data);
			}
		});	
		}//final al else 

		
		
	}	
	
	function mostrar(id,direccion){
		$('#nuevaDireccion').show();
		$('#direccion').val(atob(direccion));
		$('#agregaDireccion,#direccion,#muestra_eliminar').removeClass('hidden');
		$('#direccion').focus();
		$('#id_domicilio_existente').val(id);
	}
	
	function nuevo(){
		$('#nombre_nuevo').val($('#nombre').val());
		$('#nuevaDireccion,.direcciones_precargadas').hide();
		$('#nueva_direccion_div,#direccion').removeClass('hidden');
		$('#direccion_nuevo').focus();

	}
	
	function recargar(){
		$('#nuevaDireccion,.direcciones_precargadas').show();
	//	$('#direccion,#nombre').val('');
		$('#nueva_direccion_div,#direccion,#agregaDireccion,#muestra_eliminar').addClass('hidden');
		
	}

	function validar(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[A-Za-zñÑ\s]/; // 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}
</script>