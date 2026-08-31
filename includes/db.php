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
$clave="root";
$base="vendefacil_5";

/* ===== CONEXION COMPATIBLE (PHP 5/7/8) ===== */

if (function_exists('mysql_connect')) {
    // PHP 5 con extension mysql nativa.
    $conexion = mysql_connect($servidor, $usuario, $clave);
    if (!$conexion) {
        die("Error conexion: " . mysql_error());
    }
    if (!mysql_select_db($base, $conexion)) {
        die("Error base de datos: " . mysql_error($conexion));
    }
} else {
    // PHP 7+ (o PHP 5 sin mysql): usar mysqli y wrappers mysql_*
    $conexion = mysqli_connect($servidor, $usuario, $clave, $base);

    if (!$conexion) {
        die("Error conexion: " . mysqli_connect_error());
    }

    if (!function_exists('mysql_query')) {
        function mysql_query($query, $link_identifier = null){
            global $conexion;
            $conn = $link_identifier ? $link_identifier : $conexion;
            return mysqli_query($conn, $query);
        }
    }

    if (!function_exists('mysql_fetch_assoc')) {
        function mysql_fetch_assoc($result){
            return mysqli_fetch_assoc($result);
        }
    }

    if (!function_exists('mysql_num_rows')) {
        function mysql_num_rows($result){
            return mysqli_num_rows($result);
        }
    }

    if (!function_exists('mysql_real_escape_string')) {
        function mysql_real_escape_string($string, $link_identifier = null){
            global $conexion;
            $conn = $link_identifier ? $link_identifier : $conexion;
            return mysqli_real_escape_string($conn, $string);
        }
    }

    if (!function_exists('mysql_fetch_object')) {
        function mysql_fetch_object($result){
            return mysqli_fetch_object($result);
        }
    }

    if (!function_exists('mysql_insert_id')) {
        function mysql_insert_id($link_identifier = null){
            global $conexion;
            $conn = $link_identifier ? $link_identifier : $conexion;
            return mysqli_insert_id($conn);
        }
    }

    if (!function_exists('mysql_affected_rows')) {
        function mysql_affected_rows($link_identifier = null){
            global $conexion;
            $conn = $link_identifier ? $link_identifier : $conexion;
            return mysqli_affected_rows($conn);
        }
    }

    if (!function_exists('mysql_result')) {
        function mysql_result($result, $row = 0, $field = 0){
            if (!$result) {
                return null;
            }
            $result->data_seek($row);
            $data = $result->fetch_array();
            return isset($data[$field]) ? $data[$field] : null;
        }
    }

    if (!function_exists('mysql_fetch_array')) {
        function mysql_fetch_array($result, $result_type = null){
            if ($result_type === null) {
                $result_type = defined('MYSQLI_BOTH') ? MYSQLI_BOTH : 3;
            }
            return mysqli_fetch_array($result, $result_type);
        }
    }

    if (!function_exists('mysql_error')) {
        function mysql_error($link_identifier = null){
            global $conexion;
            $conn = $link_identifier ? $link_identifier : $conexion;
            return mysqli_error($conn);
        }
    }
}
?>