<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    exit;
}

if ($_SESSION['rol'] !== 'ADMIN') {
    exit;
}

if (!isset($_GET['archivo'])) {
    die("Archivo no especificado");
}

$archivo = basename($_GET['archivo']);

$ruta = "../documentos/" . $archivo;

if (file_exists($ruta)) {
    unlink($ruta);
}

header("Location: ../paginas/documentos.php");
exit;