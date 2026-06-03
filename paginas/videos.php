<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

if (
    $_SESSION['rol'] !== 'ADMIN' &&
    $_SESSION['rol'] !== 'ALUMNO'
) {
    header("Location: inicio.php");
    exit;
}

require_once "../includes/conexion.php";

/* =========================
   ESTADÍSTICAS
========================= */
$totalVideos = $conexion->query("
    SELECT COUNT(*) 
    FROM videos
")->fetchColumn();

$totalActivos = $conexion->query("
    SELECT COUNT(*) 
    FROM videos 
    WHERE estado = 'ACTIVO'
")->fetchColumn();

$totalBorradores = $conexion->query("
    SELECT COUNT(*) 
    FROM videos 
    WHERE estado = 'BORRADOR'
")->fetchColumn();

/* =========================
   LISTADO VIDEOS
========================= */
$stmt = $conexion->query("
    SELECT * 
    FROM videos 
    ORDER BY fecha_subida DESC
");

$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LMGB - Videos</title>

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

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Videos</h4>

            <a href="../acciones/logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i>
                Salir
            </a>

        </div>

        <!-- CONTENIDO -->
        <div class="container-fluid p-4">

            <!-- ESTADÍSTICAS -->
            <div class="row mb-4">

                <div class="col-md-4">
                    <div class="card card-dashboard">
                        <div class="card-body text-center">
                            <h5>Total Videos</h5>
                            <h2><?= $totalVideos ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-dashboard">
                        <div class="card-body text-center">
                            <h5>Publicados</h5>
                            <h2><?= $totalActivos ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-dashboard">
                        <div class="card-body text-center">
                            <h5>Borradores</h5>
                            <h2><?= $totalBorradores ?></h2>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CARD PRINCIPAL -->
            <div class="card card-dashboard">

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <h5 class="mb-0">Listado de Videos</h5>

                        <?php if ($_SESSION['rol'] === 'ADMIN'): ?>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalVideo">
                                <i class="bi bi-plus-circle"></i>
                                Subir Video
                            </button>
                        <?php endif; ?>

                    </div>

                    <!-- TABLA -->
                    <table class="table table-striped">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($videos as $video): ?>

                            <tr>

                                <td><?= $video['id'] ?></td>

                                <td><?= htmlspecialchars($video['titulo']) ?></td>

                                <td><?= $video['tipo'] ?></td>

                                <td><?= $video['fecha_subida'] ?></td>

                                <td>

                                    <?php if ($video['estado'] === 'ACTIVO'): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Borrador</span>
                                    <?php endif; ?>

                                </td>

                                <td>

                                    <a href="ver_video.php?id=<?= $video['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="bi bi-play-circle"></i>
                                    </a>

                                    <?php if ($_SESSION['rol'] === 'ADMIN'): ?>

                                        <a href="editar_video.php?id=<?= $video['id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="../acciones/eliminar_video.php?id=<?= $video['id'] ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Eliminar este video?')">
                                            <i class="bi bi-trash"></i>
                                        </a>

                                    <?php endif; ?>

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

<!-- MODAL SUBIR VIDEO -->
<div class="modal fade" id="modalVideo">

    <div class="modal-dialog">

        <form action="../acciones/guardar_video.php" method="POST" enctype="multipart/form-data" class="modal-content">

            <div class="modal-header">
                <h5>Nuevo Video</h5>
            </div>

            <div class="modal-body">

                <input type="text" name="titulo" class="form-control mb-3" placeholder="Título" required>

                <textarea name="descripcion" class="form-control mb-3" placeholder="Descripción"></textarea>

                <select name="tipo" class="form-select mb-3" required>
                    <option value="LINK">Link externo</option>
                    <option value="ARCHIVO">Archivo MP4</option>
                </select>

                <input type="text" name="url" class="form-control mb-3" placeholder="https://youtube.com/...">

                <input type="file" name="video" class="form-control" accept="video/*">

            </div>

            <div class="modal-footer">

                <button class="btn btn-success">Guardar</button>

            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>