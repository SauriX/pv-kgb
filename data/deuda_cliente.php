<?
include("../includes/db.php");
include("../includes/funciones.php");



if(!$_GET['id_cliente']){ exit("Error de ID");}
extract($_GET);
$id_cliente=escapar($_GET['id_cliente'],1);
	
$deuda_actual = obtenerDeuda($id_cliente);


$sql ="SELECT*FROM abonos WHERE id_cliente = $id_cliente";
$q = mysql_query($sql);

while($ft = mysql_fetch_assoc($q)){
	
	$fh = $ft['fecha_hora'];
	$transacciones[$fh]['fecha'] = $fh;
	$transacciones[$fh]['descripcion'] = 'PAGO';
	$transacciones[$fh]['cantidad'] = '·';
	$transacciones[$fh]['monto'] = $ft['monto'];
	$transacciones[$fh]['tipo'] = 'pago';
	$transacciones[$fh]['id'] = $ft['id_abono'];
	$total_abonos+=$ft['monto'];
}


$sql ="SELECT*FROM creditos WHERE id_cliente = $id_cliente";
$q = mysql_query($sql);
$tiene = mysql_num_rows($q);

while($ft = mysql_fetch_assoc($q)){
	
	
	$sql = "
	SELECT venta_detalle.id_detalle, venta_detalle.cantidad,venta_detalle.precio_venta,productos.nombre,ventas.fecha,
	ventas.hora FROM venta_detalle 
	JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
	JOIN productos ON productos.id_producto = venta_detalle.id_producto
	WHERE venta_detalle.id_venta = ".$ft['id_venta'];

	$qqq = mysql_query($sql);
		while($fx = mysql_fetch_assoc($qqq)){
		
			$rand = mt_rand().rand();
			$fh = $fx['fecha'].' '.$fx['hora'].$rand;
			$transacciones[$fh]['fecha'] = $fh;
			$transacciones[$fh]['descripcion'] = $fx['nombre'];
			$transacciones[$fh]['cantidad'] = $fx['cantidad'];
			$transacciones[$fh]['monto'] = $fx['cantidad']*$fx['precio_venta'];
			$transacciones[$fh]['tipo'] = 'deuda';
			$transacciones[$fh]['id'] = $fx['id_detalle'];
						
		}

}


?>
<style>
.total_tabla{
	display: none;
}
</style>
<script>
$(function() {

	$('#mas_info').click(function() {
		
		var yo = $(this).html();	
		if(yo=='+'){
			$('#mas_info').html('–').removeClass('btn-primary').addClass('btn-danger');
		}else{
			$('#mas_info').html('+').removeClass('btn-danger').addClass('btn-primary');
		}
		
		$('.total_tabla').toggle();
		

		
	
	});

});	
</script>


<?
$sql ="SELECT tipo_cargo,comidas,limite_credito FROM clientes WHERE id_cliente = $id_cliente";
$q = mysql_query($sql);
$dat = mysql_fetch_assoc($q);
$tipo_cargo = $dat['tipo_cargo'];
$comidas_bool = $dat['comidas'];
$limite_credito = $dat['limite_credito'];

