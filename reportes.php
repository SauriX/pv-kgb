<script>
$(function(){

	$('.fecha').datepicker({
    	todayBtn: "linked",
    	format: "yyyy-mm-dd",
	    language: "es",
    	autoclose: true,
	    todayHighlight: true
	});

	$('#fecha_rango .input-daterange').datepicker({
	    format: "yyyy-mm-dd",
    	autoclose: true,
	    datesDisabled: ['11/06/2015', '11/21/2015'],
    	toggleActive: true
	});

});
</script>

<!-- Cortes -->
<div class="modal fade" id="reportes_cortes">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Cortes</h4>
      </div>
      <div class="modal-body">
<!--Formulario -->
		<form class="form-horizontal" action="reportes/cortes.php" method="post" target="_blank">

			<div class="form-group">
				<label for="codigo" class="col-md-4 control-label">Fecha</label>
				<div class="col-md-6">
					<input type="text" class="form-control fecha" autocomplete="off" name="fecha1">
				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

		<form class="form-horizontal" action="reportes/cortes.php" method="post" target="_blank">
			<div class="form-group">
				<label for="nombre" class="col-md-4 control-label">Rango de fechas</label>
				<div class="col-md-6" id="fecha_rango">
					<div class="input-daterange input-group" id="datepicker">
						<input type="text" class="input-sm form-control" autocomplete="off" name="fecha1" />
					    <span class="input-group-addon">a</span>
					    <input type="text" class="input-sm form-control" autocomplete="off" name="fecha2" />
					</div>

				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>

		</form>

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Cortes -->
<div class="modal fade" id="reportes_cortes2">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Insumos</h4>
      </div>
      <div class="modal-body">
<!--Formulario -->
		<form class="form-horizontal" action="reportes/prueba.php" method="post" target="_blank">

			<div class="form-group">
				<label for="codigo" class="col-md-4 control-label">Fecha</label>
				<div class="col-md-6">
					<input type="text" class="form-control fecha" autocomplete="off"  name="fecha1">
				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

		<form class="form-horizontal" action="reportes/cortes.php" method="post" target="_blank">
			<div class="form-group">
				<label for="nombre" class="col-md-4 control-label">Rango de fechas</label>
				<div class="col-md-6" id="fecha_rango">
					<div class="input-daterange input-group" id="datepicker">
						<input type="text" class="input-sm form-control" autocomplete="off" name="fecha1" />
					    <span class="input-group-addon">a</span>
					    <input type="text" class="input-sm form-control" autocomplete="off" name="fecha2" />
					</div>

				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>

		</form>

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Ventas -->
<div class="modal fade" id="reportes_ventas">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Ventas PDF</h4>
      </div>
      <div class="modal-body">
	  
<!--Formulario -->
		<form id="form_venta_una" action="reportes/ventas.php" class="form-horizontal" method="post" target="_blank">

			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Fecha</label>
				<div class="col-md-3">
					<input type="text" class="form-control fecha" name="fecha1" >
				</div>
				<div class="col-md-6 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

		<form id="form_venta_mult" action="reportes/ventas.php" class="form-horizontal" method="post" target="_blank">
			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Rango de fechas</label>
				<div class="col-md-6" id="fecha_rango">
					<div class="input-daterange input-group" id="datepicker">
						<input type="text" class="input-sm form-control" name="fecha1" />
					    <span class="input-group-addon">a</span>
					    <input type="text" class="input-sm form-control" name="fecha2" />
					</div>
				</div>
				<div class="col-md-3 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Ventas -->
<div class="modal fade" id="reportes_ventas3">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Ventas PDF</h4>
      </div>
      <div class="modal-body">
	  
