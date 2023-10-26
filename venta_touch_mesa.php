<?
$id_venta=$_GET['id_venta'];
$mesa=$_GET['mesa'];
$sq="SELECT venta_detalle.*,productos.nombre FROM venta_detalle
JOIN productos ON productos.id_producto=venta_detalle.id_producto
WHERE id_venta=$id_venta";
$qu=mysql_query($sq);

$consumo_total=0;
$valida=mysql_num_rows($qu);


$sq_bebida="SELECT COUNT(*) as bebida FROM venta_detalle JOIN productos ON productos.id_producto = venta_detalle.id_producto WHERE id_venta = $id_venta and (productos.id_categoria = 4 or productos.id_categoria = 2 or productos.id_categoria = 3)";
$q_bebida=mysql_query($sq_bebida);
$row_b = mysql_fetch_array($q_bebida);
$v_bebida = $row_b ['bebida'];


?>
<hr>
<script>
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
		<div class="row">
			<div class="col-md-9"><h3 style="margin-top:6px;">CONSUMO <? if(is_numeric($mesa)){ echo 'MESA '.$mesa; }else{ echo $mesa; }?></h3> </div>

			<div class="col-md-6" style="text-align: right; left:50%;">
			<?if($v_bebida == 0){?>
				<span class="label" style="color:red; font-size: 18px;">Faltan Bebidas</span>
				<? } ?>

				<a type="button" class="btn btn-warning" href="#" id="btn_cerrar_mesa" onclick="cerrarMesa(<?=$id_venta?>,'<?=$mesa?>')" >CERRAR MESA</a>


			</div>
		</div>
		<hr>
		<?if($valida){?>
		<table class="table table-striped" style="font-size: 18px;">
			<thead>
				<tr>
					<th>Producto</th>
					<th style="text-align: right" width="80">Precio</th>
					<th style="text-align: center" width="50">Cantidad</th>
					<th style="text-align: right" width="80">Total</th>
					<th style="text-align: right" width="80"></th>
				</tr>
			</thead>

			<tbody>
				<? while($dat=mysql_fetch_assoc($qu)){
					//Sacamos los productos
					$consumo_total+=$dat['cantidad']*$dat['precio_venta'];
				?>
				<tr id="detalle_<?=$dat['id_detalle']?>">
					<td><?=$dat['nombre']?></td>
					<td align="right"><?=number_format($dat['precio_venta'],2)?></td>
					<td align="center"><?=$dat['cantidad']?></td>
					<td align="right"><?=number_format($dat['cantidad']*$dat['precio_venta'],2)?></td>
					<td align="right"><a role="button" class="btn btn-danger btn-xs "    onclick="eliminar_detalle(<?=$dat['id_detalle'];?>);">Eliminar</a></td>
				</tr>
				<? } ?>

				<!-- Total -->
				<tr>
					<td></td>
					<td align="right"></td>
					<td align="right">TOTAL: </td>
					<td align="right" id="consumo_total_mesa"><strong><?=number_format($consumo_total,2)?></strong></td>
				</tr>


			</tbody>
		</table>
		<div class="input-group col-md-12 mb20">
		  					<span class="input-group-addon f18">Descuento: </span>
								<select class="form-control " name="descuento_txt" id="descuento_txt">
									<option value="0" data-value="0" selected> Sin descuento </option>
									<?php
										$sql = "SELECT * FROM cupones WHERE activo = 1";
										$q = mysql_query($sql);
										while($ft = mysql_fetch_assoc($q)){
									?>
									<?php if ($ft['porcentaje'] == '100'): ?>
										<option value="<?= $ft['id_cupon'] ?>" data-id="1.00"><?= $ft['cupon'] ?> Porcentaje <?= $ft['porcentaje'] ?>%</option>
									<?php else: ?>
										<option value="<?= $ft['id_cupon'] ?>" data-id=".<?= $ft['porcentaje'] ?>"><?= $ft['cupon'] ?> Porcentaje <?= $ft['porcentaje'] ?>%</option>
									<?php endif; ?>
									<?php } ?>
								</select>
								<input type="hidden" name="DescEfec_txt" id="DescEfec_txt" value="0.00" />
		  				</div>
		<div id="botones_accion row">
		<center>
<input type="text" class="form-control solo_numero" style="display:none;width: 43%" value="" id="nuevo_numero" placeholder="Ingrese nuevo número de mesa."/>
</center>
<a class="btn btn-default btn-sm nuevo_num" role="button" id="cambiar_num" style="display:none" onclick="ejecutar_cambio();">Cambiar</a>
				<a class="btn btn-default btn-sm" style="float: left;" role="button" id="cambiar_numero_mesa2" onclick="imprimir(<?=$id_venta?>);">Reimprimir Comandas</a></div>
				<a class="btn btn-default btn-sm" role="button" id="cambiar_numero_mesa" onclick="cambiar_numero_mesa();">Cambiar Número de Mesa</a></div>
	</div>

		<? }else{ ?>
			<div class="alert alert-danger" role="alert">La mesa que seleccionaste ya no existe.</div>
		<? } ?>
	</div>
