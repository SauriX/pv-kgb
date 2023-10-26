<?
include("../includes/external_db.php");
include("../includes/funciones.php");


extract($_GET);
if(!$rfc) exit("Ingrese su RFC.");

$rfc=limpiaStr($rfc,1,1);
	
	$sq="SELECT * FROM clientes WHERE rfc='$rfc'";	
	$q=mysql_query($sq,$conexion2);
	$valida=mysql_num_rows($q);
	
	if($valida>=1){
		$dat=mysql_fetch_assoc($q);
		$razon_social=$dat['razon_social'];
		$email=$dat['email'];
		echo "1|$monto|$razon_social|$email";
	}else{
		exit("2");
	}
?>