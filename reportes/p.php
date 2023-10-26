<? 
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
set_time_limit(0); 
//ob_start();
error_reporting(0);
header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
header('Content-Disposition: attachment; filename=ingredientes.xls');
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

<page backtop="20mm" backbottom="10mm" backleft="0mm" backright="2mm" footer="page">

<page_header>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="580" align="center" valign="middle">
				<h4 class="no-margin">Existencias al día <?=fechaLetraTres($fecha_actual)?></h4>
		>
			</td>
			<td width="200" align="center" valign="middle"><img src="logo.png" width="180" /></td>
		</tr>
	</table>
</page_header>

<page_footer>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="780" style="padding-top: 10px;padding-bottom: 16px;"><b>KGB grill</b> Punto de venta avanzado. <b>digmastudio.com/vendefacil</b></td>
		</tr>
	</table>
</page_footer>
  <?
         $sql_pro= "SELECT * FROM productos";
       
      $q_pro = mysql_query($sql_pro);
  while ($ft=mysql_fetch_assoc($q_pro)) {?>


 <div>    

     </div>

<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11">
	<thead>
    	<tr class="titulos">
			<th width="145" height="25" class="f11" style="padding-left: 5px;"><?echo($ft['nombre']);?></th>
			<th width="145" height="25" class="f11" style="padding-left: 5px;"></th>
			</th>

		</tr>
	</thead>
	<tbody>

		<?
$id=$ft['id_producto'];
     

     	$sql_lista="SELECT * FROM productosxbase WHERE id_producto = '$id' ";
     
     	$q= mysql_query($sql_lista);

     	$can = mysql_num_rows($q);

     	if($can==0){
     		echo('<tr class="" >');
     		echo('<td width="145" height="20" style="padding-left: 5px;">NO CONTIENE INGREDIENTES</td>');
     		echo('</tr>');
     	}

     	while ($fx=mysql_fetch_assoc($q)) {
     		
     		$idex=$fx['id_base'];

     		
     		$sql="SELECT * FROM productos_base WHERE id_base = '$idex' ";
     		
            
     	   $q2= mysql_query($sql);
             while ($fy=mysql_fetch_assoc($q2) ){
            

		?>

    	<tr class="" >
			<td width="145" height="20" style="padding-left: 5px;"><?=$fy['producto']?></td>
			<td style="text-align: center;" width="75" height="20" style="padding-left: 5px;"><?echo($fx['cantidad'])?></td>
             
		</tr>
<?
   }


     	}

?>
	</tbody>
</table>

<?}?>
</page>


<?php

/*	$content_html = ob_get_clean();
	
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