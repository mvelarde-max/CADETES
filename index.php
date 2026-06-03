<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: paginas/inicio.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LMGB - Portal Institucional</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="CSS/index.css">

</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar navbar-expand-lg navbar-dark navbar-lmgb">

        <div class="container">

            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-mortarboard-fill"></i>
                LMGB
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#noticias">Noticias</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#videos">Videos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#acceso">Acceso</a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <!-- HERO -->

    <section id="inicio" class="hero">

        <div class="container text-center">

            <h1>LMGB</h1>

            <h2>Sistema Institucional Escolar</h2>

            <p>
                Portal de acceso para alumnos, padres y administración.
            </p>

            <a href="#acceso" class="btn btn-light btn-lg">
                Acceder al Sistema
            </a>

        </div>

    </section>

    <!-- NOTICIAS -->

    <section id="noticias" class="container py-5">

        <h2 class="titulo">
            Noticias Destacadas
        </h2>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="card noticia-card h-100">

                    <div class="card-body">

                        <h5>Inicio del Ciclo Lectivo</h5>

                        <p>
                            Información institucional del nuevo año escolar.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card noticia-card h-100">

                    <div class="card-body">

                        <h5>Acto Escolar</h5>

                        <p>
                            Cronograma oficial y organización institucional.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card noticia-card h-100">

                    <div class="card-body">

                        <h5>Comunicados</h5>

                        <p>
                            Novedades para alumnos y padres.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- VIDEOS -->

    <section id="videos" class="videos-section">

        <div class="container">

            <h2 class="titulo text-white">
                Videos Institucionales
            </h2>

            <div class="row g-4">

                <div class="col-lg-6">

                    <div class="ratio ratio-16x9">

                        <iframe src="https://www.youtube.com/embed/zhLBGsHw9fQ" allowfullscreen>
                        </iframe>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="ratio ratio-16x9">

                        <iframe src="https://www.youtube.com/embed/rG4TRLFCtLk" allowfullscreen>
                        </iframe>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- ACCESO -->

    <section id="acceso" class="container py-5">

        <h2 class="titulo">
            Acceso al Sistema
        </h2>

        <div class="row g-4">

            <!-- LOGIN -->

            <div class="col-lg-4">

                <div class="card panel-card">

                    <div class="card-header bg-primary text-white">

                        <i class="bi bi-box-arrow-in-right"></i>
                        Login

                    </div>

                    <div class="card-body">

                        <form action="acciones/procesar_login.php" method="POST">

                            <div class="mb-3">

                                <label>Email</label>

                                <input type="email" name="email" class="form-control" required>

                            </div>

                            <div class="mb-3">

                                <label>Contraseña</label>

                                <input type="password" name="password" class="form-control" required>

                            </div>

                            <button type="submit" class="btn btn-primary w-100">

                                Ingresar

                            </button>

                        </form>

                    </div>

                </div>

            </div>

            <!-- PADRE -->

            <div class="col-lg-4">

                <div class="card panel-card">

                    <div class="card-header bg-success text-white">

                        <i class="bi bi-person-plus"></i>
                        Registro Padre / Tutor

                    </div>

                    <div class="card-body">

                        <form action="acciones/registro_padre.php" method="POST">

                            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>

                            <input type="text" name="apellido" class="form-control mb-2" placeholder="Apellido"
                                required>

                            <input type="text" name="dni" class="form-control mb-2" placeholder="DNI" required>

                            <input type="text" name="telefono" class="form-control mb-2" placeholder="Teléfono">

                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

                            <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña"
                                required>

                            <button type="submit" class="btn btn-success w-100">

                                Crear Cuenta

                            </button>

                        </form>

                    </div>

                </div>

            </div>

            <!-- ALUMNO -->

            <div class="col-lg-4">

                <div class="card panel-card">

                    <div class="card-header bg-warning">

                        <i class="bi bi-mortarboard"></i>
                        Registro Alumno

                    </div>

                    <div class="card-body">

                        <form action="acciones/registro_alumno.php" method="POST">

                            <input type="text" name="legajo" class="form-control mb-2" placeholder="Legajo" required>

                            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>

                            <input type="text" name="apellido" class="form-control mb-2" placeholder="Apellido"
                                required>

                            <input type="text" name="dni" class="form-control mb-2" placeholder="DNI" required>

                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

                            <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña"
                                required>

                            <button type="submit" class="btn btn-warning w-100">

                                Crear Cuenta

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- FOOTER -->

    <footer>

        <div class="container text-center">

            <p>
                LMGB © <?php echo date("Y"); ?>
            </p>

            <p>
                Sistema Institucional Escolar
            </p>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>