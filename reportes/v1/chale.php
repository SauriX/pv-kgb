<?php
$handle = fopen("PRN", "w"); // note 1 
fwrite($handle, 'text to printer'); // note 2 
fclose($handle); // note 3 
?>