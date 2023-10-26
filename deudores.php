<?
$sql="SELECT * FROM clientes WHERE activo = 1 ORDER BY nombre ASC";
$q=mysql_query($sql);


if($_GET['id_cliente']){
	$adeudo = fnum($_GET['adeudo']);
	$monto = fnum($_GET['abonado']);
	$saldo_actual = fnum($adeudo-$monto);
	$nombre = $_GET['nombre'];
?>
<div class="row" id="">
	<div class="col-md-12">
		<div class="alert alert-dismissable alert-success">
			Se abonaron <b>$<?=$monto?> pesos</b> a la cuenta de <b><?=$nombre?></b> (Saldo Anterior: $<?=$adeudo?> · Abono: $<?=$monto?> · Saldo Actual: $<?=$saldo_actual?>)
		</div>
	</div>
</div>
<?
}	
?>


<div class="row">		
	<div class="col-md-12">
		<div class="panel panel-primary">
		
		  <div class="panel-heading">
		    <h3 class="panel-title">Deudores</h3>
		  </div>
		  
		  <div class="panel-body">
		  <!-- Confirmación -->
		  <? if($_GET['msg']==1){ ?>
		  		<div class="alert alert-dismissable alert-success">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El cliente se ha agregado</p>
			  	</div>
		  <? }if($_GET['msg']==2){ ?>
		  		<div class="alert alert-dismissable alert-info">
			  		<button type="button" class="close" data-dismiss="alert">×</button>
			  		<p>El cliente se ha editado</p>
			  	</div>
		  <? } ?>
		  <!-- Contenido -->
		  		<table class="table table-striped table-hover " id="tabla" cellspacing="0" width="100%">
				      <thead>
				        <tr>
				          <th width="300">Nombre</th>
				          <th width="">Referencia</th>
				          <th width="90" style="text-align: right">Adeudo</th>
				          <th width="90" style="text-align: right">Límite</th>
				          <th width="170"></th>
				        </tr>
				      </thead>
				      <tbody>
				      <? while($ft=mysql_fetch_assoc($q)){ 
					      
					      $deuda = obtenerDeuda($ft['id_cliente']);
					      						  
						  $limite = $ft['limite_credito'];
						  
							  
							  if($limite<$deuda){
								  $b_limite = '<b style="color:#9f0400">';
								  $bb_limite = '</b>';
							  }else{
								  unset($b_limite);
								  unset($bb_limite);
							  }

							  if($limite=='999'){
							  	$limite = 'N/A';
							  unset($b_limite);
							  unset($bb_limite);
						  	  }else{
								  $limite = '$'.fnum($limite);	  
						  	  }

						  
						  if($_GET['id_cliente']==$ft['id_cliente']){
							  
							  $b = '<b style="color:#479f40">';
							  $bb = '</b>';
							  $b_limite = $b;
							  $bb_limite = $bb;
							  
						  }else{
							  
							  unset($b); unset($bb);
							  
						  }
						  
				      ?>
				        <tr>
				          <td id="nombre_<?=$ft['id_cliente']?>"><?=$b.acentos($ft['nombre']).$bb?></td>
				          <td><?=$b.acentos($ft['referencia']).$bb?></td>
				          <td align="right"><b><?=$b.'$'.fnum($deuda).$bb?></b></td>
				          <td align="right"><i><?=$b_limite.$limite.$bb_limite?></i></td>
				          <td align="right">
				          		<span class="label label-primary link btn_<?=$ft['id_cliente']?>" data-toggle="modal" data-target="#Cuenta" data-id-cuenta="<?=$ft['id_cliente']?>" data-nombre-cliente="<?=$ft['nombre']?>">Detalle</span>&nbsp; &nbsp;
				          		<span class="pago_cliente label label-success link btn_<?=$ft['id_cliente']?>" data-toggle="modal" data-nombre="<?=$ft['nombre']?>" data-backdrop="static" data-keyboard="false" data-target="#PagoCliente" data-id="<?=$ft['id_cliente']?>">Pagar / Abonar</span>
				          		<img src="img/load-azul.gif" border="0" id="load_<?=$ft['id_cliente']?>" width="19" class="oculto" />
				          </td>
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




<!-- Modals ---->
<!-- Pago -->
<div class="modal fade" id="PagoCliente">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>-->
        <h4 class="modal-title titulo_pago">Pago</h4>
      </div> 


      <div class="modal-body">
			<div class="input-group col-md-12 mb20">
			  <span class="input-group-addon f18">Adeudo: </span>
			  <input type="text" class="form-control input-lg total" id="input_adeudo" readonly="1" value="0.00">
			</div>
			
			
			<div class="input-group col-md-12 pago_div">
			  <span class="input-group-addon f18">Pago: </span>
			  <input type="text" class="form-control input-lg total" autocomplete="off" id="input_pago">
			</div>
      </div>
      
      <div class="modal-footer ">
	    <div class="col-md-6">
    	    <button type="button" class="btn btn-default btn_ac btn-lg" id="cancelar" data-dismiss="modal">Cancelar</button>
	    </div>
	    <div class="col-md-6 text-right pago_div">
		    <img src="img/load-verde.gif" border="0" id="load2" width="30" class="oculto" />
		    <input type="hidden" id="id_cliente" />
	        <button type="button" class="btn btn-primary btn_ac btn-lg" onclick="pago()">Pagar</button>
	    </div>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<!-- Modals ---->
<!-- Estado de cuenta -->
<div class="modal fade" id="Cuenta">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title detalle_trans">Detalle de Transacciones</h4>
      </div> 
      <div class="modal-body text-center" id="tabla_estado_cuenta">
	  <!-- Data estado de cuenta -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<input type="hidden" id="refrescar_al_cerrar" value="0" />

<!--- Js -->
<script>
	function eliminar_mov(id,tipo,id_cliente){
	
	$('#load_'+id+tipo).show();
	$('.class_'+id+tipo).hide();
	
	$.post('ac/eliminar_movimiento.php','id_mov='+id+'&tipo='+tipo,function(data) {
		
		if(data==1){
		$('#refrescar_al_cerrar').val('1');
		$('#tabla_estado_cuenta').html('<img src="img/load-azul.gif" border="0" width="30" />');

		    $.ajax({
		   	url: "data/deuda_cliente.php",
		   	data: 'id_cliente='+id_cliente,
		   	success: function(data2){
		   		$('#tabla_estado_cuenta').html(data2);
		   	},
		   	cache: false
		   });
			
		}else{
			alert(data);
			$('#load_'+id+tipo).hide();
			$('.class_'+id+tipo).show();
		}
	
	});
	
}	


function pago(){
	$('.btn_ac').hide();
	$('#load2').show();
	var monto = $('#input_pago').val();
	var id_cliente = $('#id_cliente').val();
	var adeudo_actual = $('#input_adeudo').val();
	$.post('ac/abono_guarda.php','id_cliente='+id_cliente+'&monto='+monto,function(data) {
		if(data==1){
			var nombre = $('#nombre_'+id_cliente).html();
			window.location = 'index.php?Modulo=Deudores&id_cliente='+id_cliente+'&adeudo='+adeudo_actual+'&abonado='+monto+'&nombre='+nombre;
		}else{
			alert(data);
			$('.btn_ac').show();
			$('#load2').hide();
		}
	
	});
	
}
$(function(){
	$('#tabla').dataTable({
        "order": [[ 2, "desc" ]],
        "iDisplayLength": 500
    });
    
    $('.pago_cliente').click(function() {
    	
    	var id = $(this).attr('data-id');
    	$('#id_cliente').val(id);
		$('#input_adeudo').val('Cargando..');
		$('.pago_div').hide();
		$.get('ac/obtener_deuda.php','id_cliente='+id,function(data) {
			$('#input_adeudo').val(data);
			if(data>0){
				$('.pago_div').show();
				$('#input_pago').focus();
			}

		});
    });
    
	$('#PagoCliente').on('shown.bs.modal', function (e) {
    	$('#input_pago').focus();    	
    
    });
    
	$('#Cuenta').on('hide.bs.modal', function (e) {

    	var ref = $('#refrescar_al_cerrar').val();	
    	if(ref=='1'){
	    	location.reload();
    	}
    
    });
    

   
    
	$(document).on('click', '[data-id-cuenta]', function () {
		
		
	    var id_cliente = $(this).attr('data-id-cuenta');
	    var nombre = $(this).attr('data-nombre-cliente');

		$('.detalle_trans').html('Detalle de '+ nombre);
		$('#tabla_estado_cuenta').html('<img src="img/load-azul.gif" border="0" width="30" />');

	    $.ajax({
	   	url: "data/deuda_cliente.php",
	   	data: 'id_cliente='+id_cliente,
	   	success: function(data){
	   		$('#tabla_estado_cuenta').html(data);
	   	},
	   	cache: false
	   });
	   
	});
	

	$(document).on('click', '[data-nombre]', function () {
		
		
	    var nombre = $(this).attr('data-nombre');
		$('.titulo_pago').html('Pago de '+nombre);
	   
	});
	
	
	
		

	
});

function EditaCliente(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_cliente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Clientes&msg=2", "_self");
	    }else{
	    	$('#load2').hide();
			$('.btn').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
}
function Desactiva(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_cliente.php', { tipo: "0", id_cliente: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Clientes", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function Activa(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_cliente.php', { tipo: "1", id_cliente: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Clientes", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevoCliente(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_cliente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Clientes&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>