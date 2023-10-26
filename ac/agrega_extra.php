<?
	include('../includes/db.php');
  extract($_POST);
  $sql2=" SELECT * FROM producto_extra WHERE id_extra = $extra AND id_producto = $producto";
  $valida=mysql_num_rows($sql2);
	if($valida>0){
    echo("producto duplicado");
  }else{	
    $sql=" INSERT INTO producto_extra(id_producto, id_extra, nivel) VALUES ('$producto','$extra','$nivel')";
	  $q=mysql_query($sql);
    if($q){ 
      $sql2="UPDATE productos SET tiene='1' WHERE id_producto=$producto";
      $q2=mysql_query($sql2);  
      if($q2){
        echo "1";
      } 
    }else{
		  echo "Ocurrió un error, intente más tarde.";
    }
  }