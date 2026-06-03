<?php

$usarLocal = true;

if ($usarLocal) {
    $host = "127.0.0.1";
    $basedatos = "if0_42071302_liceobelgrano";
    $usuario = "root";
    $password = "";
} else {
    $host = "sql206.infinityfree.com";
    $basedatos = "if0_42071302_liceobelgrano";
    $usuario = "if0_42071302";
    $password = "Maximiliano1495";
}

try {
    $conexion = new PDO(
        "mysql:host=$host;dbname=$basedatos;charset=utf8mb4",
        $usuario,
        $password
    );

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔥 alias para compatibilidad con código viejo
    $conn = $conexion;

} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

?>