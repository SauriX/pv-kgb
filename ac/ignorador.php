<?php
    include('../includes/session.php');
    include('../includes/db.php');
    include('../includes/funciones.php');
    error_reporting(0);
    extract($_POST);
    $sql="UPDATE productos SET ignorar = $ignorado WHERE productos.id_producto=$producto";
    $q=mysql_query($sql);
    if($q){
        echo(1);
    }else{
        echo("Ocurrio un error intentar mas tarde");
    };
?>