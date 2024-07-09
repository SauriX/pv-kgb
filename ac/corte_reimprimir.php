<?php

	include('../includes/session.php');	
	include('../includes/db.php');
	include('../includes/impresora.php');

	
	extract($_POST);
	
	$sql ="SELECT*FROM cortes WHERE id_corte = $id_corte";

	$q = mysql_query($sql);
	$n = mysql_num_rows($q);
	$datos_corte = mysql_fetch_assoc($q);
	$fecha_corte = $datos_corte['fecha']." ".$datos_corte['hora'];
	$efectivoCa = $datos_corte['efectivoCaja'];
	$tpvEfec = $datos_corte['tpv'];
	if(!$n){
		exit('NOEXISTE');
	}
	

	
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

		if($ft['facturado']){
			$pre_fact_ct++;
			$pre_fact_monto+=$ft['monto_facturado'];
		}else{
			$no_fact_ct++;
			$no_fact_monto+=$ft['monto_pagado'];
		}

		if($ft['reabierta']){
			$cancelaciones++;
		}

	}

		$promedio = @($total_totales/$cta_expedidas);
		$mesas_por = @($mesas_ct/$cta_expedidas)*100;
		$barra_por = @($barra_ct/$cta_expedidas)*100;
		$pre_fact_por = @($pre_fact_ct/$cta_expedidas)*100;
		$no_fact_por = @($no_fact_ct/$cta_expedidas)*100;

		$pre_fact_monto_por = @($pre_fact_monto/$total_totales)*100;
		$no_fact_monto_por = @($no_fact_monto/$total_totales)*100;

		$mesas_monto_por = @($mesas_monto/$total_totales)*100;
		$barra_monto_por = @($barra_monto/$total_totales)*100;
		$var.=imprimir_corte($id_corte,1);
			echo $var;





		