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
                                    <h4>Gestión de Agendas</h4>
                                </div>
                            </div>

                            <div class="col">
                                <div class="admin-card">
                                    <h4>Gestión de Usuarios</h4>
                                    <p>Aquí podrá gestionar los perfiles de usuarios.</p>
                                </div>
                            </div>

                            <div class="col">
                                <a href="index.php?page=reporte">
                                    <div class="admin-card">
                                        <h4>Reportes</h4>
                                        <p>Visualice reportes del sistema clínico.</p>
                                    </div>
                                </a>
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
                        <!-- Botones para alternar -->
                        <div class="btn-group mb-3" role="group">
                            <button class="btn btn-primary" id="btnUsuarios">Usuarios</button>
                            <button class="btn btn-secondary" id="btnGenero">Pacientes por Género</button>
                            <button class="btn btn-primary" id="btnObras">Obras Sociales</button>
                        </div>

                        <!-- Contenedor de gráfico de Usuarios -->
                        <div id="graficoUsuariosContainer">
                            <label for="periodoUsuarios">Registro de Usuarios</label>
                            <select id="periodoUsuarios" class="form-select mb-2">
                            <option value="diario">Diario</option>
                            <option value="semanal">Semanal</option>
                            <option value="mensual" selected>Mensual</option>
                            </select>
                            <canvas id="graficoUsuarios" height="140"></canvas>
                        </div>

                        <!-- Contenedor de gráfico de Género (oculto al inicio) -->
                        <div id="graficoGeneroContainer" style="display:none;">
                            <label for="periodoGenero">Pacientes por Género</label>
                            <select id="periodoGenero" class="form-select mb-2">
                            <option value="diario">Diario</option>
                            <option value="semanal">Semanal</option>
                            <option value="mensual" selected>Mensual</option>
                            </select>
                            <canvas id="graficoGenero" height="140"></canvas>
                        </div>
                        
                        <!-- Contenedor de gráfico de Obras Sociales (oculto al inicio) -->
                        <div id="graficoObrasContainer" style="display:none;" >
                            <label for="periodoObras">Uso de Obras Sociales</label>
                            <select id="periodoObras" class="form-select mb-2">
                            <option value="diario">Diario</option>
                            <option value="semanal">Semanal</option>
                            <option value="mensual" selected>Mensual</option>
                            </select>
                            <canvas id="graficoObras" height="140"></canvas>
                        </div>
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
