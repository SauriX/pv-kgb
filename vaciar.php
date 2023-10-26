<?php
header('Access-Control-Allow-Origin: *');
    include("includes/db.php");
    extract($_GET);
    $respuesta= array(
        "respuesta"=> null,
        'dotados'=>array()
    );
    
    $dotacion="SELECT * FROM vaciar WHERE id_local = $id AND bandera=1";
    $query=mysql_query($dotacion);
    $n= mysql_num_rows($query);
    if($n>0){
        $dotacion="UPDATE vaciar SET id_local = 0 , bandera=0";
        $query=mysql_query($dotacion);
        $respuesta["respuesta"]=true;
    }else{
        $respuesta["respuesta"]=false;
    }
    echo json_encode($respuesta);
?>