<?php

include ( "NexmoMessage.php" );
include ( "db.php" );
function  envio_mensaje($fecha_hora_ticket,$g_total,$efectivo,$tarjeta,$cheque,$transf,$noide,$g_total_gastos,$ENCAJA){

 global $conexion; 
	$salto = " ";

	$mensaje= "Taco Loco: ".$salto;
	$mensaje.=$fecha_hora_ticket.$salto;
	$mensaje.="VENTA TOTAL:".$g_total.$salto;
	$mensaje.="-EFECTIVO:".$efectivo.$salto;
	$mensaje.="-TARJETA: ".$tarjeta.$salto;
	$mensaje.="-".$salto;
	$mensaje.="GASTOS:".$g_total_gastos.$salto;
	$mensaje.="-".$salto;
	$mensaje.="EN CAJA:".$ENCAJA.$salto;
	$mensaje.="EN BANCO:".$tarjeta;
	
	// Step 1: Declare new NexmoMessage.
	$nexmo_sms = new NexmoMessage('e9668f0d', '8259f5531b5af63f');
	
	$sql_msn="SELECT * FROM msn  WHERE activo = 1 ";
	$q_msn=mysql_query($sql_msn,$conexion);
	$valida_msn=mysql_num_rows($q_msn);
	
		while($fila = mysql_fetch_assoc($q_msn)){
			$numero_completo = "+52".$fila['numero'];

			$info = $nexmo_sms->sendText( $numero_completo, 'MyApp', $mensaje );

		}
	// Step 2: Use sendText( $to, $from, $message ) method to send a message. 
	
	// Step 3: Display an overview of the message
	//echo $nexmo_sms->displayOverview($info);

	// Done!	
}


function envio_adm($random){
	//envio de mensaje al adm
	//enviado desde msn corte
	// sacar el numero del adm

	@file_get_contents('https://adminus.mx/app/sendgrid/taco.php?codigo='.$random);
	return true;

}
