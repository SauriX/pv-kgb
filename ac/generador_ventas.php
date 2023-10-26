<?
include('../includes/db.php');
include('../includes/session_ui.php');
include('../includes/cortes.php');

extract($_POST);
$dias_inhabiles=0;
if(!$fechaActual){
    exit('Debe elegir una fecha de inicio');
}

if(!$fechaRegistro){
    exit('Debe elegir una fecha  final');
}


if(!$lunes && !$martes && !$miercoles && !$jueves && !$viernes && !$sabado && !$domingo){
    exit('Debe elegir al menos un dia habil');
}

if(!$metodo){
    exit('Debe elegir metodo de pago');
}


if(!$categoria1 && !$categoria2){
    exit('Debe elegir almenos una categoria');
}

if(!$cantidad_ventas){
    exit('Debe ingresar una cantidad de ventas');
}

if(!$total){
    exit('Debe ingresar un monto');
}

if(!$hora){
    exit('Debe ingresar una hora de apertura');
}

if(!$hora2){
    exit('Debe ingresar una hora de cierre');
}
/*$lunes=1;
$martes=2;
$miercoles=3;
$jueves=4;
$viernes=5;

$sabado=0;
$domingo=0;*/


//$metodo=1;


//$categoria1=3;
//$categoria2=2;

//$cantidad_ventas=400;
//$total=100000;

//$hora = "10:00:00";
//$hora2 = "18:00:00";
$segundosFechaActual = strtotime($fechaActual);
$segundosFechaRegistro = strtotime($fechaRegistro);
$segundosTranscurridos =$segundosFechaRegistro - $segundosFechaActual ;
$diasTranscurridos = $segundosTranscurridos / 86400;



$diasTranscurridos = round( $diasTranscurridos)+1; 


$fechaInicio=strtotime($fechaActual);
$fechaFin=strtotime($fechaRegistro);




//Recorro las fechas y con la función strotime obtengo los lunes
for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
    //Sacar el dia de la semana con el modificador N de la funcion date
         
    $dia = date('N', $i);
      
if($dia==$lunes || $dia==$martes|| $dia==$miercoles || $dia==$jueves || $dia==$viernes || $dia==$sabado || $dia==$domingo){
          $dias_inhabiles++;
       // echo "Lunes. ". date ("Y-m-d", $i)."<br>";
    }
}


  $dias_habiles=  $dias_inhabiles;

  $conrador_v=0;

   list($horas, $minutos, $segundos) = explode(':', $hora);
   $hora_en_segundos = ($horas * 3600 ) + ($minutos * 60 ) + $segundos;

   
   list($horas2, $minutos2, $segundo2s) = explode(':', $hora2);
   $hora_en_segundos2 = ($horas2 * 3600 ) + ($minutos2 * 60 ) + $segundos2;
   
   $segundos_entrehoras=abs($hora_en_segundos - $hora_en_segundos2);


   $horas_entre_rango = $segundos_entrehoras/3600;

  
  

$promedio_venta=$total/$cantidad_ventas;
$promedio_ventasdias=round($cantidad_ventas/$dias_habiles);

$promedio_totaldias = $total/$diasTranscurridos;


$sql="SELECT max(id_producto) as maximo FROM productos WHERE id_categoria =$categoria1";

$q = mysql_query($sql);
$id = mysql_fetch_assoc($q);
$id_maximo1=$id['maximo'];

$impresion='';
$sql="SELECT min(id_producto) as minimo FROM productos WHERE id_categoria =$categoria1";

$q = mysql_query($sql);
$id = mysql_fetch_assoc($q);
$id_minimo1=$id['minimo'];



$sql="SELECT max(id_producto) as maximo FROM productos WHERE id_categoria =$categoria2";

$q = mysql_query($sql);
$id = mysql_fetch_assoc($q);
$id_maximo2=$id['maximo'];


$sql="SELECT min(id_producto) as minimo FROM productos WHERE id_categoria =$categoria2";

