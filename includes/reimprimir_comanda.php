<?
include('../includes/session.php');
include('../includes/db.php');
include('../includes/funciones.php');
include('../includes/impresora.php');

extract($_POST);
echo(imprimir_comandas('venta',$id_venta));