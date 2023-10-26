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
						 
					<table class="table table-striped" id="tbl-tickets">
						<thead>
							<tr>
							<th></th>
								<th>Folio</th>
								<th>Mesa</th>
								<th>Monto</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Método de Pago</th>
								<th>Facturado</th>
							</tr>
						</thead>
						<tbody>
						<? 
							while($ft=mysql_fetch_assoc($q)){
							?>
							<tr>

							<td><input name="facturas[<?=$ft['id_venta']?>]" type="checkbox" class="form_control" id="facturar_<?$ft['id_venta']?>" /> </td>
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
								<td><?
								if($ft['facturado']==1):
									
									echo "Si";
								else:
									
									echo "No";
								endif;
								?></td>
							
							</tr>
						<? 	
							} 

							
						?>
						</tbody>

					</table>
					 </form>
					 <input type="button" value="Facturar" class="btn btn-primary" style="float: right;" id="facturar" >
                   <!--  onclick="window.location = 'index.php?Modulo=NuevaFactura&id_venta=<?=$ft['id_venta']?>'"-->
				</div>
			</div>

</div>

<script>
$(function(){
	$( "#facturar" ).click(function() {
     var ids =  $( "#form_datos" ).serialize();
	 var utl = "index.php?Modulo=NuevaFacturaMasiva&"+ids;
	 window.location = utl;
});

});

</script>