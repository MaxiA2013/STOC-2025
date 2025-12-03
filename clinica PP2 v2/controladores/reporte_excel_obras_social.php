<?php
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=reporte_obras_diario.xls');

require_once 'modelos/conexion.php';
require_once 'controladores/reportes_obras_sociales.controlador.php';

$ctrl = new ReportesObrasSocialesControlador();
$diarioUso = $ctrl->diarioUso();
?>
<html>
<table border="1">
    <thead>
        <tr>
            <th>Nombre del Día</th>
            <th>Fecha</th>
            <th>Obra Social</th>
            <th>Total de Pacientes</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($diarioUso && $diarioUso->num_rows > 0): ?>
            <?php while ($fila = $diarioUso->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['nombre_dia'] ?></td>
                    <td><?= $fila['dia'] ?></td>
                    <td><?= $fila['obra_social'] ?></td>
                    <td><?= $fila['total_uso'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No hay registros</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</html>
