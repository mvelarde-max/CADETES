<?php
session_start();

// 🔒 Seguridad: si no está logueado, fuera
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

// 🔹 Datos desde sesión (CORRECTO)
$usuario_id = $_SESSION['usuario_id'];
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LMGB - Inicio</title>

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
            color: white;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,.15);
        }

        .logo {
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.2);
        }

        .main {
            flex: 1;
        }

        .topbar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
        }

        .card-dashboard {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
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

                <small>
                    Rol: <?= htmlspecialchars($rol) ?>
                </small>
            </div>

            <div class="d-flex gap-2">

                <!-- SOLO ADMIN -->
                <?php if ($rol === 'ADMIN'): ?>
                    <a href="admin_crear.php" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Crear usuario
                    </a>
                <?php endif; ?>

                <a href="../acciones/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>

            </div>

        </div>

        <!-- CONTENIDO -->
        <div class="container-fluid p-4">

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card card-dashboard">
                        <div class="card-body">
                            <h5>Usuario</h5>
                            <p><?= htmlspecialchars($nombre) ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-dashboard">
                        <div class="card-body">
                            <h5>Rol</h5>
                            <p><?= htmlspecialchars($rol) ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-dashboard">
                        <div class="card-body">
                            <h5>Estado</h5>
                            <p>Sesión activa</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card mt-4 card-dashboard">
                <div class="card-body">
                    <h3>Sistema LMGB</h3>
                    <p>Bienvenido al sistema institucional.</p>
                    <p>Desde este panel podrás acceder a las funciones según tu rol.</p>
                </div>
            </div>

        </div>

    </div>

</div>

</body>
</html>