<?
	include('../includes/db.php');
  extract($_POST); 
  $sql2=" SELECT * FROM  productos_paquete WHERE id_paquete= $ingrediente AND id_producto = $producto";
  $valida=mysql_num_rows($sql2);
  if($valida>0){
    echo("producto duplicado");
  }else{
    $sql=" INSERT INTO productos_paquete(id_producto, id_paquete, cantidad) VALUES ('$producto','$ingrediente','$cantidad')";
	  $q=mysql_query($sql);
    echo"1";
	  if(!$q){
      echo("fallo");
    }	
  }
	
 