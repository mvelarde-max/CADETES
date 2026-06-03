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

    <title>LMGB - Configuración</title>

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
                        Configuración del Sistema
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

                                <h5>Estado del Sistema</h5>

                                <h2>
                                    Activo
                                </h2>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- CONFIGURACIONES -->

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="card card-dashboard">

                            <div class="card-body">

                                <h5>
                                    Configuración General
                                </h5>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">Nombre del Sistema</label>
                                    <input type="text" class="form-control" value="LMGB">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email Administrador</label>
                                    <input type="email" class="form-control" value="admin@lmgb.com">
                                </div>

                                <button class="btn btn-primary">
                                    Guardar
                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="card card-dashboard">

                            <div class="card-body">

                                <h5>
                                    Seguridad
                                </h5>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">Cambiar Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Nueva contraseña">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Repetir contraseña">
                                </div>

                                <button class="btn btn-danger">
                                    Actualizar
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>