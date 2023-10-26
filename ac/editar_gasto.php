<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora.php");

extract($_POST);
/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/
// para editar la provision
//Validamos datos completos
if(!$gastos_descripcion) exit("Debe escribir una descripción para el gasto.");
if(!$gastos_monto) exit("Debe escribir un monto para el gasto.");

//Formateamos y validamos los valores
$gastos_descripcion=limpiaStr($gastos_descripcion,1,1);

	$sql="UPDATE gastos SET  descripcion='$gastos_descripcion', monto='$gastos_monto', fecha_hora='$fechahora', provision='0'  WHERE id_gasto=$id_gasto";
	$q=mysql_query($sql);
	imprimir_gasto($id_gasto);
	if($q){


		echo "1";
		
		
	}else{
		echo "2|Ocurrió un error, intente más tarde.";
	}

?>