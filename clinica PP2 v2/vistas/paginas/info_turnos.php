<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Info Turnos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        }
    </style>
</head>

<body>
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
                <div id="calendario"></div>
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


    <script src="assets\js\index.global.min"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendario');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
        });

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
    </script>
</body>

</html>