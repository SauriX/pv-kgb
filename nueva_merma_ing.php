<style>
	/*
	.dropdown-menu{
		width: 82%;
		font-size: 14px;
	}*/
</style>
<?
$devolucion_permiso = 1;
$corte_permiso = 1;

if($s_tipo!='1'){
	$sql = "SELECT * FROM usuarios WHERE id_usuario = $s_id_usuario";
	$q = mysql_query($sql);
	$dats = mysql_fetch_assoc($q);
	$devolucion_permiso = $dats['devoluciones'];
	$corte_permiso = $dats['cortes'];

}

$sq0="SELECT * FROM proveedores WHERE activo=1";
$q0=mysql_query($sq0);
?>
<script>
$(function() {
	
	$(document).keyup(function(e) {
	/*
			if(e.keyCode == 13) {
				agregar();
			}
			if(e.keyCode == 119){
				cobrar_devolver();
			}*/
			if(e.keyCode == 120){
				Buscar();
			
			}/*
			if(e.keyCode == 115){
				dev_toggle();
			
			}*/
	});
	

	$('.solo_numero').keyup(function () { 
	    this.value = this.value.replace(/[^0-9\.]/g,'');
	});

	$('#id_producto').focus();
	
	$('#id_producto,#precio').keyup(function(e) {
		var yo = $(this).val();
		$(this).val(yo.toUpperCase());
		
		if(e.keyCode == 13) {
			enter_codigo();
		}
		if(e.keyCode == 27) {	
			var boton_cobrar = $('#cobrar_boton').html();
			if(boton_cobrar=='Cobrar'){
				$('#venta_cobrar').modal();	
			}else if(boton_cobrar='Devolver'){
				$('#venta_cancelar').modal();
			}
		}
	});	
	
	$('#cobrar').click(function() {
		cobrar();
	});
	
	$('#venta_cobrar').on('show.bs.modal', function (e) {
		$('#cambio_modal,#recibe_modal').val('');
		if(!$('.productos_a_cobrar').length){
			CerrarBuscar();
			return false;
		}
	});
		
	$('#venta_cobrar').on('shown.bs.modal', function (e) {
		$('#recibe_modal').focus();
	});

	$('#venta_cobrar').on('hidden.bs.modal', function (e) {
		CerrarBuscar();
		$('.hidden_loader').show();
		$('#loader').hide();
		$('#id_producto').val('');
		$('#cantidad').val('');
		$('#id_producto_b').typeahead('val', '');
	});
	
	$('#recibe_modal').keyup(function(e) {
		if(e.keyCode==13){
			cobrar();
		}
			
		var recibe = $('#recibe_modal').val();
		var total = $('#total_modal').val();
		var cambio = Number(recibe)-Number(total);
		
		if(cambio>0){
			$('#cambio_modal').val(Number(cambio).toFixed(2));
		}else if(cambio==0){
			$('#cambio_modal').val('0.00');
		}else{
			$('#cambio_modal').val('');
		}
		
	});

	redDiv();
	
	$(window).bind("resize", function() { redDiv(); });

	/**** AUTOCOMPLETE	****/
	/**** AUTOCOMPLETE	****/
	/**** AUTOCOMPLETE	****/	
	var datos;    
	$('#id_producto_b').typeahead({
	
		source: function (query, process) {		
		datos = {
			<? require('lista_productos.php'); ?>
		}
		var newData = [];
		$.each(datos,function(index, object){	
			newData.push(index);
		});
			return newData;
		},
			updater: function(item){
				enter_busqueda(datos[item].codigo);
				return item;	
			}
	});
	
	/**** END - AUTOCOMPLETE	****/
	/**** END - AUTOCOMPLETE	****/
	/**** END - AUTOCOMPLETE	****/
});

function cobrar(){
		$('.hidden_loader').hide();
		$('#loader').show();
		var datos = $('#venta_form').serialize();
		var dotaciones = $('#datos_dotaciones').serialize();
		$.post('ac/mermar.php',datos+"&"+dotaciones,function(data) {
				
				if(data==1){
					cobradoExito();
				}else{
					$('.hidden_loader').show();
					$('#loader').hide();					
					alert('Error: '+ data);
				}		
		});
}

function cobradoExito(){	
	$('#venta_cobrar').modal('hide');
	$('.lista-productos').remove();
	$('#fecha').val('');
	$('#id_proveedor').val('');
	$('#factura').val('');
	actualizar_total();
	window.open("?Modulo=Mermas&msg=1", "_self");
}

