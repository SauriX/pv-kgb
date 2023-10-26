<?php
    include('../includes/session.php');
    include('../includes/db.php');
    include('../includes/funciones.php');
    error_reporting(0);
    extract($_POST);
    $sql="DELETE FROM productos WHERE id_producto=$id";
    $sql2="DELETE FROM productos_paquete WHERE id_producto=$id";
    $sql3="DELETE FROM productosxbase WHERE id_producto=$id";
    $sql4="DELETE FROM producto_extra WHERE id_producto=$id";

    $q=mysql_query($sql);
    $q2=mysql_query($sql2);
    $q3=mysql_query($sql3);
    $q4=mysql_query($sql4);

    echo(1);