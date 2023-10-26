<?php
    include("includes/db.php");

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
    $sql_ventas="SELECT * FROM ventas WHERE id_corte= 403";
    $query_ventas = mysql_query($sql_ventas);
    while($venta = mysql_fetch_object($query_ventas)){
        $venta_id= $venta->id_venta;
        $venta_mesa = $venta->mesa;
       echo('<h2 style="text-align:center;">'.$venta_mesa.': '.$venta_id.'</h2>');
       $sql_venta_detalle="SELECT * FROM venta_detalle
        JOIN productos on productos.id_producto = venta_detalle.id_producto
        WHERE venta_detalle.id_venta= $venta_id AND venta_detalle.id_venta !=0";
       $query_venta_detalle = mysql_query($sql_venta_detalle);
       while($venta_detalle=mysql_fetch_object($query_venta_detalle)){
           $nombre_producto= $venta_detalle->nombre;
           $id_producto= $venta_detalle->id_producto;
           $cantidad = $venta_detalle->cantidad;
           echo('<p style="text-align:center;">'.$nombre_producto.' cantidad: '.$cantidad.'<p>');
           echo('<br>');
           echo('<table class="default" style="margin-left:40%">');
           echo('<tr>');
           echo('<td width="40%">Ingrediente</td>');
           echo('<td width="40%">Cantidad por producto</td>');
           echo('<td width="25%">Total por venta</td>');
           echo('</tr>');
           $sql_ingredientes="SELECT * FROM productos_base
           JOIN productosxbase ON productos_base.id_base = productosxbase.id_base
           WHERE productosxbase.id_producto=$id_producto
           ";
            $query_ingredientes=mysql_query($sql_ingredientes);
            while($ingrediente=mysql_fetch_object($query_ingredientes)){
                $producto_ingrediente = $ingrediente->producto;
                $cantidad_x_piesa = $ingrediente->cantidad;
                $total = $cantidad_x_piesa * $cantidad;
                $id_base = $ingrediente->id_base;
                echo('<tr>');
                echo('<td>'.$producto_ingrediente.'</td>');
                echo('<td>'.$cantidad_x_piesa.'</td>');
                echo('<td>'.$total.'</td>');
                echo('</tr>');
                for($i=0; $i<count($tags); $i++){
                    if($tags[$i]['id']==$id_base){
                        $index=$i;

                        $tags[$i]['consumo_dia']+=$total;	
                        
                    }
                }
            }
            echo('</table>');
        }
    }
    
    for($i=0; $i<count($tags); $i++){
        echo($tags[$i]['producto'].': ');
        echo($tags[$i]['consumo_dia']);
        echo("<br>");
    }
    
   
?>