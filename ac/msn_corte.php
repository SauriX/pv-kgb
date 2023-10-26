<?
include('../includes/session.php');
include('../includes/db.php');
include('../includes/funcion_msn.php');

// hacer un random
$random = rand(1111,9999);

// Sacar el ultimo corte
$sq_corte="SELECT MAX(id_corte) as id FROM cortes ";
$q_corte=mysql_query($sq_corte);
$row = mysql_fetch_array($q_corte);
$v_corte = $row ['id'];


// Actulizar el valor del corte
$sq_update="UPDATE cortes SET codigo='$random' WHERE id_corte=$v_corte  ";
$q = mysql_query($sq_update);

echo envio_adm($random);
