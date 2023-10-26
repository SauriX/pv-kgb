<?

include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);


if(!$id_producto) exit("No llego el identificador del producto.");

$foto= $_FILES["imagen"]["tmp_name"];
$nombrefoto  = $_FILES["imagen"]["name"];
$foto=base64_encode(file_get_contents($_FILES["imagen"]["tmp_name"])); 
  

$sql_p="SELECT * FROM productos_base 
LEFT JOIN productos ON productos.nombre = productos_base.producto
where productos.id_producto=$id_producto";
	$q_p=mysql_query($sql_p);
	$ft_p=mysql_fetch_assoc($q_p);
	
$id_ingrediente=$ft_p['id_base'];

//Validamos datos completos

if($tipo != "pack"){
	if($id_categoria<=0) exit("Debe seleccionar una categoría.");
	}
//if($id_categoria<=0) exit("Debe seleccionar una categoría.");
//if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");
if(!$codigo) exit("Debe escribir un código de producto.");
if(!$nombre) exit("Debe escribir un nombre para el producto.");
if($tipo != "Sin"){
	if(!$precio_venta) exit("Debe escribir un precio de venta para el producto.");
	}
if($tipo == "Sine"){
	$sin =1;
	$extra =0;	
	$pack =0;
	}
	if($tipo == "Extra"){
		$extra =1;	
		$pack =0;
			$sin =0;
		}

		if($tipo == "pack"){
			$pack =1;
			$sin =0;
			$extra =0;
			}

//Formateamos y validamos los valores
$id_categoria=escapar($id_categoria,1);
$codigo=limpiaStr($codigo,1,1);
$nombre=limpiaStr($nombre,1,1);
//if(!validarEmail($email)) exit("El correo ".escapar($email)." no es v&aacute;lido, verifique el formato.");
if(!escapar($precio_venta,1)) exit("El precio de venta debe ser número");
//Verificamos que el usuario no exista
$q=mysql_query("SELECT * FROM productos WHERE codigo='$codigo' AND id_producto !=$id_producto ");
$valida=mysql_num_rows($q);
if($valida>0){
	exit("El código de producto esta en uso.");
}else{
	//Insertamos datos
	
	$sql="UPDATE productos SET id_categoria='$id_categoria', codigo='$codigo',color='$color', nombre='$nombre', precio_venta='$precio_venta' , sinn='$sin',extra='$extra',paquete='$pack',imagen='$foto' WHERE id_producto=$id_producto";
	$q=mysql_query($sql);
	
	if($q){

		if($extra==1){
			$sql2="UPDATE productos_base SET producto = '$nombre' WHERE id_base=$id_ingrediente";
			$q2=mysql_query($sql2);
		}

		$hash = md5(time());
		mysql_query("UPDATE refresh SET r_productos = '$hash'");
		echo 1;		
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
}
?>