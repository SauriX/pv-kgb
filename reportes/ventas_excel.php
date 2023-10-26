<?
ini_set('memory_limit', '128M');
//exit("das");
set_time_limit(0);
include("../includes/db.php");
include("../includes/funciones.php");
date_default_timezone_set('America/Mexico_City');
extract($_POST);
//print_r($_POST);
$sql="";
if(!$fecha1) exit("Seleccione al menos una fecha.");
if($fecha2){
	$sql="SELECT * FROM ventas 
	JOIN usuarios ON usuarios.id_usuario=ventas.id_usuario
	JOIN metodo_pago ON metodo_pago.id_metodo=ventas.id_metodo
	WHERE pagada=1 AND fecha BETWEEN '$fecha1' AND '$fecha2'";
	$titulo="Reporte de Ventas del día ".fechaLetra($fecha1)." al ".fechaLetra($fecha2);
    $cadena_fecha="DEL ".fechaLetra($fecha1)." AL ".fechaLetra($fecha2);
}else{
	$sql="SELECT * FROM ventas 
	JOIN usuarios ON usuarios.id_usuario=ventas.id_usuario
	JOIN metodo_pago ON metodo_pago.id_metodo=ventas.id_metodo
	WHERE fecha='$fecha1'";	
	$titulo="Reporte de Ventas del día ".fechaLetraTres($fecha1);
    $cadena_fecha="DEL ".fechaLetra($fecha1);
}


$query=mysql_query($sql);
	$str = 'A';
	$cuenta=7;
	
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	
	setlocale(LC_MONETARY, 'Spanish_Mexican');
	
	if (PHP_SAPI == 'cli')
	die('Solo se puede ejecutar desde el navegador');
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
	
	$objPHPExcel = new PHPExcel();
	PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
	$objPHPExcel->getProperties()->setCreator("EPICMEDIA (www.epicmedia.pro)")
								 ->setLastModifiedBy("Epicmedia (www.epicmedia.pro)")
								 ->setTitle("REPORTE DE VENTAS")
								 ->setSubject($titulo)
								 ->setDescription("Reporte generado por Taco Loco - Vende Facil potencializado por www.epicmedia.pro")
								 ->setKeywords("tacoloco")
								 ->setCategory("ventas");
	
	$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()
	  		    ->setWidth(15);
    
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A1',"Tacoloco - Vende Facil 2.0");

	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A3',$titulo);
            	
    $objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A4', $cadena_fecha);

	$initStr = $str;
	//check for empty doctores
	
    $objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($str.'6','FECHA');      	
	$tit_1 = $str;
	$str = ++$str;
	$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($str.'6','HORA');      	
	$tit_2 = $str;
	$str = ++$str;
	$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($str.'6','METODO DE PAGO');      	
	$tit_3 = $str;
	$str = ++$str;
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($str.'6','TICKET');      	
	$tit_4 = $str;
	$str = ++$str;
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($str.'6','MONTO');      	
	$tit_5 = $str;
	$str = ++$str;
	
$celdas_total = $cuenta;
$rows_res = mysql_num_rows($query);
while($venta = mysql_fetch_assoc($query)){
	
			
		$objPHPExcel->setActiveSheetIndex(0)
					//->setCellValue($tit_2.$cuenta,money_format('%i',$dinero_dia)); 
                    ->setCellValue($tit_1.$cuenta,$venta["fecha"]); 
					
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($tit_2.$cuenta,$venta["hora"]);
					
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($tit_3.$cuenta,$venta["metodo_pago"]);
					
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($tit_4.$cuenta,$venta["id_venta"]);

        $objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($tit_5.$cuenta,number_format($venta["monto_pagado"]));
    
		$objPHPExcel->getActiveSheet()->getStyle($tit_5.$cuenta)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
    
		$celdas_total=$cuenta;
		$cuenta++;	
}
$cuenta++;

$styleArray = array(
		'borders' => array(
        'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
		)
    )
);

if($rows_res>0){
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($tit_1.$cuenta, 'TOTAL')
                ->setCellValue($tit_5.$cuenta, '=SUM('.$tit_5.'8:'.$tit_5.''.$celdas_total.')');
    
    $objPHPExcel->getActiveSheet()->getStyle($tit_5.$cuenta)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
    
    $objPHPExcel->getActiveSheet()->getStyle($tit_1.$cuenta.':'.$tit_5.$cuenta)->applyFromArray($styleArray);
    //$objPHPExcel->getActiveSheet()->mergeCells($tit_2.$cuenta.':'.$tit_.$cuenta);
}

		
$objPHPExcel->getActiveSheet()->getStyle($initStr.'6:'.($tit_5).''.($celdas_total))->applyFromArray($styleArray);
unset($styleArray);

//AQUI TERMINA LOS FILTROS
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Ventas&Tacolo Vende Facil 2.0 &D');
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');

$objPHPExcel->getActiveSheet()->getStyle('A6:'.$str.'6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A6:'.$str.'6')->getFont()->setSize(12);
	
$objPHPExcel->getActiveSheet()->getStyle('A1:C4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:C4')->getFont()->setSize(12);
	
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
//Tamaños de las celdas
$str_2 = 'A';
while($str_2 != $str){
	//$objPHPExcel->getActiveSheet()->getColumnDimension($str_2)->setWidth(15);
	++$str_2;
}
	//Mezclamos Celdas
$objPHPExcel->getActiveSheet()->mergeCells('A1:D2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
$objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
	
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Ventas Tacoloco');
	
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
	//print_r($objPHPExcel);
//exit;
	
	// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_de_ventas_TACOLOCO-VendeFacil.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
	
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
	
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;
?>