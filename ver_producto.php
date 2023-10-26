<?
$idp=$_GET['id'];

$producto67 = "SELECT nombre FROM productos
WHERE id_producto = $idp";


$B678 = mysql_query($producto67);



$nombres= array();
while($datos=mysql_fetch_object($B678)):
	$nombres[] = $datos;
endwhile;

$sql="SELECT *, productos_paquete.id_producto as idpro FROM productos_paquete
left join productos on productos.id_producto = productos_paquete.id_paquete
WHERE productos_paquete.id_producto = $idp";


$B = mysql_query($sql);



$prodcutos= array();
while($datos=mysql_fetch_object($B)):
	$productos[] = $datos;
endwhile;
$val=count($productos);












?>
<style>
.oculto{
	display: none;
}
.link{
	cursor: pointer;
}
</style>
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<div class="page-content-inner">
	<div class="row">
		<div class="col-md-12">
			
		<!--	BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-user-follow font-dark"></i>
						<?foreach($nombres as $nombre){?>
					      <span class="caption-subject font-dark sbold uppercase"><h3><?=$nombre->nombre?>  </h3><span>
						  <?}?>
						  <br>
							  <br>
							  <br>
					</div>
				<div>

                    <a class="btn btnsm btn-primary" onclick="javascript:Apaquete(<?=$ft['id_producto']?>)" >Agregar prodcutos</a>
				</div>
				</div>
				<div class="portlet-body">
					<? if($val>0):?>
					<table class="table table-striped table-bordered table-hover" id="tabla_prospectos">
						<thead>
					        <tr>
								<th>cantidad</th>
								<th>producto</th>
								
								<th style="text-align:right;">Precio</th>
								
								<th width="50"></th>
					        </tr>
					    </thead>
					    <tbody>
					      <? foreach($productos as $producto): ?>
					        <tr class="tr_<?=$paciente->id_paciente?>">
						
								<td><?=$producto->cantidad?></td>
                                <td><?=$producto->nombre?></td>

                               
                                <td style="text-align:right;"> $
							
								<?=$producto->precio_venta?>
								</td>
								
								
							
								
								
								
								<td>
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$proyecto->id_proyecto?>" width="19" class="oculto" />

									
										<a role="button" class="btn btn-xs btn-danger btn_<?=$producto->id_prodcuto?>" onclick="javascript:Eliminar(<?=$producto->id_paquete?>)">Eliminar</a>
									
								</td>
					        </tr>
					      <? endforeach; ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se hay productos asignados</p>
				  	</div>
					<? endif; ?>
				</div>
            </div>
            <a id="regresar_cats" onclick="javascript:rerge()" class="btn btn-default btn-lg btn-danger" style="margin-top: 10px" >Regresar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a id="regresar_cats" style="float: right" onclick="javascript:rerge()" class="btn btn-default btn-lg btn-success" style="margin-top: 10px" >Terminar</a>

	    <!--	END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>











<!--- Js -->
<script>
$(function(){

	$('#tabla_prospectos').dataTable({
		language: {
			url: 'assets/global/plugins/datatables/spanish.js'
		},
		"bStateSave": true,
		"lengthMenu": [
			[20, 35, 50, -1],
			[20, 35, 50, "Todos"]
		],
		"pageLength": 20,
		"pagingType": "bootstrap_full_number",

		"order": [
			[0, "asc"]
		]
	});

	//Para la fecha final
	$(".form_meridian_datetime_2").datetimepicker({
		isRTL: App.isRTL(),
		format: "dd MM yyyy - HH:ii P",
		showMeridian: true,
		autoclose: true,
		minuteStep: 30,
		pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
		todayBtn: true,
		linkField: "fecha_hora_final",
		linkFormat: "yyyy-mm-dd hh:ii"
    });



	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	
		console.log(data_id);
		$.getJSON('data/proyecto.php','id='+data_id,function(data){
			console.log(data);

			$('#edita_nombre').val(data.proyecto);
			$('#edita_id').val(data.id_proyecto);

		
		

			$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();

	    });
	});









	

	$('form').submit(function(e){
		e.preventDefault();
	});
});

function Eliminar(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/eliminar_paquete.php', { tipo: "<?=$idp?>", producto: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=ver_producto&id=<?=$idp?>", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});


}

function rerge(){
    window.open("?Modulo=Productos", "_self");
}


function Apaquete(id){

window.open("?Modulo=Paquete&id="+<?=$idp?>, "_self");

}


</script>
