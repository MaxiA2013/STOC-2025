<?php
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];
$email = $_SESSION['email'];
$perfil_id = $_SESSION['id_perfil']; // Este dato se usará con JS
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
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
        <?php echo $_SESSION['id_perfil']; ?>
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

                <div class="col-md-4">
                    <div class="card-custom p-3">
                        <h5>Información</h5>
                        <ul class="list-unstyled">
                            <li class="mt-2"><strong>Teléfono:</strong><br> +1 54546 45648</li>
                            <li class="mt-2"><strong>Email:</strong><br> john@example.com</li>
                            <li class="mt-2"><strong>Ubicación:</strong><br> Calle Salud 123, Las Vegas, NV</li>
                        </ul>
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

        <div id="admin-container" style="background-color: #024296;">
            <header class="mb-4">
                <h1>Bienvenido Administrador, <?= htmlspecialchars($nombre_usuario) ?></h1>
            </header>

            <div class="container text-center">
                <div class="row">

                    <div class="col-5" style="background-image: url('assets/images/6511c213dadb6.jpg'); background-size: cover ;height: 180px; width: 600px;">
                        <h5>Cuadro con imagen de fondo y horario</h5>
                    </div>

                    <div class="col">

                        <div class="container text-center">
                            <div class="row row-cols-2">
                                <div class="col">
                                    <h4>ns</h4>
                                </div>

                                <div class="col">
                                    <h4>Gestión de Usuarios</h4>
                                    <p>Aquí podrá gestionar los perfiles de usuarios.</p>
                                </div>

                                <div class="col">
                                    <h4>Reportes</h4>
                                    <p>Visualice reportes del sistema clínico.</p>
                                </div>

                                <div class="col">
                                    <a href="index.php?page=tablas_maestras">
                                        <h4>Tablas Maestras</h4>
                                    </a>
                                    <p>Visualice las tablas maestras del sistema.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="container text-center">
                <div class="row">

                    <div class="col" style="background-color: #b0b0b1ff; height: 480px; ">
                        <h1>Grafico estadistico</h1>
                    </div>
                    <div class="col-md-auto">
                        <H4>Doctores No Disponibles</H4>
                    </div>

                </div>
            </div>



        </div>

    </main>

    <!-- Esta línea muestra la ruta completa del archivo actual (útil para saber en qué carpeta estoy) echo __FILE__; -->
    <hr>
    <?php if ($perfil_id == '1'): ?>
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
    <?php else: ($perfil_id == '2' || '3') ?>
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

            if (perfilId == 1) {
                pacienteContainer.style.display = 'block';
            } else if (perfilId == 2) {
                adminContainer.style.display = 'block';
            } else if (perfilId == 3) {
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
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
</body>

</html>