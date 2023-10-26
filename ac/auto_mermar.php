<?php
    include("../includes/db.php");
    extract($_POST);
    $id_usuario = $s_id_usuario;
    $fecha = date('Y-m-d');
    $respuesta = array(
        "respuesta" => null,
        "productos" => "<table class='table'>
        <tr>
            <td>Producto</td>

            <td style='text-align:right'>Cantidad</td>
        </tr>"
    );
           //insumos
           $tags = array();
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
                   "cantidad" => 0
               );
           }
       
    $sql = "INSERT INTO merma(id_usuario,fecha,comentario)VALUES('$id_usuario','$fecha','merma automatica')";
    $q = mysql_query($sql);
    if($q){
        $id_venta = mysql_insert_id(); 
        foreach($dotados as $producto){
            $id_producto = $producto["id_producto"];
            $cantidad = $producto["cantidad"];
            $sql="INSERT INTO merma_detalle (id_merma,id_producto,cantidad)VALUES('$id_venta','$id_producto','$cantidad')";
            $query = mysql_query($sql);
            if($query){
                for($i=0; $i<count($tags); $i++){
                    if($tags[$i]['id']==$id_producto){
                        $tags[$i]['cantidad']+=$cantidad;                                                   
                    }
                } 
                $respuesta["respuesta"]=true;
            }else{
                $respuesta["respuesta"]=false;
                break;
            }

        }
        for($i=0; $i<count($tags); $i++){
            $respuesta["productos"].="<tr>";
            if($tags[$i]['cantidad']>0){
                $respuesta["productos"].="<td>".$tags[$i]['producto']."</td>";
   
                $respuesta["productos"].="<td style='text-align:right'>"."   ".$tags[$i]['cantidad']."</td>";
            }
            $respuesta["productos"].="</tr>";
        } 
        $respuesta["productos"].="</table>";
    }else{
        
        $respuesta["respuesta"]=false;
    }

    echo json_encode($respuesta);
    

?>