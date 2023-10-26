<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos


//Formateamos y validamos los valores
$header_1=limpiaStr($header_1,1,1);
$header_2=limpiaStr($header_2,1,1);
$header_3=limpiaStr($header_3,1,1);
$header_4=limpiaStr($header_4,1,1);
$header_5=limpiaStr($header_5,1,1);
$header_6=limpiaStr($header_6,1,1);
$header_7=limpiaStr($header_7,1,1);
$header_8=limpiaStr($header_8,1,1);
$header_9=limpiaStr($header_9,1,1);
$header_10=limpiaStr($header_10,1,1);
$footer_1=limpiaStr($footer_1,1,1);
$footer_2=limpiaStr($footer_2,1,1);
$footer_3=limpiaStr($footer_3,1,1);
$footer_4=limpiaStr($footer_4,1,1);
$footer_5=limpiaStr($footer_5,1,1);
$footer_6=limpiaStr($footer_6,1,1);
$footer_7=limpiaStr($footer_7,1,1);
$footer_8=limpiaStr($footer_8,1,1);
$footer_9=limpiaStr($footer_9,1,1);
$footer_10=limpiaStr($footer_10,1,1);



	//Insertamos datos
	$sql="UPDATE configuracion 
	SET 
	header_1='$header_1',
	header_2='$header_2',
	header_3='$header_3', 
	header_4='$header_4',
	header_5='$header_5',
	header_6='$header_6',
	header_7='$header_7',
	header_8='$header_8',
	header_9='$header_9',
	header_10='$header_10',
	footer_1='$footer_1',
	footer_2='$footer_2',
	footer_3='$footer_3', 
	footer_4='$footer_4',
	footer_5='$footer_5',
	footer_6='$footer_6',
	footer_7='$footer_7',
	footer_8='$footer_8',
	footer_9='$footer_9',
	footer_10='$footer_10'
	
	
	
	";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>