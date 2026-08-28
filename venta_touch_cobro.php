<?
$id_venta=$_GET['id_venta'];
$mesa=$_GET['mesa'];
$sql="SELECT SUM(cantidad*precio_venta) AS total FROM venta_detalle WHERE id_venta=$id_venta";
$q=mysql_query($sql);
$ft=mysql_fetch_assoc($q);
$consumo=$ft['total'];

$sql = "SELECT pagada,facturado,descuento_txt,para_llevar FROM ventas WHERE id_venta = $id_venta";
$q = mysql_query($sql);
$dats = mysql_fetch_assoc($q);
$pagada = $dats['pagada'];
$facturado = $dats['facturado'];
$id_descuento = $dats['descuento_txt'];
$para_llevar = isset($dats['para_llevar']) ? (int)$dats['para_llevar'] : 0;
$facturale = 0;

if(($pagada==1)&&($facturado==1)) exit('Pagado y facturado already.');

if(($pagada==1)&&($facturado==0)){

	$facturale = 1;

}

if(!$facturale){

		$btn = "COBRAR";

}else{

		$btn = "FACTURAR";


}

?>
<style>
.no-select {
   -webkit-touch-callout: none;
   -webkit-user-select: none;
   -khtml-user-select: none;
   -moz-user-select: none;
   -ms-user-select: none;
   user-select: none;
}
::-moz-selection {
   background: transparent;
}
::selection {
   background: transparent;
}
</style>
<hr>
<script>


$(document).ready(function() {
    console.log('ready');

	$("#espera").hide();
	$("#error").hide();
	$("#datos_cleinte").hide();

	$( "#btcli" ).click(function() {
		$("#msj_error").hide();
	$("#datos_cleinte").hide();
		var numero =$('#numero').val();
		console.log('funca');

		console.log(numero);
  $.ajax({
            type: "GET",
			url: "http://kgbgrill.com/data/cliente.php?id="+numero,
			dataType:'json',
            success: function(data) {

				console.log(data);
				if(data.validacion==1){
					$('#msj_error').text(data.msg);
				$('#cliente').val(data.nombre);
				$('#id_cliente').val(data.id_cliente);
				$("#datos_cleinte").show();
				$("#msj_error").show();
			}else{
                 $('#msj_error').text(data.msg);
				$("#msj_error").show();
				}
			}
        });
});

});
	function killCopy(e){
		return false
	}
	function reEnable(){
		return true
	}

	document.onselectstart=new Function ("return false")

	if (window.sidebar){
		document.onmousedown=killCopy
		document.onclick=reEnable
	}



function actualizaDescuento(){
	var id = $("#descuento_txt").val();
	var porcentaje = $('option:selected','#descuento_txt').attr('data-id');
	var porcentajeC = $('option:selected','#descuento_txt').attr('data-porcent');
	var pagarOriginal=document.getElementById("consumo_txt").value;
	console.log(porcentajeC,"porcent");
	if(porcentajeC <10){
		porcentaje = Number(porcentaje)/10; 	
	}

	if (id == 0) {
		$('#total_txt').val(Number(pagarOriginal).toFixed(2));
		$('#DescEfec_txt').val('0.00');

		var totalPag = $('#total_txt').val();
		var recibe = getRecibeActual();
		if (recibe != '') {
			cambio = recibe-totalPag;
			$('#cambio_txt').val(Number(cambio).toFixed(2));
		}
	}else {
		descuento = Number(porcentaje)*Number(pagarOriginal);
		$('#DescEfec_txt').val(Number(descuento).toFixed(2));
		totalPag = pagarOriginal-descuento;
		$('#total_txt').val(Number(totalPag).toFixed(2));
		var recibe = getRecibeActual();
		if (recibe != '') {
			cambio = recibe-totalPag;
			$('#cambio_txt').val(Number(cambio).toFixed(2));
		}
	}
}

	/*var message="NoRightClicking";
	function defeatIE() {if (document.all) {(message);return false;}}
	function defeatNS(e) {if
	(document.layers||(document.getElementById&&!document.all)) {
	if (e.which==2||e.which==3) {(message);return false;}}}
	if (document.layers)
	{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=defeatNS;}
	else{document.onmouseup=defeatNS;document.oncontextmenu=defeatIE;}
	document.oncontextmenu=new Function("return false")*/

