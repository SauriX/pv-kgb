<?php 
error_reporting(0);
include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');
include('../includes/impresora.php');


$sql = "SELECT password FROM wifi_passwords WHERE usado = 0 LIMIT 1";
$q = mysql_query($sql);
$password_wifi = @mysql_result($q, 0);

if($password_wifi){
	imprimir_wifi($password_wifi);	
	$sql = "UPDATE wifi_passwords SET usado = 1 WHERE password = '$password_wifi'";
	$q = mysql_query($sql);
	echo '1';

}else{
	echo 'Error, no hay contraseñas';
}
