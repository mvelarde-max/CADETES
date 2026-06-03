<?php

session_start();
require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    header("Location: ../index.php");
    exit;
}

// Buscar usuario
$sql = "
SELECT
    u.id,
    u.nombre,
    u.apellido,
    u.email,
    u.password,
    u.rol_id,
    r.nombre AS rol
FROM usuarios u
INNER JOIN roles r ON u.rol_id = r.id
WHERE u.email = ?
AND u.activo = 1
LIMIT 1
";

$stmt = $conexion->prepare($sql);
$stmt->execute([$email]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: ../index.php");
    exit;
}

// ============================
// VALIDACIÓN DE PASSWORD
// ============================

// Soporte para passwords nuevos (hash) y viejos (texto plano)
if (strpos($usuario['password'], '$2y$') === 0) {
    // password encriptada
    $ok = password_verify($password, $usuario['password']);
} else {
    // password antigua (texto plano)
    $ok = ($password === $usuario['password']);
}

if (!$ok) {
    header("Location: ../index.php");
    exit;
}

// ============================
// SESIONES
// ============================

$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['nombre'] = $usuario['nombre'];
$_SESSION['apellido'] = $usuario['apellido'];
$_SESSION['rol'] = strtoupper($usuario['rol']);
$_SESSION['rol_id'] = $usuario['rol_id'];

// ============================
// REDIRECCIÓN SEGÚN ROL
// ============================

if ($usuario['rol_id'] == 1) {
    // ADMIN
    header("Location: ../paginas/inicio.php");
    exit;
}

if ($usuario['rol_id'] == 2) {
    // PADRE
    header("Location: ../paginas/inicio.php");
    exit;
}

if ($usuario['rol_id'] == 3) {
    // ALUMNO
    header("Location: ../paginas/inicio.php");
    exit;
}

// fallback
header("Location: ../paginas/inicio.php");
exit;

?>