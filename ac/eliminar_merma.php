<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");


extract($_POST);


$sql ="DELETE FROM merma WHERE id_merma = $id_dotacion";
$q = mysql_query($sql);


if($q){

 

    $sql ="DELETE FROM merma_detalle WHERE id_merma = $id_dotacion";
    $q = mysql_query($sql);

}else{ exit('Error al eliminar, intente nuevamente.');}




echo "1";


?>
