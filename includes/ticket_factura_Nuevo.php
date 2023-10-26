<?php
include('db.php');
include('impresoraNuevo.php');
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
      $cfdiComprobante['Version'];
      $cfdiComprobante['Serie'];
	    $cfdiComprobante['Folio'];
      $cfdiComprobante['Fecha'];
      $cfdiComprobante['MetodoPago'];
      $cfdiComprobante['FormaPago'];
      $cfdiComprobante['TipoDeComprobante'];
      $cfdiComprobante['Moneda'];
      $cfdiComprobante['Sello'];
      $total = (double)$cfdiComprobante['Total'];
      $subtotal = (double)$cfdiComprobante['SubTotal'];
      $cfdiComprobante['Certificado'];
      $cfdiComprobante['NoCertificado'];
      $cfdiComprobante['LugarExpedicion'];
      $cfdiComprobante['condicionesDePago'];
	    $cfdiComprobante['motivoDescuento'];
	    $descuento = (double)$cfdiComprobante['descuento'];



}

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){

   $Emisor['Rfc'];
   $Emisor['Nombre'];
   $Emisor['RegimenFiscal'];

}

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:RegimenFiscal') as $RegimenFiscal){
   $RegimenFiscal['RegimenFiscal'];
 }

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
   $Receptor['Rfc'];
   $Receptor['Nombre'];
   $Receptor['UsoCFDI'];
}

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){

   $Traslado['Importe'];
   $Traslado['Importe'] = (double)$Traslado['Importe'];
   $Traslado['Impuesto'];
  $TotalImpuestosTrasladados = $Traslado['Importe'];

   if($Traslado['Impuesto'] =='IVA'){
	   $cantidad_iva+=(double)$Traslado['Importe'];
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
   $tfd['SelloCFD'];
   $tfd['FechaTimbrado'];
   $tfd['UUID'];
   $tfd['NoCertificadoSAT'];
   $tfd['Version'];
   $tfd['SelloSAT'];
}

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){
   $cantidad = $Concepto['Cantidad'];
   $unidad = $Concepto['ValorUnitario'];
   $descripcion=$Concepto['Descripcion'];
   $importe=number_format((double)$Concepto['Importe'],2);
   $desc_u= $Concepto['Descripcion'];

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
  $re_rfc=$Receptor['Rfc'];
  $re_nombre=$Receptor['Nombre'];
  $re_UsoCFDI=$Receptor['UsoCFDI'];

  //emisor
  $emi_rfc=$Emisor['Rfc'];
  $emi_nombre=$Emisor['Nombre'];
  $emi_Regimen=$Emisor['RegimenFiscal'];

  //TimbreFiscalDigital
  $SelloCFD = $tfd['SelloCFD'];
  $FechaTimbrado = $tfd['FechaTimbrado'];
  $UUID = $tfd['UUID'];
  $NoCertificadoSAT = $tfd['NoCertificadoSAT'];
  $tfdVersion = $tfd['Version'];
  $SelloSAT = $tfd['SelloSAT'];
  $Serie1 =$cfdiComprobante['Serie'];


  //variables de certificaddo
  $certificado_sat = $tfd['Version'].'|'.$tfd['UUID'].'|'.$tfd['FechaTimbrado'].'|'.substr($tfd['SelloCFD'],0,45).substr($tfd['SelloCFD'],45,90).'<br>'.substr($tfd['SelloCFD'],135,800).'|'.$tfd['NoCertificadoSAT'].'||';
  $sello_cfdi=substr($tfd['SelloCFD'],0,92).substr($tfd['SelloCFD'],92,500);

  $sello_sat = $SelloSAT;

  $fecha_timbrado= $cfdiComprobante['Fecha'];
  $folio_sat=$tfd['UUID'];
  $folio_interno = $cfdiComprobante['Serie'].$cfdiComprobante['Folio'];
  $ultimos8=substr($SelloCFD, -8);
	$Cadena="https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id=$UUID&re=$emi_rfc&rr=$re_rfc&tt=$total&fe=$ultimos8";
  $name = $Emisor['Rfc'].$tfd['UUID'];
  $subtotal = number_format($subtotal,2);
  $cantidad_iva = number_format($cantidad_iva,2);
  $TOTAL = number_format($total,2);
  $leyenda = "Este documento es una representacion impresa de un CFDI 3.3";
  $c_emisor = $cfdiComprobante['NoCertificado'];
  $c_sat = $tfd['NoCertificadoSAT'];

  $forma_pago = mb_strtoupper($cfdiComprobante['FormaPago'], 'UTF-8');
  $metodo_pago = mb_strtoupper($cfdiComprobante['MetodoPago'], 'UTF-8');
  $metodo = mb_strtoupper($cfdiComprobante['MetodoPago'], 'UTF-8');
  $tipo_comprobante = mb_strtoupper($cfdiComprobante['TipoDeComprobante'], 'UTF-8');
  $numero_cuenta = $cfdiComprobante['NumCtaPago'];
  $total_letra = mb_strtoupper(NumLet($total),'UTF-8');

  // creacion del QR
  qr($Cadena,$name);

ticket_factura($UUID,$TotalImpuestosTrasladados,$Serie1,$re_UsoCFDI,$re_rfc,$re_nombre,$emi_Regimen,$emi_nombre,$emi_rfc,$certificado_sat,$sello_cfdi,$sello_sat,$fecha_timbrado,$folio_sat,$folio_interno,$cantidad,$unidad,$descripcion,$importe,$desc_u,$name,$subtotal,$cantidad_iva,$leyenda,$TOTAL,$c_emisor,$c_sat,$forma_pago,$metodo_pago,$tipo_comprobante,$numero_cuenta,$metodo,$total_letra,$re_localidad);

}


?>
