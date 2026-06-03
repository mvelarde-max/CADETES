<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

/*
    PERMISOS:
    - ADMIN: control total
    - PADRE y ALUMNO: solo lectura
*/
if (!in_array($_SESSION['rol'], ['ADMIN', 'PADRE', 'ALUMNO'])) {
    header("Location: inicio.php");
    exit;
}

$rol = $_SESSION['rol'];

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LMGB - Comunicaciones</title>

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

        <!-- SIDEBAR -->
        <div class="sidebar">
            <?php include __DIR__ . "/../includes/sidebar.php"; ?>
        </div>

        <!-- MAIN -->
        <div class="main">

            <div class="topbar d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Comunicaciones</h4>

                <a href="../acciones/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    Salir
                </a>

            </div>

            <div class="container-fluid p-4">

                <!-- CARDS -->
                <div class="row mb-4">

                    <div class="col-md-4">
                        <div class="card card-dashboard">
                            <div class="card-body text-center">
                                <h5>Total Comunicados</h5>
                                <h2>0</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-dashboard">
                            <div class="card-body text-center">
                                <h5>Enviados</h5>
                                <h2>0</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-dashboard">
                            <div class="card-body text-center">
                                <h5>Pendientes</h5>
                                <h2>0</h2>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- BOTÓN SOLO ADMIN -->
                <?php if ($rol === 'ADMIN'): ?>
                    <div class="card card-dashboard mb-4">
                        <div class="card-body">
                            <button class="btn btn-success">
                                <i class="bi bi-plus-circle"></i>
                                Nueva Comunicación
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- TABLA -->
                <div class="card card-dashboard">

                    <div class="card-body">

                        <h5 class="mb-3">Listado de Comunicaciones</h5>

                        <div class="table-responsive">

                            <table class="table table-striped">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Destinatario</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td>1</td>
                                        <td>Reunión padres</td>
                                        <td>General</td>
                                        <td>2026-06-02</td>
                                        <td><span class="badge bg-success">Enviado</span></td>

                                        <td>
                                            <button class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <?php if ($rol === 'ADMIN'): ?>
                                                <button class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

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