$q = mysql_query($sql);
$id = mysql_fetch_assoc($q);
$id_minimo2=$id['minimo'];


 
	 if($lunes==0){
		 $lunes=1;
	 }else{
		 $lunes=0;
	 }
	 if($martes==0){
		 $martes=2;
	 }else{
		 $martes=0;
	 }
	 if($miercoles==0){
		$miercoles =3;
	 }else{
		$miercoles =0;
	 }
	 if($jueves==0){
		 $jueves=4;
	 }else{
		 $jueves=0;
	 }
	 if($viernes==0){
		 $viernes=5;
	 }else{$viernes=0;}
	 if($sabado==0){
		 $sabado=6;
		}else{
			$sabado=0;
		}
	 if($domingo==0){
		 $domingo=7;
	 }else{
		 $domingo=0;
	 }

  
         $i=$segundosFechaActual;
		 $consumido=$total;
		 $contador_alt=0;
      for($l=0;$l<$diasTranscurridos;$l++ ){
		$dia = date('N', $i);
		

		
		
       
	 if($dia==$lunes || $dia==$martes|| $dia==$miercoles || $dia==$jueves || $dia==$viernes || $dia==$sabado || $dia==$domingo){
		   
		/* echo  date ("Y-m-d", $i)."<br>";
		 echo(date('N', $i).'<br>');*/
		 $i+=86400;
	 }else{

		$fecha_nota=date ("Y-m-d", $i);
	
	
	    for($a=0; $a<=$promedio_ventasdias;$a++){
			if($conrador_v==$cantidad_ventas){
			break;
			}

			if($consumido<=0){
			
			break;
			}

			
			$fe=$fecha_nota.' '.$hora;
            
            $fe2=$fecha_nota.' '.$hora2;
            
             
           
          $fe=strtotime($fe);
          
         

       
          $fe2=strtotime($fe2);
          
       
            $hora_nota = mt_rand($fe,$fe2);
            
			$hora_ofn=date('H:i:s',$hora_nota);
          

			$f=0;
			$sql="INSERT INTO ventas (id_usuario, fecha, hora,id_metodo,abierta,pagada) VALUES ('$s_id_usuario','$fecha_nota','$hora_ofn',1,0,1) "; 
			$q=mysql_query($sql);
			$id_venta_g= mysql_insert_id();
			$venta_total= $promedio_venta;
			  $contador_p=0;
			 $contador=0;
			 $contador_bus=0;
			while(true){

       		

				if($promedio_venta>=$contador){
					if($contador_p==3){
						$id=  rand($id_minimo2,$id_maximo2);
			
				   $sql="SELECT * FROM productos WHERE id_producto =$id AND id_categoria=$categoria2";
				

				   $q=mysql_query($sql);
				   $productos=mysql_fetch_assoc($q);
				   
				   $precio_producto=$productos['precio_venta'];
				   $id_producto=$productos['id_producto'];
				  
				   $falta=$promedio_venta-$contador;
				   
				if($falta>=$precio_producto){
                          if($precio_producto!=0){
							$contador_p=0;
							$contador+=$precio_producto;
							$sql="INSERT INTO venta_detalle(id_venta, id_producto, cantidad,precio_venta) VALUES ('$id_venta_g','$id_producto',1,'$precio_producto') "; 
							$q=mysql_query($sql);
							
							
							
						  }
				}else{
					if($contador_bus==30){
						$promedio_venta=$total/$cantidad_ventas;
						$promedio_venta +=$falta;
						$cond=mt_rand(8,10);

						if($contador_alt==8){
							$aumento = mt_rand($promedio_venta,1000);
							$promedio_venta+=$aumento;
							$contador_alt=0;
						}
					break;
					}else{
						$contador_p=0;
					}
					
					$contador_bus++;
					
				}
					   //  echo($contador.': '.$sql);
						 //echo('<br>');
					}else{
						
					
						$id=  rand($id_minimo1,$id_maximo1);
						$sql="SELECT * FROM productos WHERE id_producto =$id AND id_categoria=$categoria1";
					
						$q=mysql_query($sql);
						$productos=mysql_fetch_assoc($q);
						
						$precio_producto=$productos['precio_venta'];
						$id_producto=$productos['id_producto'];
					   
								 $falta=$promedio_venta-$contador;
							
							  if($falta>=$precio_producto){
							   if($precio_producto!=0){

							
								$contador+=$precio_producto;
								$contador_p++;
								$sql="INSERT INTO venta_detalle(id_venta, id_producto, cantidad,precio_venta) VALUES ('$id_venta_g','$id_producto',1,'$precio_producto') "; 
						    $q=mysql_query($sql);
								 
								
							   }}else{
								
								if($contador_bus==30){
									$promedio_venta=$total/$cantidad_ventas;
									$promedio_venta +=$falta;
									$cond=mt_rand(8,10);

									if($contador_alt==8){
										$aumento = mt_rand($promedio_venta,1000);
										$promedio_venta+=$aumento;
										$contador_alt=0;
									}
								break;
                                }else{
									$contador_p++;
								}
								$contador_bus++;
							   }
						//elese
					
						
					}
					






		//	$contador+=10;
		
			
	 }else{
		$promedio_venta=$total/$cantidad_ventas;

		$cond=mt_rand(8,10);

		 if($contador_alt==8){
			 $aumento = mt_rand($promedio_venta,1000);
			 $promedio_venta+=$aumento;
			 $contador_alt=0;
		 }
		 //echo('aqui');
		 break;
		
	   }
   
   }
		 
   $contador_alt++;

  $sql="UPDATE  ventas
   SET monto_pagado='$contador' WHERE 	id_venta=$id_venta_g";
   $q=mysql_query($sql);

   $conrador_v++;
   
   $consumido-=$contador;
 $impresion.=imprimir_mesa($id_venta_g,'cobrar',$cliente,$numero);
     
		}
		$i+=86400;

		
	 }
	}

	$consumido=$total+(-1*$consumido);

	$impresion=base64_encode($impresion);
 echo('1|'.$consumido.'|'.$impresion);