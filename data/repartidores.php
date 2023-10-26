<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_repartidor']){ exit("Error de ID");}
$id_repartidor=$_GET['id_repartidor'];

//$dt=explode("-",$datos);
//$tipo=$dt[0];
//$id_repartidor=$dt[1];
/*
if($tipo!="R"){
	$data->resultado=2;
	echo json_encode($data);
	exit;
}
*/
$sql="SELECT * FROM repartidores WHERE id_repartidor=$id_repartidor AND activo=1";
$q=mysql_query($sql);
$val=mysql_num_rows($q);
if($val){
	$data = mysql_fetch_object($q);
	$data->resultado=1;
	echo json_encode($data);
}else{
	$data->resultado=2;
	echo json_encode($data);
}


?>
