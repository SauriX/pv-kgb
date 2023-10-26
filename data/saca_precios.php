<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_producto']){ exit("Error de ID");}

extract($_GET);

$sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
$q = mysql_query($sql);
$data = mysql_fetch_assoc($q);

$precio_venta = $data['precio_venta'];
$precio_venta_2 = $data['precio_venta2'];
$precio_venta_3 = $data['precio_venta3'];


?>

<li><a href="javascript:;" onclick="agregar3('<?=$codigo?>',<?=$cantidad?>,1,<?=$precio_venta?>,1,<?=$random?>);">Precio 1: <?=$precio_venta?></a></li>
<li><a href="javascript:;" onclick="agregar3('<?=$codigo?>',<?=$cantidad?>,2,<?=$precio_venta_2?>,1,<?=$random?>);">Precio 2: <?=$precio_venta_2?></a></li>
<li><a href="javascript:;" onclick="agregar3('<?=$codigo?>',<?=$cantidad?>,3,<?=$precio_venta_3?>,1,<?=$random?>);">Precio <span title="<?=$precio_compra?>">3</span>: <?=$precio_venta_3?></a></li>
<li><a href="javascript:;" onclick="customs('<?=$codigo?>',<?=$cantidad?>,4,1,<?=$random?>);">Precio Personalizado</a></li>