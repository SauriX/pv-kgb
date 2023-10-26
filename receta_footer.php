<div class="col-md-8">


		<a href="#" onclick="" id="regresar_cats" class="btn btn-default btn-lg btn-danger" style="margin-top: 10px" >Regresar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	
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
