<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['rol'] !== 'PADRE' && $_SESSION['rol'] !== 'ADMIN') {
    header("Location: inicio.php");
    exit;
}

require_once __DIR__ . "/../includes/conexion.php";

$usuario_id = $_SESSION['usuario_id'];

/* =========================
   1. VERIFICAR CUANTOS HIJOS TIENE
========================= */
$sql = "
SELECT COUNT(*) as total
FROM alumnos_tutores at
INNER JOIN tutores t ON t.id = at.tutor_id
WHERE t.usuario_id = :usuario_id
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

if ($total >= 3) {
    die("<div style='padding:20px;font-family:Arial'>
        ❌ Ya alcanzaste el máximo de 3 hijos.
        <br><br>
        <a href='hijos.php'>Volver</a>
    </div>");
}

/* =========================
   2. OBTENER ID DEL TUTOR
========================= */
$sqlTutor = "SELECT id FROM tutores WHERE usuario_id = :usuario_id LIMIT 1";
$stmt = $conn->prepare($sqlTutor);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$tutor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tutor) {
    die("No se encontró tutor asociado a este usuario");
}

$tutor_id = $tutor['id'];

/* =========================
   3. LISTA DE ALUMNOS DISPONIBLES
   (que aún no estén asignados a este tutor)
========================= */
$sqlAlumnos = "
SELECT a.*
FROM alumnos a
WHERE a.id NOT IN (
    SELECT alumno_id 
    FROM alumnos_tutores 
    WHERE tutor_id = :tutor_id
)
ORDER BY a.apellido ASC
";

$stmt = $conn->prepare($sqlAlumnos);
$stmt->bindParam(':tutor_id', $tutor_id, PDO::PARAM_INT);
$stmt->execute();

$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   4. PROCESAR FORMULARIO
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $alumno_id = $_POST['alumno_id'] ?? null;

    if (!$alumno_id) {
        $error = "Selecciona un alumno";
    } else {

        // verificar duplicado
        $sqlCheck = "
        SELECT id FROM alumnos_tutores
        WHERE alumno_id = :alumno_id AND tutor_id = :tutor_id
        ";

        $stmt = $conn->prepare($sqlCheck);
        $stmt->bindParam(':alumno_id', $alumno_id);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "Este alumno ya está asignado";
        } else {

            $sqlInsert = "
            INSERT INTO alumnos_tutores (alumno_id, tutor_id, principal)
            VALUES (:alumno_id, :tutor_id, 0)
            ";

            $stmt = $conn->prepare($sqlInsert);
            $stmt->bindParam(':alumno_id', $alumno_id);
            $stmt->bindParam(':tutor_id', $tutor_id);

            if ($stmt->execute()) {
                header("Location: hijos.php?ok=1");
                exit;
            } else {
                $error = "Error al asignar hijo";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Hijo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f9">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-body">

            <h3 class="mb-4">Agregar Hijo</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (count($alumnos) == 0): ?>
                <div class="alert alert-warning">
                    No hay alumnos disponibles para asignar.
                </div>

                <a href="hijos.php" class="btn btn-secondary">Volver</a>

            <?php else: ?>

            <form method="POST">

                <div class="mb-3">
                    <label>Seleccionar Alumno</label>

                    <select name="alumno_id" class="form-control" required>
                        <option value="">-- Seleccionar --</option>

                        <?php foreach ($alumnos as $a): ?>
                            <option value="<?= $a['id'] ?>">
                                <?= $a['apellido'] . " " . $a['nombre'] . " (DNI: " . $a['dni'] . ")" ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <button class="btn btn-primary">
                    Agregar Hijo
                </button>

                <a href="hijos.php" class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>