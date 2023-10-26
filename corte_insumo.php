<?
require("modal_msn_corte.php");

$sq="SELECT * FROM cortes
JOIN usuarios ON usuarios.id_usuario = cortes.id_usuario
ORDER BY id_corte DESC";
$q0=mysql_query($sq);
$valida=mysql_num_rows($q0);

$sq_corte="SELECT MAX(id_corte) as id FROM cortes ";
$q_corte=mysql_query($sq_corte);
$row = mysql_fetch_array($q_corte);
$v_corte = $row ['id'];
$p_corte = $v_corte-1;


$sq_conf="SELECT * from configuracion";
$q_conf = mysql_query($sq_conf);
$row_conf = mysql_fetch_array($q_conf);
$v_confi = $row_conf['enviar_sms'];
$fact = $row_conf['ajustes_facturacion'];

//echo $sq_conf;

# Validación de mesas abiertas.
$sql ="SELECT*FROM ventas WHERE id_corte = 0";
$q = mysql_query($sql);
$n = mysql_num_rows($q);

$sql_gastos = "SELECT * FROM gastos WHERE id_corte = 0";
$q_gastos = mysql_query($sql_gastos);
$n_gastos = mysql_num_rows($q_gastos);
# termina validación.

$sql_ventas = "SELECT * FROM ventas WHERE id_corte = 0";
$q_ventas = mysql_query($sql_ventas);
$n_ventas = mysql_num_rows($q_ventas);

?>
<script>
	$(function() {
		$('#vertickets').on('shown.bs.modal',function(e) {
			$("#modal_tickets").show();
			$('#cerrarVent').focus();
		});

		$('#ajuste').click(function(event) {

		});
	});
	$('#panel-body').load('', data('name', 'value'), complete)

</script>

<div class="row">
  
</div>
   		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Insumos por corte</h3>
				</div>

				<div class="panel-body">
					<? if($valida){ ?>
					<table class="table table-striped">

						<thead>
							<tr>
								<th>Corte</th>
								<th>Usuario</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
						<? while($ft=mysql_fetch_assoc($q0)){ ?>

							<tr>
								<td><?=$ft['id_corte']?></td>
								<td><?=$ft['nombre']?></td>
								<td><?=fechaLetra($ft['fecha'])?></td>
								<td><?=horaVista($ft['hora'])?></td>
								<td align="right">
								<?php

									?>
									<a type="button" class="btn btn-primary btn-xs" href="#" onclick="reporte(<?=$ft['id_corte']?>)" >Descargar reporte</a>


								</td>
							</tr>
						<? } ?>
						</tbody>
					</table>
					<? } ?>

				</div>
			</div>
		</div>

	</div>

<script>


    
function reporte(id_corte){
    window.location  = 'reportes/prueba.php?id_corte='+id_corte;

}

function imprimeCorte(id_corte){

	$.post('ac/corte_reimprimir.php','id_corte='+id_corte,function(data) {

		if(data==1){
			alert('Corte reimpreso');
		}else{
			alert('Error al imprimir el corte. '+data);
		}

	});
}

function random_msn(){

	$('#enviar_codigo_btn').val('Enviando...');

	$.post('ac/msn_corte.php',function(data) {
		if(data==1){
			$('#mensaje').addClass('alert alert-success').html('Enviado').show(200).delay(2500).hide(200);
			//$('#mensaje').html(data);
			document.getElementById('msn').style.display = 'block';
			document.getElementById('boton').style.display = 'none';
		}else{
			alert('Error al enviar mensaje. '+data);
			//$('#mensaje').html(data);
		}
	});

	}
function vercorte(){
	window.open("?Modulo=CortesTicket");
}



$(function(){
			  $("#form_codigo").on("submit", function(e){

			            e.preventDefault();
			            var f = $(this);

			            var formData = new FormData($("#form_codigo")[0]);
			            formData.append("dato", "valor");
			            var v_corte=<?=$v_corte?>;
			            var codigo = $("#codigo").val();
			            if (codigo == ""){
			            	alert("Ingrese un código");
			            }else{
			            	 $.ajax({
			                url: "ac/deshacer_corte.php",
			                type: "post",
			                data:{codigo:codigo, v_corte:v_corte}  ,

			            })
			                .done(function(res){
			                    $("#mensaje").html(res);

			                   if(res == 0){
									$('#mensaje').addClass('alert alert-danger').html('').show(200).delay(2500).hide(200);
									alert("El codigo es erróneo, intente nuevamente");
			                   }
			                   if(res == 1){
			                   	 $('#mensaje').addClass('alert alert-success').html('Corte Eliminado').show(200).delay(2500).hide(200);

			                   	 	alert("Corte eliminado con éxito.");
			                   	 	window.location  = 'index.php';
			                   }



			                });
			            }



			        });

    });
</script>
