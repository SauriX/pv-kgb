<?php
header('Access-Control-Allow-Origin: *');
    include("includes/db.php");
    extract($_GET);
    $respuesta= array(
        "respuesta"=> null,
        'dotados'=>array()
    );
    
    $dotacion="SELECT * FROM dotaciones WHERE dotado = 0 AND id_local = $id";
    $query=mysql_query($dotacion);
    while($resultado = mysql_fetch_assoc($query)){
        $id = $resultado["id_dotacion"];
        $detalle_de_la_dotacion = "SELECT * FROM dotaciones_detalle WHERE id_dotacion = $id";
        $query_detalle=mysql_query($detalle_de_la_dotacion);
        while($resultado = mysql_fetch_object($query_detalle)){
            array_push($respuesta["dotados"],$resultado);
        }
        $sql="UPDATE dotaciones SET dotado = 1 WHERE id_dotacion = $id";
        $q = mysql_query($sql);
    }

    if(count($respuesta["dotados"])>0){
        $respuesta["respuesta"]=true;
    }else{
        $respuesta["respuesta"]=false;
    }
    echo json_encode($respuesta);
?>