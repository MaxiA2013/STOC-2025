<?php
require_once('controladores/reportes/reportes_pacientes.controlador.php');
$ctrl = new ReportesPacientesControlador();

$diario = $ctrl->diarioPorGenero();
$semanal = $ctrl->semanalPorGenero();
$mensual = $ctrl->mensualPorGenero();
?>

<div class="container-fluid mt-4">
  <ul class="nav nav-tabs">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#diario">Diario</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#semanal">Semanal</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#mensual">Mensual</button></li>
  </ul>

  <div class="tab-content">
    <!-- Diario -->
    <div class="tab-pane fade show active p-3" id="diario">
      <h4 class="text-primary text-center">Pacientes por Género - Diario</h4>
        <div class="mb-3 d-flex gap-2 justify-content-center">
            <a href="controladores/reporte_excel_pacientes.php" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="controladores/reporte_pdf_pacientes.php?tipo=diario" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
      <table class="table table-bordered text-center">
        <thead class="bg-primary text-white">
          <tr><th>Día</th><th>Fecha</th><th>Género</th><th>Total</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $diario->fetch_assoc()) { ?>
            <tr>
              <td><?= htmlspecialchars($row['nombre_dia']) ?></td>
              <td><?= htmlspecialchars($row['dia']) ?></td>
              <td><?= htmlspecialchars($row['sexo']) ?></td>
              <td><?= (int)$row['total_pacientes'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Semanal -->
    <div class="tab-pane fade p-3" id="semanal">
      <h4 class="text-success text-center">Pacientes por Género - Semanal</h4>
        <div class="mb-3 d-flex gap-2 justify-content-center">
            <a href="controladores/reporte_excel_pacientes.php" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="controladores/reporte_pdf_pacientes.php?tipo=semanal" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
      <table class="table table-bordered text-center">
        <thead class="bg-success text-white">
          <tr><th>Fecha</th><th>Género</th><th>Total</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $semanal->fetch_assoc()) { ?>
            <tr>
              <td><?= htmlspecialchars($row['dia']) ?></td>
              <td><?= htmlspecialchars($row['sexo']) ?></td>
              <td><?= (int)$row['total_pacientes'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Mensual -->
    <div class="tab-pane fade p-3" id="mensual">
      <h4 class="text-warning text-center">Pacientes por Género - Mensual</h4>
        <div class="mb-3 d-flex gap-2 justify-content-center">
            <a href="controladores/reporte_excel_pacientes.php" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="controladores/reporte_pdf_pacientes.php?tipo=mensual" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
      <table class="table table-bordered text-center">
        <thead class="bg-warning text-dark">
          <tr><th>Año</th><th>Mes</th><th>Género</th><th>Total</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $mensual->fetch_assoc()) { ?>
            <tr>
              <td><?= (int)$row['año'] ?></td>
              <td><?= htmlspecialchars($row['mes_nombre']) ?></td>
              <td><?= htmlspecialchars($row['sexo']) ?></td>
              <td><?= (int)$row['total_pacientes'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>