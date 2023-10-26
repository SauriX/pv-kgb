<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora.php");
extract($_POST);
$erro=false;

$sq="UPDATE ventas SET contar=0 ";
    $q=mysql_query($sq);
    if(!$q){
        $erro=true;
    }
    $sq="UPDATE dotaciones SET contar=0  ";
    $q=mysql_query($sq);
    if(!$q){
        $erro=true;
    }
    $sq="UPDATE merma SET contar=0  ";
    $q=mysql_query($sq);
    if(!$q){
        $erro=true;
    }
    $sq="UPDATE existencia SET contar=0  ";
    $q=mysql_query($sq);
    if(!$q){
        $erro=true;
    } 

/*     $dotacion="UPDATE vaciar SET id_local = $id , bandera=1";
    $query=mysql_query($dotacion);
    if(!$query){
        $erro=true;
    } */
    if($erro){
        
        exit('ocurrio un error intente mas tarde');
        
    }else{
        exit('1');
    }