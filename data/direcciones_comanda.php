<?
include("../includes/db.php");
include("../includes/funciones.php");
error_reporting(0);

$tel = trim($_GET['telefono']);
$vxe = 0;

if(!$tel){
    $vxe = 1;
    $mostrador = '<div class="alert alert-success" role="alert" style="text-align:center;">Pedido para entrega en mostrador.</div>';
}else{
    if(!validarTelefono($tel)) exit('<div class="alert alert-danger" role="alert" style="text-align:center;">No escribiste un número de teléfono válido.</div>');
}

$telefono=escapar($tel,1);

$sql="SELECT * FROM domicilio WHERE numero='$telefono' LIMIT 1";
$q=mysql_query($sql);
$ft=mysql_fetch_assoc($q);
$id_domicilio=$ft['id_domicilio'];
$nombre=$ft['nombre'];
$id_domicilio=$ft['id_domicilio'];

$sql="SELECT * FROM domicilio_direcciones WHERE id_domicilio=$id_domicilio";
$q=mysql_query($sql);
$val=mysql_num_rows($q);
//if(!$val) exit("Este producto aún no tiene opciones");
?>
<style>
input[type='radio']:after {
    width: 25px;
    height: 25px;
    border-radius: 15px;
    top: -4px;
    left: -2px;
    position: relative;
    background-color: #d1d3d1;
    content: '';
    display: inline-block;
    visibility: visible;
    border: 2px solid white;
}

input[type='radio']:checked:after {
    width: 25px;
    height: 25px;
    border-radius: 15px;
    top: -4px;
    left: -2px;
    position: relative;
    background-color: #ffa500;
    content: '';
    display: inline-block;
    visibility: visible;
    border: 2px solid white;
}

/*Check box*/
.checkbox label:after,
.radio label:after {
    content: '';
    display: table;
    clear: both;
}

.checkbox .cr,
.radio .cr {
    position: relative;
    display: inline-block;
    border: 1px solid #a9a9a9;
    border-radius: .25em;
    width: 1.3em;
    height: 1.3em;
    float: left;
    margin-right: .5em;
}

.radio .cr {
    border-radius: 50%;
}

.checkbox .cr .cr-icon,
.radio .cr .cr-icon {
    position: absolute;
    font-size: .8em;
    line-height: 0;
    top: 50%;
    left: 20%;
}

.radio .cr .cr-icon {
    margin-left: 0.04em;
}

.checkbox label input[type="checkbox"],
.radio label input[type="radio"] {
    display: none;
}

.checkbox label input[type="checkbox"] + .cr > .cr-icon,
.radio label input[type="radio"] + .cr > .cr-icon {
    transform: scale(3) rotateZ(-20deg);
    opacity: 0;
    transition: all .3s ease-in;
}

.checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
.radio label input[type="radio"]:checked + .cr > .cr-icon {
    transform: scale(1) rotateZ(0deg);
    opacity: 1;
}