</script>
<div class="row">
	<div class="col-md-3">
	<a type="button" class="btn btn-default btn-lg btn-block" href="index.php">VENTA</a><br>
		<a type="button" class="btn btn-primary btn-lg btn-block" href="?Modulo=VentaTouch" >MESAS</a><br>
		<a type="button" class="btn btn-success btn-lg btn-block" href="?Modulo=VentaTouchCobradas" >MESAS COBRADAS</a><br>
		<a type="button" class="btn btn-default btn-lg btn-block" href="?Modulo=VentaTouchCorte" >CORTE</a>
	</div>

	<div class="col-md-9">
		<!-- Datos -->
		<div class="row">
			<div class="col-md-8">
		  		<div class="panel panel-success">
                	<div class="panel-heading">
                	        <h3 class="panel-title"><?=$btn?> CUENTA <? if($mesa!="BARRA"){ echo 'MESA '.$mesa; }else{ echo $mesa; }?></h3>
                	</div>
                	<div class="panel-body">
                		<form class="form-horizontal" id="form_cobrar">
	                		<input type="hidden" name="id_venta_cobrar" value="<?=$id_venta?>" />
	                		<input type="hidden" name="pagarOriginal" value="<?=$consumo?>" />
							<input type="hidden" name="tc" value="1" />
	                		<?
		                	if($facturale){
							?>
	                		<input type="hidden" name="facturar_only" value="<?=$btn?>" />
	                		<input type="hidden" name="reimprime" value="1" />
	                		<input type="hidden" name="id_venta" value="<?=$id_venta?>" />
							<?
		                	}
	                		?>

							<div class="form-group form-group-lg">
								<label for="inputEmail3" class="col-sm-4 control-label">CLIENTE</label>
								<div class="col-sm-8">
									<div class="input-group">
										<input type="number" class="form-control" id="numero" name="numero">

										<span class="input-group-btn">
											<button class="btn btn-primary btn-lg" type="button" id='btcli'>VALIDAR</button>
										</span>

									</div>
									<p style="color:red" id='msj_error'></p>
									<input type="hidden" name="id_cliente" id="id_cliente" />
									<input type="hidden" name="cliente" id="cliente" />
								</div>
							</div>
							<hr>
							<div class="form-group form-group-lg">
								<label for="inputEmail3" class="col-sm-4 control-label">CONSUMO</label>
								<div class="col-sm-8">
									<input type="number" class="form-control total" id="consumo_txt" name="consumo_txt" readonly="1" value="<?=$consumo?>">
								</div>
							</div>

							<input type="hidden" id="recibe_txt" name="recibe_txt" value="0">

							<div class="form-group form-group-lg">
								<label for="inputEmail3" class="col-sm-4 control-label">EFECTIVO</label>
								<div class="col-sm-8">
									<input type="number" class="form-control total" id="monto_efectivo" name="monto_efectivo" value="0">
								</div>
							</div>

							<div class="form-group form-group-lg">
								<label for="inputEmail3" class="col-sm-4 control-label">TARJETA</label>
								<div class="col-sm-8">
									<input type="number" class="form-control total" name="monto_tarjeta" value="0">
								</div>
							</div>

							<div class="form-group form-group-lg">
								<label for="inputEmail3" class="col-sm-4 control-label">TRANSFERENCIA</label>
								<div class="col-sm-8">
									<input type="number" class="form-control total" name="monto_transferencia" value="0">
								</div>
							</div>

							<div class="form-group form-group-lg">

		  					<label class="col-sm-4 control-label">CUPÓN </label>
                             <div class="col-sm-8">
								<select class="form-control input-lg" name="descuento_txt" id="descuento_txt" onchange="actualizaDescuento()">
									<option value="0" data-value="0" selected> Sin descuento </option>
									<?php
										$sql = "SELECT * FROM cupones WHERE activo = 1";
										$q = mysql_query($sql);
										while($ft = mysql_fetch_assoc($q)){
									?>
									<?php if ($ft['porcentaje'] == '100'): ?>
										<option value="<?= $ft['id_cupon'] ?>" data-porcent ="<?= $ft['porcentaje'] ?>" data-id="1.00"><?= $ft['cupon'] ?> Porcentaje <?= $ft['porcentaje'] ?>%</option>
									<?php else: ?>
										<option value="<?= $ft['id_cupon'] ?>" data-porcent ="<?= $ft['porcentaje'] ?>"  data-id=".<?= $ft['porcentaje'] ?>" <? if($ft['id_cupon']==$id_descuento){ ?>selected<? } ?>><?= $ft['cupon'] ?> Porcentaje <?= $ft['porcentaje'] ?>%</option>
									<?php endif; ?>
									<?php } ?>
								</select>

								</div>
		  					</div>


							  <div class="form-group form-group-lg">
		  						<label class="col-sm-4 control-label">DESCUENTO </label>
								  <div class="col-sm-8">
		  						<input type="text"class="form-control input-lg total"  name="DescEfec_txt" id="DescEfec_txt" readonly="1" value="0.00">
								  </div>
							  </div>
							  <div class="form-group form-group-lg">
		  						<label class="col-sm-4 control-label">iva $: </label>
								  <div class="col-sm-8">
		  						<input type="text"class="form-control input-lg total"  name="iva_efect" id="iva_efect" readonly="1" value="0.00">
								  </div>
							  </div>

							  <div class="form-group form-group-lg">
								<label class="col-sm-4 control-label">TOTAL </label>
								<div class="col-sm-8">
								<input type="text"class="form-control input-lg total"  name="total_txt" id="total_txt" readonly="1" value="<?=$consumo?>">
							    </div>
							 </div>

								<div class="form-group form-group-lg">
									<label class="col-sm-4 control-label">PARA LLEVAR</label>
									<div class="col-sm-8">
										<select class="form-control input-lg" name="para_llevar" id="para_llevar">
											<option value="0" <? if($para_llevar==0){ ?>selected<? } ?>>No</option>
											<option value="1" <? if($para_llevar==1){ ?>selected<? } ?>>Si</option>
										</select>
									</div>
								</div>


							<div class="form-group form-group-lg">
								<label for="inputEmail3" class="col-sm-4 control-label">CAMBIO</label>
								<div class="col-sm-8">
									<input type="number" class="form-control total" name="cambio_txt" id="cambio_txt" readonly="1" value="" style="color:red">
								</div>
							</div>

							<input type="hidden" name="id_metodo_pago" id="id_metodo_pago" value="99">

							<div class="form-group form-group-lg">
								<div class="col-md-12 control-label" style="text-align: left;">FACTURA</div>
								<div class="col-md-12">
									<select class="form-control input-lg" id="req_factura" name="req_factura">
										<option value="1">No</option>
										<option value="2">Si</option>
									</select>
								</div>
							</div>

							<div class="form-group form-group-lg" id="monto_factura_div" style="display:none">
								<label class="col-sm-4 control-label">MONTO A FACTURAR</label>
								<div class="col-sm-8">
									<input type="text" class="form-control input-lg solo_numero" name="monto_facturado" id="monto_facturado" value="0">
								</div>
							</div>

							<div class="form-group form-group-lg" id="div_numero_cuenta" style="display:none">
								<label class="col-sm-4 control-label">NÚMERO DE CUENTA</label>
								<div class="col-sm-8">
									<input type="text" class="form-control input-lg solo_numero" id="numero_cuenta" maxlength="4" name="num_cta_txt" placeholder="Últimos 4 dígitos">
								</div>
							</div>

	                	</form>
	                	</div>
	            	</div>


			</div>

			<div class="col-md-4">
				<a type="button" class="btn btn-default btn-lg btn-block" href="javascript:;" onclick="verConsumo(<?=$id_venta?>,'<?=$mesa?>')" >VER DETALLE</a><br>
			<div>

			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active">
			    	<a href="#billete" aria-controls="home" role="tab" data-toggle="tab">Billetes</a>
			    </li>
			    <li role="presentation">
			    	<a href="#digital" aria-controls="profile" role="tab" data-toggle="tab">Digital</a>
			    </li>


			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
			    <div role="tabpanel" class="tab-pane active" id="billete">

				<div class="well" >
					<div class="row">
						<div class="col-md-6" style="padding: 5px 5px 5px 5px;">
						<a href="#" class="" 	onclick="calculadora_billete(1000)" role="button">
							<img src="img/billetes/1000.jpg" alt="" width="100%" >
								</a></div>
						<div class="col-md-6" style="padding: 5px 5px 5px 5px;"><a href="#" class="" 	onclick="calculadora_billete(500)" role="button">
							<img src="img/billetes/500.jpg" alt="" width="100%" >
							 	</a></div>
						<div class="col-md-6" style="padding: 5px 5px 5px 5px;"><a href="#" class="" 	onclick="calculadora_billete(200)" role="button">
							<img src="img/billetes/200.jpg" alt="" width="100%" >
								</a></div>
						<div class="col-md-6" style="padding: 5px 5px 5px 5px;"><a href="#" class="" 	onclick="calculadora_billete(100)" role="button">
							<img src="img/billetes/100.jpg" alt="" width="100%" >
							 	</a></div>
						<div class="col-md-6" style="padding: 5px 5px 5px 5px;"><a href="#" class="" 	onclick="calculadora_billete(50)" role="button">
							<img src="img/billetes/50.jpg" alt="" width="100%" >
							</a></div>
						<div class="col-md-6" style="padding: 5px 5px 5px 5px;"><a href="#" class="" 	onclick="calculadora_billete(20)" role="button">
							<img src="img/billetes/20.jpg" alt="" width="100%" >
						</a></div>




						<div class="col-md-12" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" 	onclick="calculadora('limpia')" role="button">Borrar</a></div>

					</div>
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
				<a type="button" class="btn btn-success btn-lg btn-block" id="cobrar_final" href="javascript:;" onclick="cobrar();" ><?=$btn?></a><br>
			    </div><!--Termina div de Digital-->
			    <div role="tabpanel" class="tab-pane" id="digital">

					<div class="well" >
					<div class="row">
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(1)" role="button">1</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(2)" role="button">2</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(3)" role="button">3</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(4)" role="button">4</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(5)" role="button">5</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(6)" role="button">6</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(7)" role="button">7</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(8)" role="button">8</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora(9)" role="button">9</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora('00')" role="button">00</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora('0')" role="button">0</a></div>
						<div class="col-md-4" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora('.')" role="button">.</a></div>
						<div class="col-md-12" style="padding: 5px 5px 5px 5px;"><a href="#" class="btn btn-default btn-block btn-lg" onclick="calculadora('limpia')" role="button">Borrar</a></div>

					</div>
				</div>
				<a type="button" class="btn btn-success btn-lg btn-block" id="cobrar_final" href="javascript:;" onclick="cobrar();" ><?=$btn?></a><br>
			    </div><!--Termina div de Billetes-->

			  </div>

						<input type="checkbox" id="check_imprimir" name="check_imprimir" > <a style="text-decoration: none;color:black; font-size:18px">Omitir Impresión<a>

			</div>

			<!--Terminar -->
			</div>
		</div>
	</div>
