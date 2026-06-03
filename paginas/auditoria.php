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

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LMGB - Auditoría</title>

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

        .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
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
<div class="sidebar">
    <?php include __DIR__ . "/../includes/sidebar.php"; ?>
</div>

        <!-- MAIN -->
        <div class="main">

            <div class="topbar d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Auditoría del Sistema</h4>

                <a href="../acciones/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    Salir
                </a>

            </div>

            <div class="container-fluid p-4">

                <div class="card card-dashboard">
                    <div class="card-body">

                        <h5>Registro de actividad</h5>
                        <p class="mb-0">Aquí se mostrarán los logs del sistema.</p>

                    </div>
                </div>

            </div>

        </div>

    </div>

</body>

</html>