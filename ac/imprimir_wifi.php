<?php
include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');

if (isset($_POST['confirmar'])) {
	$password_wifi = isset($_POST['password']) ? $_POST['password'] : '';
	if ($password_wifi == '') {
		exit('Error, contraseña inválida');
	}
	$password_wifi = mysql_real_escape_string($password_wifi);
	$q = mysql_query("UPDATE wifi_passwords SET usado = 1 WHERE password = '$password_wifi' AND usado = 0 LIMIT 1");
	exit($q && mysql_affected_rows() == 1 ? '1' : 'Error, contraseña no disponible');
}

$sql = "SELECT password FROM wifi_passwords WHERE usado = 0 LIMIT 1";
$q = mysql_query($sql);
$password_wifi = @mysql_result($q, 0);

if($password_wifi){
	header('X-PV-Wifi: '.rawurlencode($password_wifi));
	echo '1';

}else{
	echo 'Error, no hay contraseñas';
}