<input type="hidden" id="enfoque" value="#monto_efectivo" />

</div>
<script>
function verConsumo(id_venta,mesa){
	window.open("?Modulo=VentaTouchMesaPendiente&id_venta="+id_venta+"&mesa="+mesa, "_self");
}
function no_requiere(){

reset();

}

function getRecibeActual(){
	var recibe = $('#monto_efectivo').val();
	if(recibe===''){
		recibe = '';
	}else{
		recibe = Number(recibe);
	}
	$('#recibe_txt').val(recibe);
	return recibe;
}
<? if($id_descuento){ ?>
	actualizaDescuento();
<? } ?>
function cobrar(){

	 	var porId=document.getElementById("check_imprimir").checked;




		var datos = $('#form_cobrar').serialize()+'&check_imprimir='+porId ;
		//alert(datos);
			var yo = $('#cobrar_final').html();

		if(yo=='Cobrando...'){
			alert('Cobro en curso...');
			return false;
		}

		if(yo=='Confirmar Cobro'){

			$('#cobrar_final').html('Cobrando...');
			$.post('ac/cobrar_pagar.php',datos,function(data) {
				console.log(datos);
				data = $.trim(data);
				var datas = data.split('|');

				if(datas[0]==1){
					window.location  = 'index.php?Modulo=VentaTouch';
				}else{
					console.log(data);
					alert('Error: '+data);
					$('#cobrar_final').html('COBRAR');
					$('#cobrar_final').removeClass('btn-danger').addClass('btn-success');

				}
			});

		}else{
			$('#cobrar_final').html('Confirmar Cobro');
			$('#cobrar_final').removeClass('btn-success').addClass('btn-danger');

		}


}

