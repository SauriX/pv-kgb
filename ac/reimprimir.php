<?php
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora.php");

$nombre = $_GET['nombre'];
$direccion = $_GET['direccion'];
$numero = $_GET['numero'];

imprimir_domicilio($nombre,$numero,$direccion);

?>