<?
include("../includes/db.php");
$sql="SELECT * FROM ventas WHERE abierta=1";
$q=mysql_query($sql);
$valida=mysql_num_rows($q);
if($valida){ ?>
	<div id="dashboarMesas">
<!-- Boton para cerrar mesa -->		
		<div id="sub-menu">
			<form class="form-horizontal" onsubmit="return false;">
				<div class="form-group has-warning">
					<div class="col-xs-6">
						<div class="input-group">
							<input class="form-control" type="text" id="mesa" name="mesa" maxlength="10" placeholder="Cerrar mesa" >
							<span class="input-group-btn">
								<button class="btn btn-warning" type="button">Cerrar mesa</button>
							</span>
						</div>
						
					</div>
				</div>
			</form>
			
			<hr>
		</div>
<!-- Muestra Mesas -->		
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover ">
				    <thead>
				        <tr>
				        	<th width="100">Mesa</th>
							<th width="100" align="right" style="text-align: right;">Consumo</th>
							<th width="60" align="right"></th>
				        </tr>
				    </thead>
				    <tbody style="font-size: 15px;">
					    <? while($ft=mysql_fetch_assoc($q)){ 
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
				        <tr style="cursor: pointer;" onclick="verMesa(<?=$id_venta?>,'<?=$mesa?>')">
				        	<td width="100">Mesa <?=$mesa?></td>
							<td width="100" align="right"><?=number_format($consumo_total,2)?></td>
							<td width="60" align="right"><a class="btn btn-warning btn-xs" href="#" role="button">Cerrar mesa</a></td>
				        </tr>
				        <? } ?>
				    </tbody>
				</table>
				
			</div>
		</div>
	</div><!-- end dashboard mesas -->
<? }else{ ?>
<div class="alert alert-warning" role="alert">Aún no tienes mesas abiertas.</div>
<? } ?>