<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

require_once "../includes/conexion.php";

/* =========================
   OBTENER VIDEO
========================= */
$id = $_GET['id'] ?? null;

$stmt = $conexion->prepare("
    SELECT * 
    FROM videos 
    WHERE id = ?
");

$stmt->execute([$id]);

$video = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$video) {
    die("Video no encontrado");
}

/* =========================
   CONVERTIR YOUTUBE
========================= */
function convertirYoutube($url) {

    if (strpos($url, 'watch?v=') !== false) {

        $parts = explode('v=', $url);
        $videoId = $parts[1];
        $videoId = explode('&', $videoId)[0];

        return "https://www.youtube.com/embed/" . $videoId;
    }

    if (strpos($url, 'youtu.be/') !== false) {

        $parts = explode('youtu.be/', $url);
        $videoId = $parts[1];

        return "https://www.youtube.com/embed/" . $videoId;
    }

    return $url;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($video['titulo']) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #0f172a;
            color: white;
        }

        .container-video {
            max-width: 1000px;
            margin: auto;
            padding-top: 30px;
        }

        .card-video {
            background: #111827;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,.4);
        }

        .header-video {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .descripcion {
            padding: 20px;
            color: #cbd5e1;
        }

        .btn-back {
            margin-bottom: 15px;
        }

        iframe, video {
            width: 100%;
            height: 500px;
            border: 0;
        }
    </style>

</head>

<body>

<div class="container container-video">

    <!-- BOTÓN VOLVER -->
    <a href="videos.php" class="btn btn-light btn-sm btn-back">
        <i class="bi bi-arrow-left"></i> Volver
    </a>

    <!-- CARD -->
    <div class="card-video">

        <!-- TITULO -->
        <div class="header-video">
            <h3 class="mb-0"><?= htmlspecialchars($video['titulo']) ?></h3>
        </div>

        <!-- VIDEO -->
        <?php if ($video['tipo'] === 'LINK'): ?>

            <iframe
                src="<?= htmlspecialchars(convertirYoutube($video['url'])) ?>"
                allowfullscreen>
            </iframe>

        <?php else: ?>

            <video controls>
                <source src="../videos/<?= htmlspecialchars($video['archivo']) ?>" type="video/mp4">
                Tu navegador no soporta video.
            </video>

        <?php endif; ?>

        <!-- DESCRIPCIÓN -->
        <div class="descripcion">

            <h6>Descripción</h6>

            <p class="mb-0">
                <?= nl2br(htmlspecialchars($video['descripcion'])) ?>
            </p>

        </div>

    </div>

</div>

</body>
</html>