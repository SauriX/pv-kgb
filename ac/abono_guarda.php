<?
    include('../includes/db.php');
    include('../includes/funciones.php');
    extract($_POST);
    if(!is_numeric($id_cliente)){
        exit('NAIN.');
    }
    if(!is_numeric($monto)){
        exit('Ingrese el monto a pagar.');
    }
    $fecha_hora = date('Y-m-d H:i:s');
    $deuda_actual = obtenerDeuda($id_cliente);
    if($monto>$deuda_actual){
        exit('No es posible abonar una cantidad mayor al adeudo, por favor verifique.');
    }
    $sql = "INSERT INTO abonos (id_cliente,fecha_hora,monto)VALUES($id_cliente,'$fecha_hora','$monto')";
    $q = mysql_query($sql) or exit('Error al insertar el abono, si el problema persiste contacte a soporte técnico.');
    if($q){echo "1";}
?>