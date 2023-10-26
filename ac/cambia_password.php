<?
	include("../includes/session.php");
	include("../includes/db.php");
	extract($_POST);
	//print_r($_POST);
	//Validamos datos completos
	//if(!$tipo) exit("No llego el identificador de la operación");
	if(!$pass1){
		exit("Debe capturar su contraseña actual");
	}
	if(!$pass2){
		exit("Debe capturar la nueva contraseña");
	}
	if(!$pass3){
		exit("Debe confirmar la nueva contraseña");
	}
	if($pass2 != $pass3){
		exit("La nueva contraseña y la confirmación no coinciden.");
	}
	$pass1=md5($pass1);
	$pass2=md5($pass2);
	if($pass2 == $pass1){
		exit("No se puede cambiar la contraseña, los datos son similares.");
	}
	//Validamos que sea contraseña actual
	$sql = "SELECT * FROM usuarios WHERE id_usuario='$s_id_usuario' AND contrasena='$pass1' AND activo='1' LIMIT 1";
	$q = mysql_query($sql) or die ('Error en db');
	$num_result = mysql_num_rows($q);
	if($num_result != 0){
		//Updateamos el estado
		$sql="UPDATE usuarios SET contrasena='$pass2' WHERE id_usuario=$s_id_usuario";
		$q=mysql_query($sql);
		if($q){
			echo "1";
		}else{
			echo "Ocurrió un error al actualizar la contraseña.";
		}	
	}else{
		echo "Su contraseña actual es incorrecta.";
	}

?>