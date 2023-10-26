<?
//Modulo Gastos
//include('includes/funciones.php');
$sql_gastos="SELECT * FROM gastos 
JOIN usuarios ON usuarios.id_usuario=gastos.id_usuario
WHERE id_corte=0 and provision = 1 ORDER BY id_gasto DESC LIMIT 5 ";
$q_gastos=mysql_query($sql_gastos,$conexion);
$valida_gastos=mysql_num_rows($q_gastos);

?>
<script type="text/javascript">
	function editar(id_gasto){
	window.open("?Modulo=EDITAR_PROVISION&id_gasto="+id_gasto, "_self");
	//window.open("?Modulo=VentaTouchCobro&id_venta="+id_venta+"&mesa="+mesa, "_self");
}
</script>
<hr>
<div class="row">
	<div class="col-md-3">
	<a type="button" class="btn btn-default btn-lg btn-block" href="index.php">VENTA</a><br>
		<a href="?Modulo=NuevoGastos" type="button" class="btn btn-default btn-lg btn-block">NUEVO</a><br>
		<a href="?Modulo=MGastos" type="button" class="btn btn-primary btn-lg btn-block"  >GASTOS</a><br>
		<a href="?Modulo=PGastos" type="button" class="btn btn-success btn-lg btn-block" >PROVISIONES</a>
	</div>
	
	<div class="col-md-9">
	<div class="col-md-9"><h3 style="margin-top:6px;">Provisiones </h3> </div>
		
		<? if($valida_gastos){ ?>
		<hr>
		
		<table class="table table-striped table-hover ">
			<thead>
				<tr>
					<th>Usuario</th>
					<th>Hora</th>
					<th>Descripción</th>
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
					<td align="right"><?=number_format($ft['monto'],2)?></td>
					<td align="right">
						<button type="button" class="btn btn-xs btn-gastos btn-danger" onclick="editar(<?=$ft['id_gasto']?>)">Aplicar Gasto</button>
					</td>
				</tr>
			<? } ?>
			</tbody>
		</table>
		<? } else{?>
			<br><br><br>
			<h2 style="text-align: center;">No hay Provisiones</h2>
		<?	}  ?>  
	</div>
</div>