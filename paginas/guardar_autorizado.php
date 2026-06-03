<?php

require_once __DIR__ . "/../includes/conexion.php";

$id = $_POST['id'];

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$dni = $_POST['dni'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];

$archivo = $_POST['comprobante_actual'];

if (
    isset($_FILES['comprobante']) &&
    $_FILES['comprobante']['error'] == 0
) {

    $nuevoArchivo =
        time() . "_" .
        basename($_FILES['comprobante']['name']);

    move_uploaded_file(
        $_FILES['comprobante']['tmp_name'],
        "../uploads/" . $nuevoArchivo
    );

    $archivo = $nuevoArchivo;
}

$sql = "
UPDATE autorizados_retiro_detalle
SET
nombre=?,
apellido=?,
dni=?,
direccion=?,
telefono=?,
comprobante=?
WHERE id=?
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $nombre,
    $apellido,
    $dni,
    $direccion,
    $telefono,
    $archivo,
    $id
]);

header("Location: administrar_hijos.php");
exit;