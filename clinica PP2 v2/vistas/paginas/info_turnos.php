<?php
require_once "modelos/turno.php";
require_once "modelos/paciente.php";
require_once "modelos/doctor_obra_social.php";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// ---------------- MESES ----------------

$meses = [
  1 => "Ene",
  2 => "Feb",
  3 => "Mar",
  4 => "Abr",
  5 => "May",
  6 => "Jun",
  7 => "Jul",
  8 => "Ago",
  9 => "Sep",
  10 => "Oct",
  11 => "Nov",
  12 => "Dic"
];

// ---------------- TURNO SELECCIONADO ----------------

$id_turno = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$tur = new Turno();

$turnoInfo = $id_turno > 0 ? $tur->obtenerTurnoPorId($id_turno) : null;

if ($turnoInfo) {

  $fechaTurno = strtotime($turnoInfo['fecha_hora']);

  $dia = date("d", $fechaTurno);
  $mes = $meses[(int)date("n", $fechaTurno)];
  $hora = date("H:i", $fechaTurno);

  $fechaFinTexto = date(
    "Y-m-d H:i:s",
    strtotime(
      $turnoInfo['fecha_hora'] .
        " + " .
        (int)$turnoInfo['minutos_turnos'] .
        " minutes"
    )
  );

  $horaFin = date("H:i", strtotime($fechaFinTexto));

  $turnoDisponible = (int)$turnoInfo['disponible'] === 1;

  // ---------------- OBRAS SOCIALES DEL DOCTOR ----------------

  $doctorOS = new Doctor_Obra_Social();

  $obrasDoctor = $doctorOS->consultarArrayPorDoctor(
    (int)$turnoInfo['id_doctor']
  );

  $nombresObras = [];

  foreach ($obrasDoctor as $os) {
    // Ajustar el nombre de la columna si en tu modelo se llama distinto
    $nombresObras[] = $os['nombre_obra_social'] ?? $os['nombre'] ?? '';
  }

  $nombresObras = array_filter($nombresObras);
}

// ---------------- PACIENTE EN SESIÓN ----------------

$pacienteSesion = null;
$esPaciente = strtolower(trim($_SESSION['nombre_perfil'] ?? '')) === 'paciente';

if ($esPaciente && !empty($_SESSION['id_usuario'])) {

  $pacienteModel = new Paciente();

  $pacienteSesion = $pacienteModel->obtenerPorUsuario(
    (int)$_SESSION['id_usuario']
  );
}

// ---------------- EVENTOS DEL CALENDARIO ----------------

$eventos = [];

// Turnos del paciente
$turnoSeleccionadoYaIncluido = false;

if ($pacienteSesion) {

  $turnosPaciente = $tur->obtenerTurnosPorPaciente(
    (int)$pacienteSesion['id_paciente']
  );

  foreach ($turnosPaciente as $tp) {

    $finTp = date(
      "Y-m-d H:i:s",
      strtotime(
        $tp['fecha_hora'] .
          " + " .
          (int)$tp['minutos_turnos'] .
          " minutes"
      )
    );

    // Si es el turno que se está viendo (ya reservado por este paciente),
    // se resalta con el color naranja
    $esSeleccionado = ((int)$tp['id_turnos'] === $id_turno);

    if ($esSeleccionado) {
      $turnoSeleccionadoYaIncluido = true;
    }

    $eventos[] = [
      "id"              => "mio-" . $tp['id_agenda_turno'],
      "title"           => $esSeleccionado
        ? "Tu turno (" . $tp['tipo_estado'] . ")"
        : "Dr. " . $tp['apellido'] . " (" . $tp['tipo_estado'] . ")",
      "start"           => date("c", strtotime($tp['fecha_hora'])),
      "end"             => date("c", strtotime($finTp)),
      "backgroundColor" => $esSeleccionado ? "#f59e0b" : "rgb(95, 163, 240)",
      "borderColor"     => $esSeleccionado ? "#b45309" : "rgb(95, 163, 240)",
      "textColor"       => "#fff"
    ];
  }
}

// Turno que se quiere tomar (resaltado), solo si no se agregó ya arriba

