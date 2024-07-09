<?
session_start();
ignore_user_abort(1);
include('../includes/db.php');
include('../includes/impresora.php');
include('../includes/funcion_msn.php');
include('../includes/postmark.php');

/*require '../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\CommandPool;
use Aws\CommandInterface;
use Aws\Exception\AwsException;
use Guzzle\Service\Exception\CommandTransferException;

$s3Client = new S3Client([
	'region' => 'us-east-1',
	'version' => 'latest',
 'credentials' => [
		'key'    => 'AKIAIFF4UF2BCFOCP3RA',
		'secret' => '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',
	]
]);
$commands = array();
$bucket = 'vendefacil';
*/


$codigo = @$_GET["codigo"];
$efectivoCa = $_GET["efectivoCa"];
$tpvEfec = $_GET["tpvEfec"];
$otrosMet = 0;

if(!$efectivoCa) exit("Ingrese efectivo.");
# Validación de mesas abiertas.
if(!$codigo){
	$sql ="SELECT*FROM ventas WHERE id_corte = 0 AND pagada = 0";
	$q = mysql_query($sql);
	$n = mysql_num_rows($q);

	if($n>0){
		exit('MESASPENDIENTES');
	}
	# termina validación.
}


$id_usuario = $_SESSION['s_id'];
$hora = date('H:i:s');
$fecha = date('Y-m-d');

if(!$id_usuario) exit('NOSESSION');

if($_SESSION['s_tipo']!='1'){
	$sql ="SELECT cortes FROM usuarios WHERE id_usuario = $id_usuario";
	$q = mysql_query($sql);
	$cortes = @mysql_result($q, 0);

	if(!$cortes) exit('NOPERMISSION');
}

if(!mysql_query("BEGIN")) $error = true;

$sq_corte="SELECT MAX(id_corte) as id FROM cortes WHERE abierto = 1 LIMIT 1";
$q_corte=mysql_query($sq_corte);
$row = mysql_fetch_array($q_corte);
$id_corte = $row ['id'];
$id_corte_real = $id_corte;
$sql = "UPDATE cortes SET hora = '$hora',fecha = '$fecha',efectivoCaja = '$efectivoCa',tpv = '$tpvEfec',otrosMet = '0',abierto = '0' WHERE id_corte = $id_corte";
if(!mysql_query($sql)) $error = true;


$sql = "UPDATE ventas SET id_corte = $id_corte WHERE id_corte = 0";
if(!mysql_query($sql)) $error = true;

$sql = "UPDATE gastos SET id_corte = $id_corte WHERE id_corte = 0";
if(!mysql_query($sql)) $error = true;

$sql = "UPDATE dotaciones SET id_corte = $id_corte WHERE id_corte = 0";
if(!mysql_query($sql)) $error = true;

