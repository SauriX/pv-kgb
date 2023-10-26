<?php
include('db.php');
include('impresora.php');
include('external_db.php');
include('num_letra.php');
include('qr.php');
$id_factura = $_POST['id_factura'];


$sql ="SELECT xml FROM facturas WHERE id_factura = $id_factura";	
$q = mysql_query($sql,$conexion2);
$ft=mysql_fetch_assoc($q);
$xml=$ft['xml'];

//inicio de la funcion
$xml = simplexml_load_file('http://tacoloco.mx/facturacion/archs_cfdi/'.$xml); 
$ns = $xml->getNamespaces(true);
$xml->registerXPathNamespace('c', $ns['cfdi']);
$xml->registerXPathNamespace('t', $ns['tfd']);
 
$ret_isr = 0.00;
$ret_iva = 0.00;
$cantidad_iva = (double)0.00;

foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
      $cfdiComprobante['version']; 
      
      $cfdiComprobante['fecha']; 
      
      $cfdiComprobante['sello']; 
       
      $total = (double)$cfdiComprobante['total'];      
      $subtotal = (double)$cfdiComprobante['subTotal'];
      
      $cfdiComprobante['certificado']; 
      
      $cfdiComprobante['formaDePago']; 
      
      $cfdiComprobante['noCertificado']; 
      
      $cfdiComprobante['tipoDeComprobante']; 
      $cfdiComprobante['LugarExpedicion'];
      $cfdiComprobante['metodoDePago'];
      $cfdiComprobante['condicionesDePago'];
      
      
      $cfdiComprobante['serie'];
	  $cfdiComprobante['folio'];
	  
	  $cfdiComprobante['motivoDescuento'];
	  $cfdiComprobante['Moneda'];
	  
	  $descuento = (double)$cfdiComprobante['descuento'];
	  
	  
      
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
   $Emisor['rfc']; 
   
   $Emisor['nombre']; 
   
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){ 
   $DomicilioFiscal['pais']; 
   
   $DomicilioFiscal['calle']; 
   
   $DomicilioFiscal['estado']; 
   
   $DomicilioFiscal['colonia']; 
   
   $DomicilioFiscal['municipio']; 
   
   $DomicilioFiscal['noExterior']; 
   
   $DomicilioFiscal['codigoPostal']; 
   
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:ExpedidoEn') as $ExpedidoEn){ 
   $ExpedidoEn['pais']; 
   
   $ExpedidoEn['calle']; 
   
   $ExpedidoEn['estado']; 
   
   $ExpedidoEn['colonia']; 
   
   $ExpedidoEn['noExterior']; 
   
   $ExpedidoEn['codigoPostal']; 
   
} 

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:RegimenFiscal') as $RegimenFiscal){ 
   $RegimenFiscal['Regimen']; 

} 


foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
   $Receptor['rfc']; 
   
   $Receptor['nombre']; 
   
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){ 
   $ReceptorDomicilio['pais']; 
   
   $ReceptorDomicilio['calle']; 
   
   $ReceptorDomicilio['estado']; 
   
   $ReceptorDomicilio['colonia']; 
   
   $ReceptorDomicilio['municipio']; 
   
   $ReceptorDomicilio['noExterior']; 
   
   $ReceptorDomicilio['noInterior']; 
   
   $ReceptorDomicilio['codigoPostal']; 
   
} 

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){ 

   $Traslado['tasa'];
   $Traslado['importe'] = (double)$Traslado['importe'];
   $Traslado['impuesto']; 
   
   if($Traslado['impuesto']=='IVA'){
	   $cantidad_iva+=(double)$Traslado['importe'];
   }
     
   
} 

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $Retenciones){ 
   
   $Retenciones['importe']; 
   $Retenciones['impuesto']; 
   
   if($Retenciones['impuesto']=='IVA'){
	   $ret_iva+=(double)$Retenciones['importe'];
   }
   if($Retenciones['impuesto']=='ISR'){
	   $ret_isr+=(double)$Retenciones['importe'];
   }
     
   
} 

 
//ESTA ULTIMA PARTE ES LA QUE GENERABA EL ERROR
foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
   $tfd['selloCFD']; 
   
   $tfd['FechaTimbrado']; 
   
   $tfd['UUID']; 
   
   $tfd['noCertificadoSAT']; 
   
   $tfd['version']; 
   
   $tfd['selloSAT']; 
} 
function ProcesImpTot($ImpTot){
        $ArrayImpTot = explode(".", $ImpTot);
        $NumEnt = $ArrayImpTot[0];
        $NumDec = ProcesDecFac($ArrayImpTot[1]);
        return $NumEnt.".".$NumDec;
    }
    
function ProcesDecFac($Num){
    $FolDec = "";
    if ($Num < 10){$FolDec = "00000".$Num;}
    if ($Num > 9 and $Num < 100){$FolDec = $Num."0000";}
    if ($Num > 99 and $Num < 1000){$FolDec = $Num."000";}
    if ($Num > 999 and $Num < 10000){$FolDec = $Num."00";}
    if ($Num > 9999 and $Num < 100000){$FolDec = $Num."0";}
    return $FolDec;
}
//termino de la funcion

