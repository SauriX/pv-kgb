<?

include('../includes/postmark.php');
exit('squi'.strlen('-1'));
include('../includes/db.php');
include('../includes/funciones.php');
 
$datos=mysql_fetch_assoc(mysql_query("SELECT * FROM configuracion"));
$establecimiento=$datos['establecimiento'];
$rfc=$datos['rfc'];
$direccion=$datos['direccion'];
//$datos=mysql_fetch_assoc(mysql_query("SELECT * FROM cortes ORDER BY id_corte DESC"));
//$id_corte=$datos['id_corte'];

extract($_GET);



     //insumos
	 $tags = array();
	 $tags2 = array();
	 $tags3 = array();
	 
	

  //Genera la lista de ingredientes
	 $sql_pro= "SELECT * FROM productos_base 
	 LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad";
	 
	 $q_pro = mysql_query($sql_pro);
	 
	 while($pro= mysql_fetch_assoc($q_pro)){
	   
		$id_base=$pro['id_base'];
	 
		$producto=$pro['producto'];
  
		$abreviatura = $pro['abreviatura'];
  
		$tags[] = array(
		   "id"=> $id_base,
		   "producto" => $producto,
		   "cantidad" => 0,
		   "unidad" =>" ".$abreviatura,
		   "mermado"=>0,
		   "consumido"=>0,
		   "dotado"=>0,
		   "previa"=>0
		   
		);
	 }

  //Termina de generar la lista de ingredientes
  
 

  
  //Optiene la dotaciones de productos y los añade a existencias
	 $sql_dotacion= "SELECT * FROM dotaciones WHERE id_corte=$id_corte " ;
	 
	 $q = mysql_query($sql_dotacion);
	 
	 
	 
	 while($dotacion = mysql_fetch_assoc($q)){
	 $id_dotacion= $dotacion['id_dotacion'];
	 $corte=$dotacion['id_corte'];
	 
	 $sql_detalle="SELECT SUM(cantidad) as cantidad,id_producto,producto,abreviatura  FROM dotaciones_detalle
	  LEFT JOIN productos_base ON  dotaciones_detalle.id_producto = productos_base.id_base
	  LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad 
	  WHERE id_dotacion = $id_dotacion GROUP BY id_producto";
	  //echo($sql_detalle);
	 $detalle_q=mysql_query($sql_detalle);
	 while($ft=mysql_fetch_assoc($detalle_q)){
				$id_base=$ft['id_producto'];
				$cantidad=$ft['cantidad'];
				$producto=$ft['producto'];
				$abreviatura = $ft['abreviatura'];
			   
				  
				 for($i=0; $i<count($tags); $i++)
				 {
				   
				  if($tags[$i]['id']==$id_base){
					$index=$i;
				 
				   
					$tags[$i]['cantidad']=$tags[$i]['cantidad']+($cantidad);
					
					$consumo=$tags[$i]['dotado']+$cantidad;
				   
				  if($corte==$id_corte){
				   
					 $tags[$i]['dotado']=$consumo;	
				  }
					
		   
				  }
				 }
				  
					
				 
				   
		   
	 }
	 }
	 // fin de la dotaciones
	 
  
	 //mermas
	 
	 $sql_merma= "SELECT * FROM merma WHERE id_corte=$id_corte  ";
	 
	 $q_merma = mysql_query($sql_merma);
	 
	 
	 while($merma = mysql_fetch_assoc($q_merma)){
	 $id_merma= $merma['id_merma'];
	 $corte=$merma['id_corte'];
	 $sql_detallem="SELECT SUM(cantidad) as cantidad,id_producto,producto,abreviatura  FROM merma_detalle
	 LEFT JOIN productos_base ON  merma_detalle.id_producto = productos_base.id_base
	 LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad 
	 WHERE id_merma = $id_merma GROUP BY id_producto";
	 
	 
	 //echo($sql_detalle);
	 $detallem_q=mysql_query($sql_detallem);
	 while($ft2=mysql_fetch_assoc($detallem_q)){
			  $id_base=$ft2['id_producto'];
			  $cantidad=$ft2['cantidad'];
			  $producto=$ft2['producto'];
			  $abreviatura = $ft['abreviatura'];
		
				 
			   for($i=0; $i<count($tags); $i++)
				{
				 
				 if($tags[$i]['id']==$id_base){
				  $index=$i;
			   
				 
				  $tags[$i]['cantidad']=$tags[$i]['cantidad']-($cantidad);
				  
				 
				  if($corte==$id_corte){
				   
					$tags[$i]['mermado']=$cantidad;	
				 }
				  
																 
				 }
				}
		   
				 
		 
	 }
	 }
	 
	 //fin mermas
	
	 //ventas
	 
	 $sql_venta= "SELECT * FROM ventas WHERE id_corte=$id_corte  ";
	 
	 $q_venta= mysql_query($sql_venta);
	 
	 
	 while($venta= mysql_fetch_assoc($q_venta)){
	 $id_venta= $venta['id_venta'];
	 $corte = $venta['id_corte'];
	 $sql_detallev="SELECT * FROM  venta_detalle WHERE  id_venta =$id_venta AND id_producto!=0";
	 $q_ventade= mysql_query($sql_detallev);
	 
	 
	 
	 
	 while($ft3=mysql_fetch_assoc($q_ventade)){
	   $id_base=$ft3['id_producto'];
	   
	   $cantidad=$ft3['cantidad'];
	   
	 $sql_producto="SELECT * FROM  productosxbase WHERE  id_producto=$id_base";
	 
	 $producto_q=mysql_query($sql_producto);
	 
		  while($ft4=mysql_fetch_assoc($producto_q)){
			
			  $id_base=$ft4['id_base'];
			  $cantidad2=$ft4['cantidad']*$cantidad;
		   
				
		
				 
			   for($i=0; $i<count($tags); $i++)
				{
				 
				 if($tags[$i]['id']==$id_base){
				  $index=$i;
			   
				  $cantidad3 =$tags[$i]['cantidad']-($cantidad2);
				  
					$tags[$i]['cantidad']=$cantidad3;
				 
				   
				  $consumo=$tags[$i]['consumido']+$cantidad2;
				 
					 $tags[$i]['consumido']=$consumo;	
				
				  
				 
		  
				 }
				}
  
  
  
		   }
				 
		 
	 }

	 }
	 //fin ventas
	
	 

	 //fin insumos
	error_reporting(0);
	 header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
	 header('Content-Disposition: attachment; filename=insumos.xls');
