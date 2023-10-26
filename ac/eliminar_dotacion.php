<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");


extract($_POST);


$sql ="DELETE FROM dotaciones WHERE id_dotacion = $id_dotacion";
$q = mysql_query($sql);


if($q){



    $sql ="DELETE FROM dotaciones_detalle WHERE id_dotacion = $id_dotacion";
    $q = mysql_query($sql);

}else{ exit('Error al eliminar, intente nuevamente.');}




echo "1";


?>
