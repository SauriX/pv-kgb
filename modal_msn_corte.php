<div class="modal fade" id="msn_cortes">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Deshacer Corte</h4>
      </div>
      <div class="modal-body">
<!--Formulario -->
		<form class="form-horizontal" action="#" id="form_codigo">

			<div class="form-group" id ="msn" style="display:none;">
				<label for="codigo" class="col-md-8 control-label" style="width: 60%;height: 50px;left: 10%;">Ingrese Código de Verificación</label>
				<div class="col-md-8" style="width: 60%;height: 40px;left: 20%;">
					
					<input type="text" class="form-control" name="codigo" id ="codigo">
				</div><br>
				<div class="col-md-8" style="width: 60%;height: 50px;left:40%; top: 10px;">
					<button type="submit" class="btn btn-primary">Validar</button>
				</div>
				
			</div>
		</form>
			
		
			<div id="boton" >
				<label for="codigo" class="" style="width: 100%;height: 50px;left: 10%; text-align: center;">Haga Click en el siguiente botón para enviar el código de autorización<p> al administrador</p>
				</label>
				<div class="col-md-8" style="width: 60%;height: 50px;left:40%;">
				<input type="submit" class="btn btn-primary" id="enviar_codigo_btn" onclick="random_msn()" value ="Enviar Código">	
				</div>
				
			</div>
		
			
				<br><br>
					<div id="mensaje"></div>
			
		
		
		      
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
