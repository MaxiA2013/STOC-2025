<?php
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=reporte_diario.xls');

require_once 'modelos/conexion.php';
require_once 'controladores/reportes_usuarios.controlador.php';

$ctrl = new ReportesUsuariosControlador();
$diarioAgrupado = $ctrl->diarioAgrupado();
?>
<html>
<table border="1">
    <thead>
        <tr>
            <th>Nombre del Día</th>
            <th>Fecha</th>
            <th>Total de Usuarios</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($diarioAgrupado && $diarioAgrupado->num_rows > 0): ?>
            <?php while ($fila = $diarioAgrupado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['nombre_dia'] ?></td>
                    <td><?= $fila['dia'] ?></td>
                    <td><?= $fila['total_usuarios'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No hay registros</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</html>