<?php
error_reporting(0);

$servidor="db5020093809.hosting-data.io";
$usuario="dbu3763371";
$clave="vendefacil_5";
$base="dbs15483147";

$conexion = mysqli_connect($servidor,$usuario,$clave,$base);

if(!$conexion){
    die("Error conexión: " . mysqli_connect_error());
}

/* ===== COMPATIBILIDAD MYSQL -> MYSQLI ===== */

function mysql_query($query){
    global $conexion;
    return mysqli_query($conexion, $query);
}

function mysql_fetch_assoc($result){
    return mysqli_fetch_assoc($result);
}

function mysql_num_rows($result){
    return mysqli_num_rows($result);
}

function mysql_real_escape_string($string){
    global $conexion;
    return mysqli_real_escape_string($conexion, $string);
}
function mysql_fetch_object($result){
    return mysqli_fetch_object($result);
}
?>