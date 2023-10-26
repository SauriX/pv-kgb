<?
include("includes/db.php");
$sql="SELECT * FROM productos WHERE activo = 1";
$q=mysql_query($sql);


while($ft= mysql_fetch_assoc($q)){

	$s = "INSERT INTO existencias (id_producto,existencia)VALUES('".$ft['id_producto']."','0')";
	$qq = mysql_query($s);
}