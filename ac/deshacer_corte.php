<?php
session_start();
include('../includes/db.php');
include('../includes/impresora.php');


/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/

$codigo = $_POST['codigo'];
$v_corte = $_POST['v_corte']; //id_corte

# Validación de mesas abiertas.
$sql ="SELECT*FROM ventas WHERE id_corte = 0 ";
$q = mysql_query($sql);
$n = mysql_num_rows($q);

if($n>0){
	exit();
}

$sql ="SELECT*FROM gastos WHERE id_corte = 0 ";
$q = mysql_query($sql);
$n = mysql_num_rows($q);

if($n>0){
	exit();
}
# termina validación.

// Validacion de que el codigo este bien

$sql_validacion = "SELECT codigo FROM cortes WHERE id_corte = $v_corte AND codigo = '$codigo'";
$q_validacion=mysql_query($sql_validacion);
$row = mysql_fetch_array($q_validacion);
$v_codigo = $row ['codigo'];
//echo $sql_validacion;
if($v_codigo == $codigo){
	if(!mysql_query("BEGIN")) $error = true;

	$sql_v = "UPDATE ventas SET id_corte = 0 WHERE id_corte = $v_corte";
	if(!mysql_query($sql_v)) $error = true;

	$sql_g = "UPDATE gastos SET id_corte = 0 WHERE id_corte = $v_corte";
	if(!mysql_query($sql_g)) $error = true;
		if($error){

			if(mysql_query("ROLLBACK")) exit("ROLLBACK");

		}else{

			if(mysql_query("COMMIT")){

				$sql_delete = "DELETE FROM cortes WHERE id_corte = $v_corte";
				$q = mysql_query($sql_delete);

			}

		}
	if($q){
		echo "1"; // Valicacion de exitoso
	}
	}else{
		echo "0";// error de Valicacion
	}
