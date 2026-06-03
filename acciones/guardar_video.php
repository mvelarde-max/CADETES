<?php

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ADMIN') {
    header("Location: ../index.php");
    exit;
}

require_once "../includes/conexion.php";

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$tipo = $_POST['tipo'];

$url = null;
$archivo = null;

/* =========================
   LINK
========================= */
if ($tipo === 'LINK') {

    $url = $_POST['url'];

/* =========================
   ARCHIVO
========================= */
} else {

    if (!empty($_FILES['video']['name'])) {

        $archivo = time() . "_" . $_FILES['video']['name'];

        move_uploaded_file(
            $_FILES['video']['tmp_name'],
            "../videos/" . $archivo
        );
    }
}

/* =========================
   INSERT PDO (CORRECTO)
========================= */
$stmt = $conexion->prepare("
    INSERT INTO videos
    (titulo, descripcion, tipo, url, archivo)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $titulo,
    $descripcion,
    $tipo,
    $url,
    $archivo
]);

header("Location: ../paginas/videos.php");
exit;