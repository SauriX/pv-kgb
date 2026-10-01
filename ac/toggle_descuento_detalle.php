<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

header('Content-Type: application/json; charset=utf-8');

$id_detalle = isset($_POST['id_detalle']) ? intval($_POST['id_detalle']) : 0;
if(!$id_detalle){
	echo json_encode(array('ok' => false, 'error' => 'Detalle invalido.'));
	exit;
}

$sql = "SELECT venta_detalle.*, productos.precio_venta AS precio_catalogo, ventas.abierta, ventas.pagada
	FROM venta_detalle
	JOIN productos ON productos.id_producto = venta_detalle.id_producto
	JOIN ventas ON ventas.id_venta = venta_detalle.id_venta
	WHERE venta_detalle.id_detalle = $id_detalle
	LIMIT 1";
$q = mysql_query($sql);
if(!$q || !mysql_num_rows($q)){
	echo json_encode(array('ok' => false, 'error' => 'Producto no encontrado.'));
	exit;
}

$row = mysql_fetch_assoc($q);
if(intval($row['abierta']) !== 1 || intval($row['pagada']) !== 0){
	echo json_encode(array('ok' => false, 'error' => 'Solo se puede modificar una mesa abierta.'));
	exit;
}

$comentarios = isset($row['comentarios']) ? $row['comentarios'] : '';
$tiene_descuento = strpos($comentarios, '[[DESC100]]') !== false;
$id_venta = intval($row['id_venta']);
$cantidad = floatval($row['cantidad']);

if($tiene_descuento){
	$precio_restaurar = floatval($row['precio_catalogo']);
	if(preg_match('/\[\[PRECIO_ORIG:([0-9.]+)\]\]/', $comentarios, $matches)){
		$precio_restaurar = floatval($matches[1]);
	}
	$comentarios = preg_replace('/\n?\[\[DESC100\]\]/', '', $comentarios);
	$comentarios = preg_replace('/\n?\[\[PRECIO_ORIG:[0-9.]+\]\]/', '', $comentarios);
	$comentarios = trim($comentarios);
	$nuevo_precio = number_format($precio_restaurar, 2, '.', '');
	$descuento = 0;
	$btn = '100%';
}else{
	$precio_original = floatval($row['precio_venta']);
	$comentarios = preg_replace('/\n?\[\[PRECIO_ORIG:[0-9.]+\]\]/', '', $comentarios);
	$comentarios = trim($comentarios);
	if($precio_original > 0){
		$comentarios = ($comentarios !== '' ? $comentarios."\n" : '').'[[PRECIO_ORIG:'.number_format($precio_original, 2, '.', '').']]';
	}
	$comentarios = ($comentarios !== '' ? $comentarios."\n" : '').'[[DESC100]]';
	$nuevo_precio = '0.00';
	$descuento = 1;
	$btn = 'Restaurar';
}

$comentarios_sql = mysql_real_escape_string($comentarios);
$sql_update = "UPDATE venta_detalle
	SET precio_venta = '$nuevo_precio', comentarios = '$comentarios_sql', impreso = 0
	WHERE id_detalle = $id_detalle";
if(!mysql_query($sql_update)){
	echo json_encode(array('ok' => false, 'error' => 'No se pudo actualizar el producto.'));
	exit;
}

$sql_total = "SELECT SUM(cantidad * precio_venta) FROM venta_detalle WHERE id_venta = $id_venta";
$total = floatval(@mysql_result(mysql_query($sql_total), 0));

echo json_encode(array(
	'ok' => true,
	'precio' => number_format(floatval($nuevo_precio), 2, '.', ''),
	'subtotal' => number_format($cantidad * floatval($nuevo_precio), 2, '.', ''),
	'total' => number_format($total, 2, '.', ''),
	'descuento' => $descuento,
	'btn' => $btn
));

?>
