 <?
$id_corte=2;

include("includes/session_ui.php");
include("includes/db.php");

include("includes/impresora.php");
      
 /* //insumos
      $tags = array();
      $tags2 = array();
      $tags3 = array();
      
    
      $fecha_corte2=fechaSinHora($fecha_corte);
     $fecha_corte2= trim($fecha_corte2);
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
         );
      }
      
   //Termina de generar la lista de ingredientes

   
   //Optiene la dotaciones de productos y los añade a existencias
      $sql_dotacion= "SELECT * FROM dotaciones";
      
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
      
      $sql_merma= "SELECT * FROM merma ";
      
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
                   
                      
                   $consumo=$tags[$i]['mermado']+$cantidad;
                 
                  
                   if($corte==$id_corte){
                    
                      $tags[$i]['mermado']=$consumo;  
                   }
                   
                                                                  
                  }
                 }
            
                  
          
      }
      }
      
      //fin mermas
   
      //ventas
      
      $sql_venta= "SELECT * FROM ventas ";
      
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
                   if($cantidad3>0){
                     $tags[$i]['cantidad']=$cantidad3;
                   }else{
                     $tags[$i]['cantidad']=0;
                   }
                    
                   $consumo=$tags[$i]['consumido']+$cantidad2;
                   if($corte==$id_corte){
                      $tags[$i]['consumido']=$consumo;  
                   }
                   
                  
           
                  }
                 }
   
   
   
            }
                  
          
      }
      }*/
      //fin insumos

echo(imprimir_corte($id_corte,1));