</div>
<script>


$( document ).ready(function() {
    console.log( "ready!" );

	var pagarOriginal = $('#consumo_total_mesa').text();
	console.log(pagarOriginal);
	$("#descuento_txt").change(function() {

		var id = $(this).val();
		var porcentaje = $('option:selected',this).attr('data-id');
		if (id == 0) {
			$('#consumo_total_mesa').text(Number(pagarOriginal).toFixed(2));
			$('#DescEfec_txt').val('0.00');

			var totalPag = $('#consumo_txt').val();
			var recibe = $('#recibe_txt').val();
			if (recibe != '') {
				cambio = recibe-totalPag;
				$('#cambio_txt').val(Number(cambio).toFixed(2));
			}
		}else {
			descuento = Number(porcentaje)*Number(pagarOriginal);
			$('#DescEfec_txt').val(Number(descuento));
			totalPag = pagarOriginal-descuento;
			$('#consumo_total_mesa').text(Number(totalPag).toFixed(2));
			var recibe = $('#recibe_txt').val();
			if (recibe != '') {
				cambio = recibe-totalPag;
				$('#cambio_txt').val(Number(cambio).toFixed(2));
			}
		}
		console.log(pagarOriginal);
	});
});






function cerrarMesa(id_venta,mesa){
	$('#btn_cerrar_mesa').html('Cerrando...');
	var id_descuento = $('#descuento_txt').val();
	var monto_descuento = $('#DescEfec_txt').val();
	/*
   	if(descuento !=0){
	   descuento = $('#consumo_total_mesa').text();
   }*/
	$.post('ac/cerrar_mesa.php','mesa='+mesa+'&id_venta='+id_venta+'&id_descuento='+id_descuento+'&monto_descuento='+monto_descuento,function(data) {
		if(data==1){
			window.open("?Modulo=VentaTouch&id_venta=<?=$id_venta?>", "_self");
		}else{
			console.log(data);
			alert(data);
		}
	});
}


function eliminar_detalle(id){

		$('#detalle_'+id).hide();
		$.post('ac/elimina_detalle.php','id_detalle='+id,function(data) {

			if(data==1){
				$.post('ac/consumo_x_mesa.php','id_venta=<?=$id_venta?>',function(num) {
					location.reload();
				});
			}else{
				alert('Error: '+data);
			}


		});

	}

	function ejecutar_cambio(){
		var nuevo_numero = $('#nuevo_numero').val();
		$.post('ac/cambiar_mesa.php','mesa_deseada='+nuevo_numero+'&id_venta=<?=$id_venta?>',function(data) {

			if(data==1){
 				alert('Número de mesa cambiado con éxito');
				 location.reload();
			}else{
				alert(data);
			}


		});

	}

	function cambiar_numero_mesa(){
		$('#nuevo_numero,#cambiar_num').show();
		$('#cambiar_numero_mesa').hide();
		$('#cambiar_numero_mesa2').hide();
		$('#nuevo_numero').focus();
	}


	function imprimir(id){

		$.post('includes/reimprimir_comanda.php','id_venta=<?=$id_venta?>',function(data) {
		        alert(data);

				//window.open("?Modulo=VentaDomicilio", "_self");



		});

	}
</script>