.checkbox label input[type="checkbox"]:disabled + .cr,
.radio label input[type="radio"]:disabled + .cr {
    opacity: .5;
}
</style>


    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label" style="font-size:18px;font-weight:normal;margin-top:5px;">Cliente:</label>
        <div class="col-sm-10">
            <input type="text" autocomplete="nel" id="nombre_cliente" name="nombre_cliente" value="<?=$nombre?>" <? if(!$val){ ?>placeholder="Agrega el nombre del cliente" <? } ?> class="form-control input-lg" maxlength="255">
        </div>
    </div>


    <?  if($val){ ?>
        <div class="alert alert-warning" role="alert" style="text-align:center;">Seleccione una dirección para el envío</div>
    <?  }else{
            if(!$vxe){ ?>
                <div class="alert alert-info" role="alert" style="text-align:center;">Éste es un cliente nuevo, es momento de agregar <b>nombre</b> y una <b>dirección</b></div>
                <script>$(function(){ $('#nombre_cliente').focus(); });</script>
    <?      }else{
                echo $mostrador;
            }
       } ?>
    <table class="table table-striped">
    <tr>
            <td width="60" align="center" valign="middle"></td>
            <td align="right" style="font-size:18px;">Marcar sí el cliente recogerá el producto </td>
            <td width="120" align="right" valign="middle">
                <div class="checkbox">
                    <label style="padding-left:0px;">
                        <input type="checkbox" id="fac" name="facturar" value="1">
                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                    </label>
                </div>
            </td>
        </tr>
    <? while($dat=mysql_fetch_assoc($q)){ ?>
        <tr>
            <td width="60" align="center" valign="middle">
                <input type="radio" name="id_domicilio_direccion" style="margin-top:30px" value="<?=$dat['id_domicilio_direccion']?>" id="id_domicilio_direccion_<?=$dat['id_domicilio_direccion']?>" onclick="agregaDomicilio(<?=$dat['id_domicilio_direccion']?>)">
            </td>
            <td>
                <textarea class="form-control" rows="3" name="direccion[<?=$dat['id_domicilio_direccion']?>]"><?=$dat['direccion']?></textarea>
            </td>
            <td width="120" align="center" valign="middle">
                <select name="servicio[<?=$dat['id_domicilio_direccion']?>]" id="servicio" class="form-control servicio_<?=$dat['id_domicilio_direccion']?>" style="margin-top:20px" onchange="agregaDomicilio(<?=$dat['id_domicilio_direccion']?>)">
                    <? if($dat['costo']==""){?><option value="X" selected>Seleccione un costo</option><?}?>
                    <option value="0" <? if($dat['costo']=='0'){ echo "selected"; }?>>Gratis</option>
                    <option value="10" <? if($dat['costo']=='10'){ echo "selected"; }?>>$ 10</option>
                    <option value="15" <? if($dat['costo']=='15'){ echo "selected"; }?>>$ 15</option>
                    <option value="20" <? if($dat['costo']=='20'){ echo "selected"; }?>>$ 20</option>
                    <option value="25" <? if($dat['costo']=='25'){ echo "selected"; }?>>$ 25</option>
                    <option value="30" <? if($dat['costo']=='30'){ echo "selected"; }?>>$ 30</option>
                </select>
            </td>
        </tr>
    <? }
    if(!$vxe){
    ?>
  
        <tr>
            <td width="60" align="center" valign="middle">
                <input type="radio" name="id_domicilio_direccion" style="margin-top:30px" value="N" id="id_domicilio_direccion_N" <? if(!$val){ ?>checked<? }?> >
            </td>
            <td>
                <textarea class="form-control" rows="3" name="direccion_N" id="direccion_N" placeholder="Agregar una nueva dirección"></textarea>
            </td>
            <td width="120" align="center" valign="middle">
                <select name="servicio_nuevo" id="servicio" class="form-control servicio_N" style="margin-top:20px" onchange="agregaDomicilio('N')">
                    <option value="X" selected>Seleccione un costo</option>
                    <option value="0">Gratis</option>
                    <option value="10">$ 10</option>
                    <option value="15">$ 15</option>
                    <option value="20">$ 20</option>
                    <option value="25">$ 25</option>
                    <option value="30">$ 30</option>
                </select>
            </td>
        </tr>
    <? } ?>
        <tr>
            <td width="60" align="center" valign="middle">

            </td>
            <td>
                <textarea class="form-control" rows="3"  name="comentarios" placeholder="Comentarios para el pedido"></textarea>
            </td>
            <td width="120" align="center" valign="middle">

            </td>
        </tr>

        <tr>
            <td width="60" align="center" valign="middle"></td>
            <td align="right" style="font-size:18px;">Consumo total: </td>
            <td width="120" align="right" valign="middle">
                <span id="muestra_monto_venta" style="font-size:18px;">0.00</span>
                <intpu type="hidden" name="monto_cuenta_final" id="monto_cuenta_final" value="0" />
            </td>
        </tr>
        <tr>
            <td width="60" align="center" valign="middle"></td>
            <td align="right" style="font-size:18px;">Envío: </td>
            <td width="120" align="right" valign="middle">
                <span id="muestra_monto_envio" style="font-size:18px;">0.00</span>
                <intpu type="hidden" name="monto_envio_final" id="monto_envio_final" value="0" />
            </td>
        </tr>
        <tr>
            <td width="60" align="center" valign="middle"></td>
            <td align="right" style="font-size:18px;">
                <div class="input-group" style="width:180px;">
                    <span class="input-group-addon" id="descuento-input">Descuento</span>
                    <input type="text" class="form-control solo_numero" autocomplete="nel" placeholder="0" aria-describedby="descuento-input" maxlength="3" style="text-align:right;" id="descuento_porcentaje" name="descuento_porcentaje" >
                    <span class="input-group-addon" id="descuento-input">%</span>
                </div>
            </td>
            <td width="120" align="right" valign="middle">
                <input type="text" class="form-control solo_numero" placeholder="0" maxlength="7" style="width:100px;text-align:right;" id="descuento_cantidad" name="descuento_cantidad">
            </td>
        </tr>
        <tr>
            <td width="60" align="center" valign="middle"></td>
            <td align="right" style="font-size:18px;">Total: </td>
            <td width="120" align="right" valign="middle"><span id="muestra_monto_total" style="font-size:18px;">0.00</span></td>
        </tr>
       
    </table>
    <? if($id_domicilio){ ?>
        <input type="hidden" name="id_domicilio" value="<?=$id_domicilio?>" />
    <? } ?>