function enter_busqueda(codigo){	
	var cantidad = $('#cantidad').val();
	var precio = $('#precio').val();
	$('#id_producto').val(codigo);
	if(!cantidad){
		$('#cantidad').attr('data-toggle','tooltip');
		$('#cantidad').attr('data-placement','top');
		$('#cantidad').attr('title','Debe poner la cantidad de productos');
		$('#cantidad').focus();
		$('#cantidad').tooltip('show');
		return false;
	}

	CerrarBuscar();
	$('#id_producto').val('');
	$('#cantidad').val('');
	$('#precio').val('');
	agregar(codigo,cantidad,precio);
	$('#id_producto_b').typeahead('val', '');
}

function enter_codigo(){
	var codigo = $('#id_producto').val();
	var cantidad = $('#cantidad').val();
	var precio = $('#precio').val();
	if(!cantidad){
		$('#cantidad').attr('data-toggle','tooltip');
		$('#cantidad').attr('data-placement','top');
		$('#cantidad').attr('title','Debe poner la cantidad de productos');
		$('#cantidad').focus();
		$('#cantidad').tooltip('show');
		return false;
	}
	

	codigo = codigo.toUpperCase();
	$('#id_producto').val('');
	$('#cantidad').val('');
	$('#precio').val('');
	$('#id_producto').focus();
	/*var corta = codigo.split('*');
	if(corta[1]){
		codigo = corta[1];
		cantidad = corta[0];
	}*/
	agregar(codigo,cantidad,precio);
	CerrarBuscar();	
}

function agregar(codigo,cantidad,unitario){
	if((cantidad<=0) || (isNaN(cantidad))){
		return false;
	}
	var datos = dame_info(codigo);
	var random = new Date().getTime();
	var producto = datos['nombre'];
	//var unitario = datos['precio'];
	var precio = Number(cantidad)*Number(unitario);
	var id_producto = datos['id_producto'];
	
	var html = '';	

	html+='<div class="row lista-productos" id="'+random+'">';
	html+='<div class="col-md-8" class="lista_nombre">'+producto+'</div>';
	html+='<div class="col-md-1 text-center" class="lista_cantidad">'+cantidad+'</div>';

	
	html+='<div class="col-md-1 text-right" class="lista_eliminar"><span class="glyphicon glyphicon-remove red click" onclick="remover_item(\''+random+'\')"></span></div>';
	html+='<input type="hidden" value="'+cantidad+'" name="cobrar_producto['+random+'_'+id_producto+'_'+unitario+'_'+cantidad+']" data-precio="'+unitario+'" class="productos_a_cobrar">';
	html+='</div>';
						
	$('#lista_productos').append(html);
	$('#precio').tooltip('destroy');
	actualizar_total();
}

function actualizar_total(){
	var cantidad;
	var precio;
	var total = 0;

	$('.productos_a_cobrar').each(function() {
		cantidad = Number($(this).val());
		precio = Number($(this).attr('data-precio'));
		total+= (cantidad*precio);
	});
	
	$('#total_totales,#total_modal').val(Number(total).toFixed(2));
}

function dame_info(codigo){ 			//Devuelve en array toda la información del código del producto que se recibe. o no. o si.
	var productos;
	var datos = new Array;
	var nombre = [];
	var precio = [];
	var id_producto = [];
	productos = {				

<?
				require('lista_productos.php');
?>

	}
	$.each(productos,function(index, object){
	    nombre[object.codigo]= index;
	    precio[object.codigo]= object.precio;
	    id_producto[object.codigo]= object.id_producto;	
	});			
	if(nombre[codigo].length){
		datos['codigo'] = codigo;
		datos['nombre'] = nombre[codigo];
		datos['precio'] = precio[codigo];
		datos['id_producto'] = id_producto[codigo];
	}else{
		datos = false;
	}
	return datos;	
}

function remover_item(random){
	$('#'+random).remove();	
		actualizar_total();	
}

function redDiv(){
   	var winHeight = $(window).height();
   	var setup_minus = $('#winHeight').val();
   	var nuevo = Number(winHeight) - Number(setup_minus);
   	var auto = nuevo+'px';
	$('#lista_productos').css('height',auto);
}

function Buscar(){
	$('#id_producto').hide();
	$('#id_producto_b').show();
	$('#id_producto_b').focus();
	$('#btn_ver_busqueda').hide();
	$('#btn_cerrar_busqueda').show()
	$('#id_producto').val("");
}

function CerrarBuscar(){
	$('#id_producto_b').hide();
	$('#btn_cerrar_busqueda').hide()
	$('#btn_ver_busqueda').show();
	$('#id_producto_b').val("");
	$('#id_producto').show();
	$('#id_producto').focus();
}

//Para la venta a crédito
$(function(){
	
});

function aTitulo(cadena)
{
    return cadena.replace(/\w\S*/g, function(texto){
	    return texto.charAt(0).toUpperCase() + texto.substr(1).toLowerCase();
	});
}

</script>

