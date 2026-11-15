<?php
// 📌 reportes/reporte_grafico_obras_sociales.php
require_once __DIR__ . '/../controladores/reportes_obras_sociales.controlador.php';

$ctrl = new ReportesObrasSocialesControlador();
$periodo = $_GET['periodo'] ?? 'mensual';

// Función auxiliar
function fetchAll($result) {
    $rows = [];
    if ($result instanceof mysqli_result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

// 1) Traemos filas según período
switch ($periodo) {
    case 'diario':
        $rows = fetchAll($ctrl->diarioUso());
        break;
    case 'semanal':
        $rows = fetchAll($ctrl->semanalUso());
        break;
    default:
        $rows = fetchAll($ctrl->mensualUso());
        break;
}

// 2) Agrupamos en backend por obra_social
$agrupado = [];
foreach ($rows as $r) {
    $obra = $r['obra_social'];
    $total = (int)$r['total_uso'];
    if (!isset($agrupado[$obra])) {
        $agrupado[$obra] = 0;
    }
    $agrupado[$obra] += $total;
}

// 3) Armamos el JSON simple para Chart.js
$labels = array_keys($agrupado);
$data   = array_values($agrupado);

header('Content-Type: application/json');
echo json_encode([
    'labels' => $labels, // ej: ["OSDE","Swiss Medical","Galeno"]
    'data'   => $data    // ej: [12, 5, 3]
]);
