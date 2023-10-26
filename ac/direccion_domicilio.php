<?
include('../includes/db.php');
$sq="SELECT
	impresion_domicilio.id_impresion_domicilio,
	impresion_domicilio.numero,
impresion_domicilio.nombre,
impresion_domicilio.direccion,
impresion_domicilio.fecha_hora,
domicilio.nombre as nombre_completo
FROM
	impresion_domicilio
join domicilio ON 
impresion_domicilio.nombre = domicilio.id_domicilio

ORDER BY
	id_impresion_domicilio DESC
LIMIT 10";
$q0=mysql_query($sq);
$valida=mysql_num_rows($q0);
?>


<? if($valida){ ?>
	<table class="table table-striped" style="font-size: 18px;" id="cobradas">
			<thead>
				<tr>
					<th></th>
					<th>Numero</th>
					<th>nombre</th>
					<th>Domicilio</th>
					<th>Fecha</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
			<? 
			
			while($ft=mysql_fetch_assoc($q0)){ 
			
				
			?>
				<tr>
					<td><?=$ft['id_impresion_domicilio']?> </td>
					<td style=""><?=$ft['numero']?></td>
					<td style=""><?=$ft['nombre_completo']?></td>
					<td style=""><?=$ft['direccion']?></td>
					<td style=""><?=$ft['fecha_hora']?></td>
					<td><a type="button" class="btn btn-success" href="#" onclick="verMesa()" >IMPRIMIR</a><br></td>
				</tr>
			<? } ?>
			</tbody>
		</table>
		
		<? }else{ echo '<center><h2>No tienes ninguna mesa cobrada.</h2></center>';} ?>