<?error_reporting(0);

//include("includes/db.php");


$idp=$_GET['id'];

$producto67 = "SELECT nombre FROM productos
WHERE id_producto = $idp";


$B678 = mysql_query($producto67);



$nombres= array();
while($datos=mysql_fetch_object($B678)):
	$nombres[] = $datos;
endwhile;

$sql="SELECT * FROM productos_base
Left JOIN unidades ON unidades.id_unidad= productos_base.id_unidad
";


$B = mysql_query($sql);



$prodcutos= array();
while($datos=mysql_fetch_object($B)):
	$productos[] = $datos;
endwhile;
$val=count($productos);



?>


<input type="hidden" id="producto" value="<?=$idp?>">
<div class="page-content">
	<div class="container">
		<div class="page-content-inner">
			<div class="row">
				<div class="col-md-12">


					wqe

					<div class="portlet light portlet-fit">
					  <div class="portlet-title">
					    <div class="caption">
						  <i class="icon-globe font-dark"></i>
						  <?foreach($nombres as $nombre){?>
					      <span class="caption-subject font-dark sbold uppercase"><h3>AGREGAR INGREDIENTES A <?=$nombre->nombre?>  </h3><span>
						  <?}?>
						  
						  <br>
							  <br>
							  <br>
					    </div>
					  </div>
					  <div class="portlet-body">

					    <div class="row" >
					      <div class="col-sm-2">

						  
					        <label for="">Cantidad</label>
                            <input class="form-control" VALUE="1" name="txtCantidad" id="txtCantidad">
					        	
							
					      </div>
					      <div class="col-sm-4" id="selectDiv">
					        <label for="">Producto</label>
							<select class="form-control  " name="txtSelProduc" id="txtSelProduc">
							  <option value="0" selected> Seleccione producto </option>
							  <?foreach($productos as $producto){?>
                           
							  <option value="<?=$producto->id_base?>" data-tipo="<?=$producto->abreviatura?>" data-costo="<?=number_format($producto->precio);?>"  > <?=$producto->producto?> </option>
							  <?}?>
							 
					        </select>
					      </div>
					      
					      <div class="col-xs-1">
					        <label for="" style="visibility: hidden;"> d</label>
					        <button class="form-control btn btn-primary" id="btnAgregarPro" name="btnAgregarPro" type="button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true">
					        </span></button>
					      </div>
					    </div>

					    <br>
					    <br>

					    <div class="row">
								<div class="col-sm-12">
									<table class="table table-bordered table table-hover" id="tblProductos">
						        <thead>
						          <tr>
						            
						            <th style="width:100px; text-align:center;">Producto</th>
						            <th style="width:40px; text-align:right;">Cantidad</th>
						            <th style="width:40px;"></th>
						          </tr>
						        </thead>
						        <tbody id="tblProductosBody">
								
						        </tbody>
						      </table>
									<button type="button" class="btn btn-success" id="btnGuardarTabla" onclick="javascript:guardar()" style="float: right; margin-left:5px;">Guardar </button>
		              <button type="button" class="btn btn-danger" id="btnCanceTabla" style="float: right;" onclick="javascript:regresar()"> Cancelar</button>
								</div>
					    </div>

					  </div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<script >


$(document).ready(function() {

		
	console.log("lapagina a cargao");


			

	

});






$('#btnAgregarPro').on('click', function() {

	if($('#txtCantidad').val() == null || $('#txtCantidad').val() == '' || $('#txtCantidad').val() == 0 ){
		alert('Debes ingresar cantidad');
		return false;
	}else if ($('#txtSelProduc').val() == null || $('#txtSelProduc').val() == '' || $('#txtSelProduc').val() == 0) {
		alert('Debes seleccionar un producto');
		return false;
	
	}else {
      var nivel="";
		var cod = document.getElementById("txtSelProduc").value;
		var combo = document.getElementById("txtSelProduc");
		var producto = combo.options[combo.selectedIndex].text;
		var cod2 = document.getElementById("txtCantidad").value;
		
		var cantidad = $('#txtCantidad').val();
	
		 var unidad = combo.options[combo.selectedIndex].getAttribute("data-tipo");
		

		
		var id=0;
	$('#tblProductos #tblProductosBody tr').each(function(){
	    
		     
			var extra=$(this).attr("data-id");
  
			
	 		 if(cod==extra){
			
				
				id=1;
	  		} else{
				  id=0;
				
			  }
	  });
		
	  if(id==0){
		$('#tblProductosBody').append('<tr data-id="'+cod+'" data-cantidad="'+cantidad+'"  > <td style=" text-align:center;">' + producto + '</td> <td style=" text-align:right;">' + cantidad+"  "+ unidad+'</td>  <td style=" text-align:center;" > <button type="button" class="btn btn-sm btn-danger btn-xs" id="btnBorrar">x</button> </td> </tr>');
	  }else{
		alert('producto duplicado');
	  }

	
	
	}

});

$('#tblProductos').on("click", '#tblProductosBody tr #btnBorrar', function() {
  $(this).closest('tr').remove();
});




function guardar(){

	
	var productos = [];
	  var count = 0;
    
	  $('#tblProductos #tblProductosBody tr').each(function(){
	    count++;
		
			var ingrediente=$(this).attr("data-id");
  
			var producto=document.getElementById('producto').value;
  		   console.log(producto);
            var cantidad=$(this).attr("data-cantidad");
  	         console.log(cantidad);
               console.log(ingrediente);
  			$.post('ac/agregar_productobase.php', { cantidad, producto,ingrediente},function(data){
				
	 		 if(data==1){
			
		 		//window.open("?Modulo=Productos", "_self");
				
				  window.open("?Modulo=Ingredientes&id=<?=$idp?>", "_self");
	  		}else{
		  			console.log(data);
		 			 alert(data);
	  }
  });

 
	  
	  });
	  if(count==0){
		alert("Aun no se han ingresado productos en la tabla");

	  }
	  
}


function regresar(){

	//var proyecto=document.getElementById('tar').value;
	window.open("?Modulo=Productos&msg=0","_self");

/*	$.post('ac/eliminar_proyecto.php', { proyecto },function(data){
		if(data==1){
			window.open("?Modulo=proyectos&msg=0","_self");
		}else{
		
			alert(data);
		}
	});*/

}



</script>
