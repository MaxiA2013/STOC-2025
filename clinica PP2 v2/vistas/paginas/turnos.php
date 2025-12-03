<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Turnos</title>
<<<<<<< HEAD
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Sidebar de filtros */
    .columna_filtros {
      background: #fff;
      border-left: 1px solid #ddd;
      box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05);
      height: 100vh;
      position: sticky;
      top: 0;
      width: 290px;
      padding: 20px;
    }

    /* Contenido principal */
    .main-content {
      flex-grow: 1;
      padding: 40px;
    }

    .cards-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
      width: 300px;
      transition: 0.3s ease;
    }

    .card:hover { transform: translateY(-5px); }

    .card-header {
      display: flex;
      justify-content: space-between;
      padding: 20px 20px 10px 20px;
    }

    .date { text-align: left; }
    .day { font-size: 32px; font-weight: 700; }
    .month { font-size: 16px; font-weight: 600; margin-top: 2px; }

    .tools i {
      font-size: 14px;
      padding: 6px;
      border-radius: 4px;
      background-color: #f1f1f1;
      color: #444;
      cursor: pointer;
    }

    .card-body { padding: 0 20px 20px; }
    .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
    .description { font-size: 14px; color: #666; margin-bottom: 20px; }

    .info { display: flex; align-items: center; font-size: 14px; margin-bottom: 5px; }
    .info i { margin-right: 8px; }

    .footer-line { height: 5px; border-radius: 0 0 10px 10px; }

    .purple .day, .purple .month, .purple .title, .purple .info { color: rgb(95, 163, 240); }
    .purple .footer-line { background-color: rgb(95, 163, 240); }

    .red .day, .red .month, .red .title, .red .info { color: rgb(44, 92, 248); }
    .red .footer-line { background-color: rgb(44, 92, 248); }

    .green .day, .green .month, .green .title, .green .info { color: rgb(3, 14, 179); }
    .green .footer-line { background-color: rgb(3, 14, 179); }
  </style>
</head>
<body>

  <!-- Layout principal -->
  <div class="d-flex">
    <!-- Contenido principal -->
    <div class="main-content">
      <div class="cards-container">

        <div class="card purple">
          <div class="card-header">
            <div class="date">
              <div class="day">18</div>
              <div class="month">Dec</div>
            </div>
          </div>
          <div class="card-body">
            <div class="title">Dr.Gómez</div>
            <div class="description">Especialista en Cardiología</div>
            <div class="info"><i class="far fa-clock"></i>08 Pm - 09 Pm</div>
            <div class="info"><i class="fas fa-map-marker-alt"></i>Juan Jose Silva, 1890</div>
            <div class="info"><i class="fas fa-arrow-right"></i> <a href="index.php?page=info_turnos">Ver más</a></div>
          </div>
          <div class="footer-line"></div>
        </div>

        <div class="card red">
          <div class="card-header">
            <div class="date">
              <div class="day">25</div>
              <div class="month">Dec</div>
            </div>
            <div class="tools">
              <i class="fas fa-pen"></i>
              <i class="fas fa-trash"></i>
            </div>
          </div>
          <div class="card-body">
            <div class="title">Dra. Pérez</div>
            <div class="description">Especialista en Dermatología</div>
            <div class="info"><i class="far fa-clock"></i>09:45 Pm - 10 Pm</div>
            <div class="info"><i class="fas fa-map-marker-alt"></i>1 Circle Street Leominster, Ma 01453</div>
            <div class="info"><i class="fas fa-arrow-right"></i>Ver más</div>
          </div>
          <div class="footer-line"></div>
        </div>

        <div class="card green">
          <div class="card-header">
            <div class="date">
              <div class="day">29</div>
              <div class="month">Dec</div>
            </div>
          </div>
          <div class="card-body">
            <div class="title">Dr. Rodríguez</div>
            <div class="description">Especialista en Neurología</div>
            <div class="info"><i class="far fa-clock"></i>10 Pm - 10:30 Pm</div>
            <div class="info"><i class="fas fa-map-marker-alt"></i>1 Circle Street Leominster, Ma 01453</div>
            <div class="info"><i class="fas fa-arrow-right"></i>Ver más</div>
          </div>
          <div class="footer-line"></div>
        </div>

      </div>
    </div>

    <!-- Sidebar -->
    <?php include 'vistas/componentes/sidebar_filtros_turnos.php'; ?>
  </div>
=======

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?php
require_once "modelos/turno.php";
require_once "controladores/turno/info_turno_controlador.php";

$colores = ["purple", "red", "green"];
$i = 0;

// ---------------- PAGINADO ----------------
$porPagina = 20;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $porPagina;

$tur = new Turno();

// obtener turnos paginados
$listaTurno = $tur->consultarTurnosDisponiblesPaginado($offset, $porPagina);

// obtener total de turnos disponibles
$totalTurnos = $tur->contarTurnosDisponibles();
$totalPaginas = ceil($totalTurnos / $porPagina);
?>

<style>
    body { margin: 0; padding: 0; background-color: #f8f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .main-content { flex-grow: 1; padding: 40px; }
    .cards-container { display: flex; flex-wrap: wrap; gap: 20px; }

    .card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.05);
        width: 300px;
        transition: 0.3s ease;
    }
    .card:hover { transform: translateY(-5px); }

    .day { font-size: 32px; font-weight: 700; }
    .month { font-size: 16px; font-weight: 600; }

    .footer-line { height: 5px; border-radius: 0 0 10px 10px; }

    .purple .day, .purple .month, .purple .title, .purple .info { color: rgb(95,163,240); }
    .purple .footer-line { background-color: rgb(95,163,240); }

    .red .day, .red .month, .red .title, .red .info { color: rgb(44,92,248); }
    .red .footer-line { background-color: rgb(44,92,248); }

    .green .day, .green .month, .green .title, .green .info { color: rgb(3,14,179); }
    .green .footer-line { background-color: rgb(3,14,179); }
