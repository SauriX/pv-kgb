<?
    //Inclucion de archivos necesarios
    include("includes/session_ui.php");
    include("includes/db.php");
    include("includes/funciones.php");
    $menu = isset($_GET['Modulo']) ? $_GET['Modulo']: NULL;
    $sql_corte ="SELECT * FROM cortes WHERE abierto = 1";
    $q_corte = mysql_query($sql_corte);
    $n_corte = mysql_num_rows($q_corte);
    $conf="SELECT * FROM configuracion ";
    $q_cconf = mysql_query($conf);
    $n_cconf= mysql_num_rows($q_cconf);
    $conf=mysql_fetch_assoc($q_cconf);
    $insumo=$conf['insumos'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="KGB grill">
        <link rel="icon" href="favicon.ico">
        <title>KGB grill </title>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
        <link href="css/theme.css" rel="stylesheet">
        <link href="css/datatable.css" rel="stylesheet">
        <link href="css/bootstrap-switch.min.css" rel="stylesheet">
        <link href="css/datepicker3.css" rel="stylesheet">
        <link href="css/efectos.css" rel="stylesheet">
	    <link href="css/waves.min.css" rel="stylesheet">   
	    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
	    <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap3-typeahead.min.js"></script>
	    <script src="js/bootstrap-switch.min.js"></script>
	    <script src="js/bootstrap-datepicker.js"></script>
	    <script src="js/jquery.timeago.js" type="text/javascript"></script>
	    <script src="js/jquery.alphanumeric.pack.js"></script>
        <script src="js/sweetalert.min.js"></script>
	    <script src="js/ohsnap.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script>
            $(function() {
    	        $('.check').bootstrapSwitch();
    	        $('#gastos').on('shown.bs.modal', function () {
			        $('#gastos_descripcion').focus();
		        });
            });
        </script>

        <style>
	        a, button:active, .button:active, button:focus, .button:focus, button:hover, .button:hover{
	            outline:none !important;
	        }
        </style>
    </head>
    <body>
        
        <!-- Fixed navbar -->
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">KGB grill</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">KGB grill</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li <? if($menu=="VentaDomicilio"){ ?>class="active"<? } ?>><a href="?Modulo=VentaDomicilio">Venta </a></li>
                        <?if($conf['facturacion']==1 && $s_tipo == 1){?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Facturación <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?Modulo=VerFacturas">Ver Facturas</a></li>
                                    <li><a href="?Modulo=VerPreFacturas">Ver Pre-Facturas</a></li>
                                    <li><a href="?Modulo=VerVentas">Nueva Factura</a></li>
                                </ul>
                            </li>
                        <?}?>
                        <li><a href="?Modulo=Clientes">clientes</a></li>
                        <li><a href="?Modulo=NuevoGastos">Gastos</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Productos <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="?Modulo=Productos">Productos</a></li>
                                <?if($insumo==1  && $s_tipo == 1 ){?>
                                    <li><a href="?Modulo=ProductosBase">Ingredientes</a></li>
                                    <li><a href="?Modulo=Dotaciones">Dotaciones</a></li>
                                    <li><a href="?Modulo=Mermas">Mermas</a></li>
                                    
                                <?}?>
                            </ul>
                        </li>
                        <? if($s_tipo==1){ ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reportes <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?Modulo=CortesTicket" >Cortes Ticket</a></li>
                                    <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reportes_cortes">Cortes</a></li>
                                    <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reportes_cortes2">Insumos</a></li>
                                    <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reportes_ventas">Ventas PDF</a></li>
                                    <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reportes_ventas_excel">Ventas Excel</a></li>
                                    <li class="divider"></li>
                                    <li><a href="?Modulo=CortesInsumos" >Insumos</a></li>
                                    <li><a data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reportes_ventas3">Ventas_transferencia</a></li>
                                    <li><a data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reportes_ventas4" >Ventas_tarjeta</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reportes_dotaciones">Dotaciones</a></li>
                                    <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reportes_merma">Merma</a></li>
                                    <li><a href="?Modulo=VerVentas" >Ventas</a></li>
                                </ul>
                            </li>
                            <li class="dropdown<? if($menu=="Usuarios"){ echo "active"; }elseif($menu=="Categorias"){ echo "active"; } ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Configuración <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <? if($s_tipo==1){ ?>
                                        <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#ConfiguracionGeneral">General</a></li>
                                        <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#ConfiguracionGeneraltiket">Ticket</a></li>
                                        <li><a href="?Modulo=Cupones">Cupones (Descuentos)</a></li>
                                        <li><a href="?Modulo=Usuarios">Usuarios</a></li>
                                    <?}?>
                                    <li><a href="?Modulo=Categorias">Categorías</a></li>
                                </ul>
                            </li>
                        <?}?>

                    </ul>
                    <!-- Menu derecho-->
                    <ul class="nav navbar-nav navbar-right hidden-md">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$s_nombre?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <? if($s_tipo==2){ ?>
                                    <li><a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#CambiaPassword">Cambiar contraseña</a></li>
                                    <li class="divider"></li>
                                <?}?>
                                <li><a href="login.php">Salir</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
        <div class="container">
		    <?
		        switch($menu){
			        case 'Venta':
		                include("venta.php");
			        break;
			        case 'Desayunos':
		                include("desayunos.php");
		            break;
        	        case 'VentaDomicilio':
        	            include("venta_domicilio.php");
			        break;
		            case 'Almuerzos':
		                include("almuerzos.php");
		            break;
		            case 'Deudores':
		                include("deudores.php");
		            break;
		            case 'Productos':
		                include("productos.php");
			        break;
			        case 'ProductosBase':
		                include("productos_base.php");
			        break;
			        case 'Ingredientes':
		                include("ingredientes.php");
			        break;
			        case 'extras':
			            include("extras.php");
			        break;
			        case 'sin':
			            include("sin.php");
			        break;
			        case 'ver':
			            include("ver_extra.php");
			        break;
			        case 'ver_ingrediente':
			            include("ver_ingrediente.php");
			        break;
			        case 'ver_producto':
			            include("ver_producto.php");
			        break;
			        case 'Paquete':
			            include("productos_paquete.php");
			        break;
		            case 'Usuarios':
		                include("usuarios.php");
		            break;
		            case 'Clientes':
		                include("clientes.php");
		            break;
		            case 'Categorias':
		                include("categorias.php");
		            break;
		            case 'MGastos':
		                include("modulo_gastos.php");
		            break;
		            case 'NuevoGastos':
		                include("modulo_gastos_add.php");
		            break;
		            case 'PGastos':
		                include("modulo_gastos_provision.php");
		            break;
		            case 'EDITAR_PROVISION':
		                include("modulo_gastos_provision_edit.php");
		            break;
		            case 'Domicilio':
		                include("domicilio.php");
		            break;
		            case 'VerFacturas':
		                include("ver_facturas.php");
		            break;
		            case 'VerPreFacturas':
		                include("ver_prefacturas.php");
				    break;
		            case 'VerVentas':
		                include("ver_ventas.php");
		            break;
		            case 'NuevaFactura':
		                include("nueva_factura.php");
		            break;
                    case 'Cupones':
		                include("cupones.php");
		            break;
		            /* Touch*/
		            case 'VentaTouch':
		                include("venta_touch.php");
		            break;
		            case 'VentaTouchMesa':
		                include("venta_touch_mesa.php");
		            break;
		            case 'VentaTouchMesaPendiente':
		                include("venta_touch_mesa_pendiente.php");
		            break;
		            case 'VentaTouchMesaCobrada':
		                include("venta_touch_mesa_cobrada.php");
		            break;
		            case 'VentaTouchCobro':
		                include("venta_touch_cobro.php");
		            break;
		            case 'VentaTouchCobradas':
		                include("venta_touch_cobradas.php");
			        break;
			        case 'Dotaciones':
		                include("dotaciones.php");
			        break;
			        case 'Mermas':
		                include("mermas.php");
			        break;
			        case 'NuevaMerma':
		                include("nueva_merma.php");
			        break;
			        case 'NuevaDotacion':
		                include("nueva_dotacion.php");
		            break;
		            case 'VentaTouchCorte':
		                include("venta_touch_corte.php");
		            break;
		            case 'CortesTicket':
		                include("ver_cortes.php");
			        break;
			        case 'CortesInsumos':
			            include("corte_insumo.php");
			        break;
		            case 'Cortes':
		                include("cortes.php");
                    break;
                    case 'VerVentasT':
		                include("cortes.php");
                    break;
                    case 'VerVentasTJ':
		                include("ver_ventas.php");
                    break;
                    
		            default:
		            include('venta_domicilio.php');

		        }
                //Ventanas Emergentes
                require("configuracion.general.php");
		        require("configuracion.tiket.php");
                require("reportes.php");
                require("gastos.php");
                require("nuevo_codigo.php");
                if($s_tipo==2){ require("cambia_password.php"); }
            ?>
        </div>
    </body>
    <script>
       function generarReporte(formulario,tipo){
            //FROMULARIO        TIPO
            //0 -> 1 FECHA      0 -> PDF
            //1 -> 2 FECHAS     1 -> EXCEL
            var data;
            var link;
            if(formulario==0){
                //console.log("GENERAR REPORTE DE 1 FECHA");
                data = $("#form_venta_una").serialize();
                if(tipo==0){
                    link = "reportes/ventas.php";
                }else{
                    link = "reportes/ventas_excel.php";
                }    
            }else{
                //console.log("GENERAR REPORTE DE Multiples FECHAS");
                data = $("#form_venta_mult").serialize();
                if(tipo==0){
                   link = "reportes/ventas.php";
                }else{
                    link = "reportes/ventas_excel.php";
                }
            }
            console.log(link+"?"+data);
            //window.open(link+"?"+data,'_blank');
            window.open("index.php", "popupWindow", "width=600,height=600,scrollbars=yes");
            //window.open("index.php");
        }
    </script>
</html>
