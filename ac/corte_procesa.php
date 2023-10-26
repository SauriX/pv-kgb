<?php
include_once '../includes/db.php';

extract($_POST);

if(!$id_corte){
  exit('Falta ID de corte XDXD');
}

$hay = count($tickets);
if(!$hay){
  exit('Seleccione tickets');
}

//Obtiene el primer ID del corte
$query = "SELECT MIN(id_venta) AS id_venta FROM ventas WHERE id_corte = $id_corte;";
$idInicioArr = mysql_fetch_array(mysql_query($query));
$idinicio = intval($idInicioArr[0]);

//Se eliminan las ventas seleccionadas
$query = "SELECT id_venta FROM ventas WHERE id_corte = $id_corte";
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result)) {
  if(!in_array($row['id_venta'], $tickets)){
    $query = "DELETE FROM ventas WHERE id_venta = ".$row['id_venta'];
    $response = mysql_query($query);

    $query2 = "DELETE FROM venta_detalle WHERE id_venta = ".$row['id_venta'].";";
    $response2 = mysql_query($query2);
  }
}


//Se llena el array con los IDs nuevos y los viejos
$idsArray = array();
$query = "SELECT id_venta FROM ventas WHERE id_corte = $id_corte;";
$ids = mysql_query($query);

// while ($row = mysql_fetch_assoc($ids)) {
//   $idsArray[] = [intval($row['id_venta']), $idinicio];
//   $idinicio++;
// }

// while ($row = mysql_fetch_assoc($ids)) {
//   $idAntiguo = intval($row['id_venta']);
//   $array = [$idAntiguo, $idinicio];
//   array_push($idsArray, $array);
//   $idinicio++;
// }

while ($row = mysql_fetch_assoc($ids)) {
  $idAntiguo = intval($row['id_venta']);
  $idsArray[] = array($idAntiguo, $idinicio);
  $idinicio++;
}

//Se cambian los IDs viejos por los nuevos
foreach ($idsArray as $key => $value) {
  //echo 'id viejo: ' . $value[0] . ' id nuevo: ' . $value[1] . '<br >';
  $query = "UPDATE ventas SET id_venta = ".$value[1]." WHERE id_venta = ".$value[0].";";
  $response = mysql_query($query);
  $query = "UPDATE venta_detalle SET id_venta = ".$value[1]." WHERE id_venta = ".$value[0].";";
  $response = mysql_query($query);
}

//Obtiene el ultimo ID + 1 del corte despues de eliminar
$query = "SELECT MAX(id_venta)+1 FROM ventas WHERE id_corte = $id_corte";
$idFinalArr = mysql_fetch_array(mysql_query($query));
$idFinal = intval($idFinalArr[0]);

//Cambia el valor del auto_increment de la tabla
$query = "ALTER TABLE ventas AUTO_INCREMENT = $idFinal";
mysql_query($query);

$query = "UPDATE cortes SET ajuste = '1' WHERE id_corte = '$id_corte'";
$response = mysql_query($query);

echo '1';

 ?>
