<?php

$conexion = new mysqli(

    "localhost",
    "root",
    "",
    "cadetes"
);

if($conexion->connect_error){

    die("Error de conexión");
}
?>