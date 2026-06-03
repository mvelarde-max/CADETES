<?php

session_start();

require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../index.php");
    exit;
}

$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$dni = trim($_POST['dni']);
$telefono = trim($_POST['telefono']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

if (
    empty($nombre) ||
    empty($apellido) ||
    empty($dni) ||
    empty($email) ||
    empty($password)
) {
    header("Location: ../index.php");
    exit;
}

$buscar = $conexion->prepare("
SELECT id
FROM usuarios
WHERE email = ?
LIMIT 1
");

$buscar->execute([$email]);

if ($buscar->rowCount() > 0) {

    header("Location: ../index.php");
    exit;

}

$rol = $conexion->query("
SELECT id
FROM roles
WHERE nombre='PADRE'
LIMIT 1
")->fetch();

$rol_id = $rol['id'];

$insertUsuario = $conexion->prepare("
INSERT INTO usuarios
(
nombre,
apellido,
email,
password,
rol_id
)
VALUES
(
?,?,?,?,?
)
");

$insertUsuario->execute([
    $nombre,
    $apellido,
    $email,
    $password,
    $rol_id
]);

$usuario_id = $conexion->lastInsertId();

$insertTutor = $conexion->prepare("
INSERT INTO tutores
(
usuario_id,
nombre,
apellido,
dni,
telefono,
email
)
VALUES
(
?,?,?,?,?,?
)
");

$insertTutor->execute([
    $usuario_id,
    $nombre,
    $apellido,
    $dni,
    $telefono,
    $email
]);

$_SESSION['usuario_id'] = $usuario_id;
$_SESSION['nombre'] = $nombre;
$_SESSION['rol'] = 'PADRE';

header("Location: ../paginas/inicio.php");
exit;