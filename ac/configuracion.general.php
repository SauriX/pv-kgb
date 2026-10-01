<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$establecimiento) exit("Debe especificar un nombre para el establecimiento.");
if(!$representante) exit("Debe especificar el nombre del representante.");
if(!$telefono) exit("Debe escribir un teléfono.");
if(!$direccion) exit("Debe escribir una dirección.");

$autobackup = (isset($autobackup) && $autobackup == "on") ? "1" : "0";
$comandaim = (isset($comandaim) && $comandaim == "on") ? "1" : "0";
$autocash = (isset($autocash) && $autocash == "on") ? "1" : "0";
$paquete = (isset($paquete) && $paquete == "on") ? "1" : "0";

//Formateamos y validamos los valores
$establecimiento=limpiaStr($establecimiento,1,1);
$representante=limpiaStr($representante,1,1);
$direccion=limpiaStr($direccion,1,1);
$rfc = isset($rfc) ? $rfc : '';
$impresora_sd=limpiaStr(isset($impresora_sd) ? $impresora_sd : '',1,1);
$impresora_sd_para_llevar=limpiaStr(isset($impresora_sd_para_llevar) ? $impresora_sd_para_llevar : '',1,1);
$impresora_cuentas=limpiaStr(isset($impresora_cuentas) ? $impresora_cuentas : '',1,1);
$impresora_cuentas_para_llevar=limpiaStr(isset($impresora_cuentas_para_llevar) ? $impresora_cuentas_para_llevar : '',1,1);
$impresora_cobros=limpiaStr(isset($impresora_cobros) ? $impresora_cobros : '',1,1);
$impresora_cobros_para_llevar=limpiaStr(isset($impresora_cobros_para_llevar) ? $impresora_cobros_para_llevar : '',1,1);
$impresora_cortes=limpiaStr(isset($impresora_cortes) ? $impresora_cortes : '',1,1);
$impresora_cortes_para_llevar=limpiaStr(isset($impresora_cortes_para_llevar) ? $impresora_cortes_para_llevar : '',1,1);
$serverip=isset($serverip) ? limpiaStr($serverip,1,1) : '';
$serverip=preg_replace('/[^0-9a-zA-Z\.\-:_]/', '', $serverip);

$col = mysql_query("SHOW COLUMNS FROM configuracion LIKE 'serverip'");
if ($col && mysql_num_rows($col) == 0) {
	mysql_query("ALTER TABLE configuracion ADD COLUMN serverip VARCHAR(64) NOT NULL DEFAULT ''");
}

	//Insertamos datos
	$sql="UPDATE configuracion SET auto_cobro='$autocash' ,establecimiento='$establecimiento', representante='$representante', rfc='$rfc', telefono='$telefono', direccion='$direccion', comandain='$comandaim', autobackup='$autobackup',impresora_sd='$impresora_sd',impresora_sd_para_llevar='$impresora_sd_para_llevar',impresora_cuentas='$impresora_cuentas',impresora_cuentas_para_llevar='$impresora_cuentas_para_llevar',impresora_cobros='$impresora_cobros',impresora_cobros_para_llevar='$impresora_cobros_para_llevar',impresora_cortes='$impresora_cortes',impresora_cortes_para_llevar='$impresora_cortes_para_llevar',serverip='$serverip',paquetes='$paquete'";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>