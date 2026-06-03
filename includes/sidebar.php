<?php
if (!isset($_SESSION)) {
    session_start();
}

$rol = $_SESSION['rol'] ?? '';
?>

<div class="logo text-center p-3 fw-bold text-white border-bottom">
    LMGB
</div>

<?php if ($rol == 'ADMIN'): ?>

    <a href="inicio.php"><i class="bi bi-house"></i> Inicio</a>
    <a href="alumnos.php"><i class="bi bi-people"></i> Alumnos</a>
    <a href="tutores.php"><i class="bi bi-person-vcard"></i> Tutores</a>
    <a href="permisos.php"><i class="bi bi-check2-square"></i> Permisos</a>
    <a href="noticias.php"><i class="bi bi-newspaper"></i> Noticias</a>
    <a href="notas.php"><i class="bi bi-journal-check"></i> Notas</a>
    <a href="contactos.php"><i class="bi bi-envelope"></i> Contactos</a>
    <a href="configuracion.php"><i class="bi bi-gear"></i> Configuración</a>
    <a href="usuarios.php"><i class="bi bi-person-gear"></i> Usuarios</a>
    <a href="auditoria.php"><i class="bi bi-clock-history"></i> Auditoría</a>
    <a href="comunicaciones.php"><i class="bi bi-megaphone"></i> Comunicaciones</a>
    <a href="documentos.php"><i class="bi bi-folder2-open"></i> Documentos</a>
    <a href="calendario.php"><i class="bi bi-calendar-event"></i> Calendario</a>
    <a href="videos.php"><i class="bi bi-camera-video"></i> Videos</a>
    <a href="administrar_hijos.php"><i class="bi bi-camera-video"></i> ASIGNACIONES </a>
    <a href="admin_crear.php"><i class="bi bi-camera-video"></i> ADMINISTRADORES </a>




<?php elseif ($rol == 'PADRE'): ?>

    <a href="inicio.php"><i class="bi bi-house"></i> Inicio</a>
    <a href="noticias.php"><i class="bi bi-newspaper"></i> Noticias</a>
    <a href="comunicaciones.php"><i class="bi bi-megaphone"></i> Comunicaciones</a>
    <a href="hijos.php"><i class="bi bi-person-lines-fill"></i> Mis Hijos</a>
    <a href="autorizaciones.php"><i class="bi bi-check-circle"></i> Autorizaciones</a>

<?php elseif ($rol == 'ALUMNO'): ?>

    <a href="inicio.php"><i class="bi bi-house"></i> Inicio</a>
    <a href="noticias.php"><i class="bi bi-newspaper"></i> Noticias</a>
    <a href="documentos.php"><i class="bi bi-book"></i> Reglamentos</a>
    <a href="videos.php"><i class="bi bi-camera-video"></i> Videos</a>
    <a href="calendario.php"><i class="bi bi-calendar-event"></i> Calendario</a>
    <a href="comunicaciones.php"><i class="bi bi-megaphone"></i> Comunicados</a>

<?php endif; ?>

<hr>

<a href="../acciones/logout.php">
    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
</a>