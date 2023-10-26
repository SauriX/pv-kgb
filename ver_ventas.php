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
					<?	if(!$_GET['metodo']){
					    $sql="SELECT ventas.*,metodo_pago.metodo_pago FROM ventas
						LEFT JOIN metodo_pago ON metodo_pago.id_metodo = ventas.id_metodo
						WHERE  pagada = 1  ORDER BY id_venta DESC";}
						if($_GET['metodo']=='t'){
							$sql="SELECT ventas.*,metodo_pago.metodo_pago FROM ventas LEFT JOIN metodo_pago ON metodo_pago.id_metodo = ventas.id_metodo WHERE pagada = 1 AND ventas.id_metodo=4 OR ventas.id_metodo=28 ORDER BY id_venta DESC";
						}
						
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
								<th></th>
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
								<td><input type="button" class="btn btn-sm btn-primary" value="Ver" onclick="window.location = 'index.php?Modulo=VentaTouchMesaCobrada&id_venta=<?=$ft['id_venta']?>&mesa=<?=$ft['mesa	']?>'"/> <?=$msg?></td>
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