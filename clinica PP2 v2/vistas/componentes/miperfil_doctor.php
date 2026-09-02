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