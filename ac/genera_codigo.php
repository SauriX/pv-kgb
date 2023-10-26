<?php

include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');
include('../includes/impresora.php');


extract($_POST);

if(!is_numeric($codigo_monto)) exit('Ingrese el monto de la factura.');
if(!is_numeric($codigo_metodo_pago)) exit('Seleccione método de pago.');

obtenCodigo($codigo_monto,$codigo_metodo_pago,$codigo_digitos);

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
    
    echo $contents;
    
    switch($METODO){
    	case "1":
    	$metodo_ok = "EFECTIVO";
    	break;    
    	
    	case "2":
    	$metodo_ok = "TARJETA DE CREDITO";
    	break;    
    	
    	case "3":
    	$metodo_ok = "TRANSFERENCIA ELECTRONICA";
    	break;
        
    	case "4":
    	$metodo_ok = "CHEQUE";
	    break;
	     
    	case "5":
	    $metodo_ok = "NO IDENTIFICADO";
    	break;            
	}
	
	imprimir_codigo($contents,$MONTO,$metodo_ok,$NUMCTA);
	    
    if($returnInfo) {
        $info = curl_getinfo($ch);
    }

    curl_close($ch);

}
