<?php
require_once __DIR__ . '/reportes_obras_sociales.controlador.php';
require_once __DIR__ . '/../vendor/autoload.php'; // si usás Composer con Dompdf

use Dompdf\Dompdf;

$ctrl = new ReportesObrasSocialesControlador();
$tipo = $_GET['tipo'] ?? 'diario';

switch ($tipo) {
    case 'semanal':
        $data = $ctrl->semanalUso();
        $titulo = "Reporte Semanal de Obras Sociales";
        break;
    case 'mensual':
        $data = $ctrl->mensualUso();
        $titulo = "Reporte Mensual de Obras Sociales";
        break;
    default:
        $data = $ctrl->diarioUso();
        $titulo = "Reporte Diario de Obras Sociales";
        break;
}

$html = "<h2 style='text-align:center;'>$titulo</h2>";
$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";

// Encabezados según tipo
if ($tipo === 'diario') {
    $html .= "<tr><th>Día</th><th>Fecha</th><th>Obra Social</th><th>Total</th></tr>";
    while ($row = $data->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['nombre_dia']}</td>
                    <td>{$row['dia']}</td>
                    <td>{$row['obra_social']}</td>
                    <td>{$row['total_uso']}</td>
                  </tr>";
    }
} elseif ($tipo === 'semanal') {
    $html .= "<tr><th>Año</th><th>Semana</th><th>Obra Social</th><th>Total</th></tr>";
    while ($row = $data->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['anio']}</td>
                    <td>{$row['semana']}</td>
                    <td>{$row['obra_social']}</td>
                    <td>{$row['total_uso']}</td>
                  </tr>";
    }
} else {
    $html .= "<tr><th>Año</th><th>Mes</th><th>Obra Social</th><th>Total</th></tr>";
    while ($row = $data->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['anio']}</td>
                    <td>{$row['mes_nombre']}</td>
                    <td>{$row['obra_social']}</td>
                    <td>{$row['total_uso']}</td>
                  </tr>";
    }
}

$html .= "</table>";

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_obras_$tipo.pdf", ["Attachment" => true]);
