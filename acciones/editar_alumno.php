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

// traer alumno
$stmt = $conexion->prepare("SELECT * FROM alumnos WHERE id = ?");
$stmt->execute([$id]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    die("Alumno no encontrado");
}

// guardar cambios
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sql = "UPDATE alumnos SET
        legajo = ?,
        nombre = ?,
        apellido = ?,
        dni = ?,
        fecha_nacimiento = ?,
        telefono = ?,
        email = ?,
        activo = ?
        WHERE id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        $_POST['legajo'],
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['dni'],
        $_POST['fecha_nacimiento'],
        $_POST['telefono'],
        $_POST['email'],
        isset($_POST['activo']) ? 1 : 0,
        $id
    ]);

    header("Location: ../paginas/alumnos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-body">

            <h3 class="mb-4">Editar Alumno</h3>

            <form method="POST">

                <input type="text" name="legajo" class="form-control mb-2"
                       value="<?= htmlspecialchars($alumno['legajo']) ?>" placeholder="Legajo">

                <input type="text" name="nombre" class="form-control mb-2"
                       value="<?= htmlspecialchars($alumno['nombre']) ?>" placeholder="Nombre">

                <input type="text" name="apellido" class="form-control mb-2"
                       value="<?= htmlspecialchars($alumno['apellido']) ?>" placeholder="Apellido">

                <input type="text" name="dni" class="form-control mb-2"
                       value="<?= htmlspecialchars($alumno['dni']) ?>" placeholder="DNI">

                <input type="date" name="fecha_nacimiento" class="form-control mb-2"
                       value="<?= $alumno['fecha_nacimiento'] ?>">

                <input type="text" name="telefono" class="form-control mb-2"
                       value="<?= htmlspecialchars($alumno['telefono']) ?>" placeholder="Teléfono">

                <input type="email" name="email" class="form-control mb-2"
                       value="<?= htmlspecialchars($alumno['email']) ?>" placeholder="Email">

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="activo"
                        <?= $alumno['activo'] ? 'checked' : '' ?>>
                    <label class="form-check-label">Activo</label>
                </div>

                <button class="btn btn-primary">Guardar cambios</button>

                <a href="../paginas/alumnos.php" class="btn btn-secondary">Volver</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>