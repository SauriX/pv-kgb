<?
include("../includes/db.php");
include("../includes/funciones.php");

//Método de pago y etc
$id_corte= 6;
	$sqlMetodos = "SELECT id_metodo,metodo_pago FROM metodo_pago";
	$qMetodos = mysql_query($sqlMetodos);
	while($data_metodos = mysql_fetch_assoc($qMetodos)){
		$me[$data_metodos['id_metodo']] = $data_metodos['metodo_pago'];
	}

	$sql = "SELECT * FROM ventas WHERE id_corte = $id_corte";
	$q = mysql_query($sql);
	while($ft = mysql_fetch_assoc($q)){

 		$montos_metodo[$ft['id_metodo']]+=$ft['monto_pagado'];

	}
	foreach($montos_metodo as $id_m => $monto){
		echo "metodo:".$me[$id_m].' - Monto: '.$monto."<br>";
	}
