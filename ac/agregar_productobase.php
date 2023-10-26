<?
	include('../includes/db.php');
  extract($_POST);
  $sql2=" SELECT * FROM  productosxbase WHERE id_base= $ingrediente AND id_producto = $producto";
  $valida=mysql_num_rows($sql2);
  if($valida>0){
    echo("producto duplicado");
  }else{
    $sql=" INSERT INTO productosxbase(id_producto, id_base, cantidad) VALUES ('$producto','$ingrediente','$cantidad')";
	  $q=mysql_query($sql);
    if($q){       
      echo "1"; 
    }else{
		  echo "Ocurrió un error, intente más tarde.";
    }
  }