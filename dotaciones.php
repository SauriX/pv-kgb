<?
$sql="SELECT usuarios.nombre, dotaciones.* FROM dotaciones 
JOIN usuarios ON usuarios.id_usuario=dotaciones.id_usuario
 ORDER BY id_dotacion DESC";
$q=mysql_query($sql);

$dotaciones=array();

while($datos=mysql_fetch_object($q)):
	$dotaciones[] = $datos;
endwhile;
$val=count($dotaciones);


$sq_corte="SELECT MAX(id_dotacion) as id FROM dotaciones";
$q_corte=mysql_query($sq_corte);
$row = mysql_fetch_array($q_corte);
$v_corte = $row ['id'];


?>
<div class="row mb10">
	<div class="col-md-12 text-right">
	    <a href="?Modulo=NuevaDotacion" class="btn btn-success btn-sm">Nueva Dotación</a>
	</div>
</div>

<div class="row">		
	<div class="col-md-12">
		<div class="panel panel-primary">
			
			<div class="panel-heading">
				<h3 class="panel-title">Dotaciones</h3>
			</div>
		  
			<div class="panel-body">
				<table class="table table-striped table-hover " id="tabla"  >
					<thead>
						<tr>
							<th width="15%">#Id</th>
							<th width="25%">Productos Dotados</th>
							<th width="30%">Fecha y Hora</th>
							<th width="10%"></th>
						</tr>
					</thead>
					<tbody>
						<? foreach($dotaciones AS $dotacion):
							$id_dotacion=$dotacion->id_dotacion;
							$sql="SELECT SUM(cantidad) AS total FROM dotaciones_detalle WHERE id_dotacion=$id_dotacion AND activo=0";
							$q=mysql_query($sql);
							$dat=mysql_fetch_assoc($q);
						
							$total=$dat['total'];
							if($total>0):
								
						?>
						<tr>
							<td><?= $id_dotacion ?></td>
							<td><?=$total?> Productos dotados</td>
							<td><?= fechaLetra($dotacion->fecha) ?></td>
							<td>
							      <?if($v_corte==$dotacion->id_dotacion){?>
								<a role="button" class="btn btn-danger btn-xs "    onclick="eliminar(<?=$id_dotacion?>);">Eliminar</a>
								  <?}?>
								<a role="button" class="btn btn-success btn-xs link btn_<?=$id_dotacion?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#verDotacion" data-id="<?=$id_dotacion?>">Ver</a>
							</td>
						</tr>
						<? endif;
							endforeach; ?>
					</tbody>
				</table>
			</div>
			
		</div>	
	</div>
</div>









<!-- Modal -->
<div class="modal fade" id="verDotacion">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Dotación</h4>
			</div>
			
			<div class="modal-body">
				<div class="row oculto" id="load_big">
					<div class="col-md-12 text-center" ><img src="img/load-verde.gif" border="0" width="50" /></div>
				</div>
		
				<div class="row">
					<div class="col-md-12" id="muestra_dotacion">
					</div>
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-modal" data-dismiss="modal">Cerrar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!--- Js -->
<script>
$(function(){
	
	$(document).on('click', '[data-id]', function () {
		//Precargamos
		$('.btn-modal').hide();
		$('#muestra_dotacion').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');

	    $.ajax({
	   	url: "data/dotaciones.php",
	   	data: 'id_dotacion='+data_id,
	   	success: function(data){
		   	$('#muestra_dotacion').html(data);
	   		$('#load_big').hide();
	   		$('#muestra_dotacion').show();
	   		$('.btn-modal').show();
	   	},
	   	cache: false
	   });
	});
	
	$('#EditaProducto').on('hidden.bs.modal', function (e) {
		$('#muestra_dotacion').html("");
  	});
	
	
	
	$("#tabla").dataTable( {
    	"language": {
        	"url": "js/datatable_spanish.js"
        }
    });
});


function eliminar(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/eliminar_dotacion.php', { id_dotacion: id },function(data){
		if(data==1){
			window.open("?Modulo=Dotaciones", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
</script>