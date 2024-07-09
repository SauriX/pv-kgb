<?

include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');

include('../includes/impresora.php');

include('../includes/postmark.php');

extract($_POST);


if(!$reimprime){

	mysql_query("BEGIN");

	if(!is_numeric($id_metodo_pago)) exit('Falta método de pago');

	if($id_metodo_pago<1) exit('Falta método de pago');

	switch($id_metodo_pago){
		case '2':
		case '3':
		case '4':
		$req_num = 1;
		break;
		default:
		$req_num = 0;
		break;
	}

	$sql = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo = $id_metodo_pago";
	$metodo_pago = @mysql_result(mysql_query($sql,$conexion), 0);

	if($req_factura==1){

		$facturado = '0';
		$monto_facturado = 0;

	}elseif($req_factura==2){

		$facturado = '1';

		if($req_num){
			if(!$num_cta_txt) exit('Falta número de cuenta1');
			if(strlen($num_cta_txt)!=4) exit('Número de cuenta debe ser de 4 dígitos');
		}

		//$codigo = obtenCodigo($monto_facturado,$id_metodo_pago,$num_cta_txt);

	}else{
		$req_factura = 2;
		//exit('Seleccione si requiere Factura');
	}

	if($monto_facturado>$consumo_txt) exit('El consumo no puede ser mayor al monto a facturar');
	
	if($tc){
		$consumo_txt = $total_txt;
	}
	$fechahora_pagada = date('Y-m-d H:i:s');

	if($iva_total!=0){
		$facturado=1;
	}
   
	$sql="UPDATE ventas SET  pagada=1,abierta=0,id_metodo='$id_metodo_pago',num_cta='$num_cta_txt',facturado='$iva',monto_facturado='$iva_efect',monto_pagado='$consumo_txt',	codigo = '$codigo',	metodo_txt = '$metodo_pago',	recibe_txt = '$recibe_txt',	cambio_txt = '$cambio_txt',	fechahora_pagada = '$fechahora_pagada',	descuento_txt = '$descuento_txt',	DescEfec_txt = '$DescEfec_txt',	pagarOriginal = '$pagarOriginal' WHERE id_venta = '$id_venta_cobrar'";

	$query = mysql_query($sql,$conexion);
	if(!$query) $error = true;





	if($error==false){
		mysql_query("COMMIT");
		$sql = "SELECT mesa,reabierta FROM ventas WHERE id_venta = $id_venta_cobrar";
		$q_datos = mysql_fetch_assoc(mysql_query($sql,$conexion));
		$mesa = $q_datos['mesa'];
		$reabierta = $q_datos['reabierta'];
		$mesa =mysql_result(mysql_query($sql,$conexion),0);


		if($check_imprimir == 'false'){

			$var=imprimir_mesa($id_venta_cobrar,'cobrar',$cliente,$numero);

		}else {
			abrir_caja();
		}

		$A="SELECT email_notificacion FROM configuracion ";
		$B = mysql_query($A,$conexion);
		$C = mysql_fetch_assoc($B);
		$correo=$C['email_notificacion'];

		if($reabierta>0){
			$sql = "SELECT motivo FROM ventas_cancelaciones WHERE id_venta_cancelacion = $reabierta";
			$motivo = @mysql_result(mysql_query($sql,$conexion), 0);
			$html = acuse($id_venta_cobrar,$reabierta,$motivo);

	    $remite = "VendeFacil <bot@adminus.mx>";
	    $dato = $html;
	    $postmark = new Postmark(null,$remite);
	    $postmark->to($correo);
	    $postmark->subject('Alerta de Cancelación');
	    $postmark->html_message($dato);
	    $postmark->send();

		}

		echo '1|'.$var;
	}else{
		mysql_query("ROLLBACK");
		echo "Hubo problema, por favor intenta de nuevo ".$sql;
	}


}else{

	if($facturar_only){


		if(!is_numeric($id_metodo_pago)) exit('Falta método de pago');

		if($id_metodo_pago<1) exit('Falta método de pago');

		switch($id_metodo_pago){
			case '2': case '3': case '4': $req_num = 1;
			break;
			default: $req_num = 0;
			break;
		}

		$sql = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo = $id_metodo_pago";
		$metodo_pago = @mysql_result(mysql_query($sql,$conexion), 0);

		$facturado = '1';

		if($req_num){
			if(!$num_cta_txt) exit('Falta número de cuenta2');
			if(strlen($num_cta_txt)!=4) exit('Número de cuenta debe ser de 4 dígitos');
		}

		//$codigo = obtenCodigo($monto_facturado,$id_metodo_pago,$num_cta_txt);
		$fechahora_pagada = date('Y-m-d H:i:s');

		$sql="UPDATE ventas SET
		id_metodo = '$id_metodo_pago',
		num_cta = '$num_cta_txt',
		facturado = '$facturado',
		monto_facturado = '$monto_facturado',
		codigo = '$codigo',
		metodo_txt = '$metodo_pago',
		fechahora_pagada = '$fechahora_pagada' WHERE id_venta = $id_venta_cobrar";

		$q = mysql_query($sql,$conexion);


	}


	$sql = 	"SELECT*FROM ventas WHERE id_venta = $id_venta";
	$q = mysql_query($sql,$conexion);
	$data = mysql_fetch_assoc($q);

	$mesa = $data['mesa'];
	$facturado = $data['facturado'];
	$codigo = $data['codigo'];
	$monto_facturado = $data['monto_facturado'];
	$metodo_pago = $data['metodo_txt'];
	$recibe_txt = $data['recibe_txt'];
	$cambio_txt = $data['cambio_txt'];

	$reabierta = $data['reabierta'];



	$var=imprimir_mesa($id_venta,'cobrar',$cliente,$numero);



		//aqui
	echo '1|'.$var;

	if($reabierta>0){
		$sql = "SELECT motivo FROM ventas_cancelaciones WHERE id_venta_cancelacion = $reabierta";
		$motivo = @mysql_result(mysql_query($sql), 0);
		acuse($id_venta,$reabierta,$motivo);
	}

}

/*
function obtenCodigo($MONTO,$METODO,$NUMCTA){

    $ch = curl_init();
    $method = "GET";

    $url = "http://tacoloco.mx/genera_pre.php";
	$data = "monto=$MONTO&metodo=$METODO&cta=$NUMCTA";
	//echo $url.$data;
	if($method == 'POST') {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        if($data !== false) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    } else {
        if($data !== false) {
            if(is_array($data)) {
                $dataTokens = array();
                foreach($data as $key => $value) {
                    array_push($dataTokens, urlencode($key).'='.urlencode($value));
                }
                $data = implode('&', $dataTokens);
            }
            curl_setopt($ch, CURLOPT_URL, $url.'?'.$data);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $contents = curl_exec($ch);

    if($returnInfo) {
        $info = curl_getinfo($ch);
    }

    curl_close($ch);

    if($returnInfo) {
        return array('contents' => $contents, 'info' => $info);
    } else {
        return $contents;
    }

}
*/
