<?
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("includes/db.php");
$sql="SELECT * FROM ventas WHERE abierta = 1 AND pagada = 0 AND id_corte = 0";
$q=mysql_query($sql);
$valida=mysql_num_rows($q);


?>
	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-muted2" id="tituloGeneral">Mesas Abiertas</h4>
	</div>
	
	<div class="modal-body">
<? if($valida){ ?>
	<div id="dashboarMesas">
<!-- Boton para cerrar mesa -->		
		<div id="sub-menu">
			<form class="form-horizontal" onsubmit="return false;">
				<div class="form-group has-warning">
					<div class="col-xs-6">
						<div class="input-group">
							<input class="form-control cerrarmesa" type="text" id="mesa" name="mesa" maxlength="10" placeholder="Cerrar mesa" autocomplete="off">
							<span class="input-group-btn">
								<button class="btn btn-warning" id="btn_cerrar_mesa" type="button" onclick="cerrar_mesa_action();">Cerrar mesa</button>
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
				        <tr style="cursor: pointer;">
				        	<td width="100" onclick="verMesa(<?=$id_venta?>,'<?=$mesa?>')"><?=$mesa?></td>
							<td width="100" align="right" onclick="verMesa(<?=$id_venta?>,'<?=$mesa?>')">$<?=number_format($consumo_total,2)?></td>
							<td width="60" align="right" style="cursor: default">

							<?//Consulta de faltantes de bebida
							$sq_bebida="SELECT COUNT(*) as bebida FROM venta_detalle JOIN productos ON productos.id_producto = venta_detalle.id_producto WHERE id_venta = $id_venta and (productos.id_categoria = 4 or productos.id_categoria = 2 or productos.id_categoria = 3)";
								$q_bebida=mysql_query($sq_bebida);
								$row_b = mysql_fetch_array($q_bebida);
								$v_bebida = $row_b ['bebida'];

								if($v_bebida == 0){
									$faltanbebidas = '1'; ?>
									<span class="label" style="color:red;">Faltan Bebidas</span>
								<?}else{
									$faltanbebidas = '0';
								}
							?>	

								<a id="mesa_<?=$mesa?>" class="rojos btn btn-warning btn-xs" href="javascript:;" onclick="cerrar_mesa('<?=$mesa?>',<?=$id_venta?>,'<?=$faltanbebidas?>');" role="button">Cerrar mesa</a></td>
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
<!-- Muestra descripción de la mesa	-->
		<div class="row oculto" id="muestraDetalleMesa">

		</div>
<!-- Termina todo -->		
	</div>

<script>
$(function(){
	$('#mesa').focus();
	
	$('.rojos').focus(function() {
		
		$(this).removeClass('btn-primary').addClass('btn-danger');
		
	
	});
	
	$('.rojos').hover(function() {
		
		$('.rojos').removeClass('btn-danger').addClass('btn-primary');
		$('#mesa').focus();
	
	});



	$('#btn-regresa').click(function() {
		$('#tituloGeneral').html("Mesas Abiertas");
		$('#muestraDetalleMesa').hide();
		$('#dashboarMesas').show();
	});
	
	$('#mesa').keyup(function(e) {
		var mesa = $(this).val();
		if(e.keyCode==13){
			cerrar_mesa_action();
		}
	});
	
	$('.cerrarmesa').alphanumeric();
	$('.cerrarmesa').keyup(function() {
		var yo = $(this).val();
		$(this).val(yo.toUpperCase());
	});
});

function cerrar_mesa(mesa,id_venta,bebidas){
	var yo = $('#btn_cerrar_mesa').html();

	if(mesa){
		if(yo=='Cerrar mesa'){
			
			if(bebidas=='1'){
				if(!confirm('La mesa no cuenta con bebidas, ¿Desea continuar?')){
					return false;
				}
			}
						
			$('#btn_cerrar_mesa').html('Cerrando...');
			$.post('ac/cerrar_mesa.php','mesa='+mesa+'&id_venta='+id_venta,function(data) {
				if(data==1){
					$('#content_verMesas').load('mesas.php');
				}else{
					alert(data);
				}
			});
		}
	}
}
function verMesa(id_venta,mesa){
	$('#tituloGeneral').html("Detalle de Mesa "+mesa);
	$('#muestraDetalleMesa').load("data/detalle_mesa.php?id_venta="+id_venta);
	$('#dashboarMesas').hide();
	$('#muestraDetalleMesa').show();
}

function cerrar_mesa_action(){
	var mesa = $('#mesa').val();
	$('#mesa_'+mesa).focus();
	
	
}
</script>