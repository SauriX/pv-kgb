<?

include("includes/db.php");
        //insumos
        function burbuja($array)
        {
            for($i=1;$i<count($array);$i++)
            {
                for($j=0;$j<count($array)-$i;$j++)
                {
                    if($array[$j]['id']>$array[$j+1]['id'])
                    {
                        $k=$array[$j+1];
                        $array[$j+1]=$array[$j];
                        $array[$j]=$k;
                    }
                }
            }
         
            return $array;
        }
         
        $id_corte =  404;
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
                       "previa"=>0,
                       "consumo_dia"=>0,
                       "mermado_dia"=>0,
                       "dotado_dia"=>0
                   );
               }
                $tags = burbuja($tags);
               //Termina de generar la lista de ingredientes
           
               $sql_existencia="SELECT * FROM existencia WHERE id = $id_corte AND contar = 1";
               $q_existencia=mysql_query($sql_existencia);
              
               $existencias = mysql_fetch_assoc($q_existencia);
               $existencia = json_decode($existencias['texto'],true);
         
               $existencia = burbuja($existencia);
               for($i=0; $i<count($existencia); $i++){
                   if($tags[$i]['id']==$existencia[$i]['id']){
                       
                       $tags[$i]['dotado_dia']+=$existencia[$i]['cantidad'];
                       
                                                                       
                   }
               } 
             
           
                   //Optiene la dotaciones de productos y los añade a existencias
                   $sql_dotacion= "SELECT * FROM dotaciones WHERE id_corte=$id_corte";
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
                          
                            for($i=0; $i<count($tags); $i++){
                                    
                                if($tags[$i]['id']==$id_base){
                                    $index=$i;
                                    $tags[$i]['dotado_dia']+=$cantidad;
                                }
                            }
                            
                       }
                    }
                   // fin de la dotaciones
           
               //mermas
               $sql_merma= "SELECT * FROM merma  WHERE id_corte=$id_corte";
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
                       for($i=0; $i<count($tags); $i++){
                           if($tags[$i]['id']==$id_base){
                               $index=$i;
                               $tags[$i]['mermado_dia']+=$cantidad;                                                                
                           }
                       } 
                   }
               }
               $ignorar =0;
               //ventas
               $sql_venta= "SELECT * FROM ventas WHERE  id_corte=$id_corte ";
                   $q_venta= mysql_query($sql_venta);
                   while($venta= mysql_fetch_assoc($q_venta)){
                       $id_venta= $venta['id_venta'];
                       $corte = $venta['id_corte'];
                       $sql_detallev="SELECT * FROM  venta_detalle WHERE  id_venta =$id_venta AND id_producto!=0";
                       $q_ventade= mysql_query($sql_detallev);
                       while($ft3=mysql_fetch_assoc($q_ventade)){
                        $precio=$ft3['precio_venta'];

                            if($precio !=0){
                               $ignorar=0;
                           }
                           $id_base=$ft3['id_producto'];
                           $pro = "SELECT * FROM  productos WHERE   id_producto=$id_base";
                           $q_pros= mysql_query($pro);
                           $ft6=mysql_fetch_assoc($q_pros);
                           if($ignorar==0){
                            $id_ignorar = $ft6['ignorar'];
                           }
                           $ignorar =$id_ignorar;    
                           $cantidad=$ft3['cantidad'];
                           $sql_producto="SELECT  * FROM  productosxbase WHERE  id_producto=$id_base";
                           $producto_q=mysql_query($sql_producto);
                           
                           echo("</br>");
                           echo "ignorado".$ignorar;
                           echo "id".$id_base;
                           
                           while($ft4=mysql_fetch_assoc($producto_q)){
                               $id_base=$ft4['id_base'];
                               $cantidad2=$ft4['cantidad']*$cantidad;
                               
                               /* echo($ignorar."p") */;
                               
                                   /* S */
                                   if($ignorar!=$id_base){
                                for($i=0; $i<count($tags); $i++){
                                    if($tags[$i]['id']==$id_base){
                                        $index=$i;
            
                                        $tags[$i]['consumo_dia']+=$cantidad2;	
                                        
                                    }
                                }
                                
                           }
                        }
                          
                       }
                   }
           
               //fin mermas
               for($i=0; $i<count($tags); $i++){
           
                  $gasto=$tags[$i]['consumo_dia']+$tags[$i]['mermado_dia'];
                  $existencia = $tags[$i]['dotado_dia'];
                  $restante = $existencia - $gasto;
                  $tags[$i]['cantidad'] = $restante;
                   
               }
           
               $id_corte_siguiente= $id_corte +1;
               $sql_validad_exitencia = "SELECT *  FROM existencia where id = $id_corte_siguiente";
               $q_validad_exitencia = mysql_query($sql_validad_exitencia);
               $datos= mysql_num_rows($q_validad_exitencia);
               if(!($datos>0)){
                   $json = json_encode($tags);
                   $sql_nueva_existencia = "INSERT INTO existencia (id,texto) values($id_corte_siguiente,'$json')";
                   $q = mysql_query($sql_nueva_existencia);
               }
       

echo json_encode($tags);