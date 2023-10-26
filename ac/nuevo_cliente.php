<?

include("../includes/session.php");
include("../includes/funciones.php");
include("../includes/dbline.php");



extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre.");
//if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");
if(!$telefono) exit("Debe escribir algun telefono.");

//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);



//Insertamos datos
$sql="SELECT * FROM clientes WHERE telefono = '$telefono'";
$q=mysql_query($sql);
$valida=mysql_num_rows($q);

if($valida==0){

	$sql="INSERT INTO clientes (nombre,telefono,email,genero,fecha_nacimiento,fechahora_alta) VALUES ('$nombre','$telefono','$mail','$genero','$fechan','$fechahora')";
  
	$q=mysql_query($sql);

if($q){
	
	echo(1);
	
}else{
    exit("Ocurrió un error, intente más tarde.");
}

	
	
	
}else{
    exit("El numero ya se encuentra registrado");
}


?>