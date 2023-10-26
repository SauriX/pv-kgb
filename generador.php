


<style>
.Vigente{
	color:#47c000;
	font-weight: bold;
}	
.Cancelado{
	color:#bf0000;
	font-weight: bold;
}
</style>
<div class="row mb10">
	<div class="col-md-12 text-right">
	    <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#AgregaProducto">Generar Ventas</a>
	</div>
</div
<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Tickets</h3>
				</div>
				<div class="panel-body">
					<?
					    $sql="SELECT ventas.*,metodo_pago.metodo_pago FROM ventas
					    JOIN metodo_pago ON metodo_pago.id_metodo = ventas.id_metodo
					     WHERE id_corte = 0 AND pagada = 1 AND facturado = 0 ORDER BY id_venta DESC";
					    $q= mysql_query($sql);
					    $n= mysql_num_rows($q);
					 ?>
					 <form id="form_datos">
						 <input type="hidden" name="id_corte" value="<?=$id_corte?>"/>
					<table class="table table-striped" id="tbl-tickets">
						<thead>
							<tr>
								<th>Folio</th>
								<th>Mesa</th>
								<th>Monto</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Método de Pago</th>
								
							</tr>
						</thead>
						<tbody>
						<? 
							while($ft=mysql_fetch_assoc($q)){
							?>
							<tr>
								<td><?=$ft['id_venta']?></td>
								<td><?=$ft['mesa']?></td>
								<td><?
								if($ft['pendiente_facturar']==1):
									$msg = "(saldo de factura)";
									echo $ft['pendiente_monto'];
								else:
									unset($msg);
									echo $ft['monto_pagado'];
								endif;
								?></td>
								<td><?=fechaLetra($ft['fecha'])?></td>
								<td><?=horaVista($ft['hora'])?></td>
								<td><?=$ft['metodo_pago']?></td>
								
							</tr>
						<? 	
							} 

							
						?>
						</tbody>

					</table>
					 </form>
				</div>
			</div>

</div>





<!-- Modal -->
<div class="modal fade" id="AgregaProducto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Nuevo Producto</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm" class="form-horizontal">

		<div class="form-group">
				<label for="codigo" class="col-md-3 control-label">Monto</label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="codigo2" name="total" maxlength="120" autocomplete="off">
				</div>
			</div>

			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Cantidad de ventas</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="cantidad_ventas" maxlength="120" autocomplete="off">
				</div>
			</div>

			            
			<div class="form-group">
				<label for="categoria" class="col-md-3 control-label">Metodo de pago</label>
				<div class="col-md-7">
					<select class="form-control" id="id_categoria" name="metodo">
						<option selected="selected">Seleccione una</option>
						<? $q=mysql_query("SELECT * FROM metodo_pago"); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_metodo']?>"><?=$ft['metodo_pago']?></option>
						<? } ?>
		  			</select>
				</div>
			
			</div>

			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Fecha de inicio</label>
				<div class="col-md-9">
				<input type="text" class="form-control fecha" autocomplete="off" name="fechaActual">
				</div>
			</div>

			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Fecha de inicio</label>
				<div class="col-md-9">
				<input type="text" class="form-control fecha" autocomplete="off" name="fechaRegistro">
				</div>
			</div>

			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Hpra de apertura</label>
				<div class="col-md-9">
				<input type="time" class="form-control" name="hora">
				</div>
			</div>



			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Hpra de cierre</label>
				<div class="col-md-9">
				<input type="time" class="form-control" name="hora2">
				</div>
			</div>
		

			<div class="form-group">
				<label for="categoria" class="col-md-3 control-label">Categoría con prioridad</label>
				<div class="col-md-7">
					<select class="form-control" id="id_categoria" name="categoria1">
						<option selected="selected">Seleccione una</option>
						<? $q=mysql_query("SELECT * FROM categorias WHERE activo=1"); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_categoria']?>"><?=$ft['nombre']?></option>
						<? } ?>
		  			</select>
				</div>
			
			</div>

			<div class="form-group">
				<label for="categoria" class="col-md-3 control-label">Categoría 2</label>
				<div class="col-md-7">
					<select class="form-control" id="id_categoria" name="categoria2">
						<option selected="selected">Seleccione una</option>
						<? $q=mysql_query("SELECT * FROM categorias WHERE activo=1"); ?>
                        <? while($ft=mysql_fetch_assoc($q)){ ?>
							<option value="<?=$ft['id_categoria']?>"><?=$ft['nombre']?></option>
						<? } ?>
		  			</select>
				</div>
			
			</div>

		
			<div class="form-group">
				<label for="Tipo" class="col-md-3 control-label">Dias habiles</label>

				<div class="col-md-9">

				<div class="checkbox">
  					<label><input type="checkbox" name="lunes" value="1">Lunes</label>
				</div>
				<div class="checkbox">
 					 <label><input type="checkbox" name="martes" value="2">Martes</label>
				</div>
				<div class="checkbox">
 					 <label><input type="checkbox" name="miercoles" value="3">Miercoles</label>
				</div>
				<div class="checkbox">
 					 <label><input type="checkbox" name="jueves" value="4">Jueves</label>
				</div>
				<div class="checkbox">
  					<label><input type="checkbox" name="viernes" value="5">Viernes</label>
				</div>
				<div class="checkbox">
				  <label><input type="checkbox" name="sabado" value="6">Sabado</label>
				</div>
				<div class="checkbox">
				  <label><input type="checkbox" name="domingo" value="7">Domingo</label>
				</div>
				
					
				</div>
			</div>

	
			<!--
			<div class="form-group">
				<label for="precio_compra" class="col-md-3 control-label">Precio Compra</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="precio_compra" maxlength="8">
				</div>
			</div>
			-->
		</form>

      </div>
      <div class="modal-footer">
      	<img src="img/load-verde.gif" border="0" id="load" width="30" class="oculto" />
        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn-modal" onclick="GuardaProducto()">Generar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->






<script>

function GuardaProducto(){
	$('#msg_error').hide('Fast');
	$('.btn').hide();
	$('#load').show();
	var datos=$('#frm').serialize();
	$.post('ac/generador_ventas.php',datos,function(data){
		var datos2 = data.split('|');
		console.log(data);
	    if(datos2[0]==1){
	    	$('#msg_error').hide('Fast');
			
			$.post('http://localhost/compuplaza.php',{imprimir: datos2[2] });
			if(confirm('el monto total fue: '+datos2[1])){
			window.open("?Modulo=Gventas&msg=1", "_self");}
			

	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
};
</script>