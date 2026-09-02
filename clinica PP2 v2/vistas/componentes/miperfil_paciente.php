<html>
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

</html>