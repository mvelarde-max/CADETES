<?php

include("conexion.php");

$sql =
"SELECT * FROM noticias ORDER BY fecha DESC";

$resultado =
$conexion->query($sql);

while($row = $resultado->fetch_assoc()){

    echo '

    <div class="news-card">

        <h3>'.$row["titulo"].'</h3>

        <p>'.$row["descripcion"].'</p>

    </div>

    ';
}
?>