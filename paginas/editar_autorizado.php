<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ADMIN') {
    header("Location: ../index.php");
    exit;
}

require_once "../includes/conexion.php";

$id = $_GET['id'] ?? null;

$stmt = $conn->prepare("SELECT * FROM autorizados_retiro_detalle WHERE id = ?");
$stmt->execute([$id]);
$aut = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aut) {
    echo "No encontrado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $stmt = $conn->prepare("
        UPDATE autorizados_retiro_detalle 
        SET nombre=?, apellido=?, dni=?, telefono=?, direccion=?
        WHERE id=?
    ");

    $stmt->execute([
        $nombre, $apellido, $dni, $telefono, $direccion, $id
    ]);

    header("Location: permisos.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Autorizado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card p-4">

        <h4>Editar Autorizado</h4>

        <form method="POST">

            <input class="form-control mb-2" name="nombre" value="<?= $aut['nombre'] ?>">
            <input class="form-control mb-2" name="apellido" value="<?= $aut['apellido'] ?>">
            <input class="form-control mb-2" name="dni" value="<?= $aut['dni'] ?>">
            <input class="form-control mb-2" name="telefono" value="<?= $aut['telefono'] ?>">
            <input class="form-control mb-2" name="direccion" value="<?= $aut['direccion'] ?>">

            <button class="btn btn-success">Guardar</button>
            <a href="permisos.php" class="btn btn-secondary">Volver</a>

        </form>

    </div>

</div>

</body>
</html>