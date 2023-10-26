<?
  include('includes/db.php');
$id=$_GET['id'];;
$sql4="SELECT proyecto FROM proyectos
WHERE id_proyecto=$id";
$q4=mysqli_query($conn, $sql4);
$proyectos = array();
while($datos4=mysqli_fetch_object($q4)):
    $proyectos[] = $datos4;
    
endwhile;
$val=count($proyectos);
$total =1;
$totalp= array();
$tags = array();
 $tags2=array();
function tipo2($ti){
global $totalp,$tags,$total;

include('includes/db.php');
$sql="SELECT  precio_unitario,cantidad,producto,detalle_tarjeta.tipo,detalle_tarjeta.id_producto as id,abreviatura as unidad FROM  detalle_tarjeta
LEFT JOIN  productos ON productos.id_producto = detalle_tarjeta.id_producto
LEFT JOIN  unidades ON  productos.id_unidad = unidades.id_unidad
WHERE detalle_tarjeta.id_tarjeta=$ti ORDER BY  detalle_tarjeta.tipo";
$q=mysqli_query($conn, $sql);
while($datos=mysqli_fetch_object($q)):
    

    
    if(($datos->tipo) ==1){
        
       $index=-1;
        $totalp[]=$datos->precio_unitario * $datos->cantidad;
         
        for($i=0; $i<count($tags); $i++)
        {
          
         if($tags[$i]['id']==$datos->id){
           $index=$i;
       
          
           $tags[$i]['cantidad']=$tags[$i]['cantidad']+$datos->cantidad;
           
           $tags[$i]['precio_total'] = $tags[$i]['cantidad']* $datos->precio_unitario;
           break;

         }
        }
      ;
          if($index==-1){
           
            $tags[] = array(
              "id" => $datos->id,
              "producto" => $datos->producto,
              "cantidad" => $datos->cantidad,
               "precio_unitario"=>$datos->precio_unitario,
               "precio_total"=>$datos->precio_unitario * $datos->cantidad,
               "Unidad"=>$datos->unidad

                    );
          }
        
         
      }
      if(($datos->tipo) ==2 ){
        
        $i=0;
        $tipo2 =$datos->id;
        while($i<$datos->cantidad):
        tipo2($tipo2);
        $i++;
       endwhile;
     
      }
endwhile;



}




function proyecto($ti){
  global $totalp,$tags,$total,$tags2;
  
  include('includes/db.php');
  $sql="SELECT  precio_unitario,cantidad,producto,detalle_proyecto.tipo,detalle_proyecto.id_producto as id,abreviatura as unidad  FROM  detalle_proyecto
  LEFT JOIN  productos ON productos.id_producto = detalle_proyecto.id_producto
  LEFT JOIN  unidades ON  productos.id_unidad = unidades.id_unidad
  WHERE detalle_proyecto.id_proyecto=$ti ORDER BY  detalle_proyecto.tipo";
  $q=mysqli_query($conn, $sql);

  while($datos=mysqli_fetch_object($q)):
      
  
      
      if(($datos->tipo) ==1){
         
          $totalp[]=$datos->precio_unitario * $datos->cantidad;
          $tags[] = array(
            "id" => $datos->id,
            "producto" => $datos->producto,
            "cantidad" => $datos->cantidad,
             "precio_unitario"=>$datos->precio_unitario,
             "precio_total"=>$datos->precio_unitario * $datos->cantidad,
             "Unidad"=>$datos->unidad
                  );
           
        }
        if(($datos->tipo) ==2 ){
          
          $i=0;
          $tipo2 =$datos->id;
          while($i<$datos->cantidad):
          tipo2($tipo2);
          $i++;
         endwhile;
       
        }
  endwhile;
  $total=array_sum($totalp);
  foreach($tags as $producto){
    $tags2[] = array(
      
      "producto" => $producto['producto'],
      "cantidad" =>number_format($producto['cantidad'])."  ".$producto['Unidad']."'s",
       "precio_unitario"=>"$".number_format($producto['precio_unitario']),
       "precio_total"=>"$".number_format($producto['precio_total'])
            );
    
  }
  return $total;
  }
  




  
 
 
   
 
 

   

  proyecto($id);


use Spipu\Html2Pdf\Html2Pdf;
ob_start();
?>




<page backtop="10mm" backbottom="10mm" backleft="20mm" backright="20mm">

<div class="row">
		<div class="col-md-5">
        <div class="table-hover table-bordered">          
  <table class="table">
    <thead>
      <tr class="table100-head">
        
        <th >Producto</th>
        <th style="text-align:right;">Precio Unitario</th>
        <th style="text-align:right;">cantidad</th>
        
        
        <th style="text-align:right;">Total</th>
      </tr>
    </thead>
    <tbody>
      
      <?foreach($tags as $producto){?>
        <tr >
        <td WIDTH="50"><?=$producto['producto']?></td>
        <td WIDTH="100" style="text-align:right;">$ <?=number_format($producto['precio_unitario'])?></td>
        <td WIDTH="100" style="text-align:right;"><?=$producto['cantidad']?> <span><?=$producto['Unidad']?></span>'s</td>
        <td WIDTH="100" style="text-align:right;">$ <?=number_format($producto['precio_total'])?></td>
        </tr>
            <?}?>
       
        <tr>
        <td WIDTH="50"></td>
        <td WIDTH="100" style="text-align:right;"></td>
        <td WIDTH="100" style="text-align:right;"><b>Total</b></td>
        <td WIDTH="100" style="text-align:right;"> $<?=number_format($total)?></td> 
        </tr>
    </tbody>
  </table>
  </div>
        </div>
</div>

</page>

<?php

  $content = ob_get_clean();
  require_once(dirname(__FILE__).'/vendor/autoload.php');
  try
  {
      $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', 3);
      $html2pdf->pdf->SetDisplayMode('fullpage');
      $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
      $html2pdf->Output('PDF-CF.pdf');
  }
  catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
  }