</style>
</head>
<body>

<div class="container py-4">
    <h2 class="mb-4">Turnos disponibles</h2>

    <div class="cards-container">

        <?php foreach ($listaTurno as $t):
            $color = $colores[$i % 3];
            $i++;

            $fecha = date("d", strtotime($t['fecha_hora']));
            $mes   = date("M", strtotime($t['fecha_hora']));
            $hora  = date("H:i", strtotime($t['fecha_hora']));
            $horaFin = date("H:i", strtotime($t['fecha_hora'] . " + {$t['minutos_turnos']} minutes"));
        ?>

        <!-- CARD -->
        <div class="card <?= $color ?>">
            <div class="card-header d-flex justify-content-between">
                <div class="date">
                    <div class="day"><?= $fecha ?></div>
                    <div class="month"><?= $mes ?></div>
                </div>
            </div>

            <div class="card-body">
                <div class="title"><?= $t['nombre'] . " " . $t['apellido'] ?></div>
                <div class="info"><i class="far fa-clock"></i> <?= $hora ?> - <?= $horaFin ?></div>
                <div class="info">
                    <i class="fas fa-arrow-right"></i>
                    <a href="index.php?page=info_turnos&id=<?= $t['id_turnos'] ?>">Ver más</a>
                </div>
            </div>

            <div class="footer-line"></div>
        </div>

        <?php endforeach; ?>

    </div>

    <!-- ================= PAGINADO ================= -->
    <?php if ($totalPaginas > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">

            <!-- Botón anterior -->
            <li class="page-item <?= $pagina == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?page=turnos&pagina=<?= $pagina - 1 ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>

            <!-- Números -->
            <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                <li class="page-item <?= ($p == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?page=turnos&pagina=<?= $p ?>">
                        <?= $p ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Botón siguiente -->
            <li class="page-item <?= $pagina == $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?page=turnos&pagina=<?= $pagina + 1 ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>

        </ul>
    </nav>
    <?php endif; ?>

</div>
>>>>>>> origin/mi-ramita

</body>
</html>
