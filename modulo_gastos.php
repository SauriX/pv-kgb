<?
//Modulo Gastos
//include('includes/funciones.php');
$sql_gastos="SELECT * FROM gastos 
JOIN usuarios ON usuarios.id_usuario=gastos.id_usuario
WHERE id_corte=0 ORDER BY id_gasto DESC ";
$q_gastos=mysql_query($sql_gastos,$conexion);
$valida_gastos=mysql_num_rows($q_gastos);

?>

<hr>
<div class="row">
	<div class="col-md-3">
	<a type="button" class="btn btn-default btn-lg btn-block" href="index.php">VENTA</a><br>
		<a href="?Modulo=NuevoGastos" type="button" class="btn btn-default btn-lg btn-block">NUEVO</a><br>
		<a href="?Modulo=MGastos" type="button" class="btn btn-primary btn-lg btn-block"  >GASTOS</a><br>
		<a href="?Modulo=PGastos" type="button" class="btn btn-success btn-lg btn-block" >PROVISIONES</a>
	</div>
	
	<div class="col-md-9">
	<div class="col-md-9"><h3 style="margin-top:6px;">Gastos </h3> </div>
		
		<? if($valida_gastos){ ?>
		<hr>
		
		<table class="table table-striped table-hover ">
			<thead>
				<tr>
					<th>Usuario</th>
					<th>Hora</th>
					<th>Descripción</th>
					<th></th>
					<th class="text-right">Monto</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="agrega_gasto">
			<? while($ft=mysql_fetch_assoc($q_gastos)){ ?>
				<tr id="gasto_<?=$ft['id_gasto']?>">
					<td><?=$ft['nombre']?></td>
					<td><?=devuelveFechaHora($ft['fecha_hora'])?></td>
					<td><?=$ft['descripcion']?></td>
					<? if($ft['provision'] == 1){?>
						<td><span class="label label-success" style="color:white;">PROVISIONADO</span></td>
					<? }else {?>
						<td></td>
					<?} ?>
					
					<td align="right"><?=number_format($ft['monto'],2)?></td>
					<td align="right">
						<button type="button" class="btn btn-xs btn-gastos btn-danger" onclick="eliminarGasto(<?=$ft['id_gasto']?>)">X</button>
					</td>
				</tr>
			 <? } ?>
			</tbody>
		</table>
		<? } else {?>
			<br><br><br>
			<h2 style="text-align: center;">No hay Gastos</h2>
		<?	} ?>  
	</div>
</div>