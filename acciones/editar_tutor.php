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

// traer tutor
$stmt = $conexion->prepare("SELECT * FROM tutores WHERE id = ?");
$stmt->execute([$id]);
$tutor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tutor) {
    die("Tutor no encontrado");
}

// guardar cambios
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sql = "UPDATE tutores SET
        nombre = ?,
        apellido = ?,
        dni = ?,
        telefono = ?,
        email = ?,
        direccion = ?,
        parentesco = ?,
        activo = ?
        WHERE id = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['dni'],
        $_POST['telefono'],
        $_POST['email'],
        $_POST['direccion'],
        $_POST['parentesco'],
        isset($_POST['activo']) ? 1 : 0,
        $id
    ]);

    header("Location: ../paginas/tutores.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-body">

            <h3 class="mb-4">Editar Tutor</h3>

            <form method="POST">

                <input type="text" name="nombre" class="form-control mb-2"
                       value="<?= htmlspecialchars($tutor['nombre']) ?>" placeholder="Nombre">

                <input type="text" name="apellido" class="form-control mb-2"
                       value="<?= htmlspecialchars($tutor['apellido']) ?>" placeholder="Apellido">

                <input type="text" name="dni" class="form-control mb-2"
                       value="<?= htmlspecialchars($tutor['dni']) ?>" placeholder="DNI">

                <input type="text" name="telefono" class="form-control mb-2"
                       value="<?= htmlspecialchars($tutor['telefono']) ?>" placeholder="Teléfono">

                <input type="email" name="email" class="form-control mb-2"
                       value="<?= htmlspecialchars($tutor['email']) ?>" placeholder="Email">

                <input type="text" name="direccion" class="form-control mb-2"
                       value="<?= htmlspecialchars($tutor['direccion']) ?>" placeholder="Dirección">

                <input type="text" name="parentesco" class="form-control mb-2"
                       value="<?= htmlspecialchars($tutor['parentesco']) ?>" placeholder="Parentesco">

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="activo"
                        <?= $tutor['activo'] ? 'checked' : '' ?>>
                    <label class="form-check-label">Activo</label>
                </div>

                <button class="btn btn-primary">Guardar cambios</button>

                <a href="../paginas/tutores.php" class="btn btn-secondary">Volver</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>