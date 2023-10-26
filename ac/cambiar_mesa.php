<?
    include('../includes/session.php');
    include('../includes/db.php');
    include('../includes/impresora.php');
    extract($_POST);
    $sql = "SELECT mesa FROM ventas WHERE id_venta = $id_venta";
    $q = mysql_query($sql);
    $mesa = mysql_result($q, 0);
    $sql = "SELECT * FROM ventas WHERE mesa = '$mesa_deseada' AND abierta = 1";
    $q = mysql_query($sql);
    $n = mysql_num_rows($q);
    if($n){
        exit('Hay otra mesa abierta con el mismo número ('.$mesa_deseada.').');
    }
    $sql = "UPDATE ventas SET mesa = '$mesa_deseada' WHERE id_venta = $id_venta";
    $q = mysql_query($sql);
    if($q){
        echo '1';
    }