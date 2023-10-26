<?
$id_venta = @$_GET["facturas"];
$iva_total =0;
$monto_total =0;

$ids=[];
foreach($id_venta as $id => $on){


if($on == "on"){
    $ids[] = $id;
}

}
$sql = "SELECT*FROM configuracion  ";
$query = mysql_query($sql);
$datos = mysql_fetch_assoc($query);
$rfc=$datos['rfc'];
$razon = $datos['establecimiento'];
$email = $datos['email_notificacion'];
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
		    	<h3 class="panel-title">Datos del receptor</h3>
		  	</div>
			<div class="panel-body">
				<form id="form_datos" class="form-horizontal">
					<div class="alert alert-warning oculto" style="font-size: 16px;" role="alert" id="msg_error"></div>
					<div class="alert alert-success oculto" style="font-size: 16px;" role="alert" id="msg_ok"></div>
				<div id="step1">

  					<div class="form-group">
						<label for="rfc" class="col-sm-2 control-label">RFC:</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input readonly="readonly" value="<?=$rfc?>" type="text" class="form-control" id="rfc" name="rfc" autocomplete="off" maxlength="13">
							
							</div>
    					</div>
  					</div>

				</div>
				<div id="step2" class="">
					<div class="alert alert-warning oculto" style="font-size: 16px;" role="alert" id="msg_error"></div>
					<div class="form-group">
						<label for="rfc" class="col-sm-2 control-label">Razón Social:</label>
						<div class="col-sm-10">
							<input type="text" readonly="readonly" value="<?=$razon?>" class="form-control limpia" id="razon_social" name="razon_social" autocomplete="off">
    					</div>
  					</div>

    					<label for="email" class="col-sm-2 control-label">Email:</label>
						<div class="col-sm-4">
							<input type="email" r value="<?=$email?>" class="form-control limpia" id="email" name="email" autocomplete="off">
    					</div>
  					</div>

				</div><!-- end step2-->
				</form>

				<div id="confirmacion" class="oculto">
					<div class="col-md-12 text-center">
						<center><img src="http://tacoloco.mx/logo.png" alt="logo" /></center>
						<br>
						<p id="mensaje_confirmacion" style="font-size: 22px;"></p>
					</div>
				</div>


			</div>
		</div>
	</div>


