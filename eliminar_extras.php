<?
include("includes/session_ui.php");
include("includes/db.php");
include("includes/funciones.php");



$sql2="SELECT * FROM clientes ";
$q2 = mysql_query($sql2);
$ft=mysql_fetch_assoc($q2);
echo($ft['nombre']);



$sql2="SELECT * FROM productos";
$q2 = mysql_query($sql2,$conexion);
$ft2=mysql_fetch_assoc($q2);
echo($ft2['nombre']);



$sql = "INSERT INTO ventas (id_usuario,fecha,hora,mesa)VALUES('2','$fecha','$hora','90')";
$q = mysql_query($sql,$conexion);
echo(mysql_insert_Id($conexion));