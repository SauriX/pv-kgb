<script>
	function killCopy(e){
		return false
	}
	function reEnable(){
		return true
	}
	
	document.onselectstart=new Function ("return false")
	
	if (window.sidebar){
		document.onmousedown=killCopy
		document.onclick=reEnable
	}

	var message="NoRightClicking";
	function defeatIE() {if (document.all) {(message);return false;}}
	function defeatNS(e) {if 
	(document.layers||(document.getElementById&&!document.all)) {
	if (e.which==2||e.which==3) {(message);return false;}}}
	if (document.layers) 
	{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=defeatNS;}
	else{document.onmouseup=defeatNS;document.oncontextmenu=defeatIE;}
	document.oncontextmenu=new Function("return false")
	
</script>
<script>
$(function(){
	$('#corte_caja').on('show.bs.modal',function(e) {
		$('#cancelar_corte_caja').focus();
	});
	
	$('#corte_caja').on('shown.bs.modal', function (e) {

		$('#cancelar_corte_caja').focus();
	
		
		
	});
	
	
	$('#corte_caja').on('hidden.bs.modal', function (e) {
		CerrarBuscar();
		$('#loader_corte').show();
		$('.hidden_modal_corte').hide();
		$('#imagen_loader_corte').show();
		$('#mensaje_loader_corte').html('Obteniendo..');
		$('#corte_doit_ok').hide();
		$('#corte_doit').show();
		$('#id_producto').val('');
		$('#cantidad').val('1');
		$('#id_producto_b').typeahead('val', '');
		$('#contenido_auxiliar').html('').hide();

				
	});
	
	$('#corte_doit').click(function() {
	
		$(this).hide();
		
		$.get("ac/alerta_corte.php", function(data){
       if(data == 1){
       	if (window.confirm("El efectivo en caja es en negativo, tiene más gastos que ingresos en efectivo, ¿desea continuar?")) { 
			  $('#corte_doit_ok').fadeIn();
			  $('#msn_corte').fadeIn();
			}
       }else {
 			$('#corte_doit_ok').fadeIn();
 			$('#msn_corte').fadeIn();
       }
        
    		});//final del get


		
	});
	

	$('#corte_doit_ok').click(function(){
		var efectivo = $('#txtEfectivo').val();
		var tpv = $('#txtTpv').val();
		if (efectivo == '' || efectivo <= 0 || efectivo == 'e') {
			alert('Verifica Efectivo en caja');
		}else {

			$('#hidden_modal_corte').hide();
			$('#corte_doit_ok').hide();
			$('#cancelar_corte_caja').hide();
			$('#loader_corte').show();
			$('#imagen_loader_corte').attr('src','img/load-verde.gif').show();
			$('#mensaje_loader_corte').html('Realizando Corte de Caja..');
			$.get('ac/corte_realizar.php', { efectivoCa: efectivo, tpvEfec: tpv, otrosMet:0 } ,function(data) {
				if(data==1){
					$('#imagen_loader_corte').css('-webkit-filter','hue-rotate(40deg)').attr('src','img/ok.png').show();
					$('#mensaje_loader_corte').html('Listo.<br/><br/><button onclick="location.reload();" type="button" class="btn btn-default btn_ac btn-md">Terminar</button>');

				}else if(data=='NOSESSION'){
					alert('Su sesión ha expirado, por favor reingrese nuevamente.');
					location.reload();
				}else if(data=='NOPERMISSION'){
					alert('Usted no tiene permisos para realizar el Corte de Caja.');
					location.reload();
				}else if(data=='ROLLBACK'){
					alert('Ocurrió un error al realizar el Corte de Caja, por favor contacte a soporte.');
					location.reload();
				}else{
					alert(data);
				}
			});

		}
	});
});

function abrir(){
	$('#corte_content').load('data/cortes.php');
	$('#corte_caja').modal();
}

</script>
<hr>
<div class="row">
	<div class="col-md-3">
	<a type="button" class="btn btn-default btn-lg btn-block" href="index.php">VENTA</a><br>
		<a type="button" class="btn btn-primary btn-lg btn-block" href="?Modulo=VentaTouch" >MESAS</a><br>
		<a type="button" class="btn btn-success btn-lg btn-block" href="?Modulo=VentaTouchCobradas" >MESAS COBRADAS</a><br>
		<a type="button" class="btn btn-default btn-lg btn-block" href="?Modulo=VentaTouchCorte" >CORTE</a>
	</div>
	
	<div class="col-md-9">
		<center>
		<a href="#" style="margin-top: 60px;" class="btn btn-danger btn-lg boton_corte_caja" data-toggle="modal" data-backdrop="static" data-keyboard="false"  onclick="abrir();" >HACER CORTE</a>
		</center>
		
	</div>
</div>





<!-- Corte de Caja -->
<div class="modal fade" id="corte_caja" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Corte de caja</h4>
			</div>
			<div class="modal-body" id="corte_content">

			




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="cancelar_corte_caja">Cancelar</button>
				<button type="button" class=" btn btn-success btn_ac btn-lg" id="corte_doit_ok" style="display:none">Confirmar</button>
			</div>
		</div>

	</div>
</div>