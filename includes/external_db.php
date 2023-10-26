<?php
$servidor2="tacoloco.mx";
$usuario2="digmastu_diego";
$clave2="camacho";
$base2="digmastu_facturacion_cfdi_mango_chile";
$conexion2 = @mysql_connect ($servidor2,$usuario2,$clave2) or die ("Error en conexi&oacute;n.");
@mysql_select_db($base2) or die ("No BD");
?>
