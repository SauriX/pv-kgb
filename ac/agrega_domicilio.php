<?
	include("../includes/session.php");
	include("../includes/db.php");
	include("../includes/funciones.php");
	include("../includes/impresora.php");
	extract($_POST);
	if($id_domicilio_direccion!=0){
		//Aqui selecciono una dirección entonces solo la updateamos(por si hizo algún cambio) y la imprimimos
		if(!$direccion){
			exit("Debe escribir una dirección.");
		}
		$direccion=str_replace('"','',$direccion);  
		$direccion=str_replace("'",'',$direccion); 
		$direccion=limpiaStr($direccion,1,1);
		$direccion=str_replace("\\",'',$direccion);  
		$direccion=str_replace("/",'',$direccion);  
		$sq="UPDATE domicilio_direcciones SET direccion='$direccion' WHERE id_domicilio_direccion='$id_domicilio_direccion'";
		$q=mysql_query($sq);
		if($q){
			$s = "SELECT numero,nombre FROM domicilio WHERE id_domicilio = $id_domicilio";
			$qq = mysql_query($s);
			$dat = mysql_fetch_assoc($qq);
			$nombre = $dat['nombre'];
			$numero = $dat['numero'];
			imprimir_domicilio($nombre,$numero,$direccion);
			exit("1");
		}else{
			exit("Ocurrió un error al actualizar la dirección seleccionada.");
		}
	}

	if(!$tipo){
		exit("No llego el tipo de operación.");
	}
	//Aqui se agrega un domicilio nuevo a un cliente ya existente
	if($tipo==1){	
		//Validamos datos completos
		if(!$id_domicilio){
			exit("Error al identificar el cliente.");
		}
		if(!$direccion){
			exit("Debe escribir una dirección.");
		}
		//Formateamos y validamos los valores
		$id_domicilio=escapar($id_domicilio,1);
		$direccion=str_replace('"','',$direccion);  
		$direccion=str_replace("'",'',$direccion);  
		$direccion=limpiaStr($direccion,1,1);
		$direccion=str_replace("\\",'',$direccion);  
		$direccion=str_replace("/",'',$direccion);  
		//Insertamos datos
		$sql="INSERT INTO domicilio_direcciones (id_domicilio,direccion)VALUES('$id_domicilio','$direccion')";
		$q=mysql_query($sql);
		if($q){
			$s = "SELECT numero,nombre FROM domicilio WHERE id_domicilio = $id_domicilio";
			$qq = mysql_query($s);
			$dat = mysql_fetch_assoc($qq);
			$nombre = $dat['nombre'];
			$numero = $dat['numero'];
			imprimir_domicilio($nombre,$numero,$direccion);
			echo "1";
		}else{
			echo "Ocurrió un error, intente más tarde.";
		}
	}elseif($tipo==2){
		//Aqui se agrega un cliente nuevo con una dirección	
		//Validamos datos completos
		if(!$numero){
			exit("Debe escribir un número.");
		}
		if(!$nombre){
			exit("Debe escribir un nombre.");
		}
		if(!$direccion){
			exit("Debe escribir una dirección.");
		}
		//Formateamos y validamos los valores
		$numero=escapar($numero,1);
		$nombre=limpiaStr($nombre,1,1);
		$direccion=str_replace('"','',$direccion);  
		$direccion=str_replace("'",'',$direccion);  
		$direccion=limpiaStr($direccion,1,1);
		$direccion=str_replace("\\",'',$direccion);  
		$direccion=str_replace("/",'',$direccion);  
		//Insertamos datos
		$q=mysql_query("INSERT INTO domicilio (numero,nombre)VALUES('$numero','$nombre')");
		$id_domicilio=mysql_insert_id();
		if($id_domicilio){
			$sql="INSERT INTO domicilio_direcciones (id_domicilio,direccion)VALUES('$id_domicilio','$direccion')";
			$q=mysql_query($sql);
			if($q){
				$s = "SELECT numero,nombre FROM domicilio WHERE id_domicilio = $id_domicilio";
				$qq = mysql_query($s);
				$dat = mysql_fetch_assoc($qq);
				$nombre = $dat['nombre'];
				$numero = $dat['numero'];
				imprimir_domicilio($nombre,$numero,$direccion);				
				echo "1";
			}else{
				echo "Ocurrió un error al insertar la dirección, intente más tarde.";
			}
		}else{
			exit("Ocurrió no se insertaron los datos, contacta a soporte.");
		}
	
	}else{
		exit("Ocurrió un error con el tipo de operación.");
	}

