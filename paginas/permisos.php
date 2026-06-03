<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['rol'] !== 'ADMIN') {
    header("Location: inicio.php");
    exit;
}

require_once "../includes/conexion.php";

/* =========================
   PERMISOS DE RETIRO
========================= */
$sql = "
SELECT 
    pr.id,
    a.nombre AS alumno_nombre,
    a.apellido AS alumno_apellido,
    t.nombre AS tutor_nombre,
    t.apellido AS tutor_apellido,
    ar.nombre AS autorizado_nombre,
    ar.apellido AS autorizado_apellido,
    pr.fecha_inicio,
    pr.fecha_fin,
    pr.estado
FROM permisos_retiro pr
INNER JOIN alumnos a ON pr.alumno_id = a.id
INNER JOIN tutores t ON pr.tutor_id = t.id
INNER JOIN autorizados_retiro ar ON pr.autorizado_id = ar.id
ORDER BY pr.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   AUTORIZADOS DETALLE
========================= */
/* =========================
   BUSCADOR DNI ALUMNO
========================= */
$buscar_dni = trim($_GET['dni'] ?? '');

$sql2 = "
SELECT 
    ard.id,
    a.nombre AS alumno_nombre,
    a.apellido AS alumno_apellido,
    a.dni AS alumno_dni,
    ard.nombre AS autorizado_nombre,
    ard.apellido AS autorizado_apellido,
    ard.dni,
    ard.telefono,
    ard.direccion,
    ard.comprobante,
    ard.creado_en
FROM autorizados_retiro_detalle ard
INNER JOIN alumnos a ON a.id = ard.alumno_id
";

if (!empty($buscar_dni)) {
    $sql2 .= " WHERE a.dni LIKE :dni ";
}

$sql2 .= " ORDER BY ard.id DESC ";

$stmt2 = $conn->prepare($sql2);

if (!empty($buscar_dni)) {
    $stmt2->bindValue(':dni', "%{$buscar_dni}%");
}

$stmt2->execute();
$autorizados = $stmt2->fetchAll(PDO::FETCH_ASSOC);



/* TOTAL */
$total2 = $conn->query("SELECT COUNT(*) FROM autorizados_retiro_detalle")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permisos y Autorizados</title>

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
            display: block;
            color: white;
            text-decoration: none;
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

        .card-box {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
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
                <h4 class="mb-0">Gestión de Permisos</h4>
                <a href="../acciones/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>

            <div class="container-fluid p-4">

                <!-- RESUMEN -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card card-box p-3 text-center">
                            <h6>Autorizados cargados</h6>
                            <h2><?= $total2 ?></h2>
                        </div>
                    </div>
                </div>

                <!-- TABLA -->
                <div class="card card-box">
                    <div class="card-body">

                        <h5>Autorizados cargados por padres</h5>

                        <form method="GET" id="formBuscar" class="row g-2 mb-3">

                            <div class="col-md-4">
                                <input type="text" id="buscarDni" name="dni" class="form-control"
                                    placeholder="Buscar por DNI del alumno"
                                    value="<?= htmlspecialchars($buscar_dni) ?>">
                            </div>

                            <div class="col-md-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Buscar
                                </button>

                                <a href="permisos.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Limpiar
                                </a>
                            </div>

                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">

                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Alumno</th>
                                        <th>DNI Alumno</th>
                                        <th>Autorizado</th>
                                        <th>DNI</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Fecha</th>
                                        <th>Comprobante</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php foreach ($autorizados as $a): ?>

                                        <tr>
                                            <td><?= $a['id'] ?></td>
                                            <td><?= $a['alumno_nombre'] . " " . $a['alumno_apellido'] ?></td>
                                            <td><?= $a['alumno_dni'] ?></td>
                                            <td><?= $a['autorizado_nombre'] . " " . $a['autorizado_apellido'] ?></td>
                                            <td><?= $a['dni'] ?></td>
                                            <td><?= $a['telefono'] ?></td>
                                            <td><?= $a['direccion'] ?></td>
                                            <td><?= $a['creado_en'] ?></td>

                                            <!-- COMPROBANTE -->
                                            <td>
                                                <?php if (!empty($a['comprobante'])): ?>

                                                    <?php
                                                    $file = "../uploads/" . $a['comprobante'];
                                                    $ext = strtolower(pathinfo($a['comprobante'], PATHINFO_EXTENSION));
                                                    ?>

                                                    <a href="<?= $file ?>" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> Ver
                                                    </a>

                                                    <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])): ?>
                                                        <br>
                                                        <img src="<?= $file ?>"
                                                            style="width:40px;height:40px;object-fit:cover;border-radius:5px;margin-top:5px;">
                                                    <?php endif; ?>

                                                <?php else: ?>
                                                    <span class="text-muted">Sin archivo</span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- ACCIONES -->
                                            <td>

                                                <a href="editar_autorizado.php?id=<?= $a['id'] ?>"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <a href="eliminar_autorizado.php?id=<?= $a['id'] ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('¿Seguro que quieres eliminar este autorizado?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        let timer;

        document.getElementById('buscarDni').addEventListener('keyup', function () {

            clearTimeout(timer);

            timer = setTimeout(() => {
                document.getElementById('formBuscar').submit();
            }, 500);

        });
    </script>
</body>

</html>