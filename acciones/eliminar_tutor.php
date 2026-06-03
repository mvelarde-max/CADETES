<?php
session_start();
require_once '../includes/conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ADMIN') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID inválido");
}

// eliminar tutor físicamente
$stmt = $conexion->prepare("DELETE FROM tutores WHERE id = ?");
$stmt->execute([$id]);

header("Location: ../paginas/tutores.php");
exit;