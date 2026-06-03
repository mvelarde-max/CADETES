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

// Roles disponibles
$roles = [
    "ADMIN" => 1,
    "PADRE" => 2,
    "ALUMNO" => 3
];

// Mensaje
$mensaje = "";

// PROCESAR FORMULARIO
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre   = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $rol_text = $_POST["rol"];

    if (!isset($roles[$rol_text])) {
        $mensaje = "❌ Rol inválido";
    } else {

        $rol_id = $roles[$rol_text];

        // Verificar email
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $mensaje = "⚠️ El usuario ya existe con ese email";
        } else {

            $hash = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO usuarios (nombre, apellido, email, password, rol_id, activo)
                    VALUES (?, ?, ?, ?, ?, 1)";

            $stmt = $conexion->prepare($sql);

            if ($stmt->execute([$nombre, $apellido, $email, $hash, $rol_id])) {
                $mensaje = "✅ Usuario creado correctamente como $rol_text";
            } else {
                $mensaje = "❌ Error al crear usuario";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LMGB - Crear Usuario</title>

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

            <h4 class="mb-0">Crear Usuario</h4>

            <a href="../acciones/logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i>
                Salir
            </a>

        </div>

        <!-- CONTENIDO -->
        <div class="container-fluid p-4">

            <!-- MENSAJE -->
            <?php if ($mensaje): ?>
                <div class="alert alert-info">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <div class="card card-dashboard">

                <div class="card-body">

                    <h5 class="mb-3">Nuevo Usuario</h5>

                    <form method="POST">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Apellido</label>
                                <input type="text" name="apellido" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Rol</label>
                                <select name="rol" class="form-select" required>
                                    <option value="">Seleccione rol</option>
                                    <option value="ADMIN">Admin</option>
                                    <option value="PADRE">Padre</option>
                                    <option value="ALUMNO">Alumno</option>
                                </select>
                            </div>

                        </div>

                        <button class="btn btn-success">
                            <i class="bi bi-person-plus"></i>
                            Crear Usuario
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>