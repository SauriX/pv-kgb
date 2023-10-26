<?php 
    include("../includes/session.php");
    include("../includes/db.php");
    include('../includes/funciones.php');
    extract($_POST);
    
    $codigo=generateRandomString(6);
    echo json_encode($codigo);
