<link rel="stylesheet" href="assets/css/modal_registro.css">


<div class="form-container">
                <form id="registroForm" class="needs-validation" novalidate action="controladores/login.controlador.php" method="POST">
                    <input type="hidden" name="action" value="registro" />

                    <h2>Registro de Nuevos Usuarios</h2>

                    <!-- Barra de Progreso -->
                    <div class="progreso-container">
                        <div class="progreso-item active" id="step1">1</div>
                        <div class="progreso-item" id="step2">2</div>
                    </div>

                    <!-- === PASO 1 === -->
                    <div class="pagina active" id="pagina1">
                        <h5 class="mb-3">Datos de Persona</h5>

                        <div class="mb-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="error-message" id="error-nombre"></div>
                        </div>

                        <div class="mb-3">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                            <div class="error-message" id="error-apellido"></div>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                            <div class="error-message" id="error-fecha_nacimiento"></div>
                        </div>

                        <div class="mb-3">
                            <label for="sexo">Sexo</label>
                            <select class="form-select" id="sexo" name="sexo" required>
                                <option value="">Seleccione...</option>
                                <option value="1">Masculino</option>
                                <option value="2">Femenino</option>
                            </select>
                            <div class="error-message" id="error-sexo"></div>
                        </div>

                        <button type="button" class="btn btn-primary w-100" onclick="validarPaso(1)">Siguiente</button>
                    </div>

                    <!-- === PASO 2 === -->
                    <div class="pagina" id="pagina2">
                        <h5 class="mb-3">Datos de Usuario</h5>

                        <div class="mb-3">
                            <label for="nombre_usuario">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                            <div class="error-message" id="error-nombre_usuario"></div>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="error-message" id="error-email"></div>
                        </div>

                        <div class="mb-3">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                            <div class="error-message" id="error-password"></div>
                        </div>

                        <input type="hidden" name="perfil_id_perfil" value="3">

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="mostrarPaso(1)">Anterior</button>
                            <button type="submit" class="btn btn-success">Registrarse</button>
                        </div>
                    </div>
                </form>
            </div>