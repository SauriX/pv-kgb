<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/impresora.php");

extract($_POST);

//Validamos datos completos
if(!$gastos_descripcion) exit("Debe escribir una descripción para el gasto.");
if(!$gastos_monto) exit("Debe escribir un monto para el gasto.");

//Formateamos y validamos los valores
$gastos_descripcion=limpiaStr($gastos_descripcion,1,1);

	//Insertamos datos
	$sql="INSERT INTO gastos (id_usuario,descripcion,monto,fecha_hora,provision) VALUES ('$s_id_usuario','$gastos_descripcion','$gastos_monto','$fechahora',$gasto_s)";
	//file_put_contents('xxxxxx.txt',$sql);
	$q=mysql_query($sql);
	
	if($q){
		$id_gasto=mysql_insert_id();
		imprimir_gasto($id_gasto);
		//gastos_imprimir();
		echo "1";
	}else{
		echo "2|Ocurrió un error, intente más tarde.";
	}

?>
