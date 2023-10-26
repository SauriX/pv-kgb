<?php 
    include("../includes/session.php");
    include('../includes/funciones.php');
    include("../includes/db.php");
    extract($_POST);

    echo json_encode($_POST);
?>