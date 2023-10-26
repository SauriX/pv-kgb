<?php
include('../includes/postmark.php');
include('../includes/db.php');


$A="SELECT email_notificacion FROM configuracion ";
$B = mysql_query($A);
$C = mysql_fetch_assoc($B);
$correo=$C['email_notificacion'];
$remite = "VendeFacil <bot@adminus.mx>";
$dato = '	<h4>------------- ALERTA DE CANCELACION -------------</h4>

		 		<p>MODIFICACION #1: $515.00</p>
				<h4>--------------------------------------------</h4>

		 		<p>MODIFICACION #2: $465.00</p>
				<h4>--------------------------------------------</h4>

					<p>ULTIMA MODIFICACION: $180.00</p>
					<h4>--------------------------------------------</h4>
					<p>DETALLE: <a href="#">ver</a></p>
				';
$postmark = new Postmark(null,$remite);
$postmark->to(	$correo);
$postmark->subject('Alerta de Cancelación');
$postmark->html_message($dato);
$datos = $postmark->send();
print_r($datos);


?>
