<?php
// ===============================
//   VALIDACIÓN DEL ID
// ===============================
if (!isset($_GET['id'])) {
    die("No se seleccionó ningún turno");
}

$idTurno = intval($_GET['id']);

require_once "modelos/turno.php";
$turnoObj = new Turno();
$turno = $turnoObj->obtenerTurnoPorId($idTurno);

if (!$turno) {
    die("Turno no encontrado");
}

// Datos limpios
$doctor = $turno['nombre'] . " " . $turno['apellido'];
$especialidad = $turno['especialidad'] ?? "Sin especificar";
$obraSociales = $turno['obras_sociales'] ?? "No registra";
$fecha = date("d", strtotime($turno['fecha_hora']));
$mes = date("M", strtotime($turno['fecha_hora']));
$horaInicio = date("H:i", strtotime($turno['fecha_hora']));
$horaFin = date("H:i", strtotime($turno['fecha_hora'] . " + {$turno['minutos_turnos']} minutes"));
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Turno Seleccionado</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>

    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .layout {
            display: flex;
            gap: 20px;
        }

        .left-column {
            width: 35%;
        }

        .calendar-section {
            flex-grow: 1;
        }

        .card-custom {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            padding: 18px;
        }

        .date-box {
            background: #e9f1ff;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            width: 90px;
        }

        .date-box .day {
            font-size: 36px;
            font-weight: bold;
        }

        .date-box .month {
            font-size: 16px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
<div class="container py-4">

    <h2 class="mb-4">Información del Turno Seleccionado</h2>

    <div class="layout">

        <!-- ============================
                COLUMNA IZQUIERDA
        =============================== -->
        <div class="left-column">

            <!-- Tarjeta del Doctor -->
            <div class="card-custom mb-3 d-flex align-items-center gap-3">
                <img src="assets/images/img_avatar1.png" width="70" class="rounded-circle">
                <div>
                    <h5 class="mb-1"><?= $doctor ?></h5>
                    <p class="text-muted mb-0"><?= $especialidad ?></p>
                </div>
            </div>

            <!-- Tarjeta del Turno -->
            <div class="card-custom mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="date-box">
                        <div class="day"><?= $fecha ?></div>
                        <div class="month"><?= $mes ?></div>
                    </div>
                    <div>
                        <h5><?= $doctor ?></h5>
                        <p class="text-muted mb-1"><?= $especialidad ?></p>
                        <p class="mb-1"><i class="far fa-clock"></i> <?= $horaInicio ?> - <?= $horaFin ?></p>
                        <p class="mb-1"><i class="fas fa-id-card"></i> Obras Sociales: <?= $obraSociales ?></p>
                    </div>
                </div>

                <button class="btn btn-primary w-100 mt-3" id="openFormBtn">Tomar Turno</button>
            </div>

        </div>

        <!-- ============================
                COLUMNA DERECHA
        =============================== -->
        <div class="calendar-section">
            <div class="card-custom">
                <h4 class="mb-3">Calendario</h4>
                <div id="calendarioTurnos"></div>
            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var calendar = new FullCalendar.Calendar(document.getElementById('calendarioTurnos'), {
        initialView: 'dayGridMonth',
        locale: 'es',

        events: [
            {
                title: "Turno con <?= $doctor ?>",
                start: "<?= $turno['fecha_hora'] ?>",
                end: "<?= date('Y-m-d H:i:s', strtotime($turno['fecha_hora'] . " + {$turno['minutos_turnos']} minutes")) ?>",
                color: "#5fa3f0"
            }
        ]
    });

    calendar.render();
});
</script>

</body>
</html>
