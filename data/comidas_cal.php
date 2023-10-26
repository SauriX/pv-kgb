<?php
include("../includes/db.php");
include("../includes/funciones.php");

extract($_GET);

if(!$id_cliente) exit('Falta ID cliente.');

$sql ="SELECT * FROM comidas_creditos WHERE id_cliente = $id_cliente";

$q = mysql_query($sql);
$d = mysql_fetch_assoc($q);

$des = $d['creditos_desayuno'];
$alm = $d['creditos_almuerzo'];

if($des<0){
	$des_neg = abs($des);
	$hay_neg = true;
	$des = 0;
}

if($alm<0){
	$alm_neg = abs($alm);
	$hay_neg = true;
	$alm = 0;
}


$sql ="SELECT * FROM clientes WHERE id_cliente = $id_cliente";
$q = mysql_query($sql);
$data = mysql_fetch_assoc($q);
$nombre = $data['nombre'];
$ref = explode('-', $data['referencia']);
$ref = $ref[0];

if(file_exists('../fotos/'.$id_cliente.'.jpg')){
	$foto = 'fotos/'.$id_cliente.'.jpg';
}else{
	$foto = 'fotos/default.jpg';
}

$fecha_plus = date('Y-m-d');

$sql = "SELECT * FROM comidas_creditos_consumo WHERE fecha = '$fecha_plus' AND id_cliente = $id_cliente";
$q = mysql_query($sql);
$data = mysql_fetch_assoc($q);

$vueltas = 0;

if($data['fecha']==$fecha_plus){
	$vueltas++;
	$desayuno = $data['creditos_desayuno'];
	$almuerzo = $data['creditos_almuerzo'];
	$hay_hoy = true;
}


if($des>$alm){
	$vueltas = $des;
}elseif($des<$alm){
	$vueltas = $alm;
}elseif($des==$alm){
	$vueltas = $des;
}

if($alm_neg>$des_neg){
	$vueltas_neg = $alm_neg;
}elseif($alm_neg<$des_neg){
	$vueltas_neg = $des_neg;
}elseif($alm_neg==$des_neg){
	$vueltas_neg = $alm_neg;
}


$cont = 1;

$dia_semana = function ($date){
	
	$day = date('l', strtotime($date));
	switch($day){
		case 'Monday':
			return 'Lunes';
		break;
		case 'Tuesday':
			return 'Martes';
		break;
		case 'Wednesday':
			return 'Miércoles';
		break;
		case 'Thursday':
			return 'Jueves';
		break;
		case 'Friday':
			return 'Viernes';
		break;		
	}
	
}


?>
<div class="row">
	<div class="col-md-4">
	<div class="panel panel-default panel_ok" style=" background-color: rgb(249, 249, 249) !important;">
		<div class="panel-body">
		    <a href="#" style="cursor:default">
		        <img src="<?=$foto?>">
		    </a>
		</div>
		<div class="panel-footer text-center">
		    <h5 class="text-muted"><?=$nombre?> · <?=$ref?></h5>  
		</div>
	</div>
	</div>
	<div class="col-md-8">
		

<?

if((!$hay_neg)&&(($des==0)&&($alm==0))){	
	
	echo '<h4>Sin comidas ni deudas.</h4>';

}

