<?include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos

//if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");

if(!$nombre) exit("Debe escribir un nombre para el producto.");

if(!$id_unidad) exit("Debe ingresar una unidad para el producto.");


//if(!$precio) exit("Debe escribir un precio para el producto.");



//Formateamos y validamos los valores


$nombre=limpiaStr($nombre,1,1);
$precio=limpiaStr($precio,1,1);




	


//Verificamos que el usuario no exista
$q=mysql_query("SELECT * FROM productos_base WHERE nombre = '$nombre'");
$valida=mysql_num_rows($q);
if($valida>0){
	exit(" nombre del producto se encuentra en uso.");
}else{
	//Insertamos datos
    $sql="UPDATE productos_base SET id_unidad='$id_unidad',  producto='$nombre', precio='$precio'  WHERE id_base=$id_producto";
	$q=mysql_query($sql);

	$id_producto=mysql_insert_id();
	if($q){

        echo "1";
	}else{
	
        echo "Ocurrió un error, intente más tarde.";
        //echo $sql;
	}
}

?>	