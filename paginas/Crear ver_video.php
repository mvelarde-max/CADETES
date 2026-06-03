<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

require_once "../includes/conexion.php";

$id = (int)$_GET['id'];

$stmt = $conexion->prepare("
    SELECT *
    FROM videos
    WHERE id = ?
");

$stmt->execute([$id]);

$video = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$video) {
    die("Video no encontrado");
}
?>