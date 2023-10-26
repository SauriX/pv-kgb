<?
include("includes/session.php");
include("includes/db.php");

$sql="SELECT id_cliente,nombre FROM clientes WHERE activo=1 AND comidas=1";
$q=mysql_query($sql);
$cuantos=mysql_num_rows($q);
$cuenta=0;
$val=$cuantos-1;
echo '[';
while($ft=mysql_fetch_assoc($q)){
	echo '"('.$ft['id_cliente'].')'.$ft['nombre'].'"';
	if($val==$cuenta){
		echo "";
	}else{
		echo ",";
	}
	$cuenta++;
}
echo ']';