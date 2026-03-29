<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
/* $servidor="db5020093809.hosting-data.io";
$usuario="dbu3763371";
$clave="vendefacil_5";
$base="dbs15483147"; */

$servidor="localhost";
$usuario="root";
$clave="";
$base="vendefacil_5";

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

function mysql_insert_id(){
    global $conexion;
    return mysqli_insert_id($conexion);
}

function mysql_result($result, $row = 0, $field = 0){
    $result->data_seek($row);
    $data = $result->fetch_array();
    return $data[$field];
}
function mysql_fetch_array($result){
    return mysqli_fetch_array($result);
}

function mysql_error(){
    global $conexion;
    return mysqli_error($conexion);
}

if(!function_exists('mysql_result')){
    function mysql_result($result, $row = 0, $field = 0){
        if(!$result) return null;
        $data = mysqli_fetch_array($result);
        return $data[$field] ?? null;
    }
}
?>