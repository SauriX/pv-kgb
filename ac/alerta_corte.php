<?
	include('../includes/db.php');
	alerta_corte();
	function alerta_corte(){
		$sql = "SELECT SUM(monto_pagado) as total_ventas FROM ventas WHERE id_corte = 0";
		$q = mysql_query($sql);
		$row = mysql_fetch_array($q);
		$total_ventas = $row ['total_ventas'];
		$sql_g = "SELECT SUM(monto) as total_gasto FROM gastos WHERE id_corte = 0";
		$q_g = mysql_query($sql_g);
		$row_g = mysql_fetch_array($q_g);
		$total_gastos = $row_g ['total_gasto'];
		$ENCAJA = $total_ventas-$total_gastos;
		if(is_int(strpos($ENCAJA, "-"))){
			echo "1";//negativo
		}else{
			echo "0";//Positivo
		}
	}

?>