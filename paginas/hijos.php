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

$sql = "
SELECT 
    a.id,
    a.legajo,
    a.nombre,
    a.apellido,
    a.dni,
    a.fecha_nacimiento,
    a.sexo,
    a.email,
    a.telefono
FROM alumnos a
INNER JOIN alumnos_tutores at ON at.alumno_id = a.id
INNER JOIN tutores t ON t.id = at.tutor_id
WHERE t.usuario_id = :usuario_id
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();

$hijos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($hijos);

$puede_cargar = ($total < 3);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Hijos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background:#f4f6f9; }
        .sidebar { width:250px; min-height:100vh; background:#0d6efd; }
        .sidebar a { display:block; color:white; padding:12px 20px; text-decoration:none; }
        .sidebar a:hover { background:rgba(255,255,255,.15); }
        .main { flex:1; }
        .topbar { background:white; padding:15px 25px; }
        .card-dashboard { border:none; border-radius:15px; }
    </style>
</head>

<body>

<div class="d-flex">

    <div class="sidebar">
        <?php include __DIR__ . "/../includes/sidebar.php"; ?>
    </div>

    <div class="main">

        <div class="topbar d-flex justify-content-between align-items-center">
            <h4>Mis Hijos</h4>

            <a href="../acciones/logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
        </div>

        <div class="container-fluid p-4">

            <!-- ALERTA LÍMITE -->
            <?php if (!$puede_cargar): ?>
                <div class="alert alert-warning">
                    Ya alcanzaste el máximo de <b>3 hijos</b>.
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-dashboard">
                        <div class="card-body text-center">
                            <h5>Total Hijos</h5>
                            <h2><?= $total ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTÓN AGREGAR -->
            <div class="mb-3">
                <a href="agregar_hijo.php" 
                   class="btn btn-primary <?= !$puede_cargar ? 'disabled' : '' ?>">
                    <i class="bi bi-person-plus"></i> Agregar Hijo
                </a>
            </div>

            <!-- TABLA -->
            <div class="card">
                <div class="card-body">

                    <table class="table table-bordered table-hover">

                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Legajo</th>
                                <th>Nombre</th>
                                <th>DNI</th>
                                <th>Nacimiento</th>
                                <th>Sexo</th>
                                <th>Email</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if ($total > 0): ?>
                            <?php foreach ($hijos as $hijo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($hijo['id']) ?></td>
                                    <td><?= htmlspecialchars($hijo['legajo']) ?></td>
                                    <td><?= htmlspecialchars($hijo['nombre'] . " " . $hijo['apellido']) ?></td>
                                    <td><?= htmlspecialchars($hijo['dni']) ?></td>
                                    <td><?= htmlspecialchars($hijo['fecha_nacimiento']) ?></td>
                                    <td><?= htmlspecialchars($hijo['sexo']) ?></td>
                                    <td><?= htmlspecialchars($hijo['email']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay hijos registrados
                                </td>
                            </tr>
                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>