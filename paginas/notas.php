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

    <title>LMGB - Notas</title>

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

        <!-- CONTENIDO -->

        <div class="main">

            <div class="topbar d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-0">
                        Gestión de Notas
                    </h4>
                </div>

                <a href="../acciones/logout.php" class="btn btn-danger">

                    <i class="bi bi-box-arrow-right"></i>
                    Salir

                </a>

            </div>

            <div class="container-fluid p-4">

                <!-- RESUMEN -->

                <div class="row mb-4">

                    <div class="col-md-4">

                        <div class="card card-dashboard">

                            <div class="card-body text-center">

                                <h5>Total Registros</h5>

                                <h2>12</h2>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- BOTÓN NUEVA NOTA -->

                <div class="card card-dashboard mb-4">

                    <div class="card-body">

                        <button class="btn btn-success">

                            <i class="bi bi-plus-circle"></i>
                            Nueva Nota

                        </button>

                    </div>

                </div>

                <!-- TABLA -->

                <div class="card card-dashboard">

                    <div class="card-body">

                        <h5 class="mb-3">
                            Listado de Notas
                        </h5>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-primary">

                                    <tr>

                                        <th>ID</th>
                                        <th>Alumno</th>
                                        <th>Materia</th>
                                        <th>Trimestre</th>
                                        <th>Nota</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>

                                        <td>1</td>
                                        <td>Juan Gómez</td>
                                        <td>Matemática</td>
                                        <td>1°</td>
                                        <td>9</td>

                                        <td>
                                            <span class="badge bg-success">
                                                Aprobado
                                            </span>
                                        </td>

                                        <td>

                                            <button class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <button class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>2</td>
                                        <td>María López</td>
                                        <td>Lengua</td>
                                        <td>1°</td>
                                        <td>7</td>

                                        <td>
                                            <span class="badge bg-success">
                                                Aprobado
                                            </span>
                                        </td>

                                        <td>

                                            <button class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <button class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>

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