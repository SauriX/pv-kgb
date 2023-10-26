<?
    include("includes/db.php");
    $reporte = array();
    function reporte($fecha,$fecha_anterior){
        $productos = array();
        //llenamos el array de productos

        $sql_pro= "SELECT * FROM productos_base
        LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad";

        $q_pro = mysql_query($sql_pro);

        while($pro= mysql_fetch_assoc($q_pro)){

            $id_base=$pro['id_base'];
            $producto=$pro['producto'];
            $productos[] = array(
                "id"=> $id_base,
                "producto" => $producto,
                "cantidad" => 0,
                "consumo_total"=>0,
                "consumo_dia"=>0,
                "dotacion_total"=>0,
                "dotacion_dia"=>0,
                "merma_total"=>0,
                "merma_dia"=>0,
                
            );
        }
        //traemos todas las mermas de la fecha y anteriores
        $sqlm="SELECT * FROM merma  WHERE fecha > '$fecha_anterior' AND fecha <= '$fecha' AND contar = 1";
        $qm=mysql_query($sqlm);
        while($merma = mysql_fetch_assoc($qm)){    
            $id_merma= $merma['id_merma'];
            $corte=$merma['id_corte'];
            $fecha_p= strtotime($merma['fecha']);
            $sql_detallem="SELECT SUM(cantidad) as cantidad,id_producto,producto,abreviatura  FROM merma_detalle
            LEFT JOIN productos_base ON  merma_detalle.id_producto = productos_base.id_base
            LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad 
            WHERE id_merma = $id_merma GROUP BY id_producto";
            $detallem_q=mysql_query($sql_detallem);
            while($ft2=mysql_fetch_assoc($detallem_q)){
                $id_base=$ft2['id_producto'];
                $cantidad=$ft2['cantidad'];
                $producto=$ft2['producto'];
                for($i=0; $i<count($productos); $i++){
                    if($productos[$i]['id']==$id_base){
                        $index=$i;
                        $productos[$i]['cantidad']-=($cantidad);
                        if(strtotime($fecha) == $fecha_p){
                            $productos[$i]['merma_dia']+=($cantidad);
                        }                                               
                    }
                }   
            }
        }
        //fin mermas
        //traemos todas las dotaciones de la fecha y anteriores
        $ignorar=0;
        $sqld="SELECT * FROM dotaciones WHERE fecha > '$fecha_anterior' AND fecha <= '$fecha' AND contar = 1";
        $qd=mysql_query($sqld);
        while($dota = mysql_fetch_assoc($qd)){    
            $id_dota= $dota['id_dotacion'];
            $corte=$dota['id_corte'];
            $fecha_p= strtotime($dota['fecha']);           
            $sql_detallem="SELECT SUM(cantidad) as cantidad,id_producto,producto,abreviatura  FROM dotaciones_detalle
            LEFT JOIN productos_base ON  dotaciones_detalle.id_producto = productos_base.id_base
            LEFT JOIN unidades ON productos_base.id_unidad = unidades.id_unidad 
            WHERE id_dotacion = $id_dota GROUP BY id_producto";
            $detallem_q=mysql_query($sql_detallem);
            while($ft2=mysql_fetch_assoc($detallem_q)){
                echo $ignorar;
                $ignorar = $id_dota;
                $id_base=$ft2['id_producto'];
                $cantidad=$ft2['cantidad'];
                $producto=$ft2['producto'];
                for($i=0; $i<count($productos); $i++){
                    if($productos[$i]['id']==$id_base){
                        $index=$i;
                        $productos[$i]['cantidad']+=($cantidad);
                        if(strtotime($fecha) == $fecha_p){
                            $productos[$i]['dotacion_dia']+=($cantidad);
                        }                                               
                    }
                }   
            }
        }
        //fin dotaciones
                
       return $sqld;
    }
    $sql = "SELECT * FROM cortes WHERE fecha BETWEEN '2020-11-04' AND '2020-12-08'GROUP BY fecha;";
    $query= mysql_query($sql);
    $fecha_anterior = "0000-00-00";
    while($cortes=mysql_fetch_object($query)){
       $reporte[]=reporte($cortes->fecha,$fecha_anterior);
       $fecha_anterior = $cortes->fecha;
    }
    
   echo json_encode($reporte);

?>