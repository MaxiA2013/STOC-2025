<?php
require_once('controladores/reportes_obras_sociales.controlador.php');
$ctrl = new ReportesObrasSocialesControlador();

$diarioUso   = $ctrl->diarioUso();
$semanalUso  = $ctrl->semanalUso();
$mensualUso  = $ctrl->mensualUso();
?>

<div class="container-fluid mt-4">

  <!-- Nav Tabs -->
  <ul class="nav nav-tabs" id="myTabObras" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="diario-obras-tab" data-bs-toggle="tab" data-bs-target="#diario-obras-pane" type="button" role="tab" aria-controls="diario-obras-pane" aria-selected="true">
        Diario
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="semanal-obras-tab" data-bs-toggle="tab" data-bs-target="#semanal-obras-pane" type="button" role="tab" aria-controls="semanal-obras-pane" aria-selected="false">
        Semanal
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="mensual-obras-tab" data-bs-toggle="tab" data-bs-target="#mensual-obras-pane" type="button" role="tab" aria-controls="mensual-obras-pane" aria-selected="false">
        Mensual
      </button>
    </li>
  </ul>

  <!-- Contenido de cada tab -->
  <div class="tab-content" id="myTabObrasContent">

    <!-- Diario -->
    <div class="tab-pane fade show active p-3" id="diario-obras-pane" role="tabpanel" aria-labelledby="diario-obras-tab">
      <div class="text-center mb-3">
        <h4 class="fw-bold text-primary">Reporte Diario de Obras Sociales</h4>
        <small class="text-muted">Generado el <?= date("d/m/Y H:i:s") ?></small>
      </div>

        <div class="mb-3 d-flex gap-2 justify-content-center">
            <a href="controladores/reporte_excel_obras_social.php" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="controladores/reporte_pdf_obras_social.php?tipo=diario" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>

      <table class="table table-striped table-hover table-bordered align-middle shadow-sm">
        <thead class="bg-primary text-white text-center">
          <tr>
            <th>Nombre del Día</th>
            <th>Fecha</th>
            <th>Obra Social</th>
            <th>Total de Pacientes</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $diarioUso->fetch_assoc()) { ?>
            <tr>
              <td><?= htmlspecialchars($row['nombre_dia']) ?></td>
              <td><?= htmlspecialchars($row['dia']) ?></td>
              <td><?= htmlspecialchars($row['obra_social']) ?></td>
              <td><?= (int)$row['total_uso'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Semanal -->
    <div class="tab-pane fade p-3" id="semanal-obras-pane" role="tabpanel" aria-labelledby="semanal-obras-tab">
      <div class="text-center mb-3">
        <h4 class="fw-bold text-success">Reporte Semanal de Obras Sociales</h4>
        <small class="text-muted">Generado el <?= date("d/m/Y H:i:s") ?></small>
      </div>

      <table class="table table-striped table-hover table-bordered align-middle shadow-sm">
        <thead class="bg-success text-white text-center">
          <tr>
            <th>Año</th>
            <th>Semana</th>
            <th>Obra Social</th>
            <th>Total de Pacientes</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $semanalUso->fetch_assoc()) { ?>
            <tr>
              <td><?= (int)$row['anio'] ?></td>
              <td><?= (int)$row['semana'] ?></td>
              <td><?= htmlspecialchars($row['obra_social']) ?></td>
              <td><?= (int)$row['total_uso'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Mensual -->
    <div class="tab-pane fade p-3" id="mensual-obras-pane" role="tabpanel" aria-labelledby="mensual-obras-tab">
      <div class="text-center mb-3">
        <h4 class="fw-bold text-warning">Reporte Mensual de Obras Sociales</h4>
        <small class="text-muted">Generado el <?= date("d/m/Y H:i:s") ?></small>
      </div>

      <table class="table table-striped table-hover table-bordered align-middle shadow-sm">
        <thead class="bg-warning text-dark text-center">
          <tr>
            <th>Año</th>
            <th>Mes</th>
            <th>Obra Social</th>
            <th>Total de Pacientes</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $mensualUso->fetch_assoc()) { ?>
            <tr>
              <td><?= (int)$row['anio'] ?></td>
              <td><?= htmlspecialchars($row['mes_nombre']) ?></td>
              <td><?= htmlspecialchars($row['obra_social']) ?></td>
              <td><?= (int)$row['total_uso'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
