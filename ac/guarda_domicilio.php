<?
include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');
include('../includes/impresora_ext.php');
error_reporting(0);
//print_r($_POST);
//exit();
extract($_POST);

//Validaciones
//descuento_porcentaje
//descuento_cantidad
//Costo por servicio $servicio

$numero_telefono = trim($numero_telefono);
$vxe = 0;

if(!$nombre_cliente) exit("Debes escribir un nombre para el cliente.");

if(!$numero_telefono){
    $vxe = 1;
}else{
    if(!validarTelefono($numero_telefono)) exit("Número de teléfono inválido.");
    if(!$id_domicilio_direccion) exit("Debes seleccionar una dirección.");
}


$nombre_cliente=limpiaStr($nombre_cliente,1,1);
if($comentarios){ $comentarios=limpiaStr($comentarios,1,1); }

$id_costo_envio = id_costo_envio();

mysql_query("BEGIN");

if(!$vxe){
    if($id_domicilio_direccion=="N"){
        //Alta del nuevo domicilio
        $direccion_N=trim(limpiaStr($direccion_N,1,1));
        if(!$direccion_N) exit("Debe escribir una dirección para el cliente");

        if(!$id_domicilio){
            $sql = "INSERT INTO domicilio (numero,nombre)VALUES('$numero_telefono','$nombre_cliente')";
            $q = mysql_query($sql);
            $id_domicilio = mysql_insert_id();
            if(!$q) $error = true;
        }else{
            $sql = "UPDATE domicilio SET nombre='$nombre_cliente' WHERE id_domicilio='$id_domicilio'";
            $q = mysql_query($sql);
            if(!$q) $error = true;
        }

        $sql = "INSERT INTO domicilio_direcciones (id_domicilio,direccion,costo)VALUES('$id_domicilio','$direccion_N','$servicio_nuevo')";
        $q = mysql_query($sql);
        $id_domicilio_direccion = mysql_insert_id();
        if(!$q) $error = true;

        $costo_servicio_domicilio=$servicio_nuevo;
    }else{
        $direccion=limpiaStr($direccion[$id_domicilio_direccion],1,1);
        $servicio_ok=$servicio[$id_domicilio_direccion];
        if($servicio_ok=="X"){
            exit("Seleccione un precio por el servicio.");
        }
        //Edición de un domicilio existente
        $sql = "UPDATE domicilio SET nombre='$nombre_cliente' WHERE id_domicilio='$id_domicilio'";
        $q = mysql_query($sql);
        if(!$q) $error = true;

        $sql = "UPDATE domicilio_direcciones SET direccion='$direccion', costo='$servicio_ok' WHERE id_domicilio_direccion='$id_domicilio_direccion'";
        $q = mysql_query($sql);
        if(!$q) $error = true;

        $costo_servicio_domicilio=$servicio_ok;

    }
}

    //Guardamos los datos de la venta
    $sql = "INSERT INTO ventas_domicilio (id_usuario,id_domicilio_direccion,fechahora_alta,facturar,comentarios,descuento_cantidad,descuento_porcentaje,nombre_para_llevar)VALUES('$s_id_usuario','$id_domicilio_direccion','$fechahora','$facturar','$comentarios','$descuento_cantidad','$descuento_porcentaje','$nombre_cliente')";
    $q = mysql_query($sql);
    $id_venta_domicilio = mysql_insert_id();
    if(!$q) $error = true;

    //Metemos el servicio a domicilio
    $sql="INSERT INTO venta_domicilio_detalle (id_venta_domicilio,id_producto,cantidad,precio_venta)VALUES('$id_venta_domicilio','$id_costo_envio','1','$costo_servicio_domicilio')";
    $query = mysql_query($sql);
    if(!$query) $error = true;


    foreach($_POST as $p => $v){
    	foreach($v as $input_name => $cantidad){

    		$item = explode("_",$input_name);
            $id_temporal=$item[0];
    		$id_producto = $item[1];
    		$precio = $item[2];
            $comentario=$adicional[$id_temporal];
            if($id_producto==""){ continue; }

    		$sql="INSERT INTO venta_domicilio_detalle (id_venta_domicilio,id_producto,cantidad,precio_venta,comentarios)VALUES('$id_venta_domicilio','$id_producto','$cantidad','$precio','$comentario')";
    		$query = mysql_query($sql);
    		if(!$query) $error = true;

    		$total_totales+=$precio*$cantidad;

    	}
    }
    imprimir_comandas('domicilio',$id_venta_domicilio);
    imprimir_ticket_domicilio($id_venta_domicilio);

if($error==false){
	mysql_query("COMMIT");
    if($vxe){
        $nombre_cliente = str_replace("  "," ",$nombre_cliente);
        $nombre_cliente = str_replace(" ","-",$nombre_cliente);
        agregar_a_venta($id_venta_domicilio,$nombre_cliente);
    }
	echo "1";
}else{
	mysql_query("ROLLBACK");
	echo "Hubo problema, por favor intenta de nuevo";
}