<!-- Configuración de compra -->
<div class="panel panel-default">
    <div class="panel-body">
    	<div class="row">		
    		<div class="col-md-12">
    			<div class="col-md-6">
    			    <div class="form-group mb0">
    			    	<div class="input-group col-md-12">
    			    	  <span class="input-group-addon">Producto</span>
    			    	  <input type="text" class="form-control" tabindex="1" style="display:none" autocomplete="off" data-provide="typeahead" id="id_producto_b" name="id_producto_b">
	   			    	  <input type="text" class="form-control" tabindex="2" autocomplete="off" id="id_producto" name="id_producto">


    			    	  <span class="input-group-btn" >
    			    	    <button class="btn btn-primary" type="button" onclick="Buscar()" id="btn_ver_busqueda">Buscar</button>

    			    	  	<button class="btn btn-danger oculto" type="button" onclick="CerrarBuscar()" id="btn_cerrar_busqueda">Cerrar</button>
    			    	  </span>
    			    	</div>
    			    </div>
    			</div>
    			<div class="col-md-6">
    			    <div class="form-group mb0">
    			    	<div class="input-group col-md-12">
    			    	  <span class="input-group-addon">Cantidad</span>
    			    	  <input type="text" id="cantidad" autocomplete="off" class="form-control solo_numero" tabindex="3">
    			    	  <span style="visibility: hidden" class="input-group-addon">Precio</span>
						  <button class="form-control btn btn-primary" onclick="enter_codigo()" id="btnAgregarPro" name="btnAgregarPro" type="button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true">
    			    	</div>
    			    </div>
    			</div>
    		</div>
    	</div>
    </div>
</div>



<!-- Lista de compras -->
	<div class="panel panel-default">
	
		<div class="panel-heading">
			<div class="row">
			    <div class="col-md-8"><b>Producto</b></div>
			    <div class="col-md-1 text-center"><b>Cantidad</b></div>
			  
			    <div class="col-md-1"></div>
			</div>
		</div>
		<form id="venta_form">
			<div class="panel-body" style="padding-top: 0px;" >
				<div class="row">
						<div class="col-md-12" id="lista_productos" style="overflow:scroll;height:300px">
                 		</div>
	
				</div>

				<div class="row">
				  	<div class="col-md-12">
				        <div class="col-md-6">
						 <div class="input-group col-md-8">
						  	<span class="input-group-addon">Fecha</span>
						  	<input type="text" id="fecha" name="fecha" autocomplete="off" class="form-control fecha" tabindex="6">
						  </div>
						 
						</div>

						<div class="col-md-6">
							<div class="input-group col-md-8">
								<span class="input-group-addon">Comentarios</span>
								
								<textarea class="form-control"  rows="1" id="comment" name="coment"></textarea>
							</div>
						</div>
				       
					</div>
				</div>	
			</div>
            
		
			
		</form>
<!-- Footer cobor -->		
		<div class="panel-footer">
		<div class="com-md-12" >
			<div class="row">
			
				    
			 <button style="float: right;" class="btn btn-primary btn-lg" type="button" data-toggle="modal" data-backdrop="static" data-keyboard="true" data-target="#venta_cobrar" id="cobrar_boton">Mermar</button>
			</div>
			
	
				    	  
				    	   
							

				    	
	</div>
</div>



<!-- Modals ---->
<!-- Pago -->
<div class="modal fade" id="venta_cobrar">
  <div class="modal-dialog modal-md" style="margin-top: 60px;">
    <div class="modal-content">
      <div class="modal-body">
		      	<div id="loader" style="display:none;margin-top:80px;text-align:center;margin-bottom:100px">
			      	<p><img src="img/load-verde.gif" width="90"/></p>
			      	<p class="lead">Guardando..</p>
	      		</div>
	      		<h3 class="hidden_loader">¿Es correcta la Merma?</h3>
	      	<!--<div class="input-group col-md-12 mb20 hidden_loader">
			  <span class="input-group-addon f18">Recibe: &nbsp;</span>
			  <input type="text" autocomplete="off" id="recibe_modal" class="form-control input-lg total solo_numero">
			</div>

			<div class="input-group col-md-12 mb20 hidden_loader">
			  <span class="input-group-addon f18">Total: &nbsp;&nbsp;&nbsp;&nbsp;</span>
			  <input type="text" id="total_modal" class="form-control input-lg total" readonly="1" value="0.00">
			</div>
			
			<div class="input-group col-md-12 hidden_loader">
			  <span class="input-group-addon f18">Cambio: </span>
			  <input type="text" id="cambio_modal" class="form-control input-lg total" readonly="1" value="">
			</div>-->
			
      </div>
      <div class="modal-footer hidden_loader">
	    <div class="col-md-6">
    	    <button type="button" class="btn btn-default btn_ac btn-lg" data-dismiss="modal">No</button>
	    </div>
	    <div class="col-md-6 text-right">
	        <button type="button" class="btn btn-success btn_ac btn-lg" id="cobrar" >Si</button>
	    </div>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


