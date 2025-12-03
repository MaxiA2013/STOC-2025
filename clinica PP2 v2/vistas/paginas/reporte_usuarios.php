<?php
<<<<<<< HEAD
require_once('controladores/reportes_usuarios.controlador.php');
=======
require_once('controladores/reportes/reportes_usuarios.controlador.php');
>>>>>>> origin/mi-ramita
$ctrl = new ReportesUsuariosControlador();

$diarioAgrupado = $ctrl->diarioAgrupado();
$semanalAgrupado = $ctrl->semanalAgrupado();
$mensualAgrupado = $ctrl->mensualAgrupado();
?>

<div class="container-fluid mt-4">

  <!-- Nav Tabs -->
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="diario-tab" data-bs-toggle="tab" data-bs-target="#diario-pane" type="button" role="tab" aria-controls="diario-pane" aria-selected="true">
        Diario
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="semanal-tab" data-bs-toggle="tab" data-bs-target="#semanal-pane" type="button" role="tab" aria-controls="semanal-pane" aria-selected="false">
        Semanal
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="mensual-tab" data-bs-toggle="tab" data-bs-target="#mensual-pane" type="button" role="tab" aria-controls="mensual-pane" aria-selected="false">
        Mensual
      </button>
    </li>
  </ul>

  <!-- Contenido de cada tab -->
  <div class="tab-content" id="myTabContent">

    <!-- Diario -->
    <div class="tab-pane fade show active p-3" id="diario-pane" role="tabpanel" aria-labelledby="diario-tab">
      <div class="text-center mb-3">
        <h4 class="fw-bold text-primary">Reporte Diario de Usuarios</h4>
        <small class="text-muted">Generado el <?= date("d/m/Y H:i:s") ?></small>
      </div>

      <div class="mb-3 d-flex gap-2 justify-content-center">
        <a href="controladores/reporte_excel.php" class="btn btn-success">
          <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="controladores/reporte_pdf.php?tipo=diario" class="btn btn-danger">
          <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
      </div>

      <table class="table table-striped table-hover table-bordered align-middle shadow-sm">
        <thead class="bg-primary text-white text-center">
          <tr>
            <th>Nombre del Día</th>
            <th>Fecha</th>
            <th>Total de Usuarios</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $diarioAgrupado->fetch_assoc()) { ?>
            <tr>
              <td><?= htmlspecialchars($row['nombre_dia']) ?></td>
              <td><?= htmlspecialchars($row['dia']) ?></td>
              <td><?= (int)$row['total_usuarios'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Semanal -->
    <div class="tab-pane fade p-3" id="semanal-pane" role="tabpanel" aria-labelledby="semanal-tab">
      <div class="text-center mb-3">
        <h4 class="fw-bold text-success">Reporte Semanal de Usuarios</h4>
        <small class="text-muted">Generado el <?= date("d/m/Y H:i:s") ?></small>
      </div>

      <div class="mb-3 d-flex gap-2 justify-content-center">
        <a href="controladores/reporte_excel.php" class="btn btn-success">
          <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="controladores/reporte_pdf.php?tipo=semanal" class="btn btn-danger">
          <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
      </div>

      <table class="table table-striped table-hover table-bordered align-middle shadow-sm">
        <thead class="bg-success text-white text-center">
          <tr>
            <th>Fecha</th>
            <th>Total de Usuarios</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $semanalAgrupado->fetch_assoc()) { ?>
            <tr>
              <td><?= htmlspecialchars($row['dia']) ?></td>
              <td><?= (int)$row['total_usuarios'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Mensual -->
    <div class="tab-pane fade p-3" id="mensual-pane" role="tabpanel" aria-labelledby="mensual-tab">
      <div class="text-center mb-3">
        <h4 class="fw-bold text-warning">Reporte Mensual de Usuarios</h4>
        <small class="text-muted">Generado el <?= date("d/m/Y H:i:s") ?></small>
      </div>

      <div class="mb-3 d-flex gap-2 justify-content-center">
        <a href="controladores/reporte_excel.php" class="btn btn-success">
          <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="controladores/reporte_pdf.php?tipo=mensual" class="btn btn-danger">
          <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
      </div>

      <table class="table table-striped table-hover table-bordered align-middle shadow-sm">
        <thead class="bg-warning text-dark text-center">
          <tr>
            <th>Año</th>
            <th>Mes</th>
            <th>Total de Usuarios</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $mensualAgrupado->fetch_assoc()) { ?>
            <tr>
              <td><?= (int)$row['año'] ?></td>
              <td><?= htmlspecialchars($row['mes_nombre']) ?></td>
              <td><?= (int)$row['total_usuarios'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

  </div>
<<<<<<< HEAD
</div>
=======
</div>
>>>>>>> origin/mi-ramita
