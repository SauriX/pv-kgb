<?
include("../includes/db.php");
include("../includes/funciones.php");
if(!$_GET['id_dotacion']){ exit("Error de ID");}
$id_dotacion=escapar($_GET['id_dotacion'],1);


$sql="SELECT productos_base.producto, merma_detalle.* FROM merma_detalle 
JOIN productos_base ON productos_base.id_base=merma_detalle.id_producto
WHERE id_merma=$id_dotacion AND merma_detalle.activo=0 ";

$q=mysql_query($sql);
$detalles=array();
while($datos=mysql_fetch_object($q)):
	$detalles[] = $datos;

endwhile;
$val=count($detalles);

$sq_corte="SELECT *  FROM merma WHERE id_merma= $id_dotacion";
$q_corte=mysql_query($sq_corte);
$row = mysql_fetch_array($q_corte);
$fecha = $row ['fecha'];
$comentario =$row['observaciones'];



if($val):
?>

<div>
 <h5>fecha: <?=$fecha?></h5> 

 <h5> Comentario: 
     <?=$comentario?>
  </h5>
</div>

<table class="table table-striped table-hover " id="tabla" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th >Producto</th>
			<th width="100" style="text-align: right;">Cantidad</th>
			<th width="100"></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($detalles AS $detalle): ?>
		<tr id="tr_<?=$detalle->id_merma_detalle?>">
			
			<td><?=$detalle->producto?></td>
			<td align="right"><?= $detalle->cantidad ?></td>
			<td align="right">
				<img src="img/load-azul.gif" border="0" id="load_<?=$detalle->id_dotacion_detalle?>" width="22" class="oculto" />
				
			</td>
		</tr>
		<? endforeach; ?>
	</tbody>
</table>
<script>
function borra(id){
	var valida = confirm("Al eliminar esta merma se sumaran las existencias al producto.");
	
	if(valida==true){
		$(".bn_"+id+"").hide();
		$("#load_"+id+"").show();	
		$.post('ac/borra_producto_dotacion.php', { id: ""+id+"" },function(data){
			if(data==1){
				$("#tr_"+id+"").hide();
			}else{
				$("#load_"+id+"").hide();
				$(".btn_"+id+"").show();
				alert(data);
			}
		});
	}else{
		return false;
	}
}	
</script>
<? else: ?>
<div class="alert alert-info" role="alert">No se encontraron resultados</div>
<? endif; ?>