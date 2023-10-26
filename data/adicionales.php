<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_producto']){ exit("Error de ID");}
$id_producto=escapar($_GET['id_producto'],1);
$randmon=escapar($_GET['randmon'],1);
/*
$sql="SELECT id_categoria FROM productos WHERE id_producto=$id_producto";
$q=mysql_query($sql);
$ft=mysql_fetch_assoc($q);
$id_categoria=$ft['id_categoria'];

$sql="SELECT * FROM categorias_opciones WHERE id_categoria=$id_categoria";
$q=mysql_query($sql);
$val=mysql_num_rows($q);*/
//if(!$val) exit("Este producto aún no tiene opciones");
?>
<style>
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
<div class="row">
    <form id="frm_adicionales">
        <? if($val){ ?>
        <div class="col-md-4">
            <? while($dat=mysql_fetch_assoc($q)){ ?>
                <div class="checkbox1">
                    <label style="font-size: 1.3em;margin-bottom:10px;">
                        <input type="checkbox" class="checkboxs checks_<?=$randmon?>" value="<?=strtoupper($dat['opcion'])?>" id="opcion_<?=$dat['id_categoria_opcion']?>" name="opcion[<?=$dat['id_categoria_opcion']?>]">
                        <!--<span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>-->
                        <?=strtoupper($dat['opcion'])?>
                    </label>
                </div>
            <? } ?>
        </div>
        <? } ?>
        <div class="<? if(!$val){ echo "col-md-12"; }else{ echo "col-md-8"; }?>" style="text-align:center;">
            <div class="form-group">
                <label for="disabledSelect">Comentarios adicionales</label>
                <textarea class="form-control comentarios coms_<?=$randmon?>" rows="4" name="comentarios" id="comentarios_adicionales"></textarea>
            </div>
        </div>
        <input type="hidden" id="rand-nw" value="<?=$randmon?>" />
    </form>
</div>
<script>
$(function(){
    $('#btn-opciones-<?=$randmon?>').click(function(){
        var opciones = $('#adicional_<?=$randmon?>').val();
        var datos = jQuery.parseJSON(opciones);
        console.log(datos);
    });

    var datos_actuales = $('#adicional_<?=$randmon?>').val();
    if(datos_actuales){
        $('.coms_<?=$randmon?>').val(datos_actuales);
    }
});

function agregarOpciones2(){
    var random = $('#rand-nw').val();
    //$('#datos_carga')
	//var adicionales = JSON.stringify($('#frm_adicionales').serializeArray());
    var adicionales = $('#comentarios_adicionales').val();
    console.log(adicionales);
    if(adicionales){
        $('#adicional_'+random).val(adicionales);
        $('#btn-opciones-'+random).addClass('btn-info').val('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> OPCIONES');
    }else{
        $('#adicional_'+random).val("");
        $('#btn-opciones-'+random).removeClass('btn-info').val('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> OPCIONES');

    }

    $('#verOpcionesProductos2').modal('toggle');
}
</script>
