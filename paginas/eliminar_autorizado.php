<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ADMIN') {
    header("Location: ../index.php");
    exit;
}

require_once "../includes/conexion.php";

if (!isset($_GET['id'])) {
    header("Location: permisos.php");
    exit;
}

$id = $_GET['id'];

/* borrar registro */
$stmt = $conn->prepare("DELETE FROM autorizados_retiro_detalle WHERE id = ?");
$stmt->execute([$id]);

header("Location: permisos.php");
exit;