<div class="row " id="step3">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
		    	<h3 class="panel-title">Datos en factura</h3>
		  	</div>
			<div class="panel-body">
				<form id="form_datos2" class="form-horizontal">
				<div class="alert alert-warning oculto" style="font-size: 16px;" role="alert" id="msg_error2"></div>
				<div id="step3">
					<div class="row row justify-content-center justify-content-md-start	">
                    <?foreach($ids as $idventa){
                        
                        $sql = "SELECT*FROM ventas WHERE id_venta = $idventa ";
 
	
                        $query = mysql_query($sql);
                        $existe = mysql_num_rows($query);
                        $datos = mysql_fetch_assoc($query);
                        $metodo_venta = $datos['id_metodo'];
                        if(!$existe) exit('No existe la venta');
                    
                        if($datos['pendiente_facturar']==1):
                            $monto = $datos['pendiente_monto'];
                            $monto_total += $monto;
                            $iva = $monto*0.16;
                        
                            $iva_total +=$iva;
                         
                            $monto = $monto - $iva;
                            
                        else:
                            $monto = $datos['monto_pagado'];
                            $monto_total += $monto;
                            $iva = $monto*0.16;
                        
                            $iva_total +=$iva;
                         
                            $monto = $monto - $iva;
                        endif;
                        ?>
						<div class="col-xs-2">
							<label>Cantidad</label>
							<input type="text" readonly="readonly" class="form-control " name="cantidad" id="cantidad" maxlength="5" autocomplete="off" value="1">
						</div>
						<div class="col-xs-2">
							<label>Unidad</label>
							<select disabled="true" name="unidad" id="unidad" class="form-control" 	>
								<option>Seleccione uno</option>
								<option value="PIEZA" >PIEZA</option>
								<option value="KILO">KILO</option>
								<option value="LITRO">LITRO</option>
								<option value="METRO">METRO</option>
								<option value="SERVICIO" selected="selected">SERVICIO</option>
								<option value="NA" >NA</option>
							</select>
						</div>
						<div class="col-xs-2">
							<label>Clave</label>
							<input type="text" value="90101501" class="form-control" name="clave" id="clave" autocomplete="off" readonly="readonly" value="01">
						</div>
						<div class="col-xs-4">
							<label>Descripción</label>
							<input type="text" readonly="readonly" class="form-control" name="descripcion" id="descripcion"  autocomplete="off" value="TICKET DE VENTA #<?=$datos['id_venta']?>">
						</div>
						<div class="col-xs-2">
							<label>Importe</label>
							<input type="text" value="<?=$monto?>" class="form-control total2" name="importe" id="importe2" maxlength="10" autocomplete="off" readonly="readonly">
						</div>
                        <br>
                        <br>
                        <br>
                        
                        
                        <hr>
                        <?}?>
					</div>
                  
					<br>
					<hr>
					<div class="form-group">
						<label for="rfc" class="col-sm-2 control-label">Método de pago:</label>
						<div class="col-sm-4">
							<select disabled="true" name="metodo_pago" id="metodo_pago" class="form-control">
								<option selected disabled>SELECCIONE</option>
								<? 
								
								
	$sql="SELECT * FROM metodo_pago";
	$q=mysql_query($sql);
								while($ft=mysql_fetch_assoc($q)){ ?>
								<option <? if($metodo_venta==$ft['id_metodo']) echo 'selected="selected"'; ?> value="<?=$ft['id_metodo']?>"><?=$ft['metodo_pago']?></option>
								<? } ?>
							</select>
    					</div>
    					<div class="col-sm-2">
	    					<input type="hidden" class="form-control limpia" name="digitos" id="digitos" placeholder="# Cuenta (4 Digitos)" autocomplete="off" maxlength="4">
    					</div>

    					<label for="rfc" class="col-sm-2 control-label text-right">IVA:</label>
						<div class="col-sm-2">
							<input type="text" value="<?=$iva_total?>" class="form-control  total2" id="iva" name="iva" readonly="readonly" maxlength="10" >
    					</div>
                        
  					</div>

  					<div class="form-group">
						<label for="rfc" class="col-sm-2 control-label">Observación:</label>
						<div class="col-sm-6">
							<textarea class="form-control" rows="3" name="observacion" id="observacion" autocomplete="off"></textarea>
    					</div>

    					<label for="rfc" class="col-sm-2 control-label text-right">Total:</label>
						<div class="col-sm-2">
							<input type="text" class="form-control total2" readonly="readonly" id="total" name="total" value="<?=$monto_total?>" maxlength="10">
    					</div>
  					</div>
				</div><!-- end step3-->
				</form>


			</div>
			<div class="panel-footer text-right">
				
				<button type="button" class="btn btn-primary" onclick="javascript:factura();" id="btn-factura" data-loading-text="Facturando..." >Generar Factura</button>
			</div>
		</div>
	</div>
</div>
</div>


