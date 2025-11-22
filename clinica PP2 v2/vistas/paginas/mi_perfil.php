<?php
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];
$email = $_SESSION['email'];
$perfil_id = $_SESSION['id_perfil']; // Este dato se usará con JS

require_once 'modelos/doctor.php';
$docs = new Doctor();
$doctores_no_disponibles = $docs->all_doctores();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="assets/css/adminStyle.css">
    <style>
        header,
        h1,
        h4 {
            color: #000967;
        }

        .card-custom {
            background-color: #8999AE;
            border: none;
            border-radius: 10px;
            color: white;
        }

        .section-title {
            color: #024296;
        }

        .badge-active {
            background-color: #007DC6;
            color: white;
            padding: 0.3em 0.6em;
            border-radius: 0.25rem;
        }

        .badge-pending {
            background-color: #024296;
            color: white;
            padding: 0.3em 0.6em;
            border-radius: 0.25rem;
        }

        #admin-container,
        #paciente-container,
        #doctor-container {
            display: none;
        }
    </style>
</head>

<body>

    <main class="py-5 container">
        <!--------------------------------- CONTENEDOR DE PACIENTE ---------------------------------->
        <!--------------------------------- CONTENEDOR DE PACIENTE ---------------------------------->
        <!--------------------------------- CONTENEDOR DE PACIENTE ---------------------------------->
        <!--------------------------------- CONTENEDOR DE PACIENTE ---------------------------------->
        <div id="paciente-container">
            <header class="mb-4">
                <h1>Bienvenido Paciente, <?= htmlspecialchars($nombre_usuario) ?></h1>
                <p>Gestione sus turnos y datos personales desde aquí.</p>
            </header>
            <?php echo $_SESSION['nombre_perfil']; ?>

            <div class="row mb-4">
                <div class="col-md-4">
                    <h4>Cuenta</h4>
                    <p>Correo: <?= htmlspecialchars($email) ?></p>
                    <p>Contraseña: ********</p>
                </div>

                <div class="col-md-4">
                    <h4>Notificaciones</h4>
                    <ul class="list-group">
                        <li class="list-group-item">Tiene una cita mañana a las 10:00</li>
                        <li class="list-group-item">Resultado disponible del examen cardiológico</li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h4>Calendario</h4>
                    <div id='calendar'></div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="container card-custom p-3 mb-4">
                        <h5 class="section-title">Historial de Citas Médicas</h5>
                        <table class="table table-sm text-white">
                            <thead>
                                <tr>
                                    <th>Médico</th>
                                    <th>Especialidad</th>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Dr. Jenny Smith</td>
                                    <td>Dermatología</td>
                                    <td>12/05/2025</td>
                                    <td><a href="#">Reprogramar</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom p-3 mb-4">
                        <h5 class="section-title">Próximas Citas</h5>
                        <div class="mb-2">
                            <strong>Chequeo General</strong><br>
                            <small>Dr. Dianne Philips - 10:00 AM</small><br>
                            <span class="badge badge-active">Activa</span>
                        </div>
                        <div>
                            <strong>Dolor de Cabeza</strong><br>
                            <small>Dr. Jenny Smith - 05:00 PM</small><br>
                            <span class="badge badge-pending">Pendiente</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--------------------------------- CONTENEDOR DE DOCTOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE DOCTOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE DOCTOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE DOCTOR ---------------------------------->

        <div id="doctor-container">
            <h1>Bienvenido Doctor, <?= htmlspecialchars($nombre_usuario) ?></h1>

            <div class="card-custom p-4 mb-4">
                <div class="row">
                    <div class="col-md-2">
                        <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="doctor" class="img-fluid rounded">
                    </div>
                    <div class="col-md-7">
                        <h4 class="mb-0">Dr. John Smith <span class="badge bg-light text-dark">Cardiología</span></h4>
                        <small>MBBS, M.D, Cardiología</small>
                        <p class="mt-2 mb-1"><i class="fas fa-hospital me-2"></i> Clínica: Centro Médico Central <span class="badge bg-success">Disponible</span></p>
                    </div>
                    <div class="col-md-3 text-end">
                        <p class="mb-1">Costo por Consulta</p>
                        <h5>$499 <small>/ 30 Min</small></h5>
                        <button class="btn btn-dark btn-sm"><i class="fas fa-calendar-check me-1"></i> Reservar Turno</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card-custom p-3 mb-3">
                        <h5>Disponibilidad</h5>
                        <ul class="nav nav-tabs border-0 mt-2">
                            <li class="nav-item"><a class="nav-link active-tab" href="#">Lunes</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="#">Martes</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="#">Miércoles</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="#">Jueves</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="#">Viernes</a></li>
                        </ul>
                        <div class="mt-3">
                            <span class="tab-time">11:30 AM - 12:30 PM</span>
                            <span class="tab-time">06:00 PM - 07:30 PM</span>
                            <span class="tab-time">12:30 PM - 01:30 PM</span>
                            <span class="tab-time">07:00 PM - 08:30 PM</span>
                            <span class="tab-time">02:30 PM - 03:30 PM</span>
                            <span class="tab-time">09:00 PM - 11:00 PM</span>
                            <span class="tab-time">04:30 PM - 05:30 PM</span>
                            <span class="tab-time">11:00 PM - 11:30 PM</span>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="container card-custom p-3 mb-4">
                        <h5 class="section-title">Historial de Citas Médicas</h5>
                        <table class="table table-sm text-white">
                            <thead>
                                <tr>
                                    <th>Médico</th>
                                    <th>Especialidad</th>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Dr. Jenny Smith</td>
                                    <td>Dermatología</td>
                                    <td>12/05/2025</td>
                                    <td><a href="#">Reprogramar</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom p-3 mb-4">
                        <h5 class="section-title">Próximas Citas</h5>
                        <div class="mb-2">
                            <strong>Chequeo General</strong><br>
                            <small>Dr. Dianne Philips - 10:00 AM</small><br>
                            <span class="badge badge-active">Activa</span>
                        </div>
                        <div>
                            <strong>Dolor de Cabeza</strong><br>
                            <small>Dr. Jenny Smith - 05:00 PM</small><br>
                            <span class="badge badge-pending">Pendiente</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">

                <div class="col-md-4">
                    <h4>Notificaciones</h4>
                    <ul class="list-group">
                        <li class="list-group-item">Tiene una cita mañana a las 10:00</li>
                        <li class="list-group-item">Resultado disponible del examen cardiológico</li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h4>Calendario</h4>
                    <p>(Aquí se mostrará el calendario con sus citas)</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-custom p-3 mb-3">
                        <h4>Gestión de Usuarios</h4>
                        <p>Aquí podrá gestionar los perfiles de usuarios.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-custom p-3 mb-3">
                        <h4>Reportes</h4>
                        <p>Visualice reportes del sistema clínico.</p>
                    </div>
                </div>

            </div>
        </div>

        <!--------------------------------- CONTENEDOR DE ADMINISTRADOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE ADMINSITRADOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE ADMINISTRADOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE ADMINISTRADOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE ADMINISTRADOR ---------------------------------->


        <div id="admin-container">
            <header class="mb-4 text-center">
                <h1>Bienvenido Administrador, <?= htmlspecialchars($nombre_usuario) ?></h1>
            </header>

            <!-- PRIMER BLOQUE -->
            <div class="container text-center mb-5">
                <div class="row g-4">

                    <!-- Imagen grande con horario -->
                    <div class="col-12 col-md-5 admin-image-box"
                        style="background-image: url('assets/images/6511c213dadb6.jpg'); height: 180px;">

                        <div class="admin-datetime-box">
                            <span id="fecha"><?= date("d/m/Y") ?></span> —
                            <span id="hora"></span>
                        </div>
                    </div>

                    <!-- Tarjetas funcionales -->
                    <div class="col-12 col-md-7">
                        <div class="row row-cols-1 row-cols-md-2 g-4">

                            <div class="col">
                                <div class="admin-card">
                                    <h4>ns</h4>
                                </div>
                            </div>

                            <div class="col">
                                <div class="admin-card">
                                    <h4>Gestión de Usuarios</h4>
                                    <p>Aquí podrá gestionar los perfiles de usuarios.</p>
                                </div>
                            </div>

                            <div class="col">
                                <div class="admin-card">
                                    <h4>Reportes</h4>
                                    <p>Visualice reportes del sistema clínico.</p>
                                </div>
                            </div>

                            <div class="col">
                                <a href="index.php?page=tablas_maestras" style="text-decoration: none;">
                                    <div class="admin-card">
                                        <h4>Tablas Maestras</h4>
                                        <p>Visualice las tablas maestras del sistema.</p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- SEGUNDO BLOQUE -->
            <div class="container text-center mt-4">
                <div class="row g-4">

                    <!-- Gráfico estadístico -->
                    <div class="col graph-container">
                        <h2>Gráfico Estadístico</h2>
                    </div>

                    <!-- Información lateral -->
                    <div class="col-md-auto side-info">
                        <div class="doctores-offline">
                            <h4 class="doctores-title">Doctores No Disponibles</h4>

                            <!-- Loop dinámico -->
                            <?php foreach ($doctores_no_disponibles as $doc): ?>
                                <div class="doctor-card">
                                    <div class="doctor-icon">
                                        <?= strtoupper(substr($doc['nombre'], 0, 1)) ?>
                                    </div>

                                    <div class="doctor-name">
                                        <?= htmlspecialchars($doc['nombre']) ?>
                                    </div>

                                    <span class="status-badge">No disponible</span>
                                </div>
                            <?php endforeach; ?>

                            <!-- Si no hay doctores fuera de servicio -->
                            <?php if (empty($doctores_no_disponibles)): ?>
                                <div class="doctor-card" style="background: rgba(255,255,255,0.15); cursor: default;">
                                    <div class="doctor-icon">✓</div>
                                    <div class="doctor-name">Todos los doctores están disponibles</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </main>

    <!-- Esta línea muestra la ruta completa del archivo actual (útil para saber en qué carpeta estoy) echo __FILE__; -->
    <hr>
    <?php if ($perfil_id == '3'): ?>
        <h3>Cambiar Contraseña</h3>
        <form action="controladores/contrasena.controlador.php" method="POST">
            <input type="hidden" name="action" value="cambiar_password">
            <div class="mb-3">
                <label for="actual" class="form-label">Contraseña Actual:</label>
                <input type="password" name="actual" id="actual" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nueva" class="form-label">Nueva Contraseña:</label>
                <input type="password" name="nueva" id="nueva" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirmar" class="form-label">Confirmar Nueva Contraseña:</label>
                <input type="password" name="confirmar" id="confirmar" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
        </form>
    <?php else: ($perfil_id == '1' || '2') ?>
        <h3>Resetear mi contraseña</h3>
        <form action="controladores/contrasena.controlador.php" method="POST">
            <input type="hidden" name="action" value="resetear_password">
            <button type="submit" class="btn btn-warning" onclick="return confirm('¿Estás seguro que quieres resetear tu contraseña?')">
                Resetear a contraseña por defecto
            </button>
        </form>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const perfilId = <?= json_encode($perfil_id); ?>;
            const doctorContainer = document.getElementById('doctor-container');
            const pacienteContainer = document.getElementById('paciente-container');
            const adminContainer = document.getElementById('admin-container');

            if (perfilId == 3) {
                pacienteContainer.style.display = 'block';
            } else if (perfilId == 1) {
                adminContainer.style.display = 'block';
            } else if (perfilId == 2) {
                doctorContainer.style.display = 'block';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
        });
    </script>
    <script>
        function actualizarHora() {
            const ahora = new Date();

            // Formato HH:MM:SS
            const hora = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');

            document.getElementById("hora").textContent = `${hora}:${minutos}:${segundos}`;
        }

        // Actualiza al cargar
        actualizarHora();

        // Actualiza cada 1 segundo
        setInterval(actualizarHora, 1000);
    </script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
</body>

</html>