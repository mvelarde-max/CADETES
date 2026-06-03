<?php
require_once __DIR__ . "/../includes/conexion.php";

$dni = $_GET['dni'] ?? '';

$sql = "
SELECT 
    a.id AS alumno_id,
    a.nombre AS alumno_nombre,
    a.apellido AS alumno_apellido,
    a.dni AS alumno_dni,

    t.nombre AS tutor_nombre,
    t.apellido AS tutor_apellido,
    t.dni AS tutor_dni,
    t.email AS tutor_email,

    ard.nombre AS aut_nombre,
    ard.apellido AS aut_apellido,
    ard.dni AS aut_dni,
    ard.telefono AS aut_telefono,
    ard.comprobante AS aut_comprobante

FROM alumnos a
LEFT JOIN alumnos_tutores at ON at.alumno_id = a.id
LEFT JOIN tutores t ON t.id = at.tutor_id
LEFT JOIN autorizados_retiro_detalle ard ON ard.alumno_id = a.id
";

if ($dni != '') {
    $sql .= " WHERE a.dni LIKE :dni ";
}

$sql .= " ORDER BY a.id DESC";

$stmt = $conn->prepare($sql);

if ($dni != '') {
    $stmt->bindValue(':dni', "%$dni%");
}

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$alumnos = [];

foreach ($data as $row) {

    $id = $row['alumno_id'];

    if (!isset($alumnos[$id])) {
        $alumnos[$id] = [
            'nombre' => $row['alumno_nombre'],
            'apellido' => $row['alumno_apellido'],
            'dni' => $row['alumno_dni'],
            'tutores' => [],
            'autorizados' => []
        ];
    }

    if ($row['tutor_dni']) {
        $alumnos[$id]['tutores'][$row['tutor_dni']] = $row;
    }

    if ($row['aut_dni']) {
        $alumnos[$id]['autorizados'][$row['aut_dni']] = $row;
    }
}

foreach ($alumnos as $id => $alumno):
?>
<tr>

    <td>
        <b><?= $alumno['nombre'] ?> <?= $alumno['apellido'] ?></b><br>
        DNI: <?= $alumno['dni'] ?>
    </td>

    <td>
        <?php foreach ($alumno['tutores'] as $t): ?>
            <div class="mb-2 border-bottom pb-1">
                <b><?= $t['tutor_nombre'] ?> <?= $t['tutor_apellido'] ?></b><br>
                DNI: <?= $t['tutor_dni'] ?><br>
                Email: <?= $t['tutor_email'] ?>
            </div>
        <?php endforeach; ?>
    </td>

    <td>
        <?php foreach ($alumno['autorizados'] as $a): ?>
            <div class="mb-2 border-bottom pb-1">
                <b><?= $a['aut_nombre'] ?> <?= $a['aut_apellido'] ?></b><br>
                DNI: <?= $a['aut_dni'] ?><br>
                Tel: <?= $a['aut_telefono'] ?>
            </div>
        <?php endforeach; ?>
    </td>

</tr>
<?php endforeach; ?>