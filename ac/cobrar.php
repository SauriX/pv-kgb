<?php
;
include('../includes/session.php');

include('../includes/funciones.php');
include('../includes/dbline.php');
include('../includes/db.php');
include('../includes/impresora.php');


$id_usuario = $s_id_usuario;
$fecha = date('Y-m-d');
$hora = date('H:i:s');
$total_totales = 0;
$mensaje = "";
$error = false;
$var = '';
$mesa_cerrada_imprimir = 0;
$id_venta = 0;
$id_venta2 = 0;
extract($_POST);
if(!isset($numero_mesa)) $numero_mesa = '';
if(!isset($domicilio)) $domicilio = 0;
if(!isset($cobro)) $cobro = 0;
$sql="SELECT * FROM configuracion ";
$q =mysql_query($sql);
$ft=mysql_fetch_assoc($q);
$sucursal=$ft['sucursal'];

$codigo=generateRandomString(15);
$fechahora_default = $fecha.' '.$hora;

mysql_query("BEGIN");

$cobro1=$cobro;
if($numero_mesa){

		$sql = "SELECT id_venta FROM ventas WHERE id_corte = 0 AND abierta = 1 AND pagada = 0 AND mesa = '$numero_mesa'";
		$q = mysql_query($sql);
		
      


		$n = mysql_num_rows($q);
	   
		


		
		if($n){

			$id_venta = @mysql_result($q,0);
			$id_venta2 = $id_venta;
			if(!$id_venta){
				$error = true;
				$mensaje = "No se pudo obtener id_venta";
			}
         
			

		}else{

			
			$sql = "INSERT INTO ventas
				(id_usuario,fecha,hora,mesa,fechahora_cerrada,fechahora_pagada,id_metodo,facturado,monto_facturado,monto_pagado,codigo,metodo_txt,recibe_txt,cambio_txt,pendiente_facturar,pendiente_monto,descuento_txt,DescEfec_txt,pagarOriginal,codigo_activacion,domicilio)
				VALUES('$id_usuario','$fecha','$hora','$numero_mesa','$fechahora_default','$fechahora_default','0','0','0.00','0.00','','','0.00','0.00','0','0.00','0','0.00','0.00','$codigo','$domicilio')";
		
			
			$q = mysql_query($sql);
			
		
			if($q) {
				
                
				$id_venta= mysql_insert_id();

				$id_venta2 = $id_venta;
							
			
		
			
				$sql2 = "INSERT INTO ventas
					(id_usuario,fecha,hora,mesa,fechahora_cerrada,fechahora_pagada,id_metodo,facturado,monto_facturado,monto_pagado,codigo,metodo_txt,recibe_txt,cambio_txt,pendiente_facturar,pendiente_monto,descuento_txt,DescEfec_txt,pagarOriginal,codigo_activacion,id_sucursal,id_venta_sucursal,domicilio)
					VALUES('$id_usuario','$fecha','$hora','$numero_mesa','$fechahora_default','$fechahora_default','0','0','0.00','0.00','','','0.00','0.00','0','0.00','0','0.00','0.00','$codigo','$sucursal','$id_venta','$domicilio')";

			
			

			}else{
				$error = true;
				$mensaje = mysql_error();
				if($mensaje == ""){
					$mensaje = "No se pudo crear la venta";
				}
			}

		}
}else{
         
			$sql = "INSERT INTO ventas
				(id_usuario,fecha,hora,mesa,abierta,fechahora_cerrada,fechahora_pagada,id_metodo,facturado,monto_facturado,monto_pagado,codigo,metodo_txt,recibe_txt,cambio_txt,pendiente_facturar,pendiente_monto,descuento_txt,DescEfec_txt,pagarOriginal,codigo_activacion,domicilio)
				VALUES('$id_usuario','$fecha','$hora','BARRA','0','$fechahora_default','$fechahora_default','0','0','0.00','0.00','','','0.00','0.00','0','0.00','0','0.00','0.00','$codigo','$domicilio')";
	
			   
			$q = mysql_query($sql);
			if($q) {
				
				$id_venta = mysql_insert_id();
				$id_venta2 = $id_venta; // 👈 AQUÍ SÍ
			if(!$id_venta){
    $error = true;
    $mensaje = "No se pudo generar id_venta";
}

				$mesa_cerrada_imprimir = 1;

			}else{
				$error = true;
				$mensaje = mysql_error();
				if($mensaje == ""){
					$mensaje = "No se pudo crear la venta de barra";
				}
			}


}



unset($_POST['id_cliente']);
unset($_POST['abono']);



