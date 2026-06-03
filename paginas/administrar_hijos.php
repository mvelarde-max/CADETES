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

require_once __DIR__ . "/../includes/conexion.php";

/* =========================
   CONSULTA ALUMNOS
========================= */

$sql = "
SELECT 
    a.id AS alumno_id,
    a.nombre AS alumno_nombre,
    a.apellido AS alumno_apellido,
    a.dni AS alumno_dni,

    t.nombre AS tutor_nombre,
    t.apellido AS tutor_apellido,
    t.dni AS tutor_dni,
    t.email AS tutor_email,

    ard.nombre AS aut_nombre,
    ard.apellido AS aut_apellido,
    ard.dni AS aut_dni,
    ard.direccion AS aut_direccion,
    ard.telefono AS aut_telefono,
    ard.comprobante AS aut_comprobante

FROM alumnos a
LEFT JOIN alumnos_tutores at ON at.alumno_id = a.id
LEFT JOIN tutores t ON t.id = at.tutor_id
LEFT JOIN autorizados_retiro_detalle ard ON ard.alumno_id = a.id

ORDER BY a.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   AGRUPAR POR ALUMNO
========================= */

$alumnos = [];

foreach ($data as $row) {

    $id = $row['alumno_id'];

    if (!isset($alumnos[$id])) {
        $alumnos[$id] = [
            'nombre' => $row['alumno_nombre'],
            'apellido' => $row['alumno_apellido'],
            'dni' => $row['alumno_dni'],
            'tutores' => [],
            'autorizados' => []
        ];
    }

    if (!empty($row['tutor_dni'])) {
        $key = $row['tutor_dni'] . $row['tutor_email'];
        $alumnos[$id]['tutores'][$key] = $row;
    }

    if (!empty($row['aut_dni'])) {
        $alumnos[$id]['autorizados'][$row['aut_dni']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expediente Alumnos</title>

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
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
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

        table {
            background: white;
            font-size: 14px;
        }

        th {
            background: #0d6efd;
            color: white;
            white-space: nowrap;
        }

        td {
            vertical-align: top;
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
                <h4 class="mb-0">Expediente en lista</h4>

                <a href="../acciones/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>

            <div class="container-fluid p-4">

                <div class="card card-box">
                    <div class="card-body">

                        <h5>Listado estructurado</h5>

                        <!-- BUSCADOR -->
                        <div class="mb-3">
                            <input type="text" id="buscarDni" class="form-control"
                                placeholder="Buscar alumno por DNI...">
                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered align-middle">

                                <thead>
                                    <tr>
                                        <th>Alumno</th>
                                        <th>Tutores</th>
                                        <th>Autorizados</th>
                                    </tr>
                                </thead>

                                <tbody id="tablaResultados">

                                    <?php foreach ($alumnos as $id => $alumno): ?>

                                        <tr data-dni="<?= trim($alumno['dni']) ?>">

                                            <td>
                                                <b><?= $alumno['nombre'] ?>     <?= $alumno['apellido'] ?></b><br>
                                                DNI: <?= $alumno['dni'] ?>
                                            </td>

                                            <td>
                                                <?php if (!empty($alumno['tutores'])): ?>
                                                    <?php foreach ($alumno['tutores'] as $t): ?>
                                                        <div>
                                                            <?= $t['tutor_nombre'] ?>
                                                            <?= $t['tutor_apellido'] ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?php if (!empty($alumno['autorizados'])): ?>
                                                    <?php foreach ($alumno['autorizados'] as $a): ?>
                                                        <div>
                                                            <?= $a['aut_nombre'] ?>
                                                            <?= $a['aut_apellido'] ?>
                                                        </div>
                                                    <?php endforeach; ?>
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

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const buscador = document.getElementById('buscarDni');

            buscador.addEventListener('keyup', function () {

                const filtro = this.value.trim();

                const filas = document.querySelectorAll('#tablaResultados tr');

                filas.forEach(function (fila) {

                    const dni = fila.dataset.dni || '';

                    if (dni.includes(filtro)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }

                });

            });

        });
    </script>

</body>

</html>