if($deuda_actual>0){
	if($deuda_actual>$limite_credito){
		$style_1= 'danger';	
	}else{
		$style_1= 'warning';	
	}
}else{
	$style_1= 'info';
}

		
if($comidas_bool){
	
		$sql = "SELECT * FROM comidas_creditos WHERE id_cliente = $id_cliente";	
		$q = mysql_query($sql);
		$dd = mysql_fetch_assoc($q);
		$des = $dd['creditos_desayuno'];
		$alm = $dd['creditos_almuerzo'];
		
		$sql ="SELECT precio_venta FROM productos WHERE id_producto = 1";
		$q = mysql_query($sql);
		$precio_kinder = @mysql_result($q,0);
		
		$sql ="SELECT precio_venta FROM productos WHERE id_producto = 3";
		$q = mysql_query($sql);
		$precio_primaria = @mysql_result($q,0);
		
		
		
		
		if($tipo_cargo==1){
			if($des<0){
				$deuda_desayunos = abs($des)*$precio_kinder;
				$cuantos_des = ' ('.abs($des).')';
			}
			if($alm<0){
				$cuantos_alm = ' ('.abs($alm).')';
				$deuda_almuerzos = abs($alm)*$precio_kinder;
			}
		}else if($tipo_cargo==2){
			if($des<0){
				$cuantos_des = ' ('.abs($des).')';
				$deuda_desayunos = abs($des)*$precio_primaria;
			}
			if($alm<0){
				$cuantos_alm = ' ('.abs($alm).')';
				$deuda_almuerzos = abs($alm)*$precio_primaria;
			}}
		

		if($deuda_desayunos>0){
			$style_2= 'danger';
		}else{
			$style_2= 'info';
		}
		if($deuda_almuerzos>0){
			$style_3= 'danger';
		}else{
			$style_3= 'info';
		}
		

?>
<div class="row">
	<div class="col-md-4">
		<div class="alert alert-dismissable alert-<?=$style_2?>">

			<b style="cursor:pointer" onclick="window.location = '?Modulo=Desayunos'">Desayunos<?=$cuantos_des?>: $<?=fnum($deuda_desayunos)?></b><br/>
		</div>
	</div>
	<div class="col-md-4">
		<div class="alert alert-dismissable alert-<?=$style_3?>">
			<b style="cursor:pointer" onclick="window.location = '?Modulo=Almuerzos'">Almuerzos<?=$cuantos_alm?>: $<?=fnum($deuda_almuerzos)?></b><br/>
		</div>
	</div>
	<div class="col-md-4">
		<div class="alert alert-dismissable alert-<?=$style_1?>">
			<b>Consumo: $<?=fnum($deuda_actual)?></b><br/>
		</div>
	</div>
</div>
<?
	}
	

	$deuda_actual+= $deuda_desayunos+$deuda_almuerzos;

	if($deuda_actual>0){
		if($deuda_actual>$limite_credito){
			$style_1= 'danger';	
		}else{
			$style_1= 'warning';	
		}
	}else{
		$style_1= 'info';
	}		
?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-dismissable alert-<?=$style_1?>">
			<b style="font-size:16px">Deuda Actual: $<?=fnum($deuda_actual)?></b>
		</div>
	</div>
</div>

<?
if($tiene){	
?>
<table class="table table-striped table-hover">
    <thead>
      <tr>
        <th align="left" width="150">Fecha</th>
        <th align="left">Descripción</th>
        <th style="text-align: center;">Cantidad</th>
        <th style="text-align: right;">Abono</th>
        <th style="text-align: right;">Cargo</th>
        <th class="total_tabla" style="text-align: right;">Total</th>
        <th style="text-align: right;"><button type="button" id="mas_info" class="btn btn-primary btn-xs">+</button></th>
      </tr>
    </thead>
    <tbody>

<?
	$total_abonos_ok = $total_abonos;
	$total = 0;
	asort($transacciones);
	foreach($transacciones as $data => $d){
		
					$desc = $d['descripcion'];
					$cantidad = $d['cantidad'];
					$monto = $d['monto'];
					$tipo = $d['tipo'];
					$fecha = $d['fecha'];
					$id = $d['id'];
											
					if($tipo=='deuda'){
						$deuda = fnum($monto);
						$abono = '·';
						$deuda_total+=$monto;
						$total+=$monto;
					}elseif($tipo=='pago'){
						$abono = fnum($monto);
						$deuda = '·';
						$abono_total+=$monto;
						$total-=$monto;

					}
					
				
			
	?>
	      <tr>
	        <td align="left"><b><?=devuelveFechaHora($fecha)?></b></td>
	        <td align="left"><b><?=$desc?></b></td>
	        <td align="center"><?=$cantidad?></td>
	        <td align="right"><b style="color:#307300"><?=$abono?></b></td>
	        <td align="right"><b style="color:#920000"><?=$deuda?></b></td>
	        <td class="total_tabla" align="right"><b><?=fnum($total)?></b></td>
	        <td align="right"><span class="total_tabla glyphicon glyphicon-remove text-danger class_<?=$id.$tipo?>" style="cursor:pointer" onclick="eliminar_mov(<?=$id?>,'<?=$tipo?>',<?=$id_cliente?>);"></span>
			<img src="img/load-rojo.gif" border="0" id="load_<?=$id.$tipo?>" width="15" class="oculto" />
	         </td>
	      </tr>
	<?		
		
	}	
	
?>       
      
      
<!-- Total -->
	  <tr>
        <td align="left"></td>
        <td align="left"></td>
        <td align="center"></td>
        <td align="right"><b style="color:#307300"><?=fnum($abono_total)?></b></td>
        <td align="right"><b style="color:#920000"><?=fnum($deuda_total)?></b></td>
        <td align="right"></td>
        <td align="right" class="total_tabla"></td>
      </tr>
      
    </tbody>
</table>
<?
}	
?>
<script>

	
</script>