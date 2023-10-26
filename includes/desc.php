<?
$url = 'https://misdistribuidores.greenelvnutrition.com/validar.php';
$source = file_get_contents($url);
file_put_contents('../includes/nombredelarchivo.txt', $source);