if ($turnoInfo && !$turnoSeleccionadoYaIncluido) {

  $eventos[] = [
    "id"              => "seleccionado",
    "title"           => "Turno a tomar",
    "start"           => date("c", $fechaTurno),
    "end"             => date("c", strtotime($fechaFinTexto)),
    "backgroundColor" => "#f59e0b",
    "borderColor"     => "#b45309",
    "textColor"       => "#fff"
  ];
}

// Turno que se quiere tomar (resaltado)

if ($turnoInfo) {

  $eventos[] = [
    "id"              => "seleccionado",
    "title"           => "Turno a tomar",
    "start"           => date("c", $fechaTurno),
    "end"             => date("c", strtotime($fechaFinTexto)),
    "backgroundColor" => "#f59e0b",
    "borderColor"     => "#b45309",
    "textColor"       => "#fff"
  ];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Info Turnos</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <!-- FullCalendar idioma español -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fc;
    }

    /* Contenedor principal */
    .layout {
      display: flex;
      width: 100%;
    }

    /* Columna de tarjetas */
    .main-content {
      width: 40%;
      padding: 15px;
      overflow-y: auto;
      border-right: 1px solid #ddd;
      background-color: #ffffff;
    }

    .container {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
      padding: 20px;
      transition: 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 10px;
    }

    .card-header img {
      height: 60px;
      width: 60px;
      border-radius: 50%;
    }

    .day {
      font-size: 32px;
      font-weight: 700;
    }

    .month {
      font-size: 16px;
      font-weight: 600;
    }

    .card-body .title {
      font-size: 18px;
      font-weight: bold;
    }

    .card-body .description {
      font-size: 14px;
      color: #666;
    }

    .card-body .info {
      margin-top: 10px;
      font-size: 14px;
    }

    .card-body .info i {
      margin-right: 8px;
    }

    .footer-line {
      height: 5px;
      border-radius: 0 0 10px 10px;
      background-color: rgb(95, 163, 240);
      margin-top: 10px;
    }

    .purple .title,
    .purple .info,
    .purple .day,
    .purple .month {
      color: rgb(95, 163, 240);
    }

    /* Columna calendario */
    .calendar-section {
      flex-grow: 1;
      background: #ffffff;
      padding: 10px;
      overflow-y: auto;
    }

    .calendar-container {
      background: #f4f6fb;
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      height: 100%;
    }

    .calendar-container h2 {
      font-size: 22px;
      color: #333;
      margin-bottom: 10px;
    }

    .leyenda {
      display: flex;
      gap: 15px;
      font-size: 13px;
      margin-bottom: 15px;
      color: #555;
    }

    .leyenda span::before {
      content: "";
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 3px;
      margin-right: 6px;
      vertical-align: middle;
    }

    .leyenda .l-seleccionado::before {
      background: #f59e0b;
    }

    .leyenda .l-mios::before {
      background: rgb(95, 163, 240);
    }

    button {
      padding: 10px 16px;
      font-size: 14px;
      background-color: rgb(95, 163, 240);
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: rgb(75, 145, 220);
    }

    .aviso {
      margin-top: 10px;
      font-size: 13px;
      color: #888;
    }

    @media (max-width: 768px) {
      .layout {
        flex-direction: column;
      }

      .main-content,
      .calendar-section {
        width: 100%;
      }
    }

        button:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .form-group input[type="radio"] {
      margin-right: 8px;
    }

    .os-warning {
      margin-top: 8px;
      padding: 8px 10px;
      font-size: 13px;
      color: #92400e;
      background: #fef3c7;
      border: 1px solid #fcd34d;
      border-radius: 6px;
    }

    /* Overlay */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(240, 240, 240, 0.95);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
      width: 400px;
    }

    .form-step {
      display: none;
    }

    .form-step.active {
      display: block;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }

    .form-group input[type="checkbox"] {
      margin-right: 8px;
    }

    .form-group select,
    .form-group input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .close-btn {
      float: right;
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #555;
    }

    .summary p {
      margin: 5px 0;
    }
  </style>
</head>

