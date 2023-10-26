<?php
include("includes/db.php");


include("includes/impresora.php");



echo(imprimir_corte(382,true));

$printer = esc_pos_open("EPSON", "ch-latin-2", false, true);
 esc_pos_align($printer, "center");
 esc_pos_font($printer, "A");
 esc_pos_char_width($printer, "");
 esc_pos_line($printer, "CANGRE BURGER");
 esc_pos_line($printer, "CORTE DE CAJA #382");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, "FECHA APERTURA: 2021-02-26 16:49:17");
 esc_pos_line($printer, "FECHA CORTE: 2021-02-26 22:38:27");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, "################# VENTA ##################");
 esc_pos_align($printer, "left");
 esc_pos_line($printer, "PRODUCTO CANT UNIT SUBT");
esc_pos_line($printer, "PITAYA 3 18.00 54.00");
esc_pos_line($printer, "JAMAICA 2 18.00 36.00");
esc_pos_line($printer, "LIMONADA 1 18.00 18.00");
esc_pos_line($printer, "DEDOS DE QUESO 2 60.00 120.00");
esc_pos_line($printer, "ALITAS 10PZS 1 119.00 119.00");
esc_pos_line($printer, "ESPAÃ‘OLA 2 60.00 120.00");
esc_pos_line($printer, "RANCHERA 9 60.00 540.00");
esc_pos_line($printer, "MOZZARELA 2 60.00 120.00");
esc_pos_line($printer, "SUPREMA 1 75.00 75.00");
esc_pos_line($printer, "KGB 2 85.00 170.00");
esc_pos_line($printer, "BBQ HOT 1 70.00 70.00");
esc_pos_line($printer, "CUBANA 1 70.00 70.00");
esc_pos_line($printer, "CUBANA DOBLE 2 99.00 198.00");
esc_pos_line($printer, "FEROZ 1 65.00 65.00");
esc_pos_line($printer, "MEXICANA 4 60.00 240.00");
esc_pos_line($printer, "TROPICANA 2 65.00 130.00");
esc_pos_line($printer, "CAVERNICOLA 3 75.00 225.00");
esc_pos_line($printer, "PARRILLERA 1 60.00 60.00");
esc_pos_line($printer, "CHEESE 4 60.00 240.00");
esc_pos_line($printer, "INCOGNITA 3 70.00 210.00");
esc_pos_line($printer, "ITALIANA 5 60.00 300.00");
esc_pos_line($printer, "MR. KRAB 3 60.00 180.00");
esc_pos_line($printer, "GABACHA 5 58.00 290.00");
esc_pos_line($printer, "GABACHA DOBLE 1 88.00 88.00");
esc_pos_line($printer, "HAWAIANA 20 58.00 1160.00");
esc_pos_line($printer, "SENCILLA 13 48.00 624.00");
esc_pos_line($printer, "NUGGETS 4 45.00 180.00");
esc_pos_line($printer, "AROS DE CEBOLLA 2 40.00 80.00");
esc_pos_line($printer, "TENDERS 1 80.00 80.00");
esc_pos_line($printer, "(EXTRA)SALAMI 1 10.00 10.00");
esc_pos_line($printer, "(EXTRA)PINA 1 10.00 10.00");
esc_pos_line($printer, "(EXTRA)CHAMPINON 1 10.00 10.00");
esc_pos_line($printer, "TOP SIRLOIN 2 115.00 230.00");
esc_pos_line($printer, "ORDEN PAPAS 4 40.00 160.00");
esc_pos_line($printer, "BOTANERO 1 65.00 65.00");
esc_pos_line($printer, "WAFLES 3 48.00 144.00");
esc_pos_line($printer, "COCA 600ML 2 22.00 44.00");
esc_pos_line($printer, "SERV. DOM. CERCANO 2 30.00 60.00");
esc_pos_line($printer, "(PAQ)BOX ALI-BURGUER1 159.00 159.00");
esc_pos_line($printer, "ORDEN PAPAS MESA 1 40.00 40.00");
esc_pos_line($printer, "------------------------------------------");
 esc_pos_align($printer, "right");
 esc_pos_line($printer, "VENTA SUBTOTAL: 6794.00");
 esc_pos_line($printer, "DESCUENTOS: 0.00");
 esc_pos_line($printer, "VENTA TOTAL: 6794.00");
 esc_pos_font($printer, "A");
 esc_pos_align($printer, "left");
 esc_pos_line($printer, "DESGLOSE:");
 esc_pos_line($printer, "EFECTIVO: 6320");
 esc_pos_line($printer, "TARJETAS: 0.00");
 esc_pos_line($printer, "TRANSFERENCIAS: 474");
 esc_pos_line($printer, "");
 esc_pos_line($printer, "CUENTAS EXPEDIDAS: 39");
 esc_pos_line($printer, "PROMEDIO POR CUENTA: 174.21");
 esc_pos_line($printer, "CANCELACIONES: 0");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, "################# GASTOS #################");
 esc_pos_line($printer, "DESCRIPCION MONTO");
 esc_pos_line($printer, "PAN 125.00");
