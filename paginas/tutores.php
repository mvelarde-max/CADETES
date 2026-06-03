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

require_once '../includes/conexion.php';

$sql = "
SELECT
    id,
    nombre,
    apellido,
    dni,
    telefono,
    email,
    parentesco,
    activo
FROM tutores
ORDER BY apellido, nombre
";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LMGB - Tutores</title>

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

        .card-dashboard {
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

        <!-- CONTENIDO -->
        <div class="main">

            <div class="topbar d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Gestión de Tutores</h4>

                <a href="../acciones/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>

            </div>

            <div class="container-fluid p-4">

                <!-- RESUMEN -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card card-dashboard">
                            <div class="card-body text-center">
                                <h5>Total Tutores</h5>
                                <h2><?= count($tutores) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTÓN NUEVO -->
                <div class="card card-dashboard mb-4">
                    <div class="card-body">
                        <a href="nuevo_tutor.php" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Nuevo Tutor
                        </a>
                    </div>
                </div>

                <!-- TABLA -->
                <div class="card card-dashboard">
                    <div class="card-body">

                        <h5 class="mb-3">Listado de Tutores</h5>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Apellido</th>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Parentesco</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php foreach ($tutores as $tutor): ?>
                                        <tr>

                                            <td><?= $tutor['id'] ?></td>
                                            <td><?= htmlspecialchars($tutor['apellido']) ?></td>
                                            <td><?= htmlspecialchars($tutor['nombre']) ?></td>
                                            <td><?= htmlspecialchars($tutor['dni']) ?></td>
                                            <td><?= htmlspecialchars($tutor['telefono']) ?></td>
                                            <td><?= htmlspecialchars($tutor['email']) ?></td>
                                            <td><?= htmlspecialchars($tutor['parentesco']) ?></td>

                                            <td>
                                                <?php if ($tutor['activo']): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-nowrap">

                                                <!-- VER -->
                                                <a href="../acciones/ver_tutor.php?id=<?= $tutor['id'] ?>"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <!-- EDITAR -->
                                                <a href="../acciones/editar_tutor.php?id=<?= $tutor['id'] ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <!-- ELIMINAR -->
                                                <a href="../acciones/eliminar_tutor.php?id=<?= $tutor['id'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('¿Seguro que deseas ELIMINAR este tutor? Esta acción no se puede deshacer.')">

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

</body>

</html>