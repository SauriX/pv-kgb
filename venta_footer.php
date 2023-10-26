<div class="col-md-8">

	<?
	if(!$touch){

		?>
		<a href="?Modulo=Venta&Touch=<?=$touch_venta?>" class="btn btn-danger mt10">Touch Venta</a>&nbsp;&nbsp;

		<?	if($s_tipo==1){	 ?>
			<a href="?Modulo=VentaTouch" class="btn btn-danger mt10">Touch</a>&nbsp;&nbsp;

			<?
		}
		$sq_abrir="SELECT abrir_caja from configuracion";
		$q_abrir=mysql_query($sq_abrir);
		$row_abrir = mysql_fetch_array($q_abrir);
		$v_abrir = $row_abrir ['abrir_caja'];

		if($v_abrir == 1 && $s_tipo==1){?>
			<!--a href="javascript:abrirCaja()" class="btn btn-danger mt10">Abrir Caja</a-->&nbsp;&nbsp;
		<? } ?>
		<a href="mesas.php?tipo_u=<?=$s_tipo?>" class="btn btn-warning mt10" data-toggle="modal" data-target="#verMesas">Abiertas</a>
		&nbsp;&nbsp;
		<? if($s_tipo==1){ ?>
			<a href="mesas_x_cobrar.php" class="btn btn-primary mt10" data-toggle="modal" data-target="#verMesasxCobrar">Por Cobrar</a>
			&nbsp;&nbsp;
			<a href="mesas_cobradas.php" class="btn btn-primary mt10" data-toggle="modal" data-target="#verMesasCobradas">Cobradas</a>
			&nbsp;&nbsp;
			<a href="#" class="btn btn-default mt10 boton_corte_caja" data-toggle="modal" data-backdrop="static" data-keyboard="false"  data-target="#corte_caja">Corte de Caja</a>
		<? }}else{ 	?>
		<a href="#" onclick="" id="regresar_cats" class="btn btn-default btn-lg btn-danger" style="margin-top: 10px" >Regresar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a id="listar_touch" class="btn btn-default btn-lg" style="margin-top: 10px" >Lista</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a class="btn btn-info btn-lg" style="margin-top: 10px" onclick="agregaSeparador()" >Separador</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<!--
		<a  href="mesas.php?tipo_u=<?=$s_tipo?>" data-toggle="modal" data-target="#verMesas" class="btn btn-warning btn-lg" style="margin-top: 10px" >Mesas Abiertas</a>
		-->
		<a id="btn_cerrar_sesion" class="btn btn-danger btn-lg pull-right" style="margin-top: 10px;display:none" >Salir</a>

		<? } ?>
</div>


<div class="col-md-4" style="padding-left:0px;">
	<div class="form-group">
		<div class="input-group col-md-12">
			<div class="input-group col-md-12" style="margin-top: 10px">

				<span class="input-group-addon f18">Total: </span>
				<input type="text" class="form-control input-lg total" readonly="1" id="total_totales" value="0.00">
				<span class="input-group-btn">
					<button class="btn btn-primary btn-lg" type="button" id="cobrar_boton">Guardar</button>
					<input type="hidden" id="winHeight" value="323" />

				</span>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('#regresar_cats').click(function() {
	$('.pendiente').hide();
	$('.categorias').show();
	$('.productoop').hide();
	$('.pendiente2').hide();
});

$('#listar_touch').click(function() {

	if($('.mesa').is(":visible")){
		$('.mesa,.panel_touch').hide();
		$('#lista_productos,.panel_productos').show();
		$('#listar_touch').html('Productos');

	}else{
		$('.categorias,.panel_touch').show();
		$('.pendiente,#lista_productos,.panel_productos').hide();
		$('#listar_touch').html('Lista');
	}

});

	/*$('.pendiente').hide();
	$('.categorias').show();
	$('.productoop').hide();*/

$('#cobrar_boton').click(function() {
	var totales = $('#total_totales').val();
	if (totales != 0) {
		$('#venta_cargar').modal();
		$('#venta_cargar').modal({
			backdrop: 'static',
			keyboard: false
		});
	}
});
</script>
