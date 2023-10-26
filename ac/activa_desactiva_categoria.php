<?
	include("../includes/session.php");
	include("../includes/db.php");
	extract($_POST);

	if(!$id_categoria){
		exit("No llego el identificador de la categoria");
	}
	$pack=0;
	$sql74="SELECT es_paquete FROM categorias where id_categoria =$id_categoria";
	$query74 = mysql_query($sql74);
	while($ft=mysql_fetch_assoc($query74)){
		$pack= $ft['es_paquete'];
	}    
	if($pack==1){
		exit("No puede desactivar este producto");
	}
	//Updateamos el estado
	$sql="UPDATE categorias SET activo='$tipo' WHERE id_categoria=$id_categoria";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error al actualizar la categoría";
	}
?>