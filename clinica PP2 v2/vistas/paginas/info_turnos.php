<<<<<<< HEAD
=======
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

>>>>>>> origin/mi-ramita
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <title>Info Turnos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <!-- FullCalendar idioma español -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>

    <style>
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
        .purple .info {
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
            margin-bottom: 20px;
        }

        iframe {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 8px;
            background-color: #fff;
        }

        button {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            background-color: rgb(95, 163, 240);
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: rgb(75, 145, 220);
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

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
            margin: 50px auto;
            width: 300px;
            text-align: center;
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
=======
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
>>>>>>> origin/mi-ramita
        }
    </style>
</head>

<body>
<<<<<<< HEAD
    <div class="layout">
        <!-- Columna Izquierda - Turnos -->
        <div class="main-content">
            <div class="container">

                <div class="card purple">
                    <div class="card-header">
                        <img src="../../assets/images/img_avatar1.png" alt="Foto Perfil">
                        <div>
                            <div class="title">Dr. Gómez</div>
                            <div class="description">Cardiología</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="title">Obras Sociales:</div>
                        <div class="description">Swiss Medical, Galeno</div>
                        <div class="info"><i class="far fa-clock"></i> Ver Calendario</div>
                    </div>
                    <div class="footer-line"></div>
                </div>

                <div class="card purple">
                    <div class="card-header">
                        <div class="day" style="font-size: 32px; font-weight: bold;">18</div>
                        <div class="month" style="font-size: 14px;">Dic</div>
                    </div>
                    <div class="card-body">
                        <div class="title">Dr. Gómez</div>
                        <div class="description">Cardiología</div>
                        <div class="info"><i class="far fa-clock"></i>08 PM - 09 PM</div>
                        <div class="info"><i class="fas fa-map-marker-alt"></i>Juan Jose Silva, 1890</div>
                        <button id="openFormBtn">Tomar Turno</button>
                    </div>
                    <div class="footer-line"></div>
                </div>

            </div>
        </div>

        <!-- Columna Derecha - Calendario -->
        <div class="calendar-section">
            <div class="calendar-container">
                <h2>Calendario de Turnos</h2>
                <!-- Puedes reemplazar este iframe con un calendario dinámico real -->
                <div id="calendarioTurnos"></div>
            </div>
        </div>
    </div>

    <div class="overlay" id="formOverlay">
        <div class="form-container">
            <button class="close-btn" onclick="closeForm()">×</button>

            <!-- Paso 1 -->
            <div class="form-step active" id="step1">
                <h3>Elegí tipo de atención</h3>

                <div class="form-group">
                    <label><input type="checkbox" id="particular" onchange="toggleChecks(this)"> Particular</label>
                    <label><input type="checkbox" id="obraSocial" onchange="toggleChecks(this)"> Obra Social</label>
                </div>

                <div class="form-group" id="obraSocialSelect" style="display: none;">
                    <label for="obraSelect">Seleccioná Obra Social:</label>
                    <select id="obraSelect">
                        <option value="Swiss Medical">Swiss Medical</option>
                        <option value="Galeno">Galeno</option>
                        <option value="OSDE">OSDE</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="metodoPago">Método de Pago</label>
                    <select id="metodoPago">
                        <option>Efectivo</option>
                        <option>Débito</option>
                        <option>Crédito</option>
                        <option>Transferencia</option>
                    </select>
                </div>

                <button onclick="goToStep2()">Siguiente</button>
            </div>

            <!-- Paso 2 -->
            <div class="form-step" id="step2">
                <h3>Resumen del turno</h3>
                <div class="summary">
                    <p><strong>Usuario:</strong> Juan Pérez</p>
                    <p><strong>Atención:</strong> <span id="tipoAtencionResumen"></span></p>
                    <p><strong>Obra Social:</strong> <span id="obraResumen"></span></p>
                    <p><strong>Método de Pago:</strong> <span id="pagoResumen"></span></p>
                </div>
                <button onclick="confirmarTurno()">Confirmar</button>
            </div>
        </div>
    </div>


    <script>
        const overlay = document.getElementById("formOverlay");
        const step1 = document.getElementById("step1");
        const step2 = document.getElementById("step2");
        const obraSocialCheckbox = document.getElementById("obraSocial");
        const obraSocialSelect = document.getElementById("obraSocialSelect");

        document.getElementById("openFormBtn").addEventListener("click", () => {
            overlay.style.display = "flex";
        });

        function closeForm() {
            overlay.style.display = "none";
            resetForm();
        }

        function toggleChecks(clicked) {
            if (clicked.id === "particular") {
                obraSocialCheckbox.checked = false;
                obraSocialSelect.style.display = "none";
            } else {
                document.getElementById("particular").checked = false;
                obraSocialSelect.style.display = obraSocialCheckbox.checked ? "block" : "none";
            }
        }

        function goToStep2() {
            const tipoAtencion = obraSocialCheckbox.checked ? "Obra Social" : "Particular";
            const obra = obraSocialCheckbox.checked ? document.getElementById("obraSelect").value : "N/A";
            const metodoPago = document.getElementById("metodoPago").value;

            document.getElementById("tipoAtencionResumen").textContent = tipoAtencion;
            document.getElementById("obraResumen").textContent = obra;
            document.getElementById("pagoResumen").textContent = metodoPago;

            step1.classList.remove("active");
            step2.classList.add("active");
        }

        function confirmarTurno() {
            alert("Turno confirmado correctamente 🎉");
            closeForm();
        }

        function resetForm() {
            step1.classList.add("active");
            step2.classList.remove("active");
            document.getElementById("particular").checked = false;
            obraSocialCheckbox.checked = false;
            obraSocialSelect.style.display = "none";
        }

        
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendarioTurnos');

            var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es', // 👈 esto ya está bien
            buttonText: { // 👈 forzamos traducción de botones
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            }
            });

            calendar.render();
        });




        
    </script>
</body>

</html>
=======
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
>>>>>>> origin/mi-ramita
