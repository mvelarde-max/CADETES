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

try {
    $conexion->beginTransaction();

    // borrar relaciones primero (importante en tu sistema)
    $stmt = $conexion->prepare("DELETE FROM alumnos_tutores WHERE alumno_id = ?");
    $stmt->execute([$id]);

    $stmt = $conexion->prepare("DELETE FROM autorizados_retiro WHERE alumno_id = ?");
    $stmt->execute([$id]);

    $stmt = $conexion->prepare("DELETE FROM autorizados_retiro_detalle WHERE alumno_id = ?");
    $stmt->execute([$id]);

    $stmt = $conexion->prepare("DELETE FROM permisos_retiro WHERE alumno_id = ?");
    $stmt->execute([$id]);

    // ahora sí el alumno
    $stmt = $conexion->prepare("DELETE FROM alumnos WHERE id = ?");
    $stmt->execute([$id]);

    $conexion->commit();

} catch (Exception $e) {
    $conexion->rollBack();
    die("Error al eliminar alumno: " . $e->getMessage());
}

header("Location: ../paginas/alumnos.php");
exit;