<?
include('../includes/db.php');	
include('../includes/funciones.php');	

$sql ="SELECT clientes.nombre, abonos.* FROM abonos JOIN clientes ON clientes.id_cliente = abonos.id_cliente WHERE id_corte = 0";
$q = mysql_query($sql);
$q2 = mysql_query($sql);
while($ft = mysql_fetch_assoc($q)){
	
	$cuantos++;
	$cuanto+=$ft['monto'];
	
}

?>
<p class="lead text-center" onclick="cerrarAuxiliar();" style="cursor:pointer">
	<span class="text-center glyphicon glyphicon-chevron-left"></span>&nbsp;&nbsp;&nbsp;Comidas Pagadas <mark><?=$cuantos?></mark> · <mark>$<?=fnum($cuanto)?></mark>
</p>

<?
if($cuantos>0){	
?>
	<div class="col-md-12">

		<table class="table table-striped table-hover">
		    <thead>
		      <tr>
		        <th align="left">Cliente</th>
		        <th style="text-align: right;" width="70">Desayunos</th>
		        <th style="text-align: right;" width="70">Almuerzos</th>
		        <th style="text-align: right;" width="70">Monto</th>
		      </tr>
		    </thead>
		    <tbody>
<?
while($ft = mysql_fetch_assoc($q2)){
	
	$deuda_actual = fnum(obtenerDeuda($ft['id_cliente']));
	$abono = fnum($ft['monto']);
	$deuda_ant = fnum($deuda_actual+$abono);
	
	if($deuda_actual=='0.00'){
		$color = '#307300';
	}else{
		$color = '#920000';
	}
?>
			      <tr>
			        <td align="left"><?=$ft['nombre']?></td>
			        <td align="right"><b>$<?=$deuda_ant?></b></td>
			        <td align="right"><b style="color:#307300">$<?=$abono?></b></td>
			        <td align="right"><b style="color:<?=$color?>">$<?=$deuda_actual?></b></td>
			         </td>
			      </tr>
<?
}
?>
		<!-- Total -->
		    </tbody>
		</table>
	</div>
<?
}else{
	echo '<p class="text-center"><br>Datos no disponibles.</span>';
}	
?>