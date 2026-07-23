<?php
session_start();

// Validar sesión primero
if (!isset($_SESSION['s_id'])) {
    header("Location: login.php");
    exit;
}

// Asignar variables de forma segura
$s_id_usuario = isset($_SESSION['s_id']) ? $_SESSION['s_id'] : null;
$s_nombre     = isset($_SESSION['s_nombre']) ? $_SESSION['s_nombre'] : "Usuario";
$s_tipo       = isset($_SESSION['s_tipo']) ? $_SESSION['s_tipo'] : 0;
$cortes       = isset($_SESSION['cortes']) ? $_SESSION['cortes'] : null;
$devoluciones = isset($_SESSION['devoluciones']) ? $_SESSION['devoluciones'] : null;
?>