<!--Formulario -->
		<form id="form_venta_una" action="reportes/ventas3.php" class="form-horizontal" method="post" target="_blank">

			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Fecha</label>
				<div class="col-md-3">
					<input type="text" class="form-control fecha" autocomplete="off" name="fecha1" >
				</div>
				<div class="col-md-6 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

		<form id="form_venta_mult" action="reportes/ventas3.php" class="form-horizontal" method="post" target="_blank">
			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Rango de fechas</label>
				<div class="col-md-6" id="fecha_rango">
					<div class="input-daterange input-group" id="datepicker">
						<input type="text" class="input-sm form-control" autocomplete="off" name="fecha1" />
					    <span class="input-group-addon">a</span>
					    <input type="text" class="input-sm form-control" autocomplete="off" name="fecha2" />
					</div>
				</div>
				<div class="col-md-3 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Ventas -->
<div class="modal fade" id="reportes_ventas4">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Ventas PDF</h4>
      </div>
      <div class="modal-body">
	  
<!--Formulario -->
		<form id="form_venta_una" action="reportes/ventas4.php" class="form-horizontal" method="post" target="_blank">

			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Fecha</label>
				<div class="col-md-3">
					<input type="text" class="form-control fecha" name="fecha1" >
				</div>
				<div class="col-md-6 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

		<form id="form_venta_mult" action="reportes/ventas.php" class="form-horizontal" method="post" target="_blank">
			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Rango de fechas</label>
				<div class="col-md-6" id="fecha_rango">
					<div class="input-daterange input-group" id="datepicker">
						<input type="text" class="input-sm form-control" name="fecha1" />
					    <span class="input-group-addon">a</span>
					    <input type="text" class="input-sm form-control" name="fecha2" />
					</div>
				</div>
				<div class="col-md-3 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Ventas -->
<div class="modal fade" id="reportes_ventas_excel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Ventas Excel</h4>
      </div>
      <div class="modal-body">
<!--Formulario -->
		<form id="form_venta_una_exc" action="reportes/ventas_excel.php" class="form-horizontal" method="post" target="_blank">

			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Fecha</label>
				<div class="col-md-3">
					<input type="text" class="form-control fecha" name="fecha1" >
				</div>
				<div class="col-md-6 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

		<form id="form_venta_mult_exc" action="reportes/ventas_excel.php" class="form-horizontal" method="post" target="_blank">
			<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Rango de fechas</label>
				<div class="col-md-6" id="fecha_rango">
					<div class="input-daterange input-group" id="datepicker">
						<input type="text" class="input-sm form-control" name="fecha1" />
					    <span class="input-group-addon">a</span>
					    <input type="text" class="input-sm form-control" name="fecha2" />
					</div>
				</div>
				<div class="col-md-3 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Dotaciones -->
<div class="modal fade" id="reportes_dotaciones">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Dotaciones</h4>
      </div>
      <div class="modal-body">
<!--Formulario -->
		<form class="form-horizontal" action="reportes/dotaciones.php" method="post" target="_blank">

			<div class="form-group">
				<label for="codigo" class="col-md-4 control-label">Fecha</label>
				<div class="col-md-6">
					<input type="text" class="form-control fecha" name="codigo">
				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

		<form class="form-horizontal" action="reportes/dotaciones2.php" method="post" target="_blank">
			<div class="form-group">
				<label for="nombre" class="col-md-4 control-label">Rango de fechas</label>
				<div class="col-md-3">
					<input type="text" class="form-control fecha" name="nombre" placeholder="Inicio">
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control fecha" name="nombre" placeholder="Final">
				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>

		</form>

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Merma -->
<div class="modal fade" id="reportes_merma">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Merma</h4>
      </div>
      <div class="modal-body">
<!--Formulario -->
		<form class="form-horizontal" action="reportes/merma.php" method="post" target="_blank">

			<div class="form-group">
				<label for="codigo" class="col-md-4 control-label">Fecha</label>
				<div class="col-md-6">
					<input type="text" class="form-control fecha" name="codigo">
				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>
		</form>

		<form class="form-horizontal" action="reportes/merma2.php" method="post" target="_blank">
			<div class="form-group">
				<label for="nombre" class="col-md-4 control-label">Rango de fechas</label>
				<div class="col-md-3">
					<input type="text" class="form-control fecha" name="nombre" placeholder="Inicio">
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control fecha" name="nombre" placeholder="Final">
				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">Ver</button>
				</div>
			</div>

		</form>

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