esc_pos_line($printer, "COCA 50.00");
esc_pos_line($printer, "DOS MOTOS TRANFERENCIA 70.00");
esc_pos_line($printer, "PAN Y SERVICIO 55.00");
esc_pos_line($printer, "PROPINA 15.00");
esc_pos_line($printer, "TORTILLA 90.00");
esc_pos_line($printer, "------------------------------------------");
 esc_pos_align($printer, "right");
 esc_pos_line($printer, "TOTAL DE GASTOS: 405.00");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, "############### CORTE CAJA ###############");
 esc_pos_align($printer, "left");
 esc_pos_line($printer, "FONDO DE CAJA: 539.00");
 esc_pos_line($printer, "EFECTIVO: 6320");
 esc_pos_line($printer, "TARJETAS: 0.00");
 esc_pos_line($printer, "SUBTOTAL: 7333.00");
 esc_pos_line($printer, "GASTOS: 405.00");
 esc_pos_line($printer, "TOTAL: 6928.00");
 esc_pos_line($printer, "############## CORTE CAPTURA #############");
 esc_pos_align($printer, "left");
 esc_pos_line($printer, "EFECTIVO TOTAL: 6243.00");
 esc_pos_line($printer, "TARJETAS: 479.00");
 esc_pos_line($printer, "TOTAL: 6722.00");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, "------------------------------------------");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_align($printer, "right");
 esc_pos_line($printer, "TOTAL VENTA: 6928.00");
 esc_pos_line($printer, "TOTAL CAPTURA: 6722.00");
 esc_pos_line($printer, "FALTANTE: $206.00");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
esc_pos_line($printer, "############### INSUMOS ################");
esc_pos_line($printer, "PRODUCTO DOTA MERM CONSU EXIS");
esc_pos_line($printer, "PAN 78 0 88 0");
esc_pos_line($printer, "CARNE 95 0 89 6");
esc_pos_line($printer, "QUESO -271 0 71 0");
esc_pos_line($printer, "SALAMI -79 0 19 0");
esc_pos_line($printer, "PEPERONI -58 0 10 0");
esc_pos_line($printer, "CHORIZO -70 0 25 0");
esc_pos_line($printer, "JALAPENO -8 0 12 0");
esc_pos_line($printer, "MOZARELA 10 0 7 3");
esc_pos_line($printer, "PHILADELFIA 22 0 7 15");
esc_pos_line($printer, "SALCHICHA/ASAR 146 0 16 130");
esc_pos_line($printer, "TOCINO -129 0 25 0");
esc_pos_line($printer, "AGUACATE -40 0 7 0");
esc_pos_line($printer, "PINA 57 0 33 24");
esc_pos_line($printer, "CHAMPINON 23 2 13 8");
esc_pos_line($printer, "MANCHEGO -18 0 4 0");
esc_pos_line($printer, "SALCHICHA -205 0 48 0");
esc_pos_line($printer, "PEPINILLOS -10 0 2 0");
esc_pos_line($printer, "ARRACHERA 8 0 2 6");
esc_pos_line($printer, "QUESO DE BOLA 0 0 0 0");
esc_pos_line($printer, "PAPA 0 0 0 0");
esc_pos_line($printer, "DOBLE CARNE 0 0 0 0");
esc_pos_line($printer, "ALITAS DE POLLO 16 0 1 15");
esc_pos_line($printer, "TEN BURGER 1/2 1 0 0 1");
esc_pos_line($printer, "PEPINILLO 0 0 0 0");
esc_pos_line($printer, "SALSA PINA HABANERO 0 0 0 0");
esc_pos_line($printer, "SALSA PARMESANO 0 0 0 0");
esc_pos_line($printer, "BOX SNACK 68 0 15 53");
esc_pos_line($printer, "ORD TENDER 1 0 1 0");
esc_pos_line($printer, "ORD NUGET 13 0 4 9");
esc_pos_line($printer, "ORD WAFLE 7 0 3 4");
esc_pos_line($printer, "ORD AROS 1/2 16 1 5 10");
esc_pos_line($printer, "ORD GAJO 1/2 2 1 1 0");
esc_pos_line($printer, "ORD DEDOS 1/2 5 0 5 0");
esc_pos_line($printer, "CAJA ALITAS 5 0 0 5");
esc_pos_line($printer, "PASTOR ORDEN 0 0 0 0");
esc_pos_line($printer, "COCA 350ML 0 0 0 0");
esc_pos_line($printer, "COCA 600ML 5 0 2 3");
esc_pos_line($printer, "REFRESCO DE SABOR 0 0 0 0");
esc_pos_line($printer, "CARBON PZS 0 0 0 0");
esc_pos_line($printer, "CHIKEN BURGER 1 0 0 1");
esc_pos_line($printer, "COCA 1.700LT 2 0 0 2");
esc_pos_line($printer, "ALITAS 1/2 3 0 0 3");
esc_pos_line($printer, "AGUA 1LT 0 0 0 0");
esc_pos_line($printer, "SALSA MANGO HABANER 0 0 0 0");
esc_pos_line($printer, "SALSA CHESSTER CREA 0 0 0 0");
esc_pos_line($printer, "SALSA MANGO HABANER 0 0 0 0");
esc_pos_line($printer, "SALSA CHESSTER CREA 0 0 0 0");
esc_pos_line($printer, "CHAROLA BONELESS 8 0 1 7");
esc_pos_line($printer, "BONNE 1/2 0 0 0 0");
esc_pos_line($printer, "CUBRE BOCAS 0 0 0 0");
esc_pos_line($printer, "GORRA KGB 0 0 0 0");
esc_pos_line($printer, "PLAYERA HOMBRE 0 0 0 0");
esc_pos_line($printer, "AGUAS 2X55 0 0 0 0");
esc_pos_line($printer, "QUESO AMERICANO -66 0 13 0");
esc_pos_line($printer, "CHAROLA BOTANERO 4 0 2 2");
esc_pos_line($printer, "AGUA 500ML 12 0 6 6");
 esc_pos_line($printer, " ");
 esc_pos_align($printer, "center");
 esc_pos_line($printer, "RESPONSABLE");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, "______________________________");
 esc_pos_line($printer, "");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_line($printer, " ");
 esc_pos_cut($printer);
 esc_pos_close($printer);