$sql = "UPDATE merma SET id_corte = $id_corte WHERE id_corte = 0";
if(!mysql_query($sql)) $error = true;
if($error){

	if(mysql_query("ROLLBACK")) exit("ROLLBACK");

}else{

	if(mysql_query("COMMIT"));

	$sql ="SELECT id_venta FROM ventas WHERE id_corte = $id_corte";

	$qx = mysql_query($sql);

	$prod = array();
	$nombres = array();
	$pu = array();


	while($fx = mysql_fetch_assoc($qx)){


		$sql = "SELECT venta_detalle.id_producto,venta_detalle.cantidad,productos.nombre,productos.precio_venta FROM venta_detalle
		JOIN productos ON productos.id_producto = venta_detalle.id_producto
		WHERE id_venta =".$fx['id_venta'];

		$q = mysql_query($sql);

		while($ft=mysql_fetch_assoc($q)){

			$prod[$ft['id_producto']]+=$ft['cantidad'];
			$nombres[$ft['id_producto']] = $ft['nombre'];
			$pu[$ft['id_producto']] = $ft['precio_venta'];

		}

	}


	$mesas_ct = 0;
	$barra_ct = 0;
	$pre_fact_ct = 0;
	$no_fact_ct = 0;

	$cancelaciones = 0;
	$cta_expedidas = 0;

	$sqlMetodos = "SELECT id_metodo,metodo_pago FROM metodo_pago";
	$qMetodos = mysql_query($sqlMetodos);
	while($data_metodos = mysql_fetch_assoc($qMetodos)){
		$me[$data_metodos['id_metodo']] = $data_metodos['metodo_pago'];
	}
	$sql = "SELECT*FROM ventas WHERE id_corte = $id_corte";
	$q = mysql_query($sql);
	while($ft = mysql_fetch_assoc($q)){

		$montos_metodo[$ft['id_metodo']]+=$ft['monto_pagado'];

		$cta_expedidas++;

		if($ft['mesa']!='BARRA'){
			$mesas_ct++;
			$mesas_monto+=$ft['monto_pagado'];
		}else{
			$barra_ct++;
			$barra_monto+=$ft['monto_pagado'];
		}

		$total_totales+=$ft['monto_pagado'];

		if($ft['reabierta']){
			$cancelaciones++;
		}

	}

		$promedio = @($total_totales/$cta_expedidas);
		$mesas_por = @($mesas_ct/$cta_expedidas)*100;
		$barra_por = @($barra_ct/$cta_expedidas)*100;


		$mesas_monto_por = @($mesas_monto/$total_totales)*100;
		$barra_monto_por = @($barra_monto/$total_totales)*100;
       $fecha_corte = $fecha." ".$hora;
	  $corte= imprimir_corte($id_corte);
	   $cliente = curl_init();
	   curl_setopt($cliente, CURLOPT_URL, "http://localhost/vendefacil_restaurante/ac/realiza_backup.php");
	   curl_setopt($cliente, CURLOPT_HEADER, 0);
	   curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true); 
	   curl_setopt($cliente, CURLOPT_TIMEOUT, 1);
	   curl_exec($cliente);
	   curl_close($cliente);
		/*	$ruta_email = "../reportes/pdfs_mail/vendefacil_corte_$id_corte.pdf";

			$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$link = str_replace('ac/corte_realizar.php','reportes/cortes_id.php',$link);
			$link.= "&id_corte=$id_corte";
			$get = file_get_contents($link);

				$sql = "SELECT email_notificacion,bkp_alias FROM configuracion";
				$data = mysql_fetch_assoc(mysql_query($sql));
				$correo = $data['email_notificacion'];
				$alias = $data['bkp_alias'];

				if(  ($correo) && (is_connected())  ){

				$nombre_enviar = $alias."_corte_".$id_corte."_".mt_rand(1000,9999).".pdf";

				$commands[] = $s3Client->getCommand('PutObject', array(
		            'Bucket' => $bucket,
		            'Key'    => "$alias/$nombre_enviar",
		            'Body' => file_get_contents($ruta_email),
		            'ACL' => 'public-read'
		        ));

		        $pool = new CommandPool($s3Client, $commands);
		        $promise = $pool->promise();
		        $result = $promise->wait();
				$amazon_url = "http://vendefacil.s3-accelerate.amazonaws.com/$alias/".$nombre_enviar;
				$adjuntos["corte_$id_corte.pdf"] = $amazon_url;
				$remite = "VendeFacil Bot <bot@adminus.mx>";
			    $dato = "Se adjunta el reporte en PDF del corte número #$id_corte.";

			    $postmark = new Postmark(null,$remite);
			    $postmark->to($correo);
			    $postmark->subject("Corte realizado #$id_corte");
			    $postmark->html_message($dato);
		        $postmark->adjunta_vato($adjuntos);
			    $postmark->send();

				}*/

				echo $corte;


}



if($codigo =='d'){
	$sq_corte="SELECT MAX(id_corte) as id FROM cortes WHERE abierto = 1";
	$q_corte=mysql_query($sq_corte);
	$row = mysql_fetch_array($q_corte);
	$v_corte = $row ['id'];

	if(!mysql_query("BEGIN")) $error = true;

	$sql_v = "UPDATE ventas SET id_corte = 0 WHERE id_corte = $v_corte";
	if(!mysql_query($sql_v)) $error = true;

	$sql_g = "UPDATE gastos SET id_corte = 0 WHERE id_corte = $v_corte";
	if(!mysql_query($sql_g)) $error = true;
		if($error){
			if(mysql_query("ROLLBACK")) exit("ROLLBACK");
		}else{
			if(mysql_query("COMMIT")){
				$sql_delete = "DELETE FROM cortes WHERE id_corte = $v_corte";
				$q = mysql_query($sql_delete);
			}
		}
	if($q){
		echo $corte; // Valicacion de exitoso
	}else{
		echo '0';// error de Valicacion
	}
}
