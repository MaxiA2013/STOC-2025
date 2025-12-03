<?php
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=doctores.xls');
require_once '../modelos/conexion.php';
require_once '../modelos/doctor.php';

$conn = new Conexion();
$link = $conn->conectar();

$doctor = new Doctor();
$doctores = $doctor->all_doctores();
?>
<html>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Matrícula</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Usuario</th>
            <th>Precio de consulta</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($doctores)): ?>
            <?php foreach ($doctores as $fila): ?>
                <tr>
                    <td><?= $fila['id_doctor'] ?></td>
                    <td><?= $fila['numero_matricula_profesional'] ?></td>
                    <td><?= $fila['nombre'] ?></td>
                    <td><?= $fila['apellido'] ?></td>
                    <td><?= $fila['nombre_usuario'] ?></td>
                    <td><?= $fila['precio_consulta'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No hay doctores registrados</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</html>
