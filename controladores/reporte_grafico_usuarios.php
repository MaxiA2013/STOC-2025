<?php
// 📌 reportes/reporte_grafico_usuarios.php
require_once __DIR__ . '/../controladores/reportes_usuarios.controlador.php';

$ctrl = new ReportesUsuariosControlador();
$periodo = $_GET['periodo'] ?? 'mensual';

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
        $rows = fetchAll($ctrl->diarioAgrupado());
        $labels = array_map(fn($r) => $r['dia'], $rows);
        $data   = array_map(fn($r) => (int)$r['total_usuarios'], $rows);
        break;

    case 'semanal':
        $rows = fetchAll($ctrl->semanalAgrupado());
        $labels = array_map(fn($r) => $r['dia'], $rows);
        $data   = array_map(fn($r) => (int)$r['total_usuarios'], $rows);
        break;

    default: // mensual
        $rows = fetchAll($ctrl->mensualAgrupado());
        $labels = array_map(fn($r) => $r['mes_nombre'] . ' ' . $r['año'], $rows);
        $data   = array_map(fn($r) => (int)$r['total_usuarios'], $rows);
        break;
}

header('Content-Type: application/json');
echo json_encode([
    'labels' => $labels,
    'data'   => $data
]);
