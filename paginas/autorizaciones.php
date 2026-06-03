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
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

/* =========================
   OBTENER HIJOS DEL PADRE
========================= */
$sql = "
SELECT a.id, a.nombre, a.apellido
FROM alumnos a
INNER JOIN alumnos_tutores at ON at.alumno_id = a.id
INNER JOIN tutores t ON t.id = at.tutor_id
WHERE t.usuario_id = :usuario_id
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();

$hijos = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================
   AUTORIZADOS POR ALUMNO
========================= */
$autorizaciones = [];

foreach ($hijos as $hijo) {

    $stmtAuto = $conn->prepare("
        SELECT id,
               nombre,
               apellido,
               dni,
               direccion,
               telefono,
               comprobante
        FROM autorizados_retiro_detalle
        WHERE alumno_id = :alumno_id
        ORDER BY id DESC
    ");

    $stmtAuto->bindParam(':alumno_id', $hijo['id']);
    $stmtAuto->execute();

    $autorizaciones[$hijo['id']] = $stmtAuto->fetchAll(PDO::FETCH_ASSOC);
}

/* =========================
   FORMULARIO
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $alumno_id = $_POST["alumno_id"];
    $nombreForm = $_POST["nombre"];
    $apellidoForm = $_POST["apellido"];
    $dni = $_POST["dni"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];

    /* MAX 2 AUTORIZADOS */
    $check = $conn->prepare("
        SELECT COUNT(*) as total
        FROM autorizados_retiro_detalle
        WHERE alumno_id = :alumno_id
    ");
    $check->bindParam(':alumno_id', $alumno_id);
    $check->execute();
    $total = $check->fetch(PDO::FETCH_ASSOC)['total'];

    if ($total >= 2) {

        $error = "Máximo 2 autorizados por hijo";

    } else {

        /* =========================
           SUBIDA DE ARCHIVO
        ========================= */

        $comprobante = null;

        if (!empty($_FILES["comprobante"]["name"])) {

            $fileName = time() . "_" . basename($_FILES["comprobante"]["name"]);
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

            if (!in_array($ext, $permitidos)) {

                $error = "Solo se permiten imágenes o PDF";

            } else {

                $uploadDir = __DIR__ . "/../uploads/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $destino = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES["comprobante"]["tmp_name"], $destino)) {
                    $comprobante = $fileName;
                } else {
                    $error = "Error al subir el archivo";
                }
            }
        }

        /* =========================
           INSERT SOLO SI NO HAY ERROR
        ========================= */
        if (empty($error)) {

            $tutorStmt = $conn->prepare("SELECT id FROM tutores WHERE usuario_id=:u");
            $tutorStmt->bindParam(':u', $usuario_id);
            $tutorStmt->execute();
            $tutor_id = $tutorStmt->fetchColumn();

            $insert = $conn->prepare("
                INSERT INTO autorizados_retiro_detalle
                (alumno_id, tutor_id, nombre, apellido, dni, direccion, telefono, comprobante)
                VALUES
                (:alumno_id, :tutor_id, :nombre, :apellido, :dni, :direccion, :telefono, :comprobante)
            ");

            $insert->bindParam(':alumno_id', $alumno_id);
            $insert->bindParam(':tutor_id', $tutor_id);
            $insert->bindParam(':nombre', $nombreForm);
            $insert->bindParam(':apellido', $apellidoForm);
            $insert->bindParam(':dni', $dni);
            $insert->bindParam(':direccion', $direccion);
            $insert->bindParam(':telefono', $telefono);
            $insert->bindParam(':comprobante', $comprobante);

            $insert->execute();

            $ok = "Autorizado agregado correctamente";
        }
    }
}

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autorizaciones</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #0d6efd;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, .15);
        }

        .main {
            flex: 1;
        }

        .topbar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
        }

        .card-dashboard {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
        }
    </style>
</head>

<body>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <?php include __DIR__ . "/../includes/sidebar.php"; ?>
        </div>

        <!-- MAIN -->
        <div class="main">

            <!-- TOPBAR -->
            <div class="topbar d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-0">
                        Bienvenido <?= htmlspecialchars($nombre) ?>
                    </h4>
                    <small>Rol: <?= htmlspecialchars($rol) ?></small>
                </div>

                <a href="../acciones/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>

            </div>

            <!-- CONTENIDO -->
            <div class="container-fluid p-4">

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <?php if (!empty($ok)): ?>
                    <div class="alert alert-success"><?= $ok ?></div>
                <?php endif; ?>

                <?php foreach ($hijos as $h): ?>

                    <div class="card mb-4 card-dashboard">
                        <div class="card-body">

                            <h5><?= htmlspecialchars($h['nombre'] . " " . $h['apellido']) ?></h5>

                            <form method="POST" enctype="multipart/form-data">

                                <input type="hidden" name="alumno_id" value="<?= $h['id'] ?>">

                                <div class="row g-2">

                                    <div class="col">
                                        <input class="form-control" name="nombre" placeholder="Nombre" required>
                                    </div>

                                    <div class="col">
                                        <input class="form-control" name="apellido" placeholder="Apellido" required>
                                    </div>

                                    <div class="col">
                                        <input class="form-control" name="dni" placeholder="DNI" required>
                                    </div>

                                    <div class="col">
                                        <input class="form-control" name="direccion" placeholder="Dirección">
                                    </div>

                                    <div class="col">
                                        <input class="form-control" name="telefono" placeholder="Teléfono">
                                    </div>

                                </div>

                                <div class="mt-2">
                                    <input type="file" name="comprobante" class="form-control">
                                </div>

                                <button class="btn btn-primary mt-2">
                                    Agregar Autorizado
                                </button>



                            </form>

                            <hr>

                            <h6 class="mt-4">
                                Autorizados registrados
                                (<?= count($autorizaciones[$h['id']]) ?>/2)
                            </h6>

                            <?php if (!empty($autorizaciones[$h['id']])): ?>

                                <div class="table-responsive mt-3">

                                    <table class="table table-bordered table-striped align-middle">

                                        <thead class="table-light">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>DNI</th>
                                                <th>Dirección</th>
                                                <th>Teléfono</th>
                                                <th>Comprobante</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php foreach ($autorizaciones[$h['id']] as $a): ?>

                                                <tr>

                                                    <td>
                                                        <?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($a['dni']) ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($a['direccion']) ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($a['telefono']) ?>
                                                    </td>

                                                    <td>

                                                       <?php if (!empty($a['comprobante'])): ?>

    <?php
    $extension = strtolower(pathinfo($a['comprobante'], PATHINFO_EXTENSION));
    $rutaArchivo = "../uploads/" . $a['comprobante'];
    ?>

    <?php if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])): ?>

        <a href="<?= $rutaArchivo ?>" target="_blank">
            <img
                src="<?= $rutaArchivo ?>"
                alt="Comprobante"
                style="max-width:120px; max-height:120px; object-fit:cover; border-radius:8px;">
        </a>

    <?php else: ?>

        <a
            href="<?= $rutaArchivo ?>"
            target="_blank"
            class="btn btn-sm btn-primary">

            <i class="bi bi-file-earmark-pdf"></i>
            Ver PDF

        </a>

    <?php endif; ?>

<?php else: ?>

    <span class="text-muted">Sin archivo</span>

<?php endif; ?>

                                                    </td>

                                                </tr>

                                            <?php endforeach; ?>

                                        </tbody>

                                    </table>

                                </div>

                            <?php else: ?>

                                <div class="alert alert-secondary mt-3 mb-0">
                                    No hay autorizados registrados para este alumno.
                                </div>

                            <?php endif; ?>





                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</body>

</html>