<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if($tipo != "pack"){
if($id_categoria<=0) exit("Debe seleccionar una categoría.");
}
//if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");
if(!$codigo) exit("Debe escribir un código de producto.");
if(!$nombre) exit("Debe escribir un nombre para el producto.");

if($tipo != "Sine" && $tipo !="Extra" ){
	 
if(!$precio_venta) exit("Debe escribir un precio de venta para el producto.");
}

//Formateamos y validamos los valores
$id_categoria=escapar($id_categoria,1);
$codigo=acentos($codigo);
$codigo=limpiaStr($codigo,1,1);
$nombre=acentos($nombre);
$nombre=limpiaStr($nombre,1,1);
if($tipo == "Sine"){
$sin =1;

}
if($tipo == "Extra"){
	$extra =1;	
	}

	if($tipo == "pack"){
		$pack =1;	
		}


	if($tipo =="Producto"){
		$extra =0;
		$SIN=0;	
		$pack =0;
		}

//if(!validarEmail($email)) exit("El correo ".escapar($email)." no es v&aacute;lido, verifique el formato.");
if($tipo != "Sine" && $tipo !="Extra" ){
	
	if(!escapar($precio_venta,1)) exit("El precio de venta debe ser número");
	
	}

//Verificamos que el usuario no exista
$q=mysql_query("SELECT * FROM productos WHERE codigo='$codigo' OR nombre = '$nombre'");
$valida=mysql_num_rows($q);
if($valida>0){
	exit("El código o nombre del producto se encuentra en uso.");
}else{
	//Insertamos datos
	$sql="INSERT INTO productos (id_categoria,codigo,nombre,precio_venta,sinn,extra,paquete,color) VALUES ('$id_categoria','$codigo','$nombre','$precio_venta','$sin','$extra','$pack','$color')";
	$q=mysql_query($sql);
	$id_producto=mysql_insert_id();
	if($q){
		if($extra==1){
		$sql2="INSERT INTO productos_base (producto,precio,id_unidad) VALUES ('$nombre','$precio','3')";
	    $q2=mysql_query($sql2);}
		
		$hash = md5(time());
		mysql_query("UPDATE refresh SET r_productos = '$hash'");
		
		if($q) echo "1";		
	}else{
	
		echo "Ocurrió un error, intente más tarde.";
	}
}
?>