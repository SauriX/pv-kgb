<?
include("../includes/session.php");
include("../includes/dbline.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre.");
//if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");
if(!$telefono) exit("Debe escribir algun telefono.");
if(!$id_cliente) exit("Error al identificar el cliente.");
exit($id_cliente);
//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);





	$sql="UPDATE clientes SET nombre='$nombre' ,telefono='$telefono',email='$mail',genero='$genero',fecha_nacimiento='$fechan' WHERE id_cliente=$id_cliente";
  
	$q=mysql_query($sql);

if($q){
	
    echo(1);
	
}else{
    exit("Ocurrió un error, intente más tarde.");
}

	
	
	
