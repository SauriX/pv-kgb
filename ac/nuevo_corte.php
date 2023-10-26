<?php
session_start();
date_default_timezone_set("America/Bogota");
include('../includes/db.php');

$id_usuario = $_SESSION['s_id'];
$fechaAlta = date('Y-m-d H:i:s');
$error = false;

$fondo = $_POST['total_fondo'];



$sql_corte ="SELECT * FROM cortes WHERE abierto = 1";
$q_corte = mysql_query($sql_corte);
$n_corte = mysql_num_rows($q_corte);
if($n_corte==0){
  $sql = "INSERT INTO cortes (id_usuario,fondo_caja,fh_abierto,abierto) VALUES ('$id_usuario','$fondo','$fechaAlta','1');";
  if (!mysql_query($sql)) {
    $error = true;
  }
  
}else{
  $error=true;
}



if ($error) {
  echo "Error al abrir caja";
}else {
  echo "1";
}
?>