?>

<style>
.titulos{
	background-color: #1596b6;
	color: #FFF;
	/*padding-left: 5px;*/
}
.borde-azul{
	border: #1596b6 1px solid ;
}
.borde-iz{
	border-left: #1596b6 1px solid;
}
.borde-der{
	border-right: #1596b6 1px solid;
}
.borde-bot{
	border-bottom: #1596b6 1px solid;
}
b{
	font-family: sfsemi;
}
table{
	font-family: sf;
}
.f11{
	font-size: 11px;
}
.f10{
	font-size: 10px;
}
.no-margin{
	margin-bottom: 0px;
}
.titulo_corte{
	margin-bottom: 5px;
	margin-top: 15px;
	font-weight: normal;
}
tr.odd{
    background-color:white;     
}
tr.even{
    background-color:#FAFAFA;
}
</style>
<meta charset="UTF-8 ">
<page backtop="20mm" backbottom="10mm" backleft="0mm" backright="2mm" footer="page">

<page_header>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="580" align="center" valign="middle">
				<h4 class="no-margin">Existencias al día <?fechaLetraTres($fecha_actual)?></h4>
				<? if($establecimiento){ echo $establecimiento; }?> <? if($rfc){ echo "RFC: ".$rfc; }?><br>
				<? if($direccion){ echo $direccion; }?>
			</td>
			<td width="200" align="center" valign="middle"><img src="logo.png" width="180" /></td>
		</tr>
	</table>
</page_header>

<page_footer>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="780" style="padding-top: 10px;padding-bottom: 16px;"><b>KGB grill</b> Punto de venta avanzado. <b>http://www.conexia.mx/</b></td>
		</tr>
	</table>
</page_footer>


<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11">
	<thead>
    	<tr class="titulos">
			<th width="20%" height="25" class="f11" style="padding-left: 5px;">Ingredientes</th>
			
			<th width="20%" height="25" class="f11">Dotaciones</th>
			<th width="20%" height="25" class="f11">Mermas</th>
			<th width="20%" height="25" class="f11">Consumo</th>
			<th width="20%" height="25" class="f11" align="right" style="padding-right: 5px;">Existencias</th>
		</tr>
	</thead>
	<tbody>
<? foreach($tags as $productos){
	$counter++;
	$class="";
	if($counter%2){
		$class="even";
	}else{
		$class="odd";
	}
?>
    	<tr class="<?=$class?>" >
			<td width="20%" height="25" style="padding-left: 5px;"><?=$productos['producto'];?></td>
			
		    <td width="20%" height="25" align="right"><?=$productos['dotado'].$productos['unidad']?></td>
		    <td width="20%" height="25" align="right"><?=$productos['mermado'].$productos['unidad']?></td>
			<td width="20%" height="25" align="right"><?=$productos['consumido'].$productos['unidad']?></td>
			<td width="20%" height="25" align="right" style="padding-right: 5px;"><?=$productos['cantidad'].$productos['unidad']?></td>
		</tr>
<? } ?>
	</tbody>
</table>


</page>


<?php
exit();
	$content_html = ob_get_clean();
	
	// initialisation de HTML2PDF
	require_once(dirname(__FILE__).'/pdf/html2pdf.class.php');
	try
	{
		
		$html2pdf = new HTML2PDF('P','Letter','es', true, 'UTF-8', array(2, 0, 0, 0));
		//$html2pdf->setDefaultFont('Arial');
		$html2pdf->pdf->SetDisplayMode('fullpage');

		$html2pdf->addFont("sf");
		$html2pdf->addFont("sfsemi");
		//$html2pdf = new HTML2PDF('L','A4','es', false, 'utf-8', array(0, 0, 0, 0));
		$html2pdf->writeHTML($content_html, isset($_GET['vuehtml']));
//		$html2pdf->createIndex('Sommaire', 25, 12, false, true, 1);
		$html2pdf->Output('Corte_de_Caja.pdf');
	}
	catch(HTML2PDF_exception $e) { echo $e; }	

?>