

<?		
	include('../includes/db.php');
	$sq="SELECT * FROM ventas 
	JOIN metodo_pago ON ventas.id_metodo = metodo_pago.id_metodo
	WHERE abierta = 0 AND pagada = 1 AND id_corte = 0 ORDER BY fechahora_pagada DESC ";
	$q0=mysql_query($sq);
	$valida=mysql_num_rows($q0);?>
	<table class="table table-striped" style="font-size: 18px;" id="cobradas">
				<thead>
					<tr>
						<th>Mesa</th>
						<th style="text-align: right" width="80">Consumo</th>
						<th>Hora Cobrada</th>
						<th>Pago</th>
						<th>Facturado</th>
						<th>Reabierta</th>
						<th></th>
					</tr>
				</thead>
				
				<tbody>
				<? while($ft=mysql_fetch_assoc($q0)){ 
					$id_venta=$ft['id_venta'];
					$mesa=$ft['mesa'];
					$sq="SELECT * FROM venta_detalle WHERE id_venta=$id_venta";
					$qu=mysql_query($sq);
					$consumo_total=0;
					while($dat=mysql_fetch_assoc($qu)){
						//Sacamos los productos
						$consumo_total+=$dat['cantidad']*$dat['precio_venta'];
					}
				?>
					<tr class="tr_borra <? if($_GET['id_venta']==$id_venta){?>animated pulse<? } ?>">
						<td><? if(is_numeric($mesa)){ echo 'MESA '.$mesa; }else{ echo $mesa; }?></td>
						<td>$ <?=number_format($consumo_total,2)?></td>
						<td><? $d = explode(' ', $ft['fechahora_pagada']); echo  substr($d[1],0,5);?></td>
						<td><?=$ft['metodo_pago']?></td>
						<td><? if($ft['facturado']){echo 'SI';}else{echo 'NO';} ?></td>
						<td><? if($ft['reabierta']){echo 'SI';}else{echo 'NO';}?></td>
						<td><a type="button" class="btn btn-success" href="#" onclick="verMesa(<?=$id_venta?>,'<?=$mesa?>')" >VER</a><br></td>
					</tr>
				<? } ?>
				</tbody>
			</table>