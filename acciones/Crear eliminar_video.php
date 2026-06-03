<?php

session_start();

if ($_SESSION['rol'] !== 'ADMIN') {
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

if ($video) {

    if (
        $video['tipo'] === 'ARCHIVO' &&
        !empty($video['archivo'])
    ) {

        $ruta = "../videos/" . $video['archivo'];

        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }

    $stmt = $conexion->prepare("
        DELETE FROM videos
        WHERE id = ?
    ");

    $stmt->execute([$id]);
}

header("Location: ../paginas/videos.php");