//receptor
$re_pais=$ReceptorDomicilio['pais'];   
$re_calle=$ReceptorDomicilio['calle'];   
$re_estado=$ReceptorDomicilio['estado']; 
$re_colonia=$ReceptorDomicilio['colonia'];  
$re_municipio=$ReceptorDomicilio['municipio'];  
$re_no_exterior=$ReceptorDomicilio['noExterior'];   
$re_nointerior=$ReceptorDomicilio['noInterior']; 
$re_codigopostal=$ReceptorDomicilio['codigoPostal'];
$re_rfc=$Receptor['rfc']; 
$re_nombre=$Receptor['nombre'];
$re_localidad = $ReceptorDomicilio['localidad'];

//emisor
$emi_pais=$DomicilioFiscal['pais'];  
$emi_calle=$DomicilioFiscal['calle'];   
$emi_estado=$DomicilioFiscal['estado'];   
$emi_colonia=$DomicilioFiscal['colonia'];   
$emi_municipio=$DomicilioFiscal['municipio'];   
$emi_noexterior=$DomicilioFiscal['noExterior'];    
$emi_codigopostal=$DomicilioFiscal['codigoPostal'];
$emi_rfc=$Emisor['rfc']; 
$emi_nombre=$Emisor['nombre'];

$emi_localidad=$DomicilioFiscal['localidad'];

$exp_localidad=$DomicilioFiscal['localidad'];

//expendio
$exp_pais=$ExpedidoEn['pais'];  
$exp_calle=$ExpedidoEn['calle']; 
$exp_estado=$ExpedidoEn['estado'];
$exp_municipio=$DomicilioFiscal['municipio'];  
$exp_colonia=$ExpedidoEn['colonia'];   
$exp_noexterior=$ExpedidoEn['noExterior']; 
$exp_codigopostal=$ExpedidoEn['codigoPostal'];


//variables de certificaddo
$certificado_sat=$tfd['version'].'|'.$tfd['UUID'].'|'.$tfd['FechaTimbrado'].'|'.substr($tfd['selloCFD'],0,45).substr($tfd['selloCFD'],45,90).'<br>'.substr($tfd['selloCFD'],135,800).'|'.$tfd['noCertificadoSAT'].'||';

$sello_cfdi=substr($tfd['selloCFD'],0,92).substr($tfd['selloCFD'],92,500);

$sello_sat = $tfd['selloSAT'];

$fecha_timbrado = $cfdiComprobante['fecha'];
$folio_sat=$tfd['UUID'];
$folio_interno = $cfdiComprobante['serie'].$cfdiComprobante['folio'];
$Cadena = "?re=".$Emisor['rfc']."&rr=".$Receptor['rfc']."&tt=".$CadImpTot."&id=".$tfd['UUID'];
$name = $Emisor['rfc'].$tfd['UUID'];
$subtotal = number_format($subtotal,2);
$cantidad_iva = number_format($cantidad_iva,2);
$TOTAL = number_format($total,2);
$leyenda = "Este documento es una representacion impresa de un CFDI";
$c_emisor = $cfdiComprobante['noCertificado'];
$c_sat = $tfd['noCertificadoSAT'];

$forma_pago = mb_strtoupper($cfdiComprobante['formaDePago'], 'UTF-8');
$metodo_pago = mb_strtoupper($cfdiComprobante['metodoDePago'], 'UTF-8');
$metodo = mb_strtoupper($cfdiComprobante['metodoDePago'], 'UTF-8');
$tipo_comprobante = mb_strtoupper($cfdiComprobante['tipoDeComprobante'], 'UTF-8');
$numero_cuenta = $cfdiComprobante['NumCtaPago'];
$total_letra = mb_strtoupper(NumLet($total),'UTF-8');

  

// creacion del QR
qr($Cadena,$name);
//
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){
	 $cantidad = $Concepto['cantidad'];
	 $unidad=$Concepto['unidad'];
	 $descripcion=$Concepto['descripcion'];
	 $importe=number_format((double)$Concepto['importe'],2);
	 $desc_u= $Concepto['descripcion'];

ticket_factura($re_pais,$re_calle,$re_colonia,$re_municipio,$re_no_exterior,$re_nointerior,$re_codigopostal,$re_rfc,$re_nombre,$emi_pais,$emi_calle,$emi_estado,$emi_colonia,$emi_municipio,$emi_noexterior,$emi_codigopostal,$emi_pais,$emi_nombre,$emi_rfc,$exp_pais,$exp_calle,$exp_estado,$exp_colonia,$exp_noexterior,$exp_codigopostal,$emi_localidad,$exp_localidad,$exp_municipio,$certificado_sat,$sello_cfdi,$sello_sat,$fecha_timbrado,$folio_sat,$folio_interno,$cantidad,$unidad,$descripcion,$importe,$desc_u,$name,$subtotal,$cantidad_iva,$leyenda,$TOTAL,$c_emisor,$c_sat,$forma_pago,$metodo_pago,$tipo_comprobante,$numero_cuenta,$metodo,$total_letra,$re_localidad,$re_estado);

}


?>