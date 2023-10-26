<?
include('../includes/db.php');
include('../includes/funciones.php');


extract($_GET);
if(!is_numeric($id_cliente)) exit('NAIN.');

$deuda_actual = fnum(obtenerDeuda($id_cliente),0,1);

echo $deuda_actual;


