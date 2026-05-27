<?php

include("conexion.php");

$sql =
"SELECT * FROM padres_autorizados";

$resultado =
$conexion->query($sql);

while($row = $resultado->fetch_assoc()){

    echo '

    <tr>

        <td>'.$row["nombre_padre"].'</td>

        <td>'.$row["dni"].'</td>

        <td>'.$row["telefono"].'</td>

        <td>'.$row["cadete"].'</td>

    </tr>

    ';
}
?>