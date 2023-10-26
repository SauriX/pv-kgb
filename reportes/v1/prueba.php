<? ob_start(); ?>

<page backtop="8mm" backbottom="0mm" backleft="10mm" backright="10mm"> 
	<page_header> 
    TIENDAFACIL - TICKET DE COMPRA<br/>
    NOMBRE DE TIENDITA
   	</page_header> 
    <page_footer> 
    nada
    </page_footer> 
   --------------------------------------------------<BR>

1 COCA COLA LATA			5.00<br>
1 COCA COLA LATA			5.00<br>
1 COCA COLA LATA			5.00<br>
1 COCA COLA LATA			5.00<br>
1 COCA COLA LATA			5.00<br>
1 COCA COLA LATA			5.00<br>
1 COCA COLA LATA			5.00<br>
--------------------------------------------------<BR>
	TOTAL: TU MADRE 1301.01
   </page> 	
<?php
/*
$html=ob_get_contents(); 
ob_end_clean(); 
$pdf = new HTML2PDF('P','A8','es', false, 'ISO-8859-15', array(10, 0, 5, 1)),;
//$pdf->pdf->SetDisplayMode('fullpage');
$pdf->WriteHTML($html); 
$pdf->Output('doc.pdf','I'); */


$content = ob_get_clean(); 
        require_once('html2pdfv4/html2pdf.class.php'); 
        $pdf = new HTML2PDF('P','A8','es', false, 'ISO-8859-15'); 
        $pdf->writeHTML($content); 
        $pdf->Output();
?>