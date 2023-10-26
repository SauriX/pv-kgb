		<?
//Mesas Pendientes
$sq="SELECT * FROM ventas WHERE abierta = 0 AND pagada = 0 AND id_corte = 0 ORDER BY fechahora_cerrada ASC";
$q0=mysql_query($sq);
$valida=mysql_num_rows($q0);

//Mesas Abiertas
$sql="SELECT * FROM ventas WHERE abierta = 1 AND pagada = 0 AND id_corte = 0";
$q=mysql_query($sql);
$valida_mesas=mysql_num_rows($q);
?>
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
jQuery(document).ready(function() {
  	jQuery("time.timeago").timeago();

	setTimeout(function() {
        $('.mesa').removeClass('animated');
        $('.mesa').removeClass('pulse');
    },5000);
});
function verMesa(id_venta,mesa){
	window.open("?Modulo=VentaTouchMesa&id_venta="+id_venta+"&mesa="+mesa, "_self");
}
function verConsumo(id_venta,mesa){
	window.open("?Modulo=VentaTouchMesaPendiente&id_venta="+id_venta+"&mesa="+mesa, "_self");
}
function cobrarMesa(id_venta,mesa){
	window.open("?Modulo=VentaTouchCobro&id_venta="+id_venta+"&mesa="+mesa, "_self");
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
		<? if($valida){ ?>
		<div class="row">
			<? while($ft=mysql_fetch_assoc($q0)){
			    $id_venta=$ft['id_venta'];
			    $mesa=$ft['mesa'];
				$descuento=$ft['DescEfec_txt'];
				$id_descuento=$ft['descuento_txt'];
			    $sq="SELECT * FROM venta_detalle WHERE id_venta=$id_venta";
			    $qu=mysql_query($sq);
			    $consumo_total=0;
			    while($dat=mysql_fetch_assoc($qu)){
			    	//Sacamos los productos
			    	$consumo_total+=$dat['cantidad']*$dat['precio_venta'];
			    }
				if($id_descuento!=0){
					$consumo_total=$consumo_total-$descuento;
				}
			?>
			<div class="col-md-2 mesa pendiente <? if($_GET['id_venta']==$id_venta){?>animated pulse<? } ?>" onclick="cobrarMesa(<?=$id_venta?>,'<?=$mesa?>')">
				<h3><? if(is_numeric($mesa)){ echo 'MESA '.$mesa; }else{ echo $mesa; }?><br>
					<small>$ <?=number_format($consumo_total,2)?></small>
				</h3>
				<?

					$fecha_cerrada=substr($ft['fechahora_cerrada'],0,10);
					$hora_cerrada=substr($ft['fechahora_cerrada'],11,8);

				?>
				<p><?=$hora_cerrada?></p>
			</div>
			<? } ?>
		</div>
		<hr style="margin-top: 0px;">
		<? } ?>
		<? if($valida_mesas){ ?>
		<div class="row">
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
			<div class="col-md-2 mesa <? if($_GET['id_venta']==$id_venta){?>animated pulse<? } ?>" onclick="verMesa(<?=$id_venta?>,'<?=$mesa?>')">
				<h3><? if(is_numeric($mesa)){ echo 'MESA '.$mesa; }else{ echo $mesa; }?><br>
					<small>$ <?=number_format($consumo_total,2)?></small>
				</h3>
			</div>
			<? } ?>
		</div>
		<? } ?>

		<? if((!$valida)&&(!$valida_mesas)){ echo "<center><h2>No tienes ninguna mesa abierta o pendiente de cobro.</h2></center>"; } ?>
	</div>
</div>
