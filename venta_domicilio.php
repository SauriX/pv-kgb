
<style>
	/* Customize the label (the container) */
	.containerx {
	display: block;
	position: relative;
	padding-left: 35px;
	margin-bottom: 12px;
	cursor: pointer;
	font-size: 15px;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	}

	/* Hide the browser's default radio button */
	.containerx input {
	position: absolute;
	opacity: 0;
	cursor: pointer;
	height: 0;
	width: 0;
	}

	/* Create a custom radio button */
	.checkmark {
	position: absolute;
	top: 0;
	left: 0;
	height: 20px;
	width: 20px;
	background-color: #eee;
	border-radius: 50%;
	}

	/* On mouse-over, add a grey background color */
	.containerx:hover input ~ .checkmark {
	background-color: #ccc;
	}

	/* When the radio button is checked, add a blue background */
	.containerx input:checked ~ .checkmark {
	background-color: #2196F3;
	}

	/* Create the indicator (the dot/circle - hidden when not checked) */
	.checkmark:after {
	content: "";
	position: absolute;
	display: none;
	}

	/* Show the indicator (dot/circle) when checked */
	.containerx input:checked ~ .checkmark:after {
	display: block;
	}

	/* Style the indicator (dot/circle) */
	.containerx .checkmark:after {
	top: 6px;
	left: 6px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
	}

	.boton_corte1{
		display: none;
	}
	.text-center{
		text-align: right;
	}
	.gris{
		background-color:#757575;
		border-color: #757575;
	}
	.griso{
		background-color:#3A3A3A;
		border-color:#3A3A3A;
	}
</style>
<?
$auto_cobro=0;
$pack=0;
$sql74="SELECT auto_cobro,paquetes FROM configuracion";
		$query74 = mysql_query($sql74);
		while($ft=mysql_fetch_assoc($query74)){
		$auto_cobro = $ft['auto_cobro'];
		$pack = $ft['paquetes'];
		}

$corte_permiso = 1;

if($s_tipo!='1'){
	$sql = "SELECT * FROM usuarios WHERE id_usuario = $s_id_usuario";
	$q = mysql_query($sql);
	$dats = mysql_fetch_assoc($q);
	$corte_permiso = $dats['cortes'];

}

//$touch_get = $_GET['Touch'];
$touch_get = 'yes';

if($touch_get=='no'){
	$_SESSION['touch'] = 0;
}

if(!$touch_get){
	if($_SESSION['touch']==1){
		$touch_get = 'yes';
	}
}

if($touch_get=='yes'){
	$touch = 1;
	$touch_venta = 'no';
	$_SESSION['touch'] = 1;
}else{
	$touch = 0;
	$touch_venta = 'yes';
}

/********************************************/
/*	ALGORITMO PARA ACTUALIZAR LOS PRODUCTOS */
/********************************************/
$r_sql = "SELECT*FROM refresh";
$r_q = mysql_query($r_sql);
$r_ft = mysql_fetch_assoc($r_q);
$r_productos = $r_ft['r_productos'];
$r_venta = $r_ft['r_venta'];

$sql_productos = "SELECT productos.* FROM productos
LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria
WHERE productos.activo=1
ORDER BY productos.id_categoria ASC, productos.precio_venta ASC";




