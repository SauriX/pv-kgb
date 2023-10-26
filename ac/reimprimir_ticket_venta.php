<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora.php");

extract($_GET);

if(!$id) exit("No llegó ningún identificador.");
if(!$impresora) exit("No llegó ninguna impresora.");

imprimir_ticket_domicilio($id,$impresora);