<body>

  <?php if (!$turnoInfo): ?>

    <div style="text-align:center; padding:60px;">
      <i class="far fa-calendar-times fa-3x"></i>
      <h3>El turno solicitado no existe</h3>
      <a href="index.php?page=turnos">Volver a turnos</a>
    </div>

  <?php else: ?>

    <div class="layout">

      <!-- Columna Izquierda - Turnos -->
      <div class="main-content">
        <div class="container">

          <!-- TARJETA DOCTOR -->
          <div class="card purple">

            <div class="card-header">
              <img src="assets/images/img_avatar1.png" alt="Foto Perfil">
              <div>
                <div class="title">
                  Dr. <?= htmlspecialchars($turnoInfo['nombre'] . " " . $turnoInfo['apellido']) ?>
                </div>
                <div class="description">Especialista en especialidad</div>
              </div>
            </div>

            <div class="card-body">
              <div class="title">Obras Sociales:</div>
              <div class="description">
                <?= !empty($nombresObras)
                  ? htmlspecialchars(implode(", ", $nombresObras))
                  : "Solo atención particular" ?>
              </div>
            </div>

            <div class="footer-line"></div>
          </div>

          <!-- TARJETA TURNO -->
          <div class="card purple">

            <div class="card-header">
              <div class="date">
                <div class="day"><?= htmlspecialchars($dia) ?></div>
                <div class="month"><?= htmlspecialchars($mes) ?></div>
              </div>
            </div>

            <div class="card-body">
              <div class="title">
                Dr. <?= htmlspecialchars($turnoInfo['nombre'] . " " . $turnoInfo['apellido']) ?>
              </div>
              <div class="description">Especialista en especialidad</div>

              <div class="info">
                <i class="far fa-clock"></i>
                <?= htmlspecialchars($hora) ?> - <?= htmlspecialchars($horaFin) ?>
              </div>

              <div class="info">
                <i class="fas fa-map-marker-alt"></i>
                Dirección de la clínica
              </div>

              <?php if (!$turnoDisponible): ?>

                <div class="aviso">Este turno ya no está disponible.</div>

              <?php elseif ($pacienteSesion): ?>

                <button id="openFormBtn">Tomar Turno</button>

              <?php else: ?>

                <div class="aviso">
                  <a href="index.php?page=login">Iniciá sesión</a> como paciente para tomar este turno.
                </div>

              <?php endif; ?>
            </div>

            <div class="footer-line"></div>
          </div>

        </div>
      </div>

      <!-- Columna Derecha - Calendario -->
      <div class="calendar-section">
        <div class="calendar-container">
          <h2>Calendario de Turnos</h2>

          <div class="leyenda">
            <span class="l-seleccionado">Turno a tomar</span>
            <?php if ($pacienteSesion): ?>
              <span class="l-mios">Mis turnos</span>
            <?php endif; ?>
          </div>

          <div id="calendarioTurnos"></div>
        </div>
      </div>

    </div>

        <?php if ($pacienteSesion && $turnoDisponible): ?>

      <!-- MODAL TOMAR TURNO -->
      <div class="overlay" id="formOverlay"
        data-turno="<?= (int)$turnoInfo['id_turnos'] ?>"
        data-doctor="<?= (int)$turnoInfo['id_doctor'] ?>"
        data-paciente="<?= (int)$pacienteSesion['id_paciente'] ?>">

        <div class="form-container">
          <button class="close-btn" onclick="closeForm()">×</button>

          <!-- Paso 1 -->
          <div class="form-step active" id="step1">
            <h3>Elegí tipo de atención</h3>

            <div class="form-group">
              <label>
                <input type="radio" name="tipo_atencion" value="particular" checked> Particular
              </label>
              <label>
                <input type="radio" name="tipo_atencion" value="obra_social"> Obra Social
              </label>
            </div>

            <div class="form-group" id="obraSocialSelect" style="display: none;">
              <label for="obraSelect">Seleccioná Obra Social:</label>
              <select id="obraSelect" style="width: 100%;"></select>

              <div class="os-warning" id="obraSocialWarning" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                No hay obra social compatible
              </div>
            </div>

            <button id="btnSiguiente" onclick="goToStep2()">Siguiente</button>
          </div>

          <!-- Paso 2 -->
          <div class="form-step" id="step2">
            <h3>Resumen del turno</h3>

            <div class="summary">
              <p><strong>Doctor:</strong>
                Dr. <?= htmlspecialchars($turnoInfo['nombre'] . " " . $turnoInfo['apellido']) ?></p>
              <p><strong>Fecha:</strong>
                <?= htmlspecialchars($dia . " " . $mes . ", " . $hora . " - " . $horaFin) ?></p>
              <p><strong>Atención:</strong> <span id="tipoAtencionResumen"></span></p>
              <p><strong>Obra Social:</strong> <span id="obraResumen"></span></p>
            </div>

            <div class="os-warning" id="errorConfirmar" style="display: none;"></div>

            <button id="btnConfirmar" onclick="confirmarTurno()">Confirmar</button>
          </div>
        </div>
      </div>

    <?php endif; ?>

        <script>
      document.addEventListener('DOMContentLoaded', function() {

        var calendarEl = document.getElementById('calendarioTurnos');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          initialDate: <?= json_encode(date("Y-m-d", $fechaTurno)) ?>,
          locale: 'es',
          buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
          },
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
          },
          events: <?= json_encode(
                    $eventos,
                    JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
                  ) ?>
        });

        calendar.render();
      });

      // ==========================================================
      // MODAL TOMAR TURNO
      // (solo existe si hay paciente en sesión y el turno está disponible)
      // ==========================================================
      (function() {

        var overlay = document.getElementById("formOverlay");
        var openBtn = document.getElementById("openFormBtn");

        if (!overlay || !openBtn) {
          return;
        }

        var doctorId = overlay.dataset.doctor;
        var pacienteId = overlay.dataset.paciente;

        var step1 = document.getElementById("step1");
        var step2 = document.getElementById("step2");
        var divObra = document.getElementById("obraSocialSelect");
        var obraSelect = document.getElementById("obraSelect");
        var warning = document.getElementById("obraSocialWarning");
        var btnSiguiente = document.getElementById("btnSiguiente");

        var obrasCargadas = false;
        var tieneSelect2 = !!(window.jQuery && jQuery.fn && jQuery.fn.select2);

        if (tieneSelect2) {
          jQuery(obraSelect).select2({
            dropdownParent: jQuery(overlay),
            width: "100%",
            placeholder: "Seleccione obra social"
          });
        }

        function tipoAtencion() {
          return document.querySelector('input[name="tipo_atencion"]:checked').value;
        }

        // Refresca el select2 después de modificar las opciones
        function refrescarSelect() {
          if (tieneSelect2) {
            jQuery(obraSelect).trigger("change");
          }
        }

        // "Siguiente" queda deshabilitado si eligió obra social
        // pero todavía no hay una obra social seleccionada
        function actualizarBoton() {
          btnSiguiente.disabled =
            tipoAtencion() === "obra_social" && !obraSelect.value;
        }

        function cargarObrasSociales() {

          obraSelect.innerHTML = '<option value="">Cargando...</option>';
          obraSelect.disabled = true;
          warning.style.display = "none";
          refrescarSelect();

          fetch(
              "controladores/turno/ajax_get_obras_sociales_compatibles.php?doctor_id=" +
              encodeURIComponent(doctorId) +
              "&paciente_id=" +
              encodeURIComponent(pacienteId)
            )
            .then(function(resp) {
              return resp.json();
            })
            .then(function(data) {

              obraSelect.innerHTML = "";

              if (data.success && Array.isArray(data.data) && data.data.length > 0) {

                var vacia = document.createElement("option");
                vacia.value = "";
                vacia.textContent = "Seleccione una obra social";
                obraSelect.appendChild(vacia);

                data.data.forEach(function(os) {
                  var opt = document.createElement("option");
                  opt.value = os.id_obra_social;
                  opt.textContent = os.nombre_obra_social;
                  obraSelect.appendChild(opt);
                });

                obraSelect.disabled = false;
                warning.style.display = "none";

              } else {

                obraSelect.disabled = true;
                warning.style.display = "block";
              }

              obrasCargadas = true;
              refrescarSelect();
              actualizarBoton();
            })
            .catch(function(err) {
              console.error("Error al cargar obras sociales:", err);

              obraSelect.innerHTML = '<option value="">Error al cargar obras sociales</option>';
              obraSelect.disabled = true;
              refrescarSelect();
              actualizarBoton();
            });
        }

        // Cambio Particular / Obra Social
        document.querySelectorAll('input[name="tipo_atencion"]').forEach(function(radio) {
          radio.addEventListener("change", function() {

            if (tipoAtencion() === "obra_social") {
              divObra.style.display = "block";

              if (!obrasCargadas) {
                cargarObrasSociales();
              }
            } else {
              divObra.style.display = "none";
            }

            actualizarBoton();
          });
        });

        obraSelect.addEventListener("change", actualizarBoton);

        // select2 dispara el evento vía jQuery
        if (tieneSelect2) {
          jQuery(obraSelect).on("change", actualizarBoton);
        }

        function resetForm() {
          step1.classList.add("active");
          step2.classList.remove("active");

          document.querySelector('input[name="tipo_atencion"][value="particular"]').checked = true;
          divObra.style.display = "none";
          warning.style.display = "none";
          obrasCargadas = false;

          document.getElementById("errorConfirmar").style.display = "none";
          document.getElementById("btnConfirmar").disabled = false;
          actualizarBoton();
        }

        openBtn.addEventListener("click", function() {
          overlay.style.display = "flex";
        });

        // Funciones globales, porque se llaman desde onclick="" en el HTML
        window.closeForm = function() {
          overlay.style.display = "none";
          resetForm();
        };

        window.goToStep2 = function() {

          var esObra = tipoAtencion() === "obra_social";

          if (esObra && !obraSelect.value) {
            return;
          }

          document.getElementById("tipoAtencionResumen").textContent =
            esObra ? "Obra Social" : "Particular";

          document.getElementById("obraResumen").textContent =
            esObra ?
            obraSelect.options[obraSelect.selectedIndex].text :
            "N/A";

          step1.classList.remove("active");
          step2.classList.add("active");
        };

        // Envía la reserva a turno_controlador.php
        // (action=insertar + modo_turno=asignar).
        // El paciente y el estado los define el controlador desde la sesión.
        window.confirmarTurno = function() {

          var btnConfirmar = document.getElementById("btnConfirmar");
          var errorBox = document.getElementById("errorConfirmar");

          var esObra = tipoAtencion() === "obra_social";

          var datos = new URLSearchParams();
          datos.append("action", "insertar");
          datos.append("modo_turno", "asignar");
          datos.append("turno_id", overlay.dataset.turno);
          datos.append("con_obra_social", esObra ? "1" : "0");

          if (esObra) {
            datos.append("obra_social_id", obraSelect.value);
          }

          errorBox.style.display = "none";
          btnConfirmar.disabled = true;
          btnConfirmar.textContent = "Confirmando...";

          fetch("controladores/turno/turno_controlador.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded"
              },
              body: datos.toString()
            })
            .then(function(resp) {
              return resp.json();
            })
            .then(function(data) {

              if (data && data.success) {
                alert("Turno reservado correctamente. Quedó pendiente de aprobación.");
                // Recarga: el turno ya no está disponible y aparece en el calendario
                location.reload();
                return;
              }

              errorBox.textContent = (data && data.error) ?
                data.error :
                "No se pudo reservar el turno.";
              errorBox.style.display = "block";

              btnConfirmar.disabled = false;
              btnConfirmar.textContent = "Confirmar";
            })
            .catch(function(err) {
              console.error("Error al confirmar turno:", err);

              errorBox.textContent = "Error de conexión. Intentá nuevamente.";
              errorBox.style.display = "block";

              btnConfirmar.disabled = false;
              btnConfirmar.textContent = "Confirmar";
            });
        };

        actualizarBoton();

      })();
    </script>

  <?php endif; ?>

</body>

</html>