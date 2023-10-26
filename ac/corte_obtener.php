<?

include('../includes/db.php');
include('../includes/funciones.php');

/*
$sql = "SELECT * FROM ventas WHERE id_corte = 0";
$q = mysql_query($sql);

$corte_comidas = array();
$cuantas_ventas = array();
$corte_prestamos_otorgados = array();
$monto_ventas = 0;
$corte_comidas_real = 0;
$corte_comidas_monto = 0;
$corte_prestamos_otorgados_monto = 0;
$corte_prestamos_pagados_monto = 0;
$cartera_vencida = 0;
$deuda_total = 0;
$abonado_total = 0;

while($ft = mysql_fetch_assoc($q)){

	$sql ="SELECT
	venta_detalle.id_venta,
	venta_detalle.id_producto,
	venta_detalle.cantidad,
	venta_detalle.precio_venta,
	categorias.id_categoria
	FROM venta_detalle
	JOIN productos ON venta_detalle.id_producto = productos.id_producto
	JOIN categorias ON categorias.id_categoria = productos.id_categoria WHERE venta_detalle.id_venta = ".$ft['id_venta'];

	$qq = mysql_query($sql);

	while($fx = mysql_fetch_assoc($qq)){
			$cuantas_ventas[$fx['id_venta']] = 1;
			$monto_ventas+= $fx['cantidad']*$fx['precio_venta'];
	}
}

$cuantas_ventas = count($cuantas_ventas);

*/

$sql_pro ="SELECT  * from gastos where provision = 1 and id_corte = 0";
$q_pro=mysql_query($sql_pro);
$n_pro=mysql_num_rows($q_pro);
if($n_pro>0){
	exit('PROVICIONES');
}

$sql ="SELECT*FROM ventas WHERE id_corte = 0 AND pagada = 0";
$q = mysql_query($sql);
$n = mysql_num_rows($q);

if($n>0){

	exit('NOCORTE');

}else{

	$sql = "SELECT*FROM ventas WHERE id_corte = 0";

	$q = mysql_query($sql);
	$a = mysql_num_rows($q);
	if(!$a){
		
		
		exit('NOVENTAS');
	}

}
