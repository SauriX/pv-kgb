<? include("includes/db.php");
    $producto=$_GET['padre'];
    $sql="SELECT * FROM productos WHERE id_producto=$producto";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
    echo($ft['receta']);
}
?>