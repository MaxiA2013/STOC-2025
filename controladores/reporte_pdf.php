<?php
require '../vendor/autoload.php';
require_once '../controladores/reportes_usuarios.controlador.php';

use Dompdf\Dompdf;

// Detectar tipo de reporte
$tipo = $_GET['tipo'] ?? 'diario';
$ctrl = new ReportesUsuariosControlador();

switch ($tipo) {
    case 'diario':
        $titulo = 'Reporte Diario de Usuarios';
        $datos = $ctrl->diarioAgrupado();
        $headers = ['Nombre del Día', 'Fecha', 'Total de Usuarios'];
        $keys = ['nombre_dia', 'dia', 'total_usuarios'];
        break;
    case 'semanal':
        $titulo = 'Reporte Semanal de Usuarios';
        $datos = $ctrl->semanalAgrupado();
        $headers = ['Fecha', 'Total de Usuarios'];
        $keys = ['dia', 'total_usuarios'];
        break;
    case 'mensual':
        $titulo = 'Reporte Mensual de Usuarios';
        $datos = $ctrl->mensualAgrupado();
        $headers = ['Año', 'Mes', 'Total de Usuarios'];
        $keys = ['año', 'mes_nombre', 'total_usuarios'];
        break;
    default:
        echo 'Tipo de reporte no válido';
        exit;
}

// Generar HTML
ob_start();
?>
<style>
  body { font-family: Arial, sans-serif; }
  h2 { text-align: center; color: darkblue; margin-bottom: 10px; }
  table { width: 100%; border-collapse: collapse; font-size: 12px; }
  th, td { border: 1px solid #444; padding: 6px; }
  thead { background: #222; color: #fff; }
  .footer { margin-top: 10px; font-size: 11px; text-align: right; color: #555; }
</style>
<h2><?= htmlspecialchars($titulo) ?></h2>
<table>
  <thead>
    <tr>
      <?php foreach ($headers as $h): ?>
        <th><?= htmlspecialchars($h) ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php if ($datos && $datos->num_rows): ?>
      <?php while ($row = $datos->fetch_assoc()): ?>
        <tr>
          <?php foreach ($keys as $k): ?>
            <td><?= htmlspecialchars($row[$k] ?? '') ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="<?= count($headers) ?>">No hay datos</td></tr>
    <?php endif; ?>
  </tbody>
</table>
<div class="footer">Generado el <?= date("d/m/Y H:i:s") ?></div>
<?php
$html = ob_get_clean();

// Generar PDF
$dompdf = new Dompdf(['defaultFont' => 'Arial']);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_{$tipo}.pdf", ["Attachment" => true]);
exit;
