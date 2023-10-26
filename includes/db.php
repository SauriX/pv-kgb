<?php
error_reporting(0);

/*$servidor="localhost";
$usuario="root";
$clave="root";
$base="vendefacil_restaurante";*/

$servidor="localhost";
$usuario="root";
$clave="root";

    $base="vendefacil_5";




/* $servidor="vendefacil.mx";
$usuario="vendefac_diego";
$clave="camacho";
$base="vendefac_redcubo"; */
/*
JODETE HERMOSO!!!!


*/
/*$servidor = "epicmedia.cluster-cfh0agjg2td3.us-east-2.rds.amazonaws.com";
$usuario = "epicmedia";
$clave = "epicmedia";
$base = "vendefacil_restaurante";*/


$conexion = @mysql_connect ($servidor,$usuario,$clave) or die ("Error en conexi&oacute;n.");
@mysql_select_db($base) or die ("No BD");
?>