<script>
$(function(){
    setTimeout(function(){
        calculaDescuento();
    },300);

     $('#btn-opciones-<?=$randmon?>').click(function(){
         var opciones = $('#adicional_<?=$randmon?>').val();
         var datos = jQuery.parseJSON(opciones);
         console.log(datos);
     });

    $('.footer-domicilio').show();

    $("#nombre_cliente").keypress(function(e){
        if(e.which == 13){
			$('#direccion_N').focus();
        }
	});

    //cargar los datos
    var total_consumo = $('#total_totales').val();
    $('#muestra_monto_venta').html(total_consumo);
    $('#monto_cuenta_final').val(total_consumo);

    //Descuento
    $('#descuento_porcentaje').keyup(function(){
        var valor = $('#descuento_porcentaje').val();
        if(valor >= 1 && valor <= 100){
            calculaDescuento(1);
        }else{
            $('#descuento_porcentaje').val('');
            calculaDescuento(1);
            return false
        }
    });
    $('#descuento_cantidad').keyup(function(){
        calculaDescuento(2);
    });

});
function calculaDescuento(tipo){

    var total_consumo   = Number($('#total_totales').val());
    var servicio_dom    = Number($('#monto_envio_final').val());

    if(!tipo){
        var imprime_total_final = total_consumo+servicio_dom;
        $('#muestra_monto_total').html(imprime_total_final.toFixed(2));
        return false;
    }

    if(tipo==1){
        var porcentaje              = Number($('#descuento_porcentaje').val());
        var monto_descuento         = (total_consumo * porcentaje) / 100;
        var total_consumo_descuento = total_consumo-monto_descuento+servicio_dom;
        if(isNaN(monto_descuento)){ var monto_descuento=0; }
        $('#descuento_cantidad').val(monto_descuento.toFixed(2));
    }

    if(tipo==2){
        var monto                   = Number($('#descuento_cantidad').val());
        var total_consumo_descuento = total_consumo-monto+servicio_dom;
        var porcentaje              = (monto / total_consumo) * 100;
        if(isNaN(porcentaje)){ var porcentaje=0; }
        $('#descuento_porcentaje').val(porcentaje.toFixed(0));
    }

    $('#muestra_monto_total').html(total_consumo_descuento.toFixed(2));
}
function agregaDomicilio(id){
    
    if( $('#fac').prop('checked') ) {
     
}else{
    var monto_domicilio = Number($('.servicio_'+id).val());
    $('#monto_envio_final').val(monto_domicilio);
    $('#muestra_monto_envio').html(monto_domicilio.toFixed(2));
    calculaDescuento();
}
   
}
function calculaTotalServicio(){

}
/*
function agregarOpciones(){
    var random = $('#rand-nw').val();
    //$('#datos_carga')
	var adicionales = JSON.stringify($('#frm_adicionales').serializeArray());
    console.log(adicionales);
    if(adicionales){
        $('#adicional_'+random).val(adicionales);
        $('#btn-opciones-'+random).addClass('btn-info').val('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> OPCIONES');
    }

    $('#verOpcionesProductos').modal('toggle');
}
*/
function cargaDomicilio(){
    $('#btn-cargaDomicilio').button('loading');
    var datos_envio = $('#frm-datos-domicilio').serialize();
    var datos_venta = $('#venta_form').serialize();
    var datos = datos_venta+"&"+datos_envio;
    $.post('ac/guarda_domicilio.php',datos,function(data) {
        console.log(data);
        if(data==1){
            location.reload();
        }else{
            alert('Error: '+data);
            $('#btn-cargaDomicilio').button('reset');
        }
    });
}
</script>
