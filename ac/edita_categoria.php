<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_categoria) exit("Error al identificar la categoría.");
if(!$nombre) exit("Debe escribir un nombre.");
if(!$impresora) exit("Debe ingresar una impresora");
//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);
$impresora=limpiaStr($impresora,1,1);
$impresora_para_llevar=limpiaStr($impresora_para_llevar,1,1);
$id_categoria=escapar($id_categoria,1);

	//Insertamos datos
	$sql="UPDATE categorias SET nombre='$nombre' ,impresora='$impresora' ,impresora_para_llevar='$impresora_para_llevar' WHERE id_categoria=$id_categoria";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>