<script src="js/modalplug.js"></script>
<script>
$(function(){
	$('#rfc').focus();

	$('#rfc').keyup(function(e) {

		if(e.keyCode==13){
			consulta();
		}


	});

	$('#importe,#cantidad,#cp,#digitos,#total').keyup(function () {
	    var val = $(this).val();
	    if(isNaN(val)){
	         val = val.replace(/[^0-9\.]/g,'');
	         if(val.split('.').length>2)
	             val =val.replace(/\.+$/,"");
	    }
	    $(this).val(val);
	});

	$('#total,#cantidad').keyup(function(e) {

		/*$('#facturar_saldo').prop('checked',false);
		var total_cantidad = $('#total').val();

		if(Number(total_cantidad)>Number(<?=$monto?>)){
			$('.saldo_pendiente').hide();
		}

		if(Number(total_cantidad)>Number(<?=$monto?>)){
			$('#total').val('<?=$monto?>');
			$('.saldo_pendiente').hide();
			return true;
		}

		if(Number(total_cantidad) < Number(<?=$monto?>)){
			$('.saldo_pendiente').show();
		}

		if(!$('#total').val()){
			var total = 0;
		}else{
			var total = parseFloat($('#total').val());
		}

		if(!$('#cantidad').val()){
			var cantidad = 0;
		}else{
			var cantidad = parseFloat($('#cantidad').val());
		}

		var iva 		  = parseFloat(total/1.16);
		var importe		  = parseFloat(total-iva);
//		var total 		  = parseFloat(importe_final+iva);

		$('#iva').val(importe.toFixed(2));
		$('#importe').val(iva.toFixed(2));
		/*
		if(!$('#importe').val()){
			var importe = 0;
		}else{
			var importe = parseFloat($('#importe').val());
		}

		if(!$('#cantidad').val()){
			var cantidad = 0;
		}else{
			var cantidad = parseFloat($('#cantidad').val());
		}

		var importe_final = parseFloat(importe*cantidad);
		var iva 		  = parseFloat(importe_final*16/100);
		var total 		  = parseFloat(importe_final+iva);

		$('#iva').val(iva.toFixed(2));
		$('#total').val(total.toFixed(2));
		*/
	});
});
function consulta(){

	var codigo = $('#codigo').val();
	var rfc = $('#rfc').val();
	$('#msg_error').html('');
	$('#step2').hide();
	$('#step3').hide();

	$('#razon_social').val('');
	$('#monto').val('');

	if(!rfc){
		$('#msg_error').html("Ingrese su RFC").show();
		$('#rfc').focus();
		return false
	}
	$('#btn-consulta').button('loading');
	$('#msg_error').hide();

	$.ajax({
	   url: "data/datos_cliente.php",
	   data: 'codigo='+codigo+'&rfc='+rfc,
	   success: function(data){
	   		$('#sub').hide();
	   		var datos = data.split('|');
	   		var valida=datos[0];
	   		if(valida==1){
		   		var monto = datos[1];
		   		var razon_social = datos[2];
		   		var email = datos[3];

		   		$('#razon_social').val(razon_social);
		   		$('#monto').val(monto);
				$('#email').val(email);

		   		$('#step2').show();
		   		$('#step3').show();
		   		$('#btn-consulta').button('reset');
	   		}else if(valida==2){
		   		$('.limpia').val("");
		   		var monto = datos[1];
		   		$('#monto').val(monto);

		   		$('#step2').show();
		   		$('#step3').show();
		   		$('#btn-consulta').button('reset');
	   		}
			   $('#total').keyup();
	   	},
	   	cache: false
	});
}

function factura(){

	/*$('#msg_error2,#msg_error').hide();
	var razon_social=	$('#razon_social').val();
	var email		=	$('#email').val();

	//Paso 3
	var cantidad	=	$('#cantidad').val();
	var unidad		=	$('#unidad').val();
	var descripcion	=	$('#descripcion').val();
	var importe		=	$('#importe').val();
	var metodo_pago	=	$('#metodo_pago').val();
	//var digitos		=	$('#digitos').val();

	if(!razon_social){
		$('#msg_error').html('Ingrese razón social.').show();;
		return false;
	}
	if(!email){
		$('#msg_error').html('Ingrese email.').show();;
		return false;
	}
	if(!cantidad){
		$('#msg_error2').html('Ingrese cantidad.').show();;
		return false;
	}
	if(!unidad){
		$('#msg_error2').html('Ingrese unidad.').show();;
		return false;
	}
	if(!descripcion){
		$('#msg_error2').html('Ingrese descripcion.').show();;
		return false;
	}
	if(!importe){
		$('#msg_error2').html('Ingrese importe.').show();;
		return false;
	}
	if(metodo_pago>1){
		if(!digitos){
			$('#msg_error2').html('Para este método de pago es necesario llenar el campo "# Cuenta (4 Digitos)".').show();
			$('#digitos').focus();
			return false;
		}

		if(metodo_pago>1){
			if(digitos.length != 4){
				$('#msg_error2').html('Debes capturar los últimos 4 digitos de la tarjeta.').show();;
				$('#digitos').focus();
				return false;
			}
		}
	}*/
	if(!confirm('¿Está seguro que sus datos son correctos?')) return false;

	$('#btn-factura').button('loading');
	$('#msg_error').hide();

	/* Esta madre se va por CURL a data/factura.php */
	
	var datos = $('#form_datos').serialize()+'&'+$('#form_datos2').serialize()+'&id_venta=<?=$id_venta?>&monto_real=<?=$monto?>&facturar_saldo='+$('#facturar_saldo').val();

	$.post('data/factura_masiva.php',datos,function(data) {
		if(!isNaN(data)){
			
			//window.open("?Modulo=VerFacturas&id_factura="+data, "_self");
	     if(confirm(data)){
			location.reload();
		 }
		}else{
			$('#msg_error').html(data).show();
			$('#btn-factura').button('reset');
		}

	});

}
</script>
<!--inverso el total y limpiar campos-->
