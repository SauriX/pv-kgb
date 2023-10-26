<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

sleep(1);
extract($_POST);

if(!$id_cliente) exit("No llego el identificador del alumno");

if($tipo==1){
	$updatea="creditos_desayuno='1'";
	$desayunos=1;
	$almuerzos=0;
}elseif($tipo==2){
	$updatea="creditos_almuerzo='1'";
	$desayunos=0;
	$almuerzos=1;
}else{
	exit("Ocurrió un error, intente más tarde");
}


//verificamos que tenga registro
$v_sql="SELECT * FROM comidas_creditos_consumo WHERE id_cliente=$id_cliente AND fecha='$fecha_actual'";
$v_q=mysql_query($v_sql);
$v_datos=mysql_fetch_assoc($v_q);
$v_datos_desayuno=$v_datos['creditos_desayuno'];
$v_datos_almuerzo=$v_datos['creditos_almuerzo'];
$valida=mysql_num_rows($v_q);
if(!$valida){
	$sql1 ="INSERT INTO comidas_creditos_consumo (id_usuario,id_cliente,fecha,creditos_desayuno,creditos_almuerzo) VALUES ('$s_id_usuario','$id_cliente','$fecha_actual','$desayunos','$almuerzos')";
	$q1=mysql_query($sql1);
	if($q1){
		//Consultamos los créditos de cada cliente
		$sq="SELECT * FROM comidas_creditos WHERE id_cliente='$id_cliente'";
		$q=mysql_query($sq);
		$datos=mysql_fetch_assoc($q);
		$creditos_desayuno=$datos['creditos_desayuno'];
		$creditos_almuerzo=$datos['creditos_almuerzo'];
		//Restamos el consumo
		$nuevo_desayuno=$creditos_desayuno-$desayunos;
		$nuevo_almuerzo=$creditos_almuerzo-$almuerzos;
		//Guardamos los nuevos créditos
		$sq2="UPDATE comidas_creditos SET creditos_desayuno='$nuevo_desayuno', creditos_almuerzo='$nuevo_almuerzo' WHERE id_cliente=$id_cliente";
		$q2=mysql_query($sq2);
		
		if(!$q2) exit("Ocurrió un error al actualizar datos, intente más tarde.");
	}
}else{
	$sql1 ="UPDATE comidas_creditos_consumo SET ".$updatea." WHERE id_cliente=$id_cliente";
	$q1=mysql_query($sql1);
	if($q1){
		//Consultamos los créditos de cada cliente
		$sq="SELECT * FROM comidas_creditos WHERE id_cliente='$id_cliente'";
		$q=mysql_query($sq);
		$datos=mysql_fetch_assoc($q);
		$creditos_desayuno=$datos['creditos_desayuno'];
		$creditos_almuerzo=$datos['creditos_almuerzo'];
		//Restamos el consumo
		if($v_datos_desayuno==0){
			$nuevo_desayuno=$creditos_desayuno-$desayunos;
		}else{
			$nuevo_desayuno=$creditos_desayuno;
		}
		
		if($v_datos_almuerzo==0){
			$nuevo_almuerzo=$creditos_almuerzo-$almuerzos;	
		}else{
			$nuevo_almuerzo=$creditos_almuerzo;	
		}
		
		//Guardamos los nuevos créditos
		$sq2="UPDATE comidas_creditos SET creditos_desayuno='$nuevo_desayuno', creditos_almuerzo='$nuevo_almuerzo' WHERE id_cliente=$id_cliente";
		$q2=mysql_query($sq2);
		
		if(!$q2) exit("Ocurrió un error al actualizar datos, intente más tarde.");
	}
}	


if($q1){
	echo "1";
}else{
	echo "Ocurrió un error, intente más tarde.";
}
	
?>