<?


$servidorl="conexiadb.cu7qst8jqevg.us-east-2.rds.amazonaws.com";
$usuariol="root";
$clavel="conexia2019";
$basel="kgb_club";


/*
JODETE HERMOSO!!!!


*/
/*$servidor = "epicmedia.cluster-cfh0agjg2td3.us-east-2.rds.amazonaws.com";
$usuario = "epicmedia";
$clave = "epicmedia";
$base = "vendefacil_restaurante";*/

				
  
$conexion2 = @mysql_connect ($servidorl,$usuariol,$clavel) or die ("Error en conexi&oacute;n.");
@mysql_select_db($basel) or die ("No BD");


include("../includes/funciones.php");

if(!$_GET['id_cliente']){ exit("Error de ID");}

$id_cliente=escapar($_GET['id_cliente'],1);

$sql="SELECT * FROM clientes WHERE id_cliente=$id_cliente";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['nombre']."|".$ft['telefono']."|".$ft['email']."|".$ft['genero']."|".$ft['fecha_nacimiento'];
}else{
	echo "error";
}
?>