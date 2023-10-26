<?
ob_start();
include('../includes/db.php');
include("../includes/funciones.php");
$productos=array();


// llenamos el array
$sql_productos="SELECT * FROM productos";
$query_productos= mysql_query($sql_productos);
while($pro=mysql_fetch_assoc($query_productos))
{
    $id_base=trim($pro['id_producto']);
      
    $producto=trim($pro['nombre']);

    

    $productos[] = array(
       "id"=> $id_base,
       "producto" => $producto,
       "cantidad" => 0,
      
    );
}


//sumamos la cantidad de productos vendidos

$sql_venta= "SELECT * FROM ventas ";
      
      $q_venta= mysql_query($sql_venta);
      
      
      while($venta= mysql_fetch_assoc($q_venta)){
      $id_venta= $venta['id_venta'];
      
      $sql_detallev="SELECT * FROM  venta_detalle WHERE  id_venta =$id_venta AND id_producto!=0";
      $q_ventade= mysql_query($sql_detallev);
      
      
      
      
      while($ft3=mysql_fetch_assoc($q_ventade)){
        $id_base=$ft3['id_producto'];
        
        $cantidad=$ft3['cantidad'];
        
    
        

               $cantidad2=$cantidad;
            
              
         
                  
                for($i=0; $i<count($productos); $i++)
                 {
                    
                  if($productos[$i]['id']==$id_base){
                   
                
                    $productos[$i]['cantidad']= $productos[$i]['cantidad']+($cantidad2);
                
                    
                  
                   
                  
           
                  }
                 }
   
   
   
            
                  
          
      }
      }



/*$productos[] = array("name"=>"Bob","age"=>8,"colour"=>"red");

$productos[] =  array("name"=>"Greg","age"=>12,"colour"=>"blue");
$productos[] =  array("name"=>"Andy","age"=>13,"colour"=>"purple");*/



$sortArray = array();

foreach($productos as $person){
    foreach($person as $key=>$value){
        if(!isset($sortArray[$key])){
            $sortArray[$key] = array();
        }
        $sortArray[$key][] = $value;
    }
}

$orderby = "cantidad"; //change this to whatever key you want from the array

array_multisort($sortArray[$orderby],SORT_DESC,$productos);

$titulo="Productos" ;


?>

<style>
.titulos{
	background-color: #1596b6;
	color: #FFF;
	/*padding-left: 5px;*/
}

.titulos-rojo{
	background-color: #ff0000;
	color: #FFF;
	/*padding-left: 5px;*/
}
.borde-azul{
	border: #1596b6 1px solid ;
}

.borde-rojo{
	border: #ff0000 1px solid ;
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
.borde-top{
	border-top: #1596b6 1px solid;
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
	font-family: sfsemi;
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

<page backtop="20mm" backbottom="15mm" backleft="0mm" backright="2mm" footer="page">

<page_header>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="580" align="center" valign="middle">
				<h4 class="no-margin"><?=$titulo?></h4>
				<?
				$fecha_actual=fechaLetra(date("d-m-Y"));
				
				echo($fecha_actual); echo("&nbsp;"); echo(date("H:i"));?><br>
				<? if($establecimiento){ echo $establecimiento; }?> <? if($rfc){ echo "RFC: ".$rfc; }?><br>
				<? if($direccion){ echo $direccion; }?>
			</td>
			<td width="200" align="center" valign="middle"><img src="logo.png" width="180" /></td>
		</tr>
	</table>
</page_header>


<h4 class="titulo_corte">Productos más vendidos <br><small>Usuario: <?=$usuario?></small></h4>
<table width="780" cellpadding="0" cellspacing="0" class="borde-rojo f11">
	<thead >
    	<tr class="titulos-rojo ">
			<th width="450" height="25" class="f11" style="padding-left: 5px;">Producto</th>
			<th width="55" height="25" class="f11">Cantidad</th>
		
			
		</tr>
	</thead>
	<tbody>

<?
foreach($productos as $producto ){?>
    	<tr class="<?=$class?>">
			<td width="450" height="20" style="padding-left: 5px;"><?=$producto['producto']?></td>
			
		    <td width="55" height="20" align="center"><?=$producto['cantidad']?> pz</td>
			
			
		</tr>
<?}?>






</tbody>
</table>


		




<page_footer>
	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11">
    	<tr>
			<td width="780" style="padding-top: 10px;padding-bottom: 16px;"><b>KGB grill</b> Punto de venta avanzado. <b>epicmedia.com/vendefacil</b></td>
		</tr>
	</table>
</page_footer>

</page>





<?php

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
		$html2pdf->Output('mayor.pdf');

	
		

		
	}


	
	catch(HTML2PDF_exception $e) { echo $e; }




		

?>
