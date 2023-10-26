<?

//Utilerias
date_default_timezone_set("America/Bogota");
$fechahora=date("Y-m-d H:i:s");
$fecha_actual=date("Y-m-d");

//codigo hash
function generateRandomString($length) {
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

 //valida si hay internet

 function is_connected() { 
	 $connected = fopen("http://www.google.com:80/","r"); 
	 if($connected) { return true; } else { return false; } } 
//Valida cadena de fecha
function validaStrFecha($fecha,$ano=false){
	if(!$ano){
		if( (is_numeric($fecha)) && (strlen((string)$fecha)==2) ){
			return true;
		}else{
			return false;
		}
	}else{
		if( (is_numeric($fecha)) && (strlen((string)$fecha)==4) ){
			return true;
		}else{
			return false;
		}
	}
}

//Encripta contrase–a
function contrasena($contrasena){
	return md5($contrasena);
}
//Valida c—digo postal
function validarCP($cp){
	if( (is_numeric($cp)) && (strlen($cp)==5) ){
		return true;
	}else{
		return false;
	}
}
//Valida teléfono
function validarTelefono($telefono){
	if( (is_numeric($telefono)) && (strlen($telefono)==10) ){
		return true;
	}else{
		return false;
	}
}
//Validar email
function validarEmail($email){
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}
//Formatear cadenas
function limpiaStr($v,$base=false,$m=false){
 $v = trim($v);
 if($m){
 	$v =  mb_convert_case($v, MB_CASE_UPPER, "UTF-8");
 }else{
	$v =  mb_convert_case($v, MB_CASE_TITLE, "UTF-8");
 }
 if($base){
	 $v = mysql_real_escape_string(strip_tags($v));
 }
 return  $v;
}
//Funcion para escapar
function escapar($cadena,$numerico=false){
	if($numerico){
		if(is_numeric($cadena)){
			return mysql_real_escape_string($cadena);
		}else{
			return false;
		}
	}else{
		return mysql_real_escape_string(strip_tags($cadena));
	}
}
//Fecha para base de datos
function fechaBase($fecha){
	list($mes,$dia,$anio)=explode("/",$fecha);

	$dia=(string)(int)$dia;
	return $anio."-".$mes."-".$dia;
}
//Para mostrar fecha
function fechaSinHora($fecha){
	return $fecha=substr($fecha,0,11);
}



function horaSinFecha($fecha){
	return $fecha=substr($fecha,11,5);
	//2017-12-13 18:59:01
}
//Fecha sin hora
function fechaLetra($fecha){

	list($anio,$mes,$dia)=explode("-",$fecha);
	switch($mes){
	case 1:
	$mest="Ene";
	break;
	case 2:
	$mest="Feb";
	break;
	case 3:
	$mest="Mar";
	break;
	case 4:
	$mest="Abr";
	break;
	case 5:
	$mest="May";
	break;
	case 6:
	$mest="Jun";
	break;
	case 7:
	$mest="Jul";
	break;
	case 8:
	$mest="Ago";
	break;
	case 9:
	$mest="Sep";
	break;
	case 10:
	$mest="Oct";
	break;
	case 11:
	$mest="Nov";
	break;
	case 12:
	$mest="Dic";
	break;

	}
	$dia=(string)(int)$dia;
	return $dia." ".$mest." ".$anio;
}

function fechaLetraDos($fecha){

	list($anio,$mes,$dia)=explode("-",$fecha);
	switch($mes){
	case 1:
	$mest="Ene";
	break;
	case 2:
	$mest="Feb";
	break;
	case 3:
	$mest="Mar";
	break;
	case 4:
	$mest="Abr";
	break;
	case 5:
	$mest="May";
	break;
	case 6:
	$mest="Jun";
	break;
	case 7:
	$mest="Jul";
	break;
	case 8:
	$mest="Ago";
	break;
	case 9:
	$mest="Sep";
	break;
	case 10:
	$mest="Oct";
	break;
	case 11:
	$mest="Nov";
	break;
	case 12:
	$mest="Dic";
	break;

	}
	$dia=$dia;
	return $dia."/".$mest."/".$anio;
}

function fechaLetraTres($fecha){

	list($anio,$mes,$dia)=explode("-",$fecha);
	switch($mes){
	case 1:
	$mest="Enero";
	break;
	case 2:
	$mest="Febrero";
	break;
	case 3:
	$mest="Marzo";
	break;
	case 4:
	$mest="Abril";
	break;
	case 5:
	$mest="Mayo";
	break;
	case 6:
	$mest="Junio";
	break;
	case 7:
	$mest="Julio";
	break;
	case 8:
	$mest="Agosto";
	break;
	case 9:
	$mest="Septiembre";
	break;
	case 10:
	$mest="Octubre";
	break;
	case 11:
	$mest="Noviembre";
	break;
	case 12:
	$mest="Diciembre";
	break;

	}
	$dia=$dia;
	return $dia." de ".$mest." del ".$anio;
}

//Obtener el mes
function soloMes($mes){

	switch($mes){
	case 1:
	$mest="Enero";
	break;
	case 2:
	$mest="Febrero";
	break;
	case 3:
	$mest="Marzo";
	break;
	case 4:
	$mest="Abril";
	break;
	case 5:
	$mest="Mayo";
	break;
	case 6:
	$mest="Junio";
	break;
	case 7:
	$mest="Julio";
	break;
	case 8:
	$mest="Agosto";
	break;
	case 9:
	$mest="Septiembre";
	break;
	case 10:
	$mest="Octubre";
	break;
	case 11:
	$mest="Noviembre";
	break;
	case 12:
	$mest="Diciembre";
	break;

	}
	return $mest;
}
function fnum($num,$sinDecimales = false, $sinNumberFormat = false){

//SinDecimales = TRUE: envias: 1500.1234 devuelve: 1,500
//SinNumberFormat = TRUE: envias 1500.1234 devuelve 1500.12
//SinNumberFormat = TRUE && SinDecimales = TRUE: envias: 1500.1234 devuelve 1500

	if(is_numeric($num)){
		$roto = explode('.',$num);
		if($roto[1]){
			$dec = substr($roto[1],0,2);
		}else{
			$dec = "00";
		}

		if(is_numeric($roto[0])){
			if($sinDecimales){
				if($sinNumberFormat){
					return $roto[0];
				}else{
					return number_format($roto[0]);
				}
			}else{
				if($sinNumberFormat){
					return $roto[0].'.'.$dec;
				}else{
					return number_format($roto[0]).'.'.$dec;
				}
			}
		}else{
			if($sinDecimales){
				return '0';
			}else{
				return '0.'.$dec;
			}
		}
	}else{
		if($sinDecimales){
			return '0';
		}else{
			return '0.00';
		}
	}

}
function tipo_usuario($id_tipo_usuario){
	$sql="SELECT tipo FROM tipo_usuario WHERE id_tipo_usuario=$id_tipo_usuario";
	$q=mysql_query($sql);
	$ft=mysql_fetch_assoc($q);
	$tipo=$ft['tipo'];
	return $tipo;
}

function acentos($cadena){
    $originales =  'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    return utf8_encode($cadena);
}

function obtenerDeuda($id_cliente){

	global $conexion;

	$sql = "SELECT SUM(monto) FROM abonos WHERE id_cliente = '$id_cliente'";
	$q = mysql_query($sql);
	$abonos = mysql_result($q, 0);

	$sql ="SELECT * FROM creditos WHERE id_cliente = '$id_cliente'";
	$q = mysql_query($sql);

	while($ft = mysql_fetch_assoc($q)){

		$id_venta = $ft['id_venta'];

		$sql ="SELECT * FROM venta_detalle WHERE id_venta = $id_venta";
		$qq = mysql_query($sql);

		while($fx = mysql_fetch_assoc($qq)){

			$cantidad = $fx['cantidad'];
			$precio_venta = $fx['precio_venta'];
			$adeuda+= $cantidad*$precio_venta;

		}
	}

	$debe = $adeuda-$abonos;

	if($debe==0){
		return  "0.00";
	}else{
		return $debe;
	}

}


function devuelveFechaHora($fecha_hora){


$data = explode(' ', $fecha_hora);

return fechaLetraDos($data[0]).' · '.substr($data[1], 0,5);



}
function horaVista($hora) {

	return	date('h:i A', strtotime($hora));

}

function fechaHoraMeridian($fecha_hora){
	$data = explode(' ', $fecha_hora);
	return fechaLetraTres($data[0]).' - '.date('h:i A', strtotime($fecha_hora));
}
function fechaHoraVista($fecha_hora){
	$data = explode(' ', $fecha_hora);
	return strtoupper(fechaLetraDos($data[0]).' - '.date('h:i A', strtotime($fecha_hora)));
}

function fechaHoraMeridian2($fecha_hora){
	$data = explode('T', $fecha_hora);
	return fechaLetraTres($data[0]).' - '.date('h:i A', strtotime($fecha_hora));
}

function hace($fecha,$fecha2=false){
	date_default_timezone_set("America/Bogota");

		if($fecha2){
			$ahora=strtotime($fecha2);
		}else{
			$ahora = time();
		}
        $segundos = $ahora-strtotime($fecha);
        $dias = floor($segundos/86400);
        $mod_hora = $segundos%86400;
        $horas = floor($mod_hora/3600);
        $mod_minuto = $mod_hora%3600;
        $minutos = floor($mod_minuto/60);

		if($horas==1){
			$hrx = 'HR';
		}else{
			$hrx = 'HRS';
		}

		if($horas<=0){
                return $minutos." MINS";
        }elseif($dias<=0){
                return $horas." $hrx ".$minutos." MINS";
        }else{
                return $dias." DIA ".$horas." $hrx ".$minutos." MINS";
        }
}

function eliminar_tildes($cadena){

    $cadena = utf8_encode($cadena);

    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}

function agregar_a_venta($id_venta_domicilio,$nombre){
	global $conexion;
	$total_venta = total_venta_domicilio($id_venta_domicilio);
	$descuento =  total_descuento_venta($id_venta_domicilio);
	$total_envio = total_envio_domicilio($id_venta_domicilio);
	mysql_close($conexion);
	include('../../app/includes/db.php');
	$fecha = date('Y-m-d');
	$hora = date('H:i:s');
	$total = ($total_venta + $total_envio) - $descuento;
	if($total>0){
		$nombre = "DOM-".$id_venta_domicilio."-".$nombre;
		$sql = "INSERT INTO ventas (id_usuario,fecha,hora,mesa,abierta,fechahora_cerrada)VALUES('1','$fecha','$hora','$nombre',0,'$fecha $hora')";
		$q = mysql_query($sql);
		if($q) {
			$id_venta = mysql_insert_id();
			$sql="INSERT INTO venta_detalle
			(id_venta,id_producto,cantidad,precio_venta)VALUES('$id_venta','343','1','$total')";
			$query = mysql_query($sql);
		}
	}
	#$q = mysqli_query($);

}
function total_venta_domicilio($id_venta_domicilio){
	global $conexion;
	$id_costo_envio = id_costo_envio();
	$sql = "SELECT SUM(cantidad*precio_venta) as total FROM venta_domicilio_detalle WHERE id_venta_domicilio = $id_venta_domicilio AND id_producto != $id_costo_envio";
	$q = mysql_query($sql);
	return @mysql_result($q,0);
}
function total_descuento_venta($id_venta_domicilio){
	global $conexion;
	$sql = "SELECT descuento_cantidad FROM ventas_domicilio WHERE id_venta_domicilio = $id_venta_domicilio";
	$q = mysql_query($sql);
	return @mysql_result($q,0);
}


function total_envio_domicilio($id_venta_domicilio){
	global $conexion;
	$id_costo_envio = id_costo_envio();
	$sql = "SELECT SUM(cantidad*precio_venta) as total FROM venta_domicilio_detalle WHERE id_venta_domicilio = $id_venta_domicilio AND id_producto = $id_costo_envio";
	$q = mysql_query($sql);
	return @mysql_result($q,0);
}

function id_costo_envio(){
	global $conexion;
	$sql = "SELECT id_producto FROM productos WHERE codigo = 'SERVDOM' LIMIT 1";
	$q = mysql_query($sql);
	return @mysql_result($q,0);
}
