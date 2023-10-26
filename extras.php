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

$sql="SELECT * FROM productos
WHERE extra=1 and sinn !=1";


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


					

					<div class="portlet light portlet-fit">
					  <div class="portlet-title">
					    <div class="caption">
						  <i class="icon-globe font-dark"></i>
						  <?foreach($nombres as $nombre){?>
					      <span class="caption-subject font-dark sbold uppercase"><h3>AGREGAR EXTRA A <?=$nombre->nombre?>  </h3><span>
						  <?}?>
						  
						  <br>
							  <br>
							  <br>
					    </div>
					  </div>
					  <div class="portlet-body">

					    <div class="row" >
					      <div class="col-sm-2">

						  
					        <label for="">Nivel</label>
					        	  <select class="form-control  " name="txtCantidad" id="txtCantidad">
							  <option value="1" selected> Extras</option>
							  <option value="2" > Extra 1 </option>
							  <option value="3" > Extra 2 </option>
							  <option value="4" > Extra 3 </option>
				
							
							 
							

					        </select>
					      </div>
					      <div class="col-sm-4" id="selectDiv">
					        <label for="">Producto</label>
							<select class="form-control  " name="txtSelProduc" id="txtSelProduc">
							  <option value="0" selected> Seleccione producto </option>
							  <?foreach($productos as $producto){?>
                           
							  <option value="<?=$producto->id_producto?>" data-costo="<?=number_format($producto->precio_venta);?>"  > <?=$producto->nombre?> </option>
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
						            <th style="width:40px; text-align:center;">Nivel</th>
						            <th style="width:100px; text-align:center;">Producto</th>
						            <th style="width:40px; text-align:right;">Costo</th>
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


     
















	if($('#txtCantidad').val() == null || $('#txtCantidad').val() == '' || $('#txtCantidad').val() == 0 || $('#txtCantidad').val() < 0){
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
	
		var costoU = combo.options[combo.selectedIndex].getAttribute("data-costo");
		
		if(cantidad == 1){
			nivel="Opciones";
		}
        if(cantidad == 2){
			nivel="Extra 1";
		}

		if(cantidad == 3){
			nivel="Extra 2";
		}

		if(cantidad == 4){
			nivel="Extra 3";
		}

		var costoT = Number(cantidad)*Number(costoU);
		 var tipo = combo.options[combo.selectedIndex].getAttribute("data-tipo");


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
	  $('#tblProductosBody').append('<tr data-id="'+cod+'" data-cantidad="'+cantidad+'" data-costoU="'+costoU+'" data-tipo="'+tipo+'"><td style=" text-align:center;">' + nivel + '</td> <td style=" text-align:center;">' + producto + '</td> <td style=" text-align:right;">$' + costoU + '</td>  <td style=" text-align:center;" > <button type="button" class="btn btn-sm btn-danger btn-xs" id="btnBorrar">x</button> </td> </tr>');
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
		
			var extra=$(this).attr("data-id");
  
			var producto=document.getElementById('producto').value;
  		   console.log(producto);
            var nivel=$(this).attr("data-cantidad");
  	         console.log(nivel);
               console.log(extra);
  			$.post('ac/agrega_extra.php', { nivel, producto,extra },function(data){
				
	 		 if(data==1){
			    // window.open("?Modulo=ver&id="+<?=$idp?>, "_self");
		 		window.open("?Modulo=Productos", "_self");
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
