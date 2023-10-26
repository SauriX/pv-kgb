<?php
    $idp=$_GET['id'];
    $producto67 = "SELECT nombre FROM productos WHERE id_producto = $idp";
    $B678 = mysql_query($producto67);
    $nombres= array();
    while($datos=mysql_fetch_object($B678)){
        $nombres[] = $datos;
    }
        

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredientes Paquete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="portlet-title">
		<div class="caption">
			<i class="icon-user-follow font-dark"></i>
			<?foreach($nombres as $nombre){?>
				<span class="caption-subject font-dark sbold uppercase"><h3>INGREDIENTES PARA  <?=$nombre->nombre?>  </h3><span>
			<?}?>
			<br>
			<br>
			<br>
		</div>
	<div>
  <div class="row">
    <div class="col-sm-6">
        <table class="table">
            <thead>
                ...
            </thead>
            <tbody>
                <tr>
                    <th scope="row">3</th>
                    <td colspan="2" class="table-active">Larry the Bird</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
      One of three columns
    </div>
  </div>
</div>
</body>
</html>