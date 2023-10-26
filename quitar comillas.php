<?
set_time_limit(0);
ignore_user_abort(1);
include('includes/db.php');

include('includes/funciones.php');
$sql="SELECT  * FROM productos";
$q=mysql_query($sql);



while($fx=mysql_fetch_assoc($q)){
 
    $id=$fx['id_producto'];
$nombre =$fx['nombre'];
$nombre=acentos($nombre);
$nombre=limpiaStr($nombre,1,1);
$nombre= str_replace('\"','',$nombre);
echo($nombre);
echo('<br>');
$upd="UPDATE  productos SET nombre ='$nombre' WHERE id_producto=$id";

$q2=mysql_query($upd);


}