function requiere(){

	var x = $('.metodo_pago:checked').val();
	alert(x);
		if(x>1){
			$('.4digitos').show();
			$('#4digitos_txt').val();
		}else{
			$('.4digitos').hide();
			$('#4digitos_txt').val('');
		}

		$('#4digitos_txt').focus();

}

function reset(){

	$('#4digitos_txt').val('');
	$('.4digitos').hide();

}

function calculadora(data){
	var cad = $('#enfoque').val();

	var len = $(cad).is(':visible');
	if(!len) return false;

	if(data=='limpia'){
		$(cad).val('');
		$('#cambio_txt').val('');
		return false;
	}

	var el = $(cad).val();

		$(cad).val(el+data);

	if(cad=='#4digitos_txt'){
		var xy = $('#4digitos_txt').val().length;
		if(xy>4){
			alert('MÁXIMO 4 DÍGITOS');
			$('#4digitos_txt').val('');
			return false;
		}
	}else{
		calcular_cambio();
	}




}

function calculadora_billete(data_billete){
	var billete_d = $('#enfoque').val();
	var recibe = $(billete_d).val();


	if(recibe == ""){
		var recibe = 0;

		suma = parseFloat(recibe) + parseFloat(data_billete);
		$(billete_d).val(suma);
		getRecibeActual();
		calcular_cambio();
	}
	else{
		suma = parseFloat(recibe) + parseFloat(data_billete);
		$(billete_d).val(suma);
		getRecibeActual();
		calcular_cambio();
	}





}

