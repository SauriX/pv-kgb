<?php
date_default_timezone_set ("America/Mexico_City");
include('db.php');
include('impresora.php');
$var=abrir_caja();
echo $var;