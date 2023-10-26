<?php
//set it to writable location, a place for temp generated PNG files
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

//html PNG location prefix
$PNG_WEB_DIR = 'temp/';

include "qrlib.php";    

//ofcourse we need rights to create temp dir
if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);

$filename = $PNG_TEMP_DIR.'test.png';

$matrixPointSize = 10;//Matrix
$errorCorrectionLevel = 'L';//Tamaño del QR
$name_qr="Juan";//nombre el Q
$cadena = "re=MTL1010278B1&rr=CRM6702109K6&tt=&id=DC00FE9E-62B1-4ABC-80A6-84CBCF3241F2";

$filename = $PNG_TEMP_DIR.$name_qr.'.png';
QRcode::png($cadena, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 

//echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
?>