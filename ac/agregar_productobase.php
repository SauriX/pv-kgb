<?
  include('../includes/db.php');

  $producto = isset($_POST['producto']) ? intval($_POST['producto']) : 0;
  $ingrediente = isset($_POST['ingrediente']) ? intval($_POST['ingrediente']) : 0;
  $cantidad = isset($_POST['cantidad']) ? floatval(str_replace(',', '', $_POST['cantidad'])) : 0;

  if($producto <= 0 || $ingrediente <= 0 || $cantidad <= 0){
    exit('Datos incompletos o invalidos');
  }

  $sql2 = "SELECT id_producto_base FROM productosxbase WHERE id_base = $ingrediente AND id_producto = $producto LIMIT 1";
  $q2 = mysql_query($sql2, $conexion);
  if(!$q2){
    exit('Error al validar duplicado: '.mysql_error());
  }

  $valida = mysql_num_rows($q2);
  if($valida > 0){
    echo 'producto duplicado';
  }else{
    $sql = "INSERT INTO productosxbase(id_producto, id_base, cantidad) VALUES ('$producto','$ingrediente','$cantidad')";
    $q = mysql_query($sql, $conexion);
    if($q){
      echo '1';
    }else{
      echo 'Ocurrió un error, intente más tarde. '.mysql_error();
    }
  }