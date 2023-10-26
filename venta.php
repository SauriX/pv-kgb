<style>
	/*.dropdown-menu{
		width: 82%;
		font-size: 14px;
	}*/

	.boton_corte1{
		display: none;
	}
</style>
<?
$corte_permiso = 1;

if($s_tipo!='1'){
	$sql = "SELECT * FROM usuarios WHERE id_usuario = $s_id_usuario";
	$q = mysql_query($sql);
	$dats = mysql_fetch_assoc($q);
	$corte_permiso = $dats['cortes'];

}

/* VENTAS */
$sql="SELECT * FROM ventas
JOIN metodo_pago ON ventas.id_metodo = metodo_pago.id_metodo
WHERE abierta = 0 AND pagada = 1 AND id_corte = 0 ORDER BY fechahora_pagada DESC";
$q=mysql_query($sql);
$valida=mysql_num_rows($q);




/********************************************/
/*	ALGORITMO PARA ACTUALIZAR LOS PRODUCTOS */
/********************************************/
$r_sql = "SELECT*FROM refresh";
$r_q = mysql_query($r_sql);
$r_ft = mysql_fetch_assoc($r_q);

$r_productos = $r_ft['r_productos'];
$r_venta = $r_ft['r_venta'];

if($r_productos!=$r_venta){

	$sql="SELECT productos.* FROM productos
	WHERE productos.activo=1
	ORDER BY productos.id_categoria";

	$q = mysql_query($sql);
	$cuantos = mysql_num_rows($q);
	$strt = 1;

	while($ft = mysql_fetch_assoc($q)){
		$codigo = $ft['codigo'];
		$nombre = acentos($ft['nombre']);
		$precio = $ft['precio_venta'];
		$id_producto = $ft['id_producto'];
		$cont.= "\"$nombre\" : { codigo: \"$codigo\", precio: \"$precio\", id_producto: \"$id_producto\" }";
		if($strt<$cuantos){
			$coma = ",";
		}else{
			$coma = "";
		}
		$cont.=$coma;
 		$strt++;
 	}

	$handle = fopen("lista_productos.php","w");

	$inicio = "\n
/********************************************/
/**   VENDEFACIL 2.0 | LISTA DE PRODUCTOS  **/
/********************************************/
/* Ultima actualizacion: ".date("Y-m-d H:i:s")."*/
/********************************************/
\n";
	$final = "\n
/********************************************/
/**       TERMINA LISTA DE PRODUCTOS       **/
/********************************************/
\n";

 	$cont.= $final;
	fputs($handle,$inicio);
	fputs($handle,$cont);
	fclose($handle);
	mysql_query("UPDATE refresh SET r_venta='$r_productos'");
}
/********************************************/
/*					FIN						*/
/*	ALGORITMO PARA ACTUALIZAR LOS PRODUCTOS */
/********************************************/
?>
<script>
$(function() {

	$(document).keyup(function(e) {


			if(e.keyCode == 27) {
				if($('#venta_cobrar').hasClass('in')){

					$('#venta_cobrar').modal();
				}

			}

			if(e.keyCode == 118) {
				$('#content_verMesasxCobrar').load('mesas_x_cobrar.php');
				$('#verMesasxCobrar').modal('show');
			}


			if(e.keyCode == 119) {
				$('#content_verMesas').load('mesas.php');
				$('#verMesas').modal('show');
			}


			if(e.keyCode == 120) {
					Buscar();
			}

	});

<?
/*
if(!$corte_permiso){
	// $('.boton_corte_caja').hide();
	$text_corte_caja = 'Créditos';

}else{

	$text_corte_caja = 'Corte de Caja';

}*/
?>
	$('.solo_numero').numeric({allow:"."});

	$('#id_producto').focus();

	$('#id_producto_b').keyup(function(e) {

		if(e.keyCode == 120) {
			CerrarBuscar();
		}

	});

	$('#verMesas').on('shown.bs.modal', function (e) {
		$('#mesa').focus();
	});

	$('#verMesas').on('hidden.bs.modal', function (e) {
		$('#id_producto').focus();
		$(this).removeData('bs.modal');
	});

	$('#verMesasxCobrar').on('hidden.bs.modal', function (e) {
		$('#id_producto').focus();
		$(this).removeData('bs.modal');
	});

	$('#verMesasCobradas').on('hidden.bs.modal', function (e) {
		$(this).removeData('bs.modal');
	});


	$('#verMesasxCobrar').on('shown.bs.modal', function (e) {
		$('#mesa_x_cobrar').focus();
	});


	$('#pagarMesa').on('shown.bs.modal', function (e) {
		$('#recibe_txt').focus();
	});


	$('#id_producto,#cantidad').keyup(function(e) {
		var yo = $(this).val();
		var id = $(this).attr('id');

		$(this).val(yo.toUpperCase());

		if(e.keyCode == 13) {
			if(id=='id_producto'){
				$('#cantidad').focus();
			}else{
				enter_codigo();
			}
		}
		if(e.keyCode == 27) {
			var boton_cobrar = $('#cobrar_boton').html();
			if(boton_cobrar=='Cobrar'){
				$('#venta_cobrar').modal();
			}
		}
	});

	$('#cantidad').focus(function() {

		$(this).select();

	});

	$('#pre-corte').click(function(){
		$(this).hide();
		$.get("ac/alerta_corte.php", function(data){
	       	if(data == 1){
	       		if (window.confirm("El efectivo en caja es en negativo, tiene más gastos que ingresos en efectivo, ¿desea continuar?")) {
				  	$('#corte_doit_ok').fadeIn();
					$('#msn_corte').fadeIn();
				}
	       	}else {
	 			$('#corte_doit_ok_pre').fadeIn();

				$('#msn_corte').fadeIn();

	       	}
    	});
	});

	$('#corte_doit').click(function() {
		$(this).hide();
		$.get("ac/alerta_corte.php", function(data){
	       	if(data == 1){
	       		if (window.confirm("El efectivo en caja es en negativo, tiene más gastos que ingresos en efectivo, ¿desea continuar?")) {
				  	$('#corte_doit_ok').fadeIn();
					$('#msn_corte').fadeIn();
				}
	       	}else {
	 			$('#corte_doit_ok').fadeIn();
				$('#msn_corte').fadeIn();
	       	}
    	});//final del get
	});

	$('#corte_doit_ok').click(function(){
		var tpv = $('#txtTpv').val();
		var efectivo = $('#txtEfectivo').val();
		var otrosMet = $('#txtotrosMet').val();
		if (efectivo == '' || efectivo <= 0 || efectivo == 'e') {
			alert('Verifica Efectivo en caja');
		}else if (tpv == '' || tpv <= 0 || tpv == 'e') {
			alert('Verifica Terminal punto de venta');
		}else if (otrosMet == '' || otrosMet <= 0 || otrosMet == 'e') {
			alert('Verifica Otros metodos de pago');
		}else {

			$('#hidden_modal_corte').hide();
			$('#loader_corte').show();
			$('#imagen_loader_corte').attr('src','img/load-verde.gif').show();
			$('#mensaje_loader_corte').html('Realizando Corte de Caja..');
			console.log('hola');
			$.get('ac/corte_realizar.php', { efectivoCa: efectivo, tpvEfec: tpv, otrosMet:otrosMet } ,function(data) {
				console.log(data);
				if(data==1){
					
					$('#imagen_loader_corte').css('-webkit-filter','hue-rotate(40deg)').attr('src','img/ok.png').show();
					$('#mensaje_loader_corte').html('Listo.<br/><br/><button onclick="location.reload();" type="button" class="btn btn-default btn_ac btn-md">Terminar</button>');

				}else if(data=='NOSESSION'){
					alert('Su sesión ha expirado, por favor reingrese nuevamente.');
					location.reload();
				}else if(data=='NOPERMISSION'){
					alert('Usted no tiene permisos para realizar el Corte de Caja.');
					location.reload();
				}else if(data=='ROLLBACK'){
					alert('Ocurrió un error al realizar el Corte de Caja, por favor contacte a soporte.');
					location.reload();
				}else{
					alert(data);
				}
			});

		}
	});

	$('#pre-corte').click(function(){
		$('#hidden_modal_corte').hide();
		$('#loader_corte').show();
		$('#imagen_loader_corte').attr('src','img/load-verde.gif').show();
		$('#mensaje_loader_corte').html('Realizando Corte de Caja..');
		$.get('ac/corte_realizar.php','codigo=d',function(data) {
			if(data==11){
				console.log(data);
				$('#imagen_loader_corte').css('-webkit-filter','hue-rotate(40deg)').attr('src','img/ok.png').show();
				$('#mensaje_loader_corte').html('Listo.<br/><br/><button onclick="location.reload();" type="button" class="btn btn-default btn_ac btn-md">Terminar</button>');

			}else if(data=='NOSESSION'){
				alert('Su sesión ha expirado, por favor reingrese nuevamente.');
				location.reload();
			}else if(data=='NOPERMISSION'){
				alert('Usted no tiene permisos para realizar el Corte de Caja.');
				location.reload();
			}else if(data=='ROLLBACK'){
				alert('Ocurrió un error al realizar el Corte de Caja, por favor contacte a soporte.');
				location.reload();
			}else{
				alert(data);
			}
		});
	});

	$('#cobrar').click(function() {
			cobrar();
	});

	$('#venta_cobrar').on('show.bs.modal', function (e) {
		$('#numero_mesa').val('');
		if(!$('.productos_a_cobrar').length){
			CerrarBuscar();
			return false;
		}
	});

	$('#corte_caja').on('shown.bs.modal',function(e) {
		$('#cancelar_corte_caja').focus();
	});

	$('#corte_caja').on('show.bs.modal', function (e) {
		$('#cancelar_corte_caja').focus();
		$('#contenido_auxiliar').html('').hide();
		//optener corte
		$.get('ac/corte_obtener.php',function(dat) {
				//proviciones validacion
				if(dat=='PROVICIONES'){
					$('.boton_corte1').hide();

					$('#mensaje_provicion').show();
				}else{
					if(dat=='NOCORTE'){
						$('.boton_corte1').hide();
						$('#mesas_abiertas_msg').show();

					}else{
						$('.boton_corte1').show();
						$('#frmDetallesCaja').show();
						$('#corte_doit_ok').show();
						$('#mesas_abiertas_msg').hide();
					}//final else
				}//final validacion


				$('#hidden_modal_corte').show();
				$('#loader_corte').hide();
				$('#cancelar_corte_caja').focus();
		});

	});


	$('#corte_caja').on('hidden.bs.modal', function (e) {
		CerrarBuscar();
		$('#loader_corte').show();
		$('#hidden_modal_corte').hide();
		$('#imagen_loader_corte').show();
		$('#mensaje_loader_corte').html('Obteniendo..');
		$('#corte_doit_ok').hide();
		$('#corte_doit').show();
		$("#pre-corte").show();
		$('#id_producto').val('');
		$('#cantidad').val('1');
		$('#id_producto_b').typeahead('val', '');
		$('#contenido_auxiliar').html('').hide();
		$('#frmDetallesCaja').hide();
		$('#corte_doit_ok').hide();
	});

	$('#venta_cobrar').on('shown.bs.modal', function (e) {
		$('#numero_mesa').focus();
	});

	$('#venta_cobrar').on('hidden.bs.modal', function (e) {
		CerrarBuscar();
		$('.hidden_loader').show();
		$('#loader').hide();
		$('#id_producto').val('');
		$('#cantidad').val('1');
		$('#id_producto_b').typeahead('val', '');
	});

	$('#numero_mesa').alphanumeric();

	$('#numero_mesa').keyup(function(e) {
		if(e.keyCode==13){
			cobrar();
		}
		var yo = $(this).val();
		$(this).val(yo.toUpperCase());
	});

	$('#recibe_txt').keyup(function(e) {
		var recibe = $('#recibe_txt').val();
		var total = $('#consumo_txt').val();
		var cambio = Number(recibe)-Number(total);
		if(cambio>0){
			$('#cambio_txt').val(Number(cambio).toFixed(2));
		}else if(cambio==0){
			$('#cambio_txt').val('0.00');
		}else{
			$('#cambio_txt').val('');
			return false;
		}

		if(e.keyCode==13){
			open($('#id_metodo_pago'));
		}

	});

	$('#id_metodo_pago').change(function() {

		$('#req_factura').focus();
		if(	($(this).val()!='1')&&($(this).val()!='5')&&($(this).val()!='1')&&($(this).val()!='0')	){
			$('#div_numero_cuenta').show();
		}else{
			$('#div_numero_cuenta').hide();
		}
	});

	$('#req_factura').focus(function() {

		var yo = $(this).val();

			setTimeout(function() {

				if($('#req_factura').is(":focus")){
					if(yo=="0"){
						open($('#req_factura'));

					}
				}

			}, 300);
	});

	$('#req_factura').change(function() {
		var yo = $(this).val();
		if(yo==2){
			$('#monto_factura_div').show();
			$('#monto_facturado').attr('monto',$('#consumo_txt').val()).val($('#consumo_txt').val()).focus();

		}else if(yo==1){

			$('#monto_factura_div').hide();

				if($('#numero_cuenta').is(':visible')){
					$('#numero_cuenta').focus();
				}else{
					$('#cobrar_final').focus();
				}

		}

	});

	$('#monto_facturado').keyup(function(e) {

		valida_monto_facturar();

		if(e.keyCode==13){

			if($('#numero_cuenta').is(':visible')){

				$('#numero_cuenta').focus();

			}else{

				cobrar_cuenta();
			}

		}

	});

	$('#monto_facturado').focus(function(e) {

		$(this).select();

	});

	$('#monto_facturado').blur(function() {

		valida_monto_facturar();

	});

	$('#numero_cuenta').keyup(function(e) {

		if(e.keyCode==13){
			cobrar_cuenta();
		}


	});

	$('#cobrar_final').click(function() {

		cobrar_cuenta();

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

function valida_monto_facturar(){

	var monto = Number($('#monto_facturado').attr('monto'));
	var monto_ingresado = Number($('#monto_facturado').val());

	if(monto_ingresado>monto){
		$('#monto_facturado').val('');
	}

}

function cobrar_cuenta(){

		var datos = $('#cobrar_pagar').serialize();


		var yo = $('#cobrar_final').html();

		if(yo=='Confirmar Cobro'){

			$('#cobrar_final').html('Cobrando...');
			$.post('ac/cobrar_pagar.php',datos,function(data) {
				if(data==1){
					window.location  = 'index.php';
				}else{
					alert('Error: '+data);
				}
			});

		}else{
			$('#cobrar_final').html('Confirmar Cobro');
			$('#cobrar_final').removeClass('btn-success').addClass('btn-danger');

		}

}
function open(elem) {
    if (document.createEvent) {
        var e = document.createEvent("MouseEvents");
        e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        elem[0].dispatchEvent(e);
    } else if (element.fireEvent) {
        elem[0].fireEvent("onmousedown");
    }
}
function deshacer(){
		$.ajax({
			url: 'ac/corte_realizar.php',
			type: 'POST',
			data: {codigo:'d'},
		})
		.done(function() {
			alert("ok");
		})
		.fail(function() {
			console.log("error");
		});
}

function cargaAuxiliar(modulo){

		$('#loader_corte').show();
		$('#hidden_modal_corte').hide();
		$('#contenido_auxiliar').html('').hide();
		$.get('data/'+modulo+'.php',function(data) {

			$('#contenido_auxiliar').html(data);
			$('#loader_corte').hide();
			$('#contenido_auxiliar').show('fast',function() {

			});

		});
}
function cerrarAuxiliar(){
	$('#contenido_auxiliar').html('').hide();
	$('#hidden_modal_corte').show();
}


function cobrar(){

		var numero_mesa = $('#numero_mesa').val();


		var yo = $('#cobrar').html();

		if(yo=='Confirmar'){

			$('.hidden_loader').hide();
			$('#loader').show();

			var datos = $('#venta_form').serialize()+'&numero_mesa='+numero_mesa;
			$.post('ac/cobrar.php',datos,function(data) {

					if(data==1){
						cobradoExito();
					}else{
						$('.hidden_loader').show();
						$('#loader').hide();
						alert('Error: '+ data);
					}

			});

		}else{
			$('#cobrar').html('Confirmar');
			$('#cobrar').removeClass('btn-primary').addClass('btn-success');
		}

}

function cobradoExito(){
	$('#venta_cobrar').modal('hide');
	$('#cobrar').html('Cargar');
	$('#cobrar').removeClass('btn-success').addClass('btn-primary');
	$('.lista-productos').remove();
	actualizar_total();
}



function enter_busqueda(codigo){
	CerrarBuscar();
	var cantidad = $('#cantidad').val();
	$('#id_producto').val('');
	$('#cantidad').val('1');
	agregar(codigo,cantidad);
	$('#id_producto_b').typeahead('val', '');
}

function enter_codigo(){
	var codigo = $('#id_producto').val();
	var cantidad = $('#cantidad').val();
	codigo = codigo.toUpperCase();
	$('#id_producto').val('');
	$('#cantidad').val('1');
	$('#id_producto').focus();
	var corta = codigo.split('*');
	if(corta[1]){
		codigo = corta[1];
		cantidad = corta[0];
	}

	agregar(codigo,cantidad);
}

function agregar(codigo,cantidad){
	if((cantidad<=0) || (isNaN(cantidad))){
		return false;
	}

	var datos = dame_info(codigo);
	var random = new Date().getTime();
	var producto = datos['nombre'];
	var unitario = datos['precio'];
	var precio = Number(cantidad)*Number(unitario);
	var id_producto = datos['id_producto'];

	var html = '';

	html+='<div class="row lista-productos" id="'+random+'">';
	html+='<div class="col-md-8" class="lista_nombre">'+producto+'</div>';
	html+='<div class="col-md-1 text-center" class="lista_cantidad">'+cantidad+'</div>';
	html+='<div class="col-md-1 text-right" class="lista_unitario">'+Number(unitario).toFixed(2)+'</div>';
	html+='<div class="col-md-1 text-right" class="lista_precio">'+Number(precio).toFixed(2)+'</div>';
	html+='<div class="col-md-1 text-right" class="lista_eliminar"><span class="glyphicon glyphicon-remove red click" onclick="remover_item(\''+random+'\')"></span></div>';
	html+='<input type="hidden" value="'+cantidad+'" name="cobrar_producto['+random+'_'+id_producto+'_'+unitario+']" data-precio="'+unitario+'" class="productos_a_cobrar">';
	html+='</div>';

	$('#lista_productos').append(html);

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

//	$('#total_totales,#total_modal').val(Number(total).toFixed(2));
	$('#total_totales').val(Number(total).toFixed(2));
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

function abrirCaja(){

	$.get('ac/abrir_caja.php');

}


</script>

<!-- Configuración de compra -->
<div class="panel panel-default">
    <div class="panel-body">
    	<div class="row">
    		<div class="col-md-12">
    			<div class="col-md-8">
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
    			<div class="col-md-4">
    			    <div class="form-group mb0">
    			    	<div class="input-group col-md-12">
    			    	  <span class="input-group-addon">Cantidad</span>
    			    	  <input type="text" id="cantidad" autocomplete="off" value="1" class="form-control solo_numero" tabindex="3">
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
			    <div class="col-md-1 text-right"><b>Unitario</b></div>
			    <div class="col-md-1 text-right"><b>Total</b></div>
			    <div class="col-md-1"></div>
			</div>
		</div>
		<form id="venta_form">
			<div class="panel-body" style="padding-top: 0px;" >
				<div class="row">
					<div class="col-md-12" id="lista_productos" style="overflow:scroll;height:300px">

					</div>
				</div>
			</div>
		</form>


		
<!-- Footer cobor -->
	<div class="panel-footer">
		<div class="row">
			<div class="col-md-8">
<?
if($s_tipo==1){
?>
				<a href="?Modulo=VentaTouch" class="btn btn-danger mt10">Touch</a>&nbsp;&nbsp;

<?
}
	$sq_abrir="SELECT abrir_caja from configuracion";
	$q_abrir=mysql_query($sq_abrir);
	$row_abrir = mysql_fetch_array($q_abrir);
	$v_abrir = $row_abrir ['abrir_caja'];

		if($v_abrir == 1 && $s_tipo==1){?>
		<!--a href="javascript:abrirCaja()" class="btn btn-danger mt10">Abrir Caja</a-->&nbsp;&nbsp;
	<?}

?>
				<a href="mesas.php" class="btn btn-warning mt10" data-toggle="modal" data-target="#verMesas">Abiertas</a>
				&nbsp;&nbsp;
<? if($s_tipo==1){ ?>
				<a href="mesas_x_cobrar.php" class="btn btn-primary mt10" data-toggle="modal" data-target="#verMesasxCobrar">Por Cobrar</a>
				&nbsp;&nbsp;
				<a href="mesas_cobradas.php" class="btn btn-primary mt10" data-toggle="modal" data-target="#verMesasCobradas">Cobradas</a>

				&nbsp;&nbsp;
				<a href="#" class="btn btn-default mt10 boton_corte_caja" data-toggle="modal" data-backdrop="static" data-keyboard="false"  data-target="#corte_caja">Corte de Caja</a>

				<?} ?>
			</div>

			<div class="col-md-4" style="padding-left:0px;">
				<div class="form-group">
					<div class="input-group col-md-12">
				    	<div class="input-group col-md-12">
				    	  <span class="input-group-addon f18">Total: </span>
				    	  <input type="text" class="form-control input-lg total" readonly="1" id="total_totales" value="0.00">
				    	  <span class="input-group-btn">
				    	    <button class="btn btn-primary btn-lg" type="button" data-toggle="modal" data-backdrop="static"  data-target="#venta_cobrar" id="cobrar_boton">Cobrar</button>
							<input type="hidden" id="winHeight" value="323" />

				    	  </span>
				    	</div>
				    </div>
  				</div>
			</div>
		</div>
	</div>
</div>



<!-- Modals -->
<!-- Pago -->
<div class="modal fade" id="venta_cobrar">
  <div class="modal-dialog modal-sm" style="margin-top: 60px;">
    <div class="modal-content">
      <div class="modal-body">
		      	<div id="loader" style="display:none;margin-top:80px;text-align:center;margin-bottom:100px">
			      	<p><img src="img/load-verde.gif" width="90"/></p>
			      	<p class="lead">Guardando..</p>
	      		</div>

	      	<div class="input-group col-md-12 mb20 hidden_loader">
			  <span class="input-group-addon f18">Mesa: &nbsp;</span>
			  <input type="text" autocomplete="off" id="numero_mesa" class="form-control input-lg total ">
			</div>
		<!--
			<div class="input-group col-md-12 mb20 hidden_loader">
			  <span class="input-group-addon f18">Total: &nbsp;&nbsp;&nbsp;&nbsp;</span>
			  <input type="text" id="total_modal" class="form-control input-lg total" readonly="1" value="0.00">
			</div>

			<div class="input-group col-md-12 hidden_loader">
			  <span class="input-group-addon f18">Cambio: </span>
			  <input type="text" id="cambio_modal" class="form-control input-lg total" readonly="1" value="">
			</div>
		-->

      </div>
      <div class="modal-footer hidden_loader">
	    <div class="col-md-6">
    	    <button type="button" class="btn btn-default btn_ac btn-lg" data-dismiss="modal">Cancelar</button>
	    </div>
	    <div class="col-md-6 text-right">
	        <button type="button" class="btn btn-primary btn_ac btn-lg" id="cobrar" >Cargar</button>
	    </div>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Corte de Caja -->
<div class="modal fade" id="corte_caja" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Corte de caja</h4>
			</div>
			<div class="modal-body">

				<div id="loader_corte" style="margin-top:80px;text-align:center;margin-bottom:100px">
					<p><img src="img/load-azul.gif" width="90" id="imagen_loader_corte"/></p>
					<p class="lead" id="mensaje_loader_corte">Obteniendo..</p>
				</div>

				<div id="hidden_modal_corte" style="display:none;">
					<?php if($s_tipo==1){ ?>
						<?php
							include("includes/db.php");
							$sq_corte="SELECT alerta_corte from configuracion ";
							$q_corte=mysql_query($sq_corte);
							$row = mysql_fetch_array($q_corte);
							$v_corte = $row ['alerta_corte'];
						?>

						<center>

							<span class="label" id="msn_corte" style="display:none; color:black; font-size:15px;"><?=  $v_corte?></span>
							<span id="mesas_abiertas_msg" style="display:none"><b>No puede realizar el corte, existen mesas sin pagar, abiertas o no se han cargado mesas.</b></span>
							<span id="mensaje_provicion" style="display:none"><b>Todavía existen gastos provisionados.</b></span>

						</center>

						<form class="form-horizontal" style="display:none" id="frmDetallesCaja">

							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Efectivo en caja:</label>
								<div class="col-sm-8">
									<input type="number" class="form-control" id="txtEfectivo">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-4" for="pwd">Terminal punto de venta:</label>
								<div class="col-sm-8">
									<input type="number" class="form-control" id="txtTpv">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-4" for="pwd">Otros metodos de pago:</label>
								<div class="col-sm-8">
									<input type="number" class="form-control" id="txtotrosMet">
								</div>
							</div>

						</form>

					<?php } ?>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="cancelar_corte_caja">Cancelar</button>
				<button type="button" class=" btn btn-success btn_ac btn-lg" id="corte_doit_ok" style="display:none">Confirmar</button>
			</div>
		</div>

	</div>
</div>

<!-- PAGAR MESA-->
<div class="modal fade" id="pagarMesa">
	<div class="modal-dialog" style="margin-top: 60px;">
		<div class="modal-content">

	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-muted2" id="pagar_mesa_titulo">Pagar Mesa</h4>
	</div>


			<div class="modal-body">
			<form id="cobrar_pagar">
	      		<div class="row">
		  			<div class="col-md-6" style="margin-top: 24px">
		  				<div class="input-group col-md-12 mb20">
		  					<span class="input-group-addon f18">Recibe: &nbsp;</span>
		  					<input type="text" autocomplete="off" id="recibe_txt" name="recibe_txt" class="form-control input-lg total solo_numero">
		  				</div>

		  				<div class="input-group col-md-12 mb20">
		  					<span class="input-group-addon f18">Consumo: </span>
		  					<input type="text"class="form-control input-lg total"  name="consumo_txt" id="consumo_txt" readonly="1" value="0.00">
		  				</div>

		  				<div class="input-group col-md-12">
		  					<span class="input-group-addon f18">Cambio: </span>
		  					<input type="text" class="form-control input-lg total" name="cambio_txt" id="cambio_txt" readonly="1" value="">
		  				</div>
		  			</div>

		  			<div class="col-md-6">
			  			<form>
			  				<div class="form-group">
			  					<label for="exampleInputEmail1">Método de Pago</label>
			  					<select class="form-control" name="id_metodo_pago" id="id_metodo_pago">
				  					<option value="0">Seleccione</option>
<?
								$sql = "SELECT*FROM metodo_pago";
								$q = mysql_query($sql);
								while($ft = mysql_fetch_assoc($q)){
?>
				  					<option value="<?=$ft['id_metodo']?>"><?=$ft['metodo_pago']?></option>
<?
								}
?>
			  					</select>
			  				</div>

			  				<div class="form-group">
			  					<label for="exampleInputEmail1">Factura</label>
			  					<select disabled="true" class="form-control" id="req_factura" name="req_factura">
				  					<option value="1">Seleccione</option>
				  					<option value="1">No</option>
				  					<option value="2">Si</option>
			  					</select>
			  				</div>

			  				<div class="form-group" id="monto_factura_div" style="display: none">
			  					<label for="monto_facturado">Monto a Facturar:</label>
			  					<input type="text" class="form-control solo_numero" id="monto_facturado" name="monto_facturado">
			  				</div>

			  				<div class="form-group" id="div_numero_cuenta" style="display:none">
			  					<label for="exampleInputEmail1">Número de Cuenta</label>
			  					<input type="text" class="form-control solo_numero" id="numero_cuenta" maxlength="4" name="num_cta_txt" placeholder="Últimos 4 dígitos" >
			  				</div>


			  				<input type="hidden" name="id_venta_cobrar" id="id_venta_cobrar" value="" />
						</form>
		  			</div>
				</div>
			</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 text-right">
					<button type="button" class="btn btn-default btn_ac btn-lg" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-success btn_ac btn-lg" id="cobrar_final" >Cobrar</button>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Mesas -->
<div class="modal fade" id="verMesas">
	<div class="modal-dialog">
		<div class="modal-content" id="content_verMesas">
		</div>
	</div>
</div>


<!-- Mesas x Cobrar -->
<div class="modal fade" id="verMesasxCobrar">
	<div class="modal-dialog">
		<div class="modal-content" id="content_verMesasxCobrar">
		</div>
	</div>
</div>

<!-- Mesas Pagadas -->
<div class="modal fade" id="verMesasCobradas">
	<div class="modal-dialog">
		<div class="modal-content" id="content_verMesasCobradas">
		</div>
	</div>
</div>
