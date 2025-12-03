<?php
require_once __DIR__ . '/reportes_pacientes.controlador.php';
require_once __DIR__ . '/../vendor/autoload.php'; // si usás Composer con Dompdf

use Dompdf\Dompdf;

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

$html = "<h2 style='text-align:center;'>$titulo</h2>";
$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";

// Encabezados según tipo
if ($tipo === 'diario') {
    $html .= "<tr><th>Día</th><th>Fecha</th><th>Sexo</th><th>Total</th></tr>";
    while ($row = $data->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['nombre_dia']}</td>
                    <td>{$row['dia']}</td>
                    <td>{$row['sexo']}</td>
                    <td>{$row['total_pacientes']}</td>
                  </tr>";
    }
} elseif ($tipo === 'semanal') {
    $html .= "<tr><th>Fecha</th><th>Sexo</th><th>Total</th></tr>";
    while ($row = $data->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['dia']}</td>
                    <td>{$row['sexo']}</td>
                    <td>{$row['total_pacientes']}</td>
                  </tr>";
    }
} else {
    $html .= "<tr><th>Año</th><th>Mes</th><th>Sexo</th><th>Total</th></tr>";
    while ($row = $data->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['año']}</td>
                    <td>{$row['mes_nombre']}</td>
                    <td>{$row['sexo']}</td>
                    <td>{$row['total_pacientes']}</td>
                  </tr>";
    }
}

$html .= "</table>";

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_pacientes_$tipo.pdf", ["Attachment" => true]);
