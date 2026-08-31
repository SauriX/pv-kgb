<?php
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

header('Content-Type: application/json; charset=utf-8');
echo json_encode(array(
	'ok' => false,
	'error' => $id > 0 ? 'usar_plugin_de_impresion' : 'id_invalido'
));
