<?
include("../includes/db.php");
$id_venta=$_GET['id_venta'];

$sq="SELECT venta_detalle.*,productos.nombre FROM venta_detalle 
JOIN productos ON productos.id_producto=venta_detalle.id_producto
WHERE id_venta=$id_venta";


$qu=mysql_query($sq);
$consumo_total=0;
$valida=mysql_num_rows($qu);

$conf="SELECT * FROM configuracion ";
$q_cconf = mysql_query($conf);
$n_cconf= mysql_num_rows($q_cconf);
$reabrir = $n_cconf['pagada'];

if($valida){
?>
<div class="col-xs-12">
	<div class="col-xs-6" style="padding-left: 0px;">
		<a class="btn btn-primary btn-sm" role="button" onclick="recarga();"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar</a>
	</div>
	<div class="col-xs-6">
		<h3 style="text-align: right; margin-top: 0px;color: black;" id="tituloMesa"></h3>
	</div>
	<br>
	<hr>

	<table class="table table-striped table-hover ">
	    <thead>
	        <tr>
	        	<th width="240">Producto</th>
	        	<th width="70" align="right" style="text-align: right;">Precio</th>
	        	<th width="30" align="center" style="text-align: center;">Cantidad</th>
	        	<th width="70" align="right" style="text-align: right;">Precio</th>
	        </tr>
	    </thead>
	    <tbody style="font-size: 15px;">
		    <? while($dat=mysql_fetch_assoc($qu)){
			//Sacamos los productos
	 		$consumo_total+=$dat['cantidad']*$dat['precio_venta'];
	 		?>
	        <tr id="detalle_<?=$dat['id_detalle']?>">
	        	<td width="240"><?=$dat['nombre']?></td>
				<td width="70" align="right"><?=number_format($dat['precio_venta'],2)?></td>
				<td width="30" align="center"><?=$dat['cantidad']?></td>
				<td width="70" align="right"><?=number_format($dat['cantidad']*$dat['precio_venta'],2)?></td>
	        </tr>
	        <? } ?>
	        <tr>
	        	<td width="240"></td>
				<td width="70" align="right"></td>
				<td width="30" align="center"></td>
				<td width="70" align="right" id="consumo_total_mesa"><b><?=number_format($consumo_total,2)?></b></td>
	        </tr>
	    </tbody>
	</table>
	
	<div class="col-xs-12 text-center" style="padding-left: 0px;">
			
	<div id="botones_accion">

	<?$sql74="SELECT pagada FROM configuracion";
		$query74 = mysql_query($sql74);
		while($ft=mysql_fetch_assoc($query74)){
        $reabrir = $ft['pagada'];
		}
/**/
if($reabrir=="1"){?>
	<a class="btn btn-default btn-sm" role="button" onclick="reabrirMesa(<?=$id_venta?>);">Reabrir Mesa</a>
<?}?>
			<a class="btn btn-default btn-sm" role="button" id="reimprimir" onclick="imprimir_cuenta(<?=$id_venta?>,'<?=$mesa?>');">Reimprimir</a>
			
	</div>
	</div>

	
	
</div>
<? }else{ ?>
<div class="alert alert-danger" role="alert">La mesa que seleccionaste ya no existe.</div>
<? } ?>

<script>



function recarga(){
	$('#content_verMesasCobradas').load('mesas_cobradas.php');		
}
		
function imprimir_cuenta(id_venta,mesa){
	$.post('ac/cobrar_pagar.php','id_venta='+id_venta+'&reimprime=1',function(data) {
		$('#content_verMesasCobradas').load('mesas_cobradas.php');
	});
}


function reabrirMesa(){
		
		$('#motivo_apertura').hide();
		$('#reabrir').hide().after('Reabriendo..');
		$.post('ac/reabrir_mesa.php','id_venta=<?=$id_venta?>&motivo='+$('#motivo_apertura').val(),function(data) {
			
			if(data==1){
				
				alert('Mesa abierta con éxito');
				window.location = 'index.php';
			
			}else{
				alert('Error al abrir mesa. '+data);
			}
			
			
		
		});
				
	}
	
</script>