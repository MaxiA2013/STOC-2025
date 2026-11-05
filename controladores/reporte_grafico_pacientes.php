<?php
// 📌 reportes/reporte_grafico_pacientes.php
require_once __DIR__ . '/../controladores/reportes_pacientes.controlador.php';

$ctrl = new ReportesPacientesControlador();
$periodo = $_GET['periodo'] ?? 'mensual';

// Función auxiliar para convertir resultados en array
function fetchAll($result) {
    $rows = [];
    if ($result instanceof mysqli_result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

switch ($periodo) {
    case 'diario':
        $rows = fetchAll($ctrl->diarioPorGenero());
        $labels = array_map(fn($r) => $r['sexo'], $rows);
        $data   = array_map(fn($r) => (int)$r['total_pacientes'], $rows);
        break;

    case 'semanal':
        $rows = fetchAll($ctrl->semanalPorGenero());
        $labels = array_map(fn($r) => $r['sexo'], $rows);
        $data   = array_map(fn($r) => (int)$r['total_pacientes'], $rows);
        break;

    default: // mensual
        $rows = fetchAll($ctrl->mensualPorGenero());
        $labels = array_map(fn($r) => $r['sexo'], $rows);
        $data   = array_map(fn($r) => (int)$r['total_pacientes'], $rows);
        break;
}

header('Content-Type: application/json');
echo json_encode([
    'labels' => $labels,
    'data'   => $data
]);