if($r_productos!=$r_venta){

	$q = mysql_query($sql_productos);
	$cuantos = mysql_num_rows($q);
	$strt = 1;



	while($ft = mysql_fetch_assoc($q)){
		$codigo = trim($ft['codigo']);
		$nombre = acentos($ft['nombre']);
		$precio = $ft['precio_venta'];
		$id_producto = $ft['id_producto'];
		$impresora = $ft['impresora'];
		$impresora = (!$impresora) ? 'NULL' : $impresora;
        //New
		$cont.= "\"$nombre\" : { codigo: \"$codigo\", precio: \"$precio\", id_producto: \"$id_producto\", impresora: \"$impresora\" }";
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

function dotas (){
		dotar();
		vaciar();
		mermar();
	}


function calcular_cambio(){

	var recibe	=	$('#recibe_txt').val();
	var total	=	$('#consumo_txt').val();
	var cambio	=	Number(recibe)-Number(total);

	if(cambio>0){
		$('#cambio_txt').val(Number(cambio).toFixed(2));
	}else if(cambio==0){
		$('#cambio_txt').val('0.00');
	}else{
		$('#cambio_txt').val('');
		return false;
	}

}



function contador(){
	$.get('ac/corte_obtener.php',function(dat){
		console.log(dat);
		<?php if ($n_corte != 0){?>
		if (dat == 'NOVENTAS') {
			document.getElementById("btnDoCorte").disabled = true;
	  }else {
	    document.getElementById("btnDoCorte").disabled = false;
	  }
	  	<?php } ?>
	});



}

function dotar(){
	$.get('https://kgbgrill.com/dotaciones/dotar.php?id=1',function(respuesta){
		var datos = JSON.parse(respuesta);
		if(datos.respuesta){
			
			$.post('ac/auto_dotar.php',datos,function(respuesta){
				var datos = JSON.parse(respuesta);
				
				if(datos.respuesta){
					$('#myModalLabel').html('Dotaciones');
					$('#modal_dotaciones').html(datos.productos);
					$('#dotaciones').modal();
				}
			});
		}
	});
}
function mermar(){
	$.get('https://kgbgrill.com/dotaciones/mermar.php?id=1',function(respuesta){
		var datos = JSON.parse(respuesta);
		if(datos.respuesta){
			
			$.post('ac/auto_mermar.php',datos,function(respuesta){
				var datos = JSON.parse(respuesta);
				
				if(datos.respuesta){
					$('#myModalLabel').html('Mermas');
					$('#modal_dotaciones').html(datos.productos);
					$('#dotaciones').modal();
				}
			});
		}
	});
}
function vaciar(){
	$.get('https://kgbgrill.com/dotaciones/vaciar.php?id=1',function(respuesta){
		var datos = JSON.parse(respuesta);
		if(datos.respuesta){
			
			$.post('ac/vaciar_existencias.php',function(data){
				if(data==1){
					alert('se han restablecido las existencias');
					window.location = 'index.php';
				}else{
					$('#confi_load').hide();
					$('.btn').show();
					$('#confi_msg_error').html(data);
					$('#confi_msg_error').show('Fast');
				}
			});
		}
	});
}
jQuery(document).ready(function(){

	$('#abrir_doit_ok').click(function() {
		abrirCajaFondo();
	});

	$("#descuento_txt").change(function() {
		var id = $(this).val();
		var porcentaje = $('option:selected',this).attr('data-id');
		var porcentajeC = $('option:selected',this).attr('data-porcent');
		if(porcentajeC <10){
		porcentaje = Number(porcentaje)/10; 	
	}

		if (id == 0) {
			$('#consumo_txt').val(Number(pagarOriginal).toFixed(2));
			$('#DescEfec_txt').val('0.00');

			var totalPag = $('#consumo_txt').val();
			var recibe = $('#recibe_txt').val();
			if (recibe != '') {
				cambio = recibe-totalPag;
				$('#cambio_txt').val(Number(cambio).toFixed(2));
			}
		}else {
			descuento = Number(porcentaje)*Number(pagarOriginal);
			$('#DescEfec_txt').val(Number(descuento).toFixed(2));
			totalPag = pagarOriginal-descuento;
			$('#consumo_txt').val(Number(totalPag).toFixed(2));
			var recibe = $('#recibe_txt').val();
			if (recibe != '') {
				cambio = recibe-totalPag;
				$('#cambio_txt').val(Number(cambio).toFixed(2));
			}
		}
	});

	contador();


	setInterval('contador()',30000);

	$('.productos_extra2').hide();



	$(document).keyup(function(e) {


			if(e.keyCode == 27) {
				var totales = $('#total_totales').val();
				if (totales != 0) {
					if(!$('#venta_cargar').hasClass('in')){
						$('#venta_cargar').modal();
						$('#venta_cargar').modal({
							backdrop: 'static',
					    keyboard: false
						});
					}
				}
			}

			if(e.keyCode == 118) {
				$('#content_verMesasxCobrar').load('mesas_x_cobrar.php');
				$('#verMesasxCobrar').modal('show');
			}


			if(e.keyCode == 119) {
				$('#content_verMesas').load('mesas.php?tipo_u=<?=$s_tipo?>');
				$('#verMesas').modal('show');
			}


			if(e.keyCode == 120) {
					Buscar();
			}

	});

<?php
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

$( "#cerrador" ).click(function() {
	location.reload();
});
	$("#numero_telefono").keypress(function(e){
        if(e.keyCode == 13){
			consultaNumero();
        }
	});

	$('#txtFondo').keypress(function(e){
        if(e.keyCode == 13){
			abrirCajaFondo();
        }
	});

	$("form").submit(function(e){
    	e.preventDefault();
 	});

	$('#verMesas').on('shown.bs.modal', function (e) {
		$('#mesa').focus();
	});

	$('#venta_cargar').on('shown.bs.modal', function (e) {
		$('#numero_mesa1').focus();
	});
	$('#verMesas').on('hidden.bs.modal', function (e) {
		$('#id_producto').focus();
		$(this).removeData('bs.modal');
	});


	//verOpcionesProductos
	$('#verOpcionesProductos').on('hidden.bs.modal', function (e) {
		//$('.comentarios').val("");
		//$(this).removeData('bs.modal');
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

	$('#abrir_caja_modal').on('shown.bs.modal', function (e) {
		$('#txtFondo').focus();
	});

	$('#verOpcionesProductos2').on('hidden.bs.modal', function (e) {
		$('.comentarios').val("");
		//$(this).removeData('bs.modal');
	});

	$('#verOpcionesProductos2').on('shown.bs.modal', function (e) {
		$('.comentarios').focus();
		//$(this).removeData('bs.modal');
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
			var totales = $('#total_totales').val();
			var boton_cobrar = $('#cobrar_boton').html();
			if(boton_cobrar=='Cobrar'){
				if (totales != 0) {
					$('#venta_cargar').modal();
				}
			}
		}
	});

	$('#cantidad').focus(function() {

		$(this).select();

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
		var efectivo = $('#txtEfectivo').val();
		var tpv = $('#txtTpv').val();
		if (efectivo == '' || efectivo <= 0 || efectivo == 'e') {
			alert('Verifica Efectivo en caja');
		}else {

			$('#hidden_modal_corte').hide();
			$('#corte_doit_ok').hide();
			$('#cancelar_corte_caja').hide();
			$('#loader_corte').show();
			$('#imagen_loader_corte').attr('src','img/load-verde.gif').show();
			$('#mensaje_loader_corte').html('Realizando Corte de Caja..');
			$.get('ac/corte_realizar.php', { efectivoCa: efectivo, tpvEfec: tpv, otrosMet:0 } ,function(data) {
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

	$('#venta_cargar').on('show.bs.modal', function (e) {
		$("#numero_mesa1").val("");

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
					if(dat=='NOCORTE' || dat=='NOVENTAS'){
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

	$('#numero_mesa1').alphanumeric();


	$('#numero_mesa1').keyup(function(e) {
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
		/*
		if(e.keyCode==13){

			openXX($('#id_metodo_pago'));

		}
		*/
	});


	$('#id_metodo_pago').change(function() {

		$('#req_factura').focus();

		if(	($(this).val()!='1')&&($(this).val()!='5')&&($(this).val()!='1')&&($(this).val()!='0')	){

			$('#div_numero_cuenta').show();
		}else{
			$('#div_numero_cuenta').hide();
		}


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
	var mesa=0;
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

	$('#touchVenta').click(function() {

		if($('.pendiente').is(":visible")){
			$('.pendiente').hide();
			$('.categorias').show();
		}

	});









	/*Traemos las opciones de la categoria del producto */
	$(document).on('click', '[data-id-producto-opciones]', function () {
	    var variables = $(this).attr('data-id-producto-opciones');
		var datos = variables.split('|');
		var randmon=datos[0];
		var id_producto=datos[1];

	    $.get('data/adicionales.php',{id_producto:id_producto,randmon:randmon},function(data){
			//console.log(data);
			$('#modal_opciones_productos2').html(data);
		});

	});

	$('#cancela4').click(function() {

		numeros = [];
		extras =[];
		console.log(extras);
		console.log(numeros);
		cantidad = 1;


	});


});

var cantidad=1;

function abrirCajaFondo(){
	$('#abrir_doit_ok').attr("disabled", true);
	var fondo = $('#txtFondo').val();
	$.ajax({
		url: 'ac/nuevo_corte.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			total_fondo: fondo
		},
		success: function(SuccessData) {
			if (SuccessData == '1') {

				alert('Caja abierta con éxito');
				location.reload();
			}else {
				alert(SuccessData);
				$('#abrir_doit_ok').attr("disabled", false);

			}
		},
		error: function(ErrorData) {
			location.reload(true);
			console.log(JSON.stringify(ErrorData));
		}
	});
}

function consultaNumero(){
	$('#btn-consulta').button('loading');
	var telefono = $("#numero_telefono").val();
	$.get('data/direcciones_comanda.php',{telefono:telefono},function(data){
		$('#btn-consulta').button('reset');
		$('#muestra_direcciones').html(data);
	});
}


function mas(){
	cantidad = cantidad+1;

    document.getElementById("cantidadpro2").value=cantidad;

}


function menos(){

	if(cantidad!=1){
	cantidad = cantidad-1;

    document.getElementById("cantidadpro2").value=cantidad;
}

}

function guardatemp(){

	var numero_mesa = $('#numero_mesa1').val();


	var yo = $('#cobrar1').html();

	if(yo=='Confirmar'){

		$('.hidden_loader').hide();
		$('#loader').show();

		var datos = $('#venta_form').serialize()+'&numero_mesa='+numero_mesa ;

		$.post('ac/cobrar.php',datos,function(data) {

				if(data==1){


				}else{
					$('.hidden_loader').show();
					$('#loader').hide();
					alert('Error: '+ data);
				}

		});

	}else{
		$('#cobrar1').html('Confirmar');
		$('#cobrar1').removeClass('btn-primary').addClass('btn-success');
	}
}

function cobradoExito(){

	$('#venta_cargar').modal('hide');
	$('#cobrar1').html('Cargar');
	$('#cobrar1').removeClass('btn-success').addClass('btn-primary');
	$('.lista-productos').remove();
	location.reload();
	actualizar_total();

}
function valida_monto_facturar(){

	var monto = Number($('#monto_facturado').attr('monto'));
	var monto_ingresado = Number($('#monto_facturado').val());

	if(monto_ingresado>monto){
		$('#monto_facturado').val('');
	}

}


function inpuesto(){
	var iva = $('input:radio[name=iva]:checked').val()
	var totalPag = $('#consumo_original_txt').val();
	var inpuesto = (totalPag * iva)/100;
	var consumo_total= Number(totalPag) + inpuesto;
	$('#iva_efect').val(Number(inpuesto).toFixed(2));
	$('#consumo_txt').val(Number(consumo_total).toFixed(2));
	var recibe = $('#recibe_txt').val();
	if (recibe != '') {
		cambio = recibe-consumo_total;
		$('#cambio_txt').val(Number(cambio).toFixed(2));
	}
}



function cobrar_cuenta(){

		var datos = $('#cobrar_pagar').serialize();
		var yo = $('#cobrar_final').html();
		if(yo=='Confirmar Cobro'){

			$('#cobrar_final').html('Cobrando...');
			$('#cobrar_final').attr('disabled', 'true');
			$.post('ac/cobrar_pagar.php',datos+'&pagarOriginal='+pagarOriginal,function(data) {
				console.log(data);
				if(data==1){
					window.location  = 'index.php';
				}else{

					$('#cobrar_final').attr('disabled', 'false');
					alert('Error: '+data);
					$('#cobrar_final').html('Cobrar');
					$('#cobrar_final').removeAttr('disabled');
					$('#cobrar_final').removeClass('btn-danger').addClass('btn-success');


				}

			});

		}else{
			$('#cobrar_final').html('Confirmar Cobro');
			$('#cobrar_final').removeClass('btn-success').addClass('btn-danger');

		}

}
/*
function openXX(elem) {
    if (document.createEvent) {
        var e = document.createEvent("MouseEvents");
        e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        elem[0].dispatchEvent(e);
    } else if (element.fireEvent) {
        elem[0].fireEvent("onmousedown");
    }
}
*/
function cargaAuxiliar(modulo){

		$('#loader_corte').show();
		$('.hidden_modal_corte').hide();
		$('#contenido_auxiliar').html('').hide();
		$.get('data/'+modulo+'.php',function(data) {
			$('#verOpcionesProductos').modal();
			$('#contenido_auxiliar').html(data);
			$('#loader_corte').hide();
			$('#contenido_auxiliar').show('fast',function() {

			});

		});
}
function cerrarAuxiliar(){
	$('#contenido_auxiliar').html('').hide();
	$('.hidden_modal_corte').show();
}


/*function cobrar(){

		var numero_mesa = $('#numero_mesa').val();


		var yo = $('#cobrar').html();

		if(yo=='Confirmar'){

			$('.hidden_loader,.calculadora').hide();
			$('#loader').show();

			var datos = $('#venta_form').serialize()+'&numero_mesa='+numero_mesa+'&sesion_mesero='+$('#sesion_mesero').val();
			$.post('ac/cobrar.php',datos,function(data) {

					var d 			= data.split('|');
					var exito 		= d[0];
					var imprimir 	= d[1];
					var getPrint 	= d[2];

					if(exito==1){

						if(imprimir){

							console.log('GET PRINT: '+getPrint);
//							$.post('http://<?//$pc_puente?>/imprimir.php','imprimir='+getPrint);
						}

						cobradoExito();
					}else{
						$('.hidden_loader,.calculadora').show();
						$('#loader').hide();
						alert('Error: '+ data);
					}

			});

		}else{
			$('#cobrar').html('Confirmar');
			$('#cobrar').removeClass('btn-primary').addClass('btn-success');
		}

}*/




function enter_busqueda(codigo){
	CerrarBuscar();
	var cantidad = $('#cantidad').val();
	$('#id_producto').val('');
	$('#cantidad').val('1');
	agregar2(codigo,cantidad);
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

	agregar2(codigo,cantidad,touch = false);
}

function extra(codigo,id,nivel2){

	var pos = extras.indexOf(codigo);
	var element = document.getElementById("extra_"+id);

	if(pos==-1){
  		extras.push(codigo);
   		nivelp=nivel2;

		if(nivelp<=max){
			$('#guardar').hide();
			$('#siguiente').show();
		}

		element.classList.add("seleccionada");
	}else{
		element.classList.remove("seleccionada");
		extras.splice(pos, 1);
	}
}

function extra2(codigo,id,nivel2){

	var pos = extras.indexOf(codigo);
	var sin = document.getElementById("sin_"+id);

	if(pos==-1){
		extras.push(codigo);
		nivelp=nivel2;
 		if(nivelp<=max){
			$('#guardar').hide();
			$('#siguiente').show();
 		}
		sin.classList.add("griso");
	}else{
		sin.classList.remove("griso");
		extras.splice(pos, 1);
	}
}

function mostrar2(){

console.log("nivelmaximo: "+max);
console.log("nivelo: "+nivelp);
console.log(max>=nivelp);

	console.log("entro");
	mostrar(nivelp);
$('#guardar').show();
  $('#siguiente').hide();


}








function abrir(){

	$('#corte_content').load('data/cortes.php');
	$('#corte_caja').modal();
}





function opciones2(id_producto,id,r){

	var randmon=r;
		var id_producto=id_producto;

	    $.get('data/adicionales.php',{id_producto:id_producto,randmon:randmon},function(data){
			//console.log(data);
			$('#modal_opciones_productos2').html(data);
			$('#verOpcionesProductos2').modal();

		});
}



function agregar(codigo,id_extra,cantidad,touch,random2){


	if((cantidad<=0) || (isNaN(cantidad))){

		return false;
	}

	//$('#adicionales').modal();



	var datos = dame_info(codigo);
	var random = new Date().getTime();
	//var random = new Date().getTime();

	var producto = datos['nombre'];

	var unitario = datos['precio'];
	var impresora = datos['impresora'];
	var precio = Number(cantidad)*Number(unitario);
	var id_producto = datos['id_producto'];
	if(impresora == ''){
		//alert(id_producto);
		if(	(touch)	&&	($('.id_producto_h_'+id_producto).length)	){
	//		$('.id_producto_'+id_producto).parent().attr('id'));
			var valor = $('.id_producto_h_'+id_producto).val();
			valor = Number(valor)+Number(cantidad);
			var precio = (Number(valor)*Number(unitario)).toFixed(2);

			$('.id_producto_'+id_producto).html(valor);
			$('.id_producto_h_'+id_producto).val(valor);
			$('.id_producto_p_'+id_producto).html(precio);

		}else{
			var agrega_otro = 1;
		}

	}else{
			var agrega_otro = 1;
	}


	if(agrega_otro==1){

		var id_prod = 0;

		$('.meEncantaElFortnite').each(function (){
			var id_prod = $(this).attr('id_prod');
			var rand = $(this).attr('random');
			$(this).removeClass('id_producto_'+id_prod);
			$('#h_'+rand).removeClass('id_producto_h_'+id_prod);
			$('#p_'+rand).removeClass('id_producto_h_'+id_prod);
		});

		if(id_prod == 0){


			if($('.id_producto_'+id_producto).length){

			var cant_actual = $('.id_producto_h_'+id_producto);
			var total_sumado = Number(cant_actual.val())+Number(cantidad);
			cant_actual.val(total_sumado);

			$('.id_producto_'+id_producto).html(total_sumado);

			total_actual = $('.id_producto_p_'+id_producto).html();
			$('.id_producto_p_'+id_producto).html(Number(total_sumado)*Number(unitario));
				actualizar_total();
				return false;
			}

		}


			var html = '';

			html+='<div class="row lista-productos extra'+random2+'" id="'+random+'">';
			html+='<div class="col-md-7 lista_nombre" style="text-align:left; ">'+'-  '+producto+'</div>';
			html+='<div class="col-md-1 text-center lista_cantidad id_producto_'+id_producto+'" random="'+random+'" id_prod="'+id_producto+'" id="prod_'+id_producto+'">'+cantidad+'</div>';
			html+='<div class="col-md-1 text-right lista_unitario">'+Number(unitario).toFixed(2)+'</div>';
			html+='<div class="col-md-1 text-right lista_precio id_producto_p_'+id_producto+'" id="p_'+random+'">'+Number(precio).toFixed(2)+'</div>';
			html+='<div class="col-md-2 text-right lista_eliminar"><!--<button type="button" class="btn btn-default btn-xs" data-toggle="modal" id="btn-opciones-'+random+'" data-target="#verOpcionesProductos" data-id-producto-opciones="'+random+'|'+id_producto+'">OPCIONES</button>--> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-remove red click" onclick="remover_item(\''+random+'\')"></span></div>';
			html+='<input type="hidden" value="'+cantidad+'" class="productos_a_cobrar id_producto_h_'+id_producto+'" id="h_'+random+'" name="cobrar_producto['+random+'_'+id_producto+'_'+unitario+'_'+cantidad+']" data-cantidad="'+cantidad+'"data-precio="'+unitario+'">';
			html+='<input type="hidden" value="" id="adicional_'+random+'" name="adicional['+random+']">';
			html+='</div>';

			$('#lista_productos').append(html);
			$('#estado_separador').val("1");

	}


	actualizar_total();
	ocultar(id_extra);

}

function ocultar(id){


	//$('.pendiente2').hide();

  $('.productos_extra').show();

  $('.block'+id).hide();

}

function agregaSeparador(random2){

	if(!random2){
		var random2 = 0;
	}
	var estado_separador = $('#estado_separador').val();

	 var random = new Date().getTime();

	if(estado_separador==0){
		alert("Primero debes agregar productos.");
		return false;
	}
	var html = '';

	html+='<div class="row lista-productos extra'+random2+'" id="'+random+'" style="background-color:#5bc0de;color:#fff;">';
	html+='<div class="col-md-10 lista_nombre" style="text-align:center;">Separador de productos';
	html+='<input type="hidden" value="1" class="productos_a_cobrar id_producto_h_0"  name="cobrar_producto['+random+'_0_0.00]" data-precio="0.00">';
	html+='</div>';
	html+='<div class="col-md-2 text-right lista_eliminar"><span class="glyphicon glyphicon-remove click" style="color:#fff;" onclick="remover_item(\''+random+'\')"></span></div>';
	html+='</div>';

	$('#lista_productos').append(html);
	if(estado_separador==1){
		$('#estado_separador').val("0");
	}

	$('.lista_cantidad').each(function(){
		$(this).addClass('meEncantaElFortnite');
		var id_prod = $(this).attr('id_prod');
		var rand = $(this).attr('random');
		$(this).removeClass('id_producto_'+id_prod);
		$('#h_'+rand).removeClass('id_producto_h_'+id_prod);
		$('#p_'+rand).removeClass('id_producto_h_'+id_prod);
	});




}



function redDiv(){
   		var winHeight = $(window).height();
   		var setup_minus = $('#winHeight').val();
   		var nuevo = Number(winHeight) - Number(setup_minus);
   		var auto = nuevo+'px';
		$('#lista_productos').css('height',auto);
		//$('#vista_productos').css('height',auto);
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

function calculadora_touch(val){

	var actual = $('#numero_mesa').val();
	var nuevo = actual+val;

	if(val=='limpia'){
		var nuevo = '';
	}

	$('#numero_mesa').val(nuevo);

}

function calculadora_touch_mesero(val){

	 actual = $('#mesero_password').val();
	var nuevo = actual+val;

	if(val=='limpia'){
		var nuevo = '';
	}

	$('#mesero_password').val(nuevo);

}

</script>

<!-- Configuración de compra -->
<div class="panel panel-default">
    <div class="panel-body">
<? if(!$touch){
		$ocultar_teclado = 'display:none';

?>
    	<div class="row vista_teclado vistas">
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
<? }else{ ?>

    	<div class="row vista_touch vistas">
    		<div class="col-md-12">
					<input type="hidden" value="0" id="estado_separador" />
					<? include('venta_footer.php'); ?>

    		</div>
    	</div>
<?
	$ocultar_touch = 'display:none';
	 } ?>

    </div>
</div>

<!-- Lista de compras -->
	<div class="panel panel-default">

		<div class="panel-heading vista_teclado vistas panel_productos" style="<?=$ocultar_touch?>">
			<div class="row ">
			    <div class="col-md-7"><b>Producto</b></div>
			    <div class="col-md-1 text-center"><b>Cantidad</b></div>
			    <div class="col-md-1 text-right"><b>Unitario</b></div>
			    <div class="col-md-1 text-right"><b>Total</b></div>
			    <div class="col-md-2"></div>
			</div>
		</div>

		<form id="venta_form">
			<div class="panel-body" style="padding-top: 0px;" >

				<div class="row vista_teclado vistas">
					<div class="col-md-12" id="lista_productos" style="<?=$ocultar_touch?>;overflow:scroll;height:300px">
					</div>
				</div>

				<div class="row vista_touch vistas panel_touch" style="<?=$ocultar_teclado?>">
					<div class="col-md-12" id="vista_productos" style="min-height:410px;padding-top: 30px;padding-left:25px;">
<!-- -->
						<div class="row">

<?
$sql="SELECT * FROM categorias WHERE activo = 1 ORDER BY nombre";
$q = mysql_query($sql);
while($ft = mysql_fetch_assoc($q)){
		$sql = "SELECT id_producto FROM productos WHERE id_categoria = {$ft['id_categoria']} AND activo = 1 LIMIT 1";
		$qx = mysql_query($sql);
		$hay_p = mysql_num_rows($qx);
		if(!$hay_p) continue;
?>
<?if($ft['es_paquete']==1){?>
	<?if($pack==1 ){?>

							<div class="col-xs-2 mesa categorias" style="<?=$ocultar_teclado?>;margin-left: 17px;" onclick="mostrarProductos(<?=$ft['id_categoria']?>)">
								<h3><?=$ft['nombre']?></h3>
							</div>
							<?}



						}

else{
	if($ft['ocultar']!=0){?>
							<div class="col-xs-2 mesa categorias" style="<?=$ocultar_teclado?>;margin-left: 17px;" onclick="mostrarProductos(<?=$ft['id_categoria']?>)">
								<h3><?=$ft['nombre']?></h3>
							</div>

<?}}?>



<?
}
	$q_dt = mysql_query($sql_productos);
	while($fx = mysql_fetch_assoc($q_dt)){

		$codigo = $fx['codigo'];
		$nombre = acentos($fx['nombre']);
		$precio = $fx['precio_venta'];
		$id_producto = $fx['id_producto'];
		$tiene = $fx['tiene'];
		$imagen = $fx['imagen'];
        $color=$fx['color'];

?>

 <!--<a href="mesas_cobradas.php" class="col-md-2 mesa pendiente cat_<?=$fx['id_categoria']?>" style="<?=$ocultar_touch?>" onclick="agregar('<?=$codigo?>',1,1);" data-target="#verOpcionesProductos">Cobradas</a>-->
				<?if($tiene==1 ){?>
					<?if($imagen){?>
					<div class="col-xs-2 mesa2 pendiente2 cat_<?=$fx['id_categoria']?>" style="<?=$ocultar_touch?>;margin-left: 17px; "  onclick="opciones('<?=$codigo?>','<?=$id_producto?>')" >
						<input type=image src="data:image/png;base64,<?=$imagen?>" width="100%" height="auto">
					</div>
					<?}else{?>
 							<div class="col-xs-2 mesa negro pendiente cat_<?=$fx['id_categoria']?>" style="<?=$ocultar_touch?>;margin-left: 17px;background-color: <?=$color?>;"  onclick="opciones('<?=$codigo?>','<?=$id_producto?>')" >
								<h3 style="margin-top: 10px;"><?=$nombre?></h3>
								<h4><span class="glyphicon glyphicon-usd" aria-hidden="true"></span><?=$precio?></h4>
								
							</div> 
					<?}?>
				<?}else{?>

		<?if($fx['paquete']==1){?>
		<div class="col-xs-2 mesa pendiente negro pro cat_<?=$fx['id_categoria']?>" style="<?=$ocultar_touch?>;margin-left: 17px ;background-color: <?=$color?>;"  onclick="agregar2('<?=$codigo?>','1','1','0');" >
								<h3 style="margin-top: 10px;"><?=$nombre?></h3>
								<h4><span class="glyphicon glyphicon-usd" aria-hidden="true"></span><?=$precio?></h4>
							</div>
		<?}else{?>

			<div class="col-xs-2 mesa pendiente negro pro cat_<?=$fx['id_categoria']?>" style="<?=$ocultar_touch?>;margin-left: 17px ;background-color: <?=$color?>;"  onclick="agregar2('<?=$codigo?>','1','1','0');" >
								<h3 style="margin-top: 10px;"><?=$nombre?></h3>
								<h4><span class="glyphicon glyphicon-usd" aria-hidden="true"></span><?=$precio?></h4>
							</div>

	<?}}?>



<?
	}
?>





						</div>
					</div>
				</div>
			</div>
<!-- -->

<div class="panel-footer 1">
		<div class="row">
			<div class="col-md-8">
<?

?>
				<a href="?Modulo=VentaTouch" class="btn btn-danger mt10">Módulo de Mesas</a>&nbsp;&nbsp;
				<a href="#" onclick="dotas()"  class="btn btn-info mt10">Verficar dotacion</a>&nbsp;&nbsp;

<?

	$sq_abrir="SELECT abrir_caja from configuracion";
	$q_abrir=mysql_query($sq_abrir);
	$row_abrir = mysql_fetch_array($q_abrir);
	$v_abrir = $row_abrir ['abrir_caja'];

		if($v_abrir == 1 ){?>
		<!--a href="javascript:abrirCaja()" class="btn btn-danger mt10">Abrir Caja</a-->&nbsp;&nbsp;
	<?}

?>
				
				<!--oculte el menú -<a href="mesas_cobradas.php" class="btn btn-primary mt10" data-toggle="modal" data-target="#verMesasCobradas">Cobradas</a>
				-->
				<?php

				if($cortes!=0 || $s_tipo==1){
				if ($n_corte == 0){?>
					
					<button type="button" name="button" class="btn btn-danger mt10 boton_open_caja" data-toggle="modal" data-backdrop="static" data-keyboard="false"  data-target="#abrir_caja_modal" id="btn_open_caja" autocomplete="off">Abrir Caja</button>
					&nbsp;&nbsp;
					<?php }else{ ?>
					<a href="mesas.php" class="btn btn-warning mt10" data-toggle="modal" data-target="#verMesas">Mesas Abiertas</a>
					&nbsp;&nbsp;
 					<a href="mesas_x_cobrar.php" class="btn btn-primary mt10" data-toggle="modal" data-target="#verMesasxCobrar">Mesas Por Cobrar</a>
					&nbsp;&nbsp;
					<button type="button" name="button" class="btn btn-default mt10 boton_corte_caja" data-toggle="modal" data-backdrop="static" data-keyboard="false"  onclick="abrir();" id="btnDoCorte" autocomplete="off">Corte de Caja</button>
				<?php } 
				}?>

			</div>


		</div>
	</div>
</div>
					</div>
				</div>

			</div>
		</form>

<script>
var numeros = [];
var extras = [];
var nivelp = 0;
var cantidad = 1;

function mostrarProductos(id_cat){

	$('.categorias').hide();
		$('.cat_'+id_cat).show();

//redDiv();
}
function opciones(codigo,parent){
	
	var r = 0;
	//var numeros = [];

	numeros.push(codigo);

	$('#modal_opciones_productos').load('opcines_producto.php?padre='+parent);
	$('#siguiente').hide();
	$('#verOpcionesProductos').modal(
		{
    		backdrop: 'static',
    		keyboard: false
		}
	);
}

function dame_info(codigo){
	//Devuelve en array toda la información del código del producto que se recibe. o no. o si.

	var productos;
	var datos = new Array;
	var nombre = [];
	var precio = [];
	var id_producto = [];
	var impresora = [];

	productos = {
		<? require('lista_productos.php'); ?>
	}

	$.each(productos,function(index, object){
	    nombre[object.codigo]= index;
	    precio[object.codigo]= object.precio;
	    id_producto[object.codigo]= object.id_producto;
	    impresora[object.codigo]= object.impresora;
	});

	if(nombre[codigo].length){
		datos['codigo'] = codigo;
		datos['nombre'] = nombre[codigo];
		datos['precio'] = precio[codigo];
		datos['id_producto'] = id_producto[codigo];
		datos['impresora'] = impresora[codigo];
	}else{
		datos = false;
	}
	return datos;
}

function cobrar(){


	var numero_mesa = $('#numero_mesa1').val();
	var yo = $('#cobrar1').html();
	var  auto_cobro = "";
	if($("#radio").is(':checked')) {
		auto_cobro =1;
        } else {
			auto_cobro =0;
        }

		if($("#domicilio").is(':checked')) {
			domicilio =1;
        } else {
			domicilio =0;
        }
	if(yo=='Confirmar'){

		$('.hidden_loader').hide();
		$('#loader').show();
		$('#loader_venta3').show();
		var datos = $('#venta_form').serialize()+'&numero_mesa='+numero_mesa+'&cobro='+auto_cobro+'&domicilio='+domicilio;
	
		$.post('ac/cobrar.php',datos,function(data){
			console.log(data);
    	<?if($auto_cobro==1){?>
			if(data==1){
			cobradoExito();
			}else{
				if(!isNaN(data)){
					console.log(data);
					pagar(data);
				}else{
					alert(data);
					cobradoExito();
				}
			} 
		<?}else{?>
			if(data==1){
				cobradoExito();
			}else{
				if(!isNaN(data)){
					pagar(data);
				}else{
					alert(data);
					cobradoExito();
				}
			}    
		<?}?>

		});
	}else{
		$('#cobrar1').html('Confirmar');
		$('#cobrar1').removeClass('btn-primary').addClass('btn-success');
	}

}

function pagar(id){
	$("#secre").val(id);
	$("#secre2").val("1");
	$('#venta_cargar').modal('hide');
	$('#pagarMesa').modal({
		backdrop: 'static',
    	keyboard: false
	});
	$('#pagarMesa').on('shown.bs.modal',function(e){
		$('#recibe_txt').focus();
		var data2 = $("#secre2").val();
		if(data2==1){
			data	=	$("#secre").val();
			total	=	$("#total_totales").val();
						$("#id_venta_cobrar").val(id);
						$("#consumo_txt").val(total);
						$("#consumo_original_txt").val(total);
			pagarOriginal = total;
		}
	});
}

function agregarOpciones(){

	var random = 0;

	for (l=0;l<cantidad;l++){
    	random = new Date().getTime();
		for (x=0;x<numeros.length;x++){

		agregar2(numeros[x],1,1,random);

    	console.log(numeros[x]);
		}
	}

	if(extras.length>0){
		for (x=0;x<extras.length;x++){
	  		console.log(extras[x]);
	  		agregar(extras[x],3,1,1,random);
		}
	}

	agregaSeparador(random);
	$("#cantidadpro2").val("1");
	$('#verOpcionesProductos').modal('hide');

	numeros = [];
	extras =[];
	console.log(extras);
	console.log(numeros);
	cantidad = 1;

}



function agregar2(codigo,cantidad,touch,random){

	<? if($n_corte == 1 ){ ?>

		//if(!touch) = false;
		if(!random){ random=0 }

if((cantidad<=0) || (isNaN(cantidad))){
	return false;
}

var datos = dame_info(codigo);


if(random==0){
   random = new Date().getTime();
}

var producto = datos.nombre;
var unitario = datos.precio;
var impresora = datos.impresora;
var precio = Number(cantidad)*Number(unitario);
var id_producto = datos.id_producto;

if(impresora == ''){
//alert(id_producto);
   if(	(touch)	&&	($('.id_producto_h_'+id_producto).length)	){
//		$('.id_producto_'+id_producto).parent().attr('id'));
	   var valor = $('.id_producto_h_'+id_producto).val();
	   valor = Number(valor)+Number(cantidad);
	   var precio = (Number(valor)*Number(unitario)).toFixed(2);

	   $('.id_producto_'+id_producto).html(valor);
	   $('.id_producto_h_'+id_producto).val(valor);
	   $('.id_producto_p_'+id_producto).html(precio);

   }else{
	   var agrega_otro = 1;
   }

}else{
   var agrega_otro = 1;
}

if(agrega_otro==1){

   var id_prod = 0;

   $('.meEncantaElFortnite').each(function (){
	   var id_prod = $(this).attr('id_prod');
	   var rand = $(this).attr('random');
	   $(this).removeClass('id_producto_'+id_prod);
	   $('#h_'+rand).removeClass('id_producto_h_'+id_prod);
	   $('#p_'+rand).removeClass('id_producto_h_'+id_prod);
   });

   if(id_prod == 0){
	   if($('.id_producto_'+id_producto).length){

		   var cant_actual = $('.id_producto_h_'+id_producto);
		   var total_sumado = Number(cant_actual.val())+Number(cantidad);
		   cant_actual.val(total_sumado);

		   $('.id_producto_'+id_producto).html(total_sumado);

		   total_actual = $('.id_producto_p_'+id_producto).html();
		   $('.id_producto_p_'+id_producto).html(Number(total_sumado)*Number(unitario));
		   actualizar_total();
		   return false;
	   }
   }

   var html = '';

   html+='<div class="row lista-productos" id="'+random+'">';
   html+='<div class="col-md-7 lista_nombre " style="font-weight: bold;">'+producto+'</div>';
   html+='<div class="col-md-1 text-center lista_cantidad id_producto_'+id_producto+'" random="'+random+'" id_prod="'+id_producto+'" id="prod_'+id_producto+'">'+cantidad+'</div>';
   html+='<div class="col-md-1 text-right lista_unitario">'+Number(unitario).toFixed(2)+'</div>';
   html+='<div class="col-md-1 text-right lista_precio id_producto_p_'+id_producto+'" id="p_'+random+'">'+Number(precio).toFixed(2)+'</div>';
   html+='<div class="col-md-2 text-right lista_eliminar"><button type="button" class="btn btn-default btn-xs" data-toggle="modal" onclick="opciones2('+id_producto+','+id_producto+','+random+')" id="btn-opciones-'+random+'" data-id-producto-opciones="'+random+'|'+id_producto+'"  >OPCIONES</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-remove red click" onclick="remover_item(\''+random+'\')"></span></div>';
   html+='<input type="hidden" value="'+cantidad+'" class="productos_a_cobrar id_producto_h_'+id_producto+'" id="h_'+random+'" name="cobrar_producto['+random+'_'+id_producto+'_'+unitario+'_'+cantidad+']" data-precio="'+unitario+'" >';
   html+='<input type="hidden" value="" id="adicional_'+random+'" name="adicional['+random+']">';
   html+='</div>';

   $('#lista_productos').append(html);
   $('#estado_separador').val(1);

}
actualizar_total();
<? }else{ ?>
	alert('Abra caja para continuar');
<? } ?>


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
	$('#total_totales').val(Number(total).toFixed(2));

}

function remover_item(random){
	$('#'+random).remove();
	$('.extra'+random).remove();
		actualizar_total();
}

function mostrarProductos2(id_pro,codigo){

$('.productos_extra').hide();

$('.productos_extra2'+id_pro).show();

document.getElementById("oculto").style.visibility = "visible";



}
/*$(document).ready(function() {



        $.ajax({
            type: "POST",
            url: "alerta.php",
            success: function(data) {
				console.log(data);
			if(data!=""){


		toastr.error(data, 'Productos bajos!', {timeOut: 0,   newestOnTop: false,  positionClass: "toast-top-center"}).css("width","170px");
	}
			}
        });


});*/


$(document).ready(function() {


	$("#espera").hide();
	$("#error").hide();
	$("#datos_cleinte").hide();



});

function consultar_cliente(){
	var numero_telefonico = $("#numero").val();
	$.post("ac/clientes.php",{"numero":numero_telefonico},function(data){
		var datos = JSON.parse(data);
		alert(datos);
	});
}

function consultar_cupon(){
	var cupon = $("#cupon").val();
	$.post("ac/promociones.php",{"cupon":cupon},function(data){
		var datos = JSON.parse(data);
		alert(datos);
	});
}

</script>
<!-- Footer cobro -->
<?
if(!$touch){
?>
		<div class="panel-footer 2">
			<div class="row">
					<? include('venta_footer.php'); ?>
			</div>
		</div>
<? } ?>

<!-- end div footer -->
</div>
<div id="ohsnap"></div>
<!-- Abrir Caja -->
<div class="modal fade" id="abrir_caja_modal" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Abrir Caja</h4>
			</div>
			<div class="modal-body">

				<form id="frm_fondo">
					<div class="form-group" style="text-align:center;">

						<div class="input-group">
      						<div class="input-group-addon">$</div>
      						<input type="number" class="form-control input-lg total solo_numero" id="txtFondo" placeholder="0.00" maxlength="5" autocomplete="off">
    					</div>
						<!--
						<input type="text" class="form-control solo_numero" id="txtFondo">
					-->
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="cancelar_abrir_caja" >Cancelar</button>

				<button type="button" class="btn btn-info btn_ac" id="abrir_doit_ok">Confirmar Fondo</button>
			</div>
		</div>
	</div>
</div>



<!-- Corte de Caja -->
<div class="modal fade" id="corte_caja" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Corte de caja</h4>
			</div>
			<div class="modal-body" id="corte_content">






			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="cancelar_corte_caja">Cancelar</button>

				<button type="button" class=" btn btn-success btn_ac btn-lg" id="corte_doit_ok" style="display:none">Confirmar</button>
			</div>
		</div>

	</div>
</div>
<!-- Modals ---->
<!-- Pago -->
<div class="modal fade" id="venta_cobrar-viejo">
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

<!-- --->

					<div class="row calculadora">
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(1)" role="button">1</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(2)" role="button">2</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(3)" role="button">3</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(4)" role="button">4</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(5)" role="button">5</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(6)" role="button">6</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(7)" role="button">7</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(8)" role="button">8</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(9)" role="button">9</a></div>


	<div class="col-md-4" style="padding: 5px 5px 5px 5px;"></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch(0)" role="button">0</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"></div>



						<div class="col-md-12" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora_touch('limpia')" role="button">Borrar</a></div>

					</div>


<!-- -->
      </div>
      <div class="modal-footer hidden_loader">
	    <div class="col-md-6">
    	    <button type="button" class="btn btn-default btn_ac btn-lg" data-dismiss="modal">Cancelar</button>
	    </div>
	    <div class="col-md-6 text-right">
	        <button type="button" class="btn btn-primary btn_ac btn-lg" id="cobrar" >Cargar </button>
	    </div>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Modals -->
<!-- Pago -->
<div class="modal fade" id="venta_cargar">
  <div class="modal-dialog modal-sm" style="margin-top: 60px;">
    <div class="modal-content">
      <div class="modal-body">
		      	<div id="loader_venta3" style="display:none;margin-top:80px;text-align:center;margin-bottom:100px">
			      	<p><img src="img/load-verde.gif" width="90"/></p>
			      	<p class="lead">Guardando..</p>
	      		</div>

	      	<div class="input-group col-md-12 mb20 hidden_loader" style="margin-bottom: 0px;">
			  <span class="input-group-addon f18">Mesa: &nbsp;</span>
			  <input type="text" autocomplete="off" id="numero_mesa1" class="form-control input-lg total ">
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
      			<div class="checkbox" style="float: right;" >
  					<label class="checkbox-inline"><input type="checkbox" name="radio" id="radio" <?if($auto_cobro==1){echo('checked');}?>>Cobro directo</label>
				</div>
				<div class="checkbox" style="float: right;" >
  				<label class="checkbox-inline"><input type="checkbox" name="domicilio" id="domicilio" >Domicilio</label>
				</div>
	  <div class="col-md-12">
	    <div class="col-md-6">
    	    <button type="button" class="btn btn-default btn_ac btn-lg" data-dismiss="modal">Cancelar</button>
	    </div>
	    <div class="col-md-6 text-right">

	        <a href="#" role="button" class="btn btn-primary btn_ac btn-lg" id="cobrar1" onclick="javascript:cobrar();"  >Cargar</a>
	    </div>
		</div>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- PAGAR MESA-->
<div class="modal fade" id="pagarMesa">
	<div class="modal-dialog" style="margin-top: 60px;">
		<div class="modal-content">

	<div class="modal-header">
		<button type="button" class="close" id="cerrador" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-muted2" id="pagar_mesa_titulo">Pagar Mesa</h4>
	</div>


			<div class="modal-body">
			<form id="cobrar_pagar">
			<input type="text" style="display: none;" value="false" id="check_imprimir" name="check_imprimir" >
	      		<div class="row">
		  			<div class="col-md-6" style="margin-top: 24px">

		  				<div class="input-group col-md-12 mb20">
		  					<span class="input-group-addon f18">Recibe: &nbsp;</span>
		  					<input type="number" autocomplete="off" id="recibe_txt" name="recibe_txt" class="form-control input-lg total solo_numero">
		  				</div>

							<div class="input-group col-md-12 mb20">
		  					<span class="input-group-addon f18">Consumo: &nbsp;</span>
		  					<input type="text" readonly="1"  autocomplete="off" id="consumo_original_txt" name="consumo_original_txt" class="form-control input-lg total solo_numero">
		  				</div>

							<div class="input-group col-md-12 mb20">
		  					<span class="input-group-addon f18">Descuento: </span>
								<select class="form-control input-lg" name="descuento_txt" id="descuento_txt">
									<option value="0" data-value="0" selected> Sin descuento </option>
									<?php
										$sql = "SELECT * FROM cupones WHERE activo = 1";
										$q = mysql_query($sql);
										while($ft = mysql_fetch_assoc($q)){
									?>
									<?php if ($ft['porcentaje'] == '100'): ?>
										<option value="<?= $ft['id_cupon'] ?>" data-porcent ="<?= $ft['porcentaje'] ?>" data-id="1.00"><?= $ft['cupon'] ?> Porcentaje <?= $ft['porcentaje'] ?>%</option>
									<?php else: ?>
										<option value="<?= $ft['id_cupon'] ?>" data-porcent ="<?= $ft['porcentaje'] ?>" data-id=".<?= $ft['porcentaje'] ?>"><?= $ft['cupon'] ?> Porcentaje <?= $ft['porcentaje'] ?>%</option>
									<?php endif; ?>
									<?php } ?>
								</select>
		  				</div>

							<div class="input-group col-md-12 mb20">
		  						<span class="input-group-addon f18">Descuento $: </span>
		  						<input type="text"class="form-control input-lg total"  name="DescEfec_txt" id="DescEfec_txt" readonly="1" value="0.00">
		  					</div>
							<div class="input-group col-md-12 mb20">
		  						<span class="input-group-addon f18">iva $: </span>
		  						<input type="text"class="form-control input-lg total"  name="iva_efect" id="iva_efect" readonly="1" value="0.00">
		  					</div>
							<div class="input-group col-md-12 mb20">
								<span class="input-group-addon f18">Total: </span>
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
			  					<label for="exampleInputEmail1">Forma de Pago</label>
			  					<!--<select class="form-control" name="id_metodo_pago" id="id_metodo_pago">-->
<?
                                $first = true;
								$sql = "SELECT * FROM metodo_pago ORDER BY id_metodo desc";
								$q = mysql_query($sql);
								while($ft = mysql_fetch_assoc($q)){
?>
				  					<!--<option value="<?=$ft['id_metodo']?>" <? if($first){?>selected="true"<? $first=false;}?> ><?=$ft['metodo_pago']?></option>-->
									<label class="containerx"><?=$ft['metodo_pago']?>
  										<!--<input type="checkbox" checked="checked">-->
										<input type="radio" checked name="id_metodo_pago" value="<?=$ft['id_metodo']?>" <? if($first){?><?}?> />
  										<span class="checkmark"></span>
									</label>


<?
								$first = false;}
?>
			  					<!--</select>-->
			  				</div>
							  <label for="exampleInputEmail1">Inpuestos</label>
							  	<div class="form-check form-check-inline">
  									<input class="form-check-input" onchange="inpuesto()" type="radio" name="iva" id="iva1" value="16">
  									<label class="form-check-label" for="inlineRadio1">16%</label>
								</div>
								<div class="form-check form-check-inline">
  									<input class="form-check-input" onchange="inpuesto()" type="radio" name="iva" id="iva2" value="8">
  									<label class="form-check-label" for="inlineRadio2">8%</label>
								</div>


							  <div class="form-group" id="club">
			  					<label for="exampleInputEmail1">Club KGB</label>
								<div class="input-group">
									<input type="number" class="form-control" id="numero" name="numero" placeholder="Numero Telefonico">
									<span class="input-group-btn">
										<button id="btcli" onclick="consultar_cliente()"  class="btn btn-primary" type="button">Consultar</button>
									</span>
								</div>
								<br>
								<div class="input-group">
									<input type="number" class="form-control" id="cupon" name="cupon" placeholder="Numero de cupon">
									<span class="input-group-btn">
										<button id="btcli" onclick="consultar_cupon()"  class="btn btn-primary" type="button">Consultar</button>
									</span>
								</div><!-- /input-group -->
<!-- 
  										<div class="form-group" id="tel">
    										<label for="numero32">Numero Telfonico: </label>
    										<input type="number" class="form-control" id="numero" name="numero">
  										</div>
										<button type="button" id="btcli" class="btn btn-primary">Consultar</button> -->

			  				</div>
							  <div class="form-group" id="espera">
    										<h2 style="color: red;">Procesando...</h2>
  										</div>

										  <div class="form-group" id="error">
    										<h3 id="msj_error" style="color: red;" ></h2>
  										</div>
							  <div class="form-group" id="datos_cleinte">
    										<label for="pwd">Nombre del cliente:</label>
   											<input type="text" class="form-control" id="cliente" name="cliente">
  										</div>
			  				<input type="hidden" name="req_factura" value="1" />
			  				<input type="hidden" name="id_venta_cobrar" id="id_venta_cobrar" value="" />
							  <input type="hidden" name="id_cliente" id="id_cliente" value="" />
							  <input type="hidden" name="iva_total" id="iva_total" value="" />
						</form>
		  			</div>
				</div>
			</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 text-right">
					<!-- comentado por mangochile<button type="button" class="btn btn-default btn_ac btn-lg" data-dismiss="modal">Cancelar</button>-->
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
<input type="hidden" id="sesion_mesero" value="" />

<input type="hidden" id="seprador_activo" value="0" />


<div id="datos_carga"></div>

<input type="hidden" id="secre" value="0">
<input type="hidden" id="secre2" value="0">
<!-- Opciones de Productos -->
<div class="modal fade" id="verOpcionesProductos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-body">
				<div class="row">
					<div id="modal_opciones_productos"></div>
				</div>
			</div>

			<div class="modal-footer">
				<div class="row">
					<div class="col-md-2" style="">
						<button type="button" id="cancela4" class="btn btn-default btn-lg" data-dismiss="modal">Cancelar</button>
					</div>

					<div class="col-md-3 col-md-offset-5" >

						<div class="input-group col-md-12" style="margin-top:0px;">
							<div class="input-group-btn ">
								<button class="btn btn-primary btn-lg" type="button" onclick="menos()">
									<i class="glyphicon glyphicon-minus"></i>
								</button>
							</div>
							<input type="text" value="1" readonly id="cantidadpro2" class="form-control input-lg" style="text-align:center;font-size:19px;font-weight:bold;" >
							<div class="input-group-btn">
								<button class="btn btn-primary btn-lg" type="button" onclick="mas()">
									<i class="glyphicon glyphicon-plus"></i>
								</button>
							</div>
						</div>
					</div>

					<div class="col-md-2" style="">
						<button type="button" class="btn btn-primary btn-lg" id="siguiente" onclick="mostrar2()" >AGREGAR</button>
					</div>
					<div class="col-md-2" style="">
						<button type="button" class="btn btn-primary btn-lg"  id="guardar" onclick="agregarOpciones()">AGREGAR</button>
					</div>


				</div>


			</div>





		</div>
	</div>
</div>

<!-- Opciones de Productos2 -->
<div class="modal fade" id="verOpcionesProductos2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Opciones para el producto</h4>
			</div>
			<div class="modal-body" id="modal_opciones_productos2">

			</div>
			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="agregarOpciones2()">Guardar</button>
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
        <h4 class="modal-title">Productos agotados</h4>
      </div>
      <div class="modal-body">
		  <div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
		  <ul>
				<?
				include('../includes/db.php');
				include('../includes/funciones.php');


				$sql_pro ="SELECT  * from categorias where alerta = 1 ";
				$q_pro=mysql_query($sql_pro);
				$n_pro=mysql_num_rows($q_pro);

				while($ft=mysql_fetch_assoc($q_pro)){
				   $id_categorias=$ft['id_categoria'];

				   $sql_pro2 ="SELECT  * from productos

				   where productos.id_categoria = $id_categorias
				   ";

				   $q_pro2=mysql_query($sql_pro2);
				   $n_pro2=mysql_num_rows($q_pro2);

				   while($ft2=mysql_fetch_assoc($q_pro2)){
					   $cantidad=$ft2['existencia'];

					   $producto=$ft2['nombre'];
					   if($cantidad<=0){?>
						  <li type="circle"><?=$producto?></li>

					  <? }

				   }

				}

				?>
				</ul>
      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">cerrar</button>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Opciones de Productos3 -->
<div class="modal fade" id="dotaciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body" id="modal_dotaciones">

			</div>
			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
				
			</div>
		</div>
	</div>
</div>