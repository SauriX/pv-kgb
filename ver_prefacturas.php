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
<?
$servidor2="digmastudio.com";
$usuario2="digmastu_diego";
$clave2="camacho";
$base2="digmastu_facturacion_cfdi";
$conexion2 = @mysql_connect ($servidor2,$usuario2,$clave2) or die ("Error en conexi&oacute;n.");
@mysql_select_db($base2) or die ("No BD");

$nuevafecha = strtotime ( '-5 day' , strtotime ( $fechahora ) ) ;
$nuevafecha = date ( 'Y-m-j H:i:s' , $nuevafecha );

$sql ="SELECT*FROM pre_facturas 
WHERE facturado = 0 AND fecha_hora BETWEEN '$nuevafecha' AND '$fechahora' 
ORDER BY fecha_hora DESC";	
$q = mysql_query($sql,$conexion2);
$n = mysql_num_rows($q);	
?>
	
	<div class="row">
		<div class="col-md-12">			

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Pre-Facturas (<?=$n?>)</h3>
				</div>
					<div class="panel-body">
						<table class="table table-striped" style="font-size:13px">
							<thead>
								<tr>
									<th width="150">Fecha</th>
									<th width="100">Código</th>
									<th width="70">Monto</th>
									<th width="150">Método</th>
									<th width="150">Cuenta</th>
									<th width="100">Estado</th>
								</tr>
							</thead>
							<tbody>
					<?

					while($ft = mysql_fetch_assoc($q)){		


	$resultado = strpos($ft['metodo_pago'], 'TARJ');
	if($resultado !== FALSE){
	  	$un1 = '<span style="color:red">';
	  	$un2 = '</span>';
	}else{
		$un1 = '';
		$un2 = '';
	}
	
	$fecha = $ft['fecha_hora'];
	$hora = substr($ft['fecha_hora'],11,20);
	$nuevafecha = strtotime ( '-5 day' , strtotime ( date('Y-m-d H:i:s') ) ) ;
	$nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );
	
	$des = explode(' ', $fecha);
	$fecha_sola = $des[0]; 
	$hora_sola = substr($des[1],0,5);
	
	if($fecha<$nuevafecha){
		$status = '<span style="color:#b90001"><b>Expirado</b></span>';
	}else{
		$status = '<span style="color:#28b918"><b>Vigente</b></span>';
	}



					?>
								<tr>
									<td><?=fechaLetra($fecha_sola)?> <?=$hora_sola?></td>
									<td><?=substr($ft['id_pre_factura'],0,6)?>...</td>
									<td><?=number_format($ft['monto'],2)?></td>
									<td><?=$un1.$ft['metodo_pago'].$un2?></td>
									<td><?=$ft['num_cta']?></td>
									<td><?=$status?></td>
								</tr>
					<?
					}			
					?>
							</tbody>
						</table>
					</div>
				</div>
		</div>
	</div>