<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    exit;
}

if ($_SESSION['rol'] !== 'ADMIN') {
    exit;
}

if (!isset($_FILES['documento'])) {
    die("No se recibió archivo");
}

$carpeta = "../documentos/";

if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$nombreOriginal = $_FILES['documento']['name'];
$tmp = $_FILES['documento']['tmp_name'];

$extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

$permitidos = [
    'pdf',
    'doc',
    'docx',
    'xls',
    'xlsx',
    'ppt',
    'pptx',
    'jpg',
    'jpeg',
    'png'
];

if (!in_array($extension, $permitidos)) {
    die("Tipo de archivo no permitido");
}

$nombreFinal = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombreOriginal);

if (move_uploaded_file($tmp, $carpeta . $nombreFinal)) {

    header("Location: ../paginas/documentos.php?ok=1");
exit;

} else {

    die("Error al subir archivo");

}