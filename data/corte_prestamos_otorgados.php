<?
include('../includes/db.php');	
include('../includes/funciones.php');	


/*  PRÉSTAMOS OTORGADOS  */
$sql = "SELECT * FROM creditos WHERE id_corte = 0";
$q = mysql_query($sql);

while($ft = mysql_fetch_assoc($q)){
		
	$sql = "SELECT * FROM venta_detalle WHERE id_venta =".$ft['id_venta'];
	$qq = mysql_query($sql);
	
	while($fx = mysql_fetch_assoc($qq)){
		
			$corte_prestamos_otorgados[$ft['id_credito']] = 1;
			$corte_prestamos_otorgados_monto+= $fx['cantidad']*$fx['precio_venta'];
		
	}

}

$corte_prestamos_otorgados = count($corte_prestamos_otorgados);





$sql = "SELECT DISTINCT(id_cliente) FROM creditos WHERE id_corte = 0";
$q = mysql_query($sql);
?>
<p class="lead text-center" onclick="cerrarAuxiliar();" style="cursor:pointer">
	<span class="text-center glyphicon glyphicon-chevron-left"></span>&nbsp;&nbsp;&nbsp;Prestamos Otorgados <mark><?=$corte_prestamos_otorgados?></mark> · <mark>$<?=fnum($corte_prestamos_otorgados_monto)?></mark>
</p>

	<div class="col-md-12">

		<table class="table table-striped table-hover">
		    <thead>
		      <tr>
		        <th align="left">Cliente</th>
		        <th style="text-align: right;" width="70">Saldo Anterior</th>
		        <th style="text-align: right;" width="70">Cargo</th>
		        <th style="text-align: right;" width="70">Saldo Nuevo</th>
		      </tr>
		    </thead>
		    <tbody>
<?
while($ft = mysql_fetch_assoc($q)){
		$id_cliente = $ft['id_cliente'];

// SALDOS, Y DESCONTAR LO QUE YA SE PAGO.
		$deuda_actual = obtenerDeuda($id_cliente);
	
		$sql_monto_otorgado = " SELECT SUM(venta_detalle.precio_venta*venta_detalle.cantidad) FROM creditos
								JOIN venta_detalle ON venta_detalle.id_venta = creditos.id_venta
								JOIN productos ON productos.id_producto = venta_detalle.id_producto
								WHERE creditos.id_corte = 0 AND creditos.id_cliente = $id_cliente";
		$monto_otorgado = fnum(@mysql_result(mysql_query($sql_monto_otorgado),0),0,1);
		
		$sql ="SELECT SUM(monto) FROM abonos WHERE id_corte = 0 AND id_cliente = $id_cliente";
		$abonos = mysql_result(mysql_query($sql),0);
		
		if($deuda_actual!=0){		
			
			/*$deuda_anterior = $deuda_actual-$monto_otorgado;*/
			
			$deuda_anterior = ($deuda_actual-$monto_otorgado)+$abonos;
			
			$deuda_anterior = '$'.fnum($deuda_anterior);
			$monto_otorgado = '$'.fnum($monto_otorgado);
			$color_cargo = '#920000';	
			$color_actual = $color_cargo;
			unset($color_nombre);	
			
			$text_abono = 'ABONO';
		}else{
			
			$deuda_anterior = fnum($abonos-$monto_otorgado);
//			$deuda_anterior = ($deuda_actual-$monto_otorgado)+$abonos;

//			$monto_otorgado = 'Pagado';
			$text_abono = 'PAGO';
			$color_cargo = '#307300';
			$color_nombre = $color_cargo;
			$color_actual = $color_cargo;
			
		}
		
		$sql = "SELECT nombre FROM clientes WHERE id_cliente = $id_cliente";		
		$q_nombre = mysql_query($sql);
		$nombre = @mysql_result($q_nombre,0);
?>
			      <tr>
			        <td align="left"><b style="color:<?=$color_nombre?>;"><?=$nombre?></b></td>
			        <td align="right"><b style="color:#307300"><?=$deuda_anterior?></b></td>
			        <td align="right"><b style="color:<?=$color_cargo?>"><?=$monto_otorgado?></b></td>
			        <td align="right"><b style="color:<?=$color_actual?>;">$<?=fnum($deuda_actual)?></b></td>
			         </td>
			      </tr>

<?

		if($abonos>0){
?>
				      <tr>
				        <td align="right" style="font-size:12px;color:#307300"><b><?=$text_abono?>:</b></td>
				        <td align="right"></td>
				        <td align="right" style="font-size:12px;color:#307300"><b>-$<?=fnum($abonos)?></b></td>
				        <td align="right"></td>
				         </td>
				      </tr>
			      
<?
	}
		$sql = "SELECT productos.nombre,venta_detalle.id_producto,venta_detalle.precio_venta,venta_detalle.cantidad FROM creditos
				JOIN venta_detalle ON venta_detalle.id_venta = creditos.id_venta
				JOIN productos ON productos.id_producto = venta_detalle.id_producto
				WHERE creditos.id_corte = 0 AND creditos.id_cliente = $id_cliente";
		$qq = mysql_query($sql);
		
		while($fx = mysql_fetch_assoc($qq)){
	
?>
				      <tr>
				        <td align="right" style="font-size:12px"><?=$fx['nombre']?> (<?=$fx['cantidad']?>)</td>
				        <td align="right"></td>
				        <td align="right" style="font-size:12px">$<?=fnum($fx['cantidad']*$fx['precio_venta'])?></td>
				        <td align="right"></td>
				         </td>
				      </tr>

<?
		}
}	
?>

		<!-- Total -->
		    </tbody>
		</table>
	</div>