function inpuesto(){
	var iva = $('input:radio[name=iva]:checked').val()
	var totalPag = $('#consumo_txt').val();
	var inpuesto = (totalPag * iva)/100;
	var consumo_total= Number(totalPag) + inpuesto;
	$('#iva_efect').val(Number(inpuesto).toFixed(2));
	$('#total_txt').val(Number(consumo_total).toFixed(2));
	var recibe = getRecibeActual();
	if (recibe != '') {
		cambio = recibe-consumo_total;
		$('#cambio_txt').val(Number(cambio).toFixed(2));
	}
}

function calcular_cambio(){

		var efectivo = Number(getRecibeActual()) || 0;
		var total = Number($('#total_txt').val()) || 0;
		var tarjeta = Number($('[name="monto_tarjeta"]').val()) || 0;
		var transferencia = Number($('[name="monto_transferencia"]').val()) || 0;

		var requerido_efectivo = total - (tarjeta + transferencia);
		if(requerido_efectivo < 0){
			requerido_efectivo = 0;
		}

		var cambio = efectivo - requerido_efectivo;
		if(cambio > 0){
			$('#cambio_txt').val(Number(cambio).toFixed(2));
		}else{
			$('#cambio_txt').val('0.00');
		}


}

$(function(){


$('#monto_efectivo').focus(function() {

	$('#enfoque').val('#monto_efectivo');

});


$('#4digitos_txt').focus(function() {

	$('#enfoque').val('#4digitos_txt');

});


$('.metodo_reset').click(function() {

	$('#si_requiere,#no_requiere').removeClass('active');

	$('#radio_si_requiere').prop('checked', false);
	$('#radio_no_requiere').prop('checked', false);

	reset();


/*
si_requiere" onclick="requiere()">
											<input type="radio" name="options" id="radio_si_requiere

*/
});


	$('#monto_efectivo').on('keyup change', function(e) {

		calcular_cambio();

		if(e.keyCode==13){
			$('#req_factura').focus();
		}

	});

	$('[name="monto_tarjeta"], [name="monto_transferencia"]').on('keyup change', function() {
		calcular_cambio();
	});

		$('#req_factura').change(function() {
			if($(this).val()=='2'){
				$('#monto_factura_div').show();
			}else{
				$('#monto_factura_div').hide();
				$('#monto_facturado').val('0');
			}
		});

		$('#req_factura').trigger('change');
});
</script>
