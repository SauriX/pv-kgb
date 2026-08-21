<? session_start();

if(!isset($_SESSION['s_id'])){
	exit("Su sesión ha expirado.");
}

$s_id_usuario = isset($_SESSION['s_id']) ? $_SESSION['s_id'] : null;
$s_nombre = isset($_SESSION['s_nombre']) ? $_SESSION['s_nombre'] : '';
$s_tipo = isset($_SESSION['s_tipo']) ? $_SESSION['s_tipo'] : 0;
?>