if($hay_neg){	
?>
	<h4>Comidas Pendientes de Pago</h4>
		<table class="table table-striped table-hover">
		    <thead>
		      <tr>
		        <th align="right">#</th>
		        <th align="left">Fecha</th>
		        <th align="left">Día</th>
		        <th align="center">Desayuno</th>
		        <th style="center">Almuerzo</th>
		      </tr>
		    </thead>
		    <tbody>
<?
	$cx = 1;
//	for($i=1;$i<=$vueltas_neg;$i++){

	$sql = "SELECT * FROM comidas_creditos_consumo WHERE id_cliente = $id_cliente ORDER BY fecha DESC";
	$q = mysql_query($sql);
	while($ft = mysql_fetch_assoc($q)){
		
		$fecha_debe = $ft['fecha'];
		
		if(($ft['creditos_desayuno'])||($ft['creditos_almuerzo'])){
		
			if(	(!$des_neg) && (!$ft['creditos_desayuno'])	){
				if(!$ft['creditos_almuerzo']){
					break;
				}
			}
	
			if(	(!$alm_neg) && (!$ft['creditos_almuerzo'])	){
				if(!$ft['creditos_desayuno']){
					break;
				}
			}
			
		}		
			
		?>
					      <tr>
					        <td align="right"><?=$cx?></td>
					        <td align="left"><?=fechaLetraDos($fecha_debe)?></td>
					        <td align="left"><?=$dia_semana($fecha_debe)?></td>
					        <td align="center">
		<?
			
				if($des_neg){
					if($ft['creditos_desayuno']==1){	
		?>
						    <span style="color:#a60202" class="glyphicon glyphicon glyphicon-cutlery" aria-hidden="true"></span>
		<?
						$des_neg--;
					}
				}else{
		?>
							   <!-- <span style="color:#a60202" class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span>-->
		<?
				}	
		?>
						    </td>
					        <td align="center">
		<?
				if($alm_neg){
					if($ft['creditos_almuerzo']==1){	
		?>
						    <span style="color:#a60202" class="glyphicon glyphicon glyphicon-cutlery" aria-hidden="true"></span>
		<?
						$alm_neg--;
					}
				}else{
		?>
							   <!-- <span style="color:#a60202" class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span>-->
		<?
				}	
		?>
						    </td>
					         </td>
					      </tr>
		<?
				$cx++;
		
				if(	(!$des_neg) && (!$alm_neg) ){
					break; 
	    		}
		}
		
?>
		    </tbody>
		</table>
		
		
<?
}

if(($des>0)||($alm>0)){	
?>
	<h4>Próximas Comidas</h4>
		<table class="table table-striped table-hover">
		    <thead>
		      <tr>
		        <th align="right">#</th>
		        <th align="left">Fecha</th>
		        <th align="left">Día</th>
		        <th align="center">Desayuno</th>
		        <th style="center">Almuerzo</th>
		      </tr>
		    </thead>
		    <tbody>
<?
	if($hay_hoy){
		$vueltas++;

?>
			      <tr>
			        <td align="right"><?=$cont?></td>
			        <td align="left"><?=fechaLetraDos($fecha_plus)?></td>
			        <td align="left"><?=$dia_semana($fecha_plus)?></td>
			        <td align="center">
				    <? if($desayuno){  ?>
				        <span style="color:#a60202" class="glyphicon glyphicon glyphicon-cutlery" aria-hidden="true"></span>
					<?	}else{ $des--;
					?>
					    <span style="color:#3fa100" class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>
					<? } ?>
				    </td>
			        <td align="center">
				    <? if($almuerzo){  ?>
				        <span style="color:#a60202" class="glyphicon glyphicon glyphicon-cutlery" aria-hidden="true"></span>
					<?	}else{ $alm--; ?>
					    <span style="color:#3fa100" class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>
					<? } ?>
				    </td>
			         </td>
			      </tr>
			     

			      
<?
		$cont++;
	}
	
		for($i=1;$i<=$vueltas;$i++){
		
			$fecha_plus = date('Y-m-d', strtotime($fecha_plus. ' + 1 day'));	
	
			if((date('l', strtotime($fecha_plus)) == 'Saturday')||(date('l', strtotime($fecha_plus)) == 'Sunday')){
				$vueltas++;
				$fecha_plus = date('Y-m-d', strtotime($fecha_plus. ' + 1 day'));
				continue;
			}
?>
			      <tr>
			        <td align="right"><?=$cont?></td>
			        <td align="left"><?=fechaLetraDos($fecha_plus)?></td>
			        <td align="left"><?=$dia_semana($fecha_plus)?></td>
			        <td align="center">
				    <? if($des){  ?>
				        <span style="color:#3fa100" class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>
					<? 		$des--;
						}else{ ?>
					<!--    <span style="color:#a60202" class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span>-->
					<? } ?>
				    </td>
			        <td align="center">
				    <? if($alm){  ?>
				        <span style="color:#3fa100" class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>
					<? 		$alm--;
						}else{ ?>
					    <!-- <span style="color:#a60202" class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> -->
					<? } ?>
				    </td>
			         </td>
			      </tr>
<?
	
			$cont++;

		}	
?>
		    </tbody>
		</table>
<?
}	
?>
	</div>
</div>
