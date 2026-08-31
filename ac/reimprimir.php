<?php
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora_plugin.php");

$nombre = $_GET['nombre'];
$direccion = $_GET['direccion'];
$numero = $_GET['numero'];

header('X-PV-Domicilio: '.impresion_plugin_encabezado_domicilio($nombre, $numero, $direccion));
echo '1';

?>