<?php
session_start();

// Validar sesión primero
if (!isset($_SESSION['s_id'])) {
    header("Location: login.php");
    exit;
}

// Asignar variables de forma segura
$s_id_usuario = $_SESSION['s_id'] ?? null;
$s_nombre     = $_SESSION['s_nombre'] ?? "Usuario";
$s_tipo       = $_SESSION['s_tipo'] ?? 0;
$cortes       = $_SESSION['cortes'] ?? null;
$devoluciones = $_SESSION['devoluciones'] ?? null;
?>