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


$carpeta = "../documentos/";

$totalDocumentos = 0;
$totalPdf = 0;
$totalOtros = 0;

if (is_dir($carpeta)) {

    $archivos = array_diff(scandir($carpeta), ['.', '..']);

    foreach ($archivos as $archivo) {

        $ruta = $carpeta . $archivo;

        if (!is_file($ruta)) {
            continue;
        }

        $totalDocumentos++;

        $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            $totalPdf++;
        } else {
            $totalOtros++;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LMGB - Documentos</title>

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

            <!-- TOPBAR -->
            <div class="topbar d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Documentos</h4>

                <a href="../acciones/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    Salir
                </a>

            </div>

            <!-- CONTENIDO -->
            <div class="container-fluid p-4">

                <div class="row mb-4">

                    <div class="col-md-4">
                        <div class="card card-dashboard">
                            <div class="card-body text-center">
                                <h5>Total Documentos</h5>
                                <h2><?= $totalDocumentos ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-dashboard">
                            <div class="card-body text-center">
                                <h5>PDF</h5>
                                <h2><?= $totalPdf ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-dashboard">
                            <div class="card-body text-center">
                                <h5>Otros</h5>
                                <h2><?= $totalOtros ?></h2>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card card-dashboard">

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <h5 class="mb-0">Listado de Documentos</h5>

                            <?php if ($_SESSION['rol'] === 'ADMIN'): ?>
                                <form action="../acciones/subir_documento.php" method="POST" enctype="multipart/form-data"
                                    class="d-flex gap-2">

                                    <input type="file" name="documento" class="form-control" required>

                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-upload"></i>
                                        Subir
                                    </button>

                                </form>
                            <?php endif; ?>

                        </div>

                        <table class="table table-striped">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th>Tamaño</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php

                                $carpeta = "../documentos/";

                                if (is_dir($carpeta)) {

                                    $archivos = array_diff(scandir($carpeta), ['.', '..']);

                                    $id = 1;

                                    foreach ($archivos as $archivo) {

                                        $ruta = $carpeta . $archivo;

                                        $extension = strtoupper(pathinfo($archivo, PATHINFO_EXTENSION));

                                        $fecha = date("Y-m-d", filemtime($ruta));

                                        $tamano = round(filesize($ruta) / 1024, 2) . " KB";

                                        echo "<tr>";

                                        echo "<td>$id</td>";
                                        echo "<td>$archivo</td>";
                                        echo "<td>$extension</td>";
                                        echo "<td>$fecha</td>";
                                        echo "<td>$tamano</td>";

                                        echo "<td>";

                                        echo "<a href='$ruta' target='_blank' class='btn btn-primary btn-sm me-1'>
        <i class='bi bi-eye'></i>
      </a>";

                                        echo "<a href='$ruta' download class='btn btn-success btn-sm me-1'>
        <i class='bi bi-download'></i>
      </a>";

                                        if ($_SESSION['rol'] === 'ADMIN') {

                                            echo "<a href='../acciones/eliminar_documento.php?archivo=" . urlencode($archivo) . "'
            class='btn btn-danger btn-sm'
            onclick=\"return confirm('¿Eliminar este documento?');\">
            <i class='bi bi-trash'></i>
          </a>";
                                        }

                                        echo "</td>";

                                        $id++;
                                    }
                                }

                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>