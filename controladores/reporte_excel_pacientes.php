<?php
require_once __DIR__ . '/reportes_pacientes.controlador.php';

$ctrl = new ReportesPacientesControlador();
$tipo = $_GET['tipo'] ?? 'diario';

switch ($tipo) {
    case 'semanal':
        $data = $ctrl->semanalPorGenero();
        $titulo = "Reporte Semanal de Pacientes por Género";
        break;
    case 'mensual':
        $data = $ctrl->mensualPorGenero();
        $titulo = "Reporte Mensual de Pacientes por Género";
        break;
    default:
        $data = $ctrl->diarioPorGenero();
        $titulo = "Reporte Diario de Pacientes por Género";
        break;
}

// Cabeceras para Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=reporte_pacientes_$tipo.xls");

echo $titulo . "\n\n";

// Encabezados según tipo
if ($tipo === 'diario') {
    echo "Nombre del Día\tFecha\tSexo\tTotal Pacientes\n";
    while ($row = $data->fetch_assoc()) {
        echo $row['nombre_dia'] . "\t" . $row['dia'] . "\t" . $row['sexo'] . "\t" . $row['total_pacientes'] . "\n";
    }
} elseif ($tipo === 'semanal') {
    echo "Fecha\tSexo\tTotal Pacientes\n";
    while ($row = $data->fetch_assoc()) {
        echo $row['dia'] . "\t" . $row['sexo'] . "\t" . $row['total_pacientes'] . "\n";
    }
} else {
    echo "Año\tMes\tSexo\tTotal Pacientes\n";
    while ($row = $data->fetch_assoc()) {
        echo $row['año'] . "\t" . $row['mes_nombre'] . "\t" . $row['sexo'] . "\t" . $row['total_pacientes'] . "\n";
    }
}
