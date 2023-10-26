<?session_start();
		
	$s_id_usuario=$_SESSION['s_id'];
	$s_nombre=$_SESSION['s_nombre'];
	$s_tipo=$_SESSION['s_tipo'];
	$cortes=$_SESSION['cortes'];

	$devoluciones=$_SESSION['devoluciones'];

	if(!isset($_SESSION['s_id'])){
		header("Location: login.php");
	}
	?>