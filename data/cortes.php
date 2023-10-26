<?
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/session_ui.php");
?>

<div id="loader_corte" style="margin-top:80px;text-align:center;margin-bottom:100px">
					<p><img src="img/load-azul.gif" width="90" id="imagen_loader_corte"/></p>
					<p class="lead" id="mensaje_loader_corte">Obteniendo..</p>
				</div>

				<div id="hidden_modal_corte" >
				
                        
                        
						<?php
							include("../includes/db.php");
							$sq_corte="SELECT alerta_corte from configuracion ";
							$q_corte=mysql_query($sq_corte);
							$row = mysql_fetch_array($q_corte);
							$v_corte = $row ['alerta_corte'];
						?>

						<center>

							<span class="label" id="msn_corte" style="display:none; color:black; font-size:15px;"><?=  $v_corte?></span>
							<span id="mesas_abiertas_msg" style="display:none"><b>No puede realizar el corte, existen mesas sin pagar, abiertas o no se han cargado mesas.</b></span>
							<span id="mensaje_provicion" style="display:none"><b>Todavía existen gastos provisionados.</b></span>

						</center>

						<form class="form-horizontal" style="display:none" id="frmDetallesCaja">

							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Tolta de efectivo en caja:</label>
								<div class="col-sm-8"> 
									<input type="text" class="form-control solo_numero" id="txtEfectivo" autocomplete="off"> 
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-4" for="pwd">Terminal punto de venta:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control solo_numero" id="txtTpv" autocomplete="off">
								</div>
							</div>

						</form>

					
                    </div>
                    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>

$(document).ready(function() {
    console.log( "ready!" );

    $('#cancelar_corte_caja').focus();
		$('#contenido_auxiliar').html('').hide();
		//optener corte
		$.get('ac/corte_obtener.php',function(dat) {
				//proviciones validacion
				if(dat=='PROVICIONES'){
					$('.boton_corte1').hide();

					$('#mensaje_provicion').show();
				}else{
					if(dat=='NOCORTE' || dat=='NOVENTAS'){
						$('.boton_corte1').hide();
						$('#mesas_abiertas_msg').show();
					}else{
						$('.boton_corte1').show();
						$('#frmDetallesCaja').show();
						$('#corte_doit_ok').show();
						$('#mesas_abiertas_msg').hide();
					}//final else
				}//final validacion


				$('#hidden_modal_corte').show();
				$('#loader_corte').hide();
				$('#cancelar_corte_caja').focus();
		});


});
</script>

                  