if(!$error && $id_venta){
foreach($_POST as $p => $v){


	// Inicializar variables para evitar warnings
	$nombre = '';
	$categorias = '';
    if(!is_array($v)) continue;
	foreach($v as $input_name => $cantidad){

		$item = explode("_",$input_name);

if(count($item) < 3) continue;
		$id_temporal=$item[0];
		$comentario = isset($adicional[$id_temporal]) ? $adicional[$id_temporal] : '';
		
		$id_producto = $item[1];
		$precio = $item[2];
		if($id_producto==""){ continue; }
		$pack=0; 
		$sql_pro="SELECT productos.* ,categorias.nombre as categorias FROM productos
		LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria
		 WHERE id_producto = $id_producto";
		
		$query_pro = mysql_query($sql_pro);

	

			while($ft=mysql_fetch_assoc($query_pro)){
				$pack = $ft['paquete'];
				$nombre = $ft['nombre'];
				$categorias = $ft['categorias'];
			}

			if($precio==0){
				$nombre = '';
				$categorias = '';
			}
		   
			$sql="INSERT INTO venta_detalle(id_venta,id_producto,cantidad,precio_venta,comentarios,nombre,categoria)VALUES('$id_venta','$id_producto','$cantidad','$precio','$comentario','$nombre','$categorias')";
			$query = mysql_query($sql);

         if($pack == 1){

		 $sql_pack="SELECT * FROM productos_paquete WHERE id_producto = $id_producto";
		 
		 $query_pack = mysql_query($sql_pack);
		 while($fx = mysql_fetch_assoc($query_pack)){
			  $id_producto2 = $fx['id_paquete'];
			  $cantidad2 = $fx['cantidad'];
			  $sqlpropa="SELECT productos.* ,categorias.nombre as categorias FROM productos
			  LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria
			   WHERE id_producto = $id_producto2";
			  $q77= mysql_query($sqlpropa);
			  $ftx3=mysql_fetch_assoc($q77);
			  $nombre2=$ftx3['nombre'];
			  $categorias2 = $ftx3['categorias'];
			$sql69="INSERT INTO venta_detalle
			(id_venta,id_producto,cantidad,precio_venta,comentarios,nombre,categoria)VALUES('$id_venta','$id_producto2','$cantidad2','00.00','$comentario','$nombre2','$categorias2')";
			$query69 = mysql_query($sql69);


		  }

                
         }


		if(!$query){
			$error = true;
			$mensaje = mysql_error();
			if($mensaje == ""){
				$mensaje = "No se pudo guardar el detalle de venta";
			}
		
			
		}
				if(!$id_venta){
					$error = true;
					$mensaje = "No se pudo generar id_venta";
				}
	

		
		$total_totales+=$precio*$cantidad;

	}
	

}
}



$auto_cobro=$cobro1;
	


if($error==false){


	if($mesa_cerrada_imprimir){
		
	
		imprimir_mesa($id_venta,'cerrar');


		imprimir_comandas('venta',$id_venta);

		 $up_coman="UPDATE venta_detalle SET impreso=1 WHERE id_venta=$id_venta";
		 mysql_query($up_coman);
	}else {
			
		imprimir_comandas('venta',$id_venta);
		$up_coman="UPDATE venta_detalle SET impreso=1 WHERE id_venta=$id_venta";
		mysql_query($up_coman);

	}

	mysql_query("COMMIT");
	//updatear ingredientes
	$sql_ing="SELECT * FROM venta_detalle 
	LEFT JOIN productos ON productos.id_producto =venta_detalle.id_producto
  WHERE id_venta= $id_venta AND productos.extra =0 AND productos.tiene=1";

$query_ing = mysql_query($sql_ing);

while($fx = mysql_fetch_assoc($query_ing)){
if(!$fx['sinn']){
$id_producto3 = $fx['id_producto'];

$sql3="SELECT productosxbase.cantidad, productos_base.id_base as id
	  FROM productosxbase
	  LEFT JOIN productos_base ON productosxbase.id_base = productos_base.id_base
	  LEFT JOIN productos ON productos.nombre = productos_base.producto
	   WHERE productosxbase.id_producto = $id_producto3 ";
	  
$q3=mysql_query($sql3);
if(!$q3){ continue; }
while($datos3=mysql_fetch_assoc($q3)){
  
$id_producto4=$datos3['id'];

 $cantidad4=$datos3['cantidad'];


 

}
}
}
	//fion de updatear ingrediente
     
    if($auto_cobro==1 or !$numero_mesa  ){
	echo $id_venta;
	}else{
		echo "1";
	}
}else{
	mysql_query("ROLLBACK");
	echo "Hubo problema, por favor intenta de nuevo".$mensaje;
}
