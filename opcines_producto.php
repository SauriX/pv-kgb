

<?


include("includes/db.php");
include("includes/funciones.php");

//----------- solo para optener el nivel maximo--------
$producto=$_GET['padre'];
$A="SELECT nivel FROM producto_extra WHERE id_producto = $producto ORDER BY nivel desc limit 1";
$B = mysql_query($A);
$C = mysql_fetch_assoc($B);
$nivelMax=$C['nivel'];



$sql="SELECT productos.nombre as nombre ,categorias.nombre as categoria FROM productos LEFT JOIN categorias ON categorias.id_categoria = productos.id_categoria WHERE id_producto=$producto";

$query=mysql_query($sql);
$tx=mysql_fetch_assoc($query);
$nombre=$tx['nombre'];
$categoria=$tx['categoria'];
//-----------------------------------------------------

//-----------generar botones------------------------------
?>

<div class="col-md-12" style="margin-bottom:20px;border-bottom: 2px dotted #ededed;padding-bottom:10px;">


           <h3 style="text-align: center;" ><?=$categoria?> : <?=$nombre?></h3>
    <div class="btn-group btn-group-lg">
        <?  $i=0;
            for($i=1;$i<=$nivelMax;$i++){
        ?>
            <?if($i==1){?>
                <a role="button" class="btn btn-default active btn-extra" onclick="mostrar(<?=$i?>);">EXTRAS</a>
            <?}else{?>
                <a role="button" class="btn btn-default btn-extra" onclick="mostrar(<?=$i?>);">EXTRA <?=$i-1?></a>
            <?}?>
        <?}?>
        <a role="button" class="btn btn-default btn-sin" onclick="mostrarsin();">SIN </a>
    </div>




</div>

<!--
<ul class="nav nav-tabs">
    <?  $i=0;
        for($i=1;$i<=$nivelMax;$i++){
    ?>
        <?if($i==1){?>
            <li class="active "> <a  style="color: #DC3645; " role="button" onclick="mostrar(<?=$i?>);">EXTRAS</a></li>
        <?}else{?>
            <li class="active"><a style="color: #DC3645;" role="button" onclick="mostrar(<?=$i?>);">EXTRA <?=$i-1?></a></li>
        <?}?>
    <? } ?>


    <li class="active"><a style="color: #900361;" role="button" onclick="mostrarsin();">SIN </a></li>

</ul>
-->
<div style="margin-top:15px;">
    <br>
<?



//para generar las opcciones
$i2=0;
for($i2=1;$i2<=$nivelMax;$i2++){


?>

<?if($i2==1){?>
    <div style="visibility:visible;" id="oculto<?=$i2?>">
     <?
        $sql="SELECT * FROM producto_extra WHERE id_producto = $producto AND nivel= $i2 ORDER BY nivel asc";

        $q = mysql_query($sql);
        while($id_pro = mysql_fetch_assoc($q)){

            $extra = $id_pro['id_extra'];
            $pro="SELECT * FROM productos WHERE id_producto = $extra  ORDER BY nombre";

            $q2 = mysql_query($pro);
            while($ft = mysql_fetch_assoc($q2)){

        ?>

    <div class="col-md-2 mesa  " id="extra_<?=$ft['id_producto']?>" data-tipo="sin" style="<?=$ocultar_teclado?>;margin-left: 17px; Text-Align:center;height:80px!important;" onclick="extra('<?=$ft['codigo']?>',<?=$ft['id_producto']?>,<?=$i2?>+1)">
								<h3><?=$ft['nombre']?></h3>

                                <? if($ft['precio_venta']!=0){?>
                                <h4 style="color: white;" > <?=$ft['precio_venta'];?></h4>
                                <?}?>



							</div>

        <?}}?>
       </div>
<?}
else{?>
     <div style="visibility:hidden;" id="oculto<?=$i2?>">

    <?
        $sql="SELECT * FROM producto_extra WHERE id_producto = $producto AND nivel= $i2 ORDER BY nivel asc";

        $q = mysql_query($sql);
        while($id_pro = mysql_fetch_assoc($q)){

            $extra = $id_pro['id_extra'];
            $pro="SELECT * FROM productos WHERE id_producto = $extra  ORDER BY nombre";

            $q2 = mysql_query($pro);
            while($ft = mysql_fetch_assoc($q2)){


        ?>

    <div class="col-md-2 mesa    " id="extra_<?=$ft['id_producto']?>"  style="<?=$ocultar_teclado?>;margin-left: 17px;" onclick="extra('<?=$ft['codigo']?>',<?=$ft['id_producto']?>,<?=$i2?>+1)">
								<h3><?=$ft['nombre']?></h3>

							</div>

        <?}}?>

        </div>
<?}?>



            <?}?>


<!---->

<div style="display:none;" id="sin">

   <?

$sql56="SELECT * FROM producto_extra WHERE id_producto = $producto AND nivel= 0";
$extra = $id_pro['id_extra'];
$q56 = mysql_query($sql56);
$cantidad =mysql_num_rows($q56);

if($cantidad == 0){
echo("<div style='text-align: center;'><h3>No hay opciones</h3></div>");

}


while($id_pro = mysql_fetch_assoc($q56)){
    $extra = $id_pro['id_extra'];
   $sql24="SELECT * FROM productos WHERE sinn=1 && id_producto = $extra";

   $q24 = mysql_query($sql24);
   while($ft = mysql_fetch_assoc($q24)){
   ?>

    <div class="col-md-2 mesa gris  " id="sin_<?=$ft['id_producto']?>" data_tipo="sin" style="<?=$ocultar_teclado?>;margin-left: 17px;" onclick="extra2('<?=$ft['codigo']?>',<?=$ft['id_producto']?>,<?=$i?>+1)">


    <h3><?=$ft['nombre']?></h3>

							</div>





            <?}}?>
</div>

<script>
var max =<?=$nivelMax?>;
var actual=1;
$(function(){

$('#verOpcionesProductos').on('show.bs.modal',function(e){
    //$('.pendiente2').hide();
     for(var i=2;max>=i;i++){
        $("#oculto"+i).hide();

     }
    if(max>0){
     $('#sin').hide();

    }
    if(max==0 ){
        mostrarsin();

    }

    if(max<0 ){
        mostrarsin();

    }
});

});


function mostrar(nivel){

    $('.btn-sin').removeClass('active');
    $('.btn-extra').addClass('active');



    var activo=    document.getElementById("oculto"+actual).style.visibility = "collapse";
 

    var nuevo= document.getElementById("oculto"+nivel).style.visibility = "visible";

    $("#oculto"+actual).hide();
    $("#oculto"+nivel).show(1);

    $('#guardar').show();
    $('#siguiente').hide();

    console.log("oculto"+nivel);

    actual=nivel;
    console.log(actual);
    $('#sin').hide();
}



function mostrarsin(){
    $('.btn-extra').removeClass('active');
    $('.btn-sin').addClass('active');

    var nuevo   =   document.getElementById('sin').style.visibility = "visible";
    var activo  =   document.getElementById("oculto"+actual).style.visibility = "collapse";

    $('#guardar').show();
    $('#siguiente').hide();
    $("#oculto"+actual).hide();
    $('#sin').show(1);
}


</script>
