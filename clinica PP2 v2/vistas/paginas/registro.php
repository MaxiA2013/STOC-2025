<?php
$is_admin = isset($_SESSION['nombre_perfil']) && $_SESSION['nombre_perfil'] === 'Administrador';
?>
<style>
    /* ======= ESTILOS GENERALES ======= */
    .form-container {
        background: #fff;
        border-radius: 12px;
        padding: 1.6rem;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        max-width: 640px;
        margin: 0 auto;
    }

    body {
        background: #f5f7fb;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 1rem;
    }

    .progreso-container {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .progreso-item {
        height: 44px;
        width: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: #cfd8dc;
        font-weight: 700;
        transition: all .25s ease;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
    }

    .progreso-item.active {
        background: #0d6efd;
        transform: scale(1.05);
    }

    .progreso-item.completed {
        background: #198754;
    }

    .pagina {
        display: none;
    }

    .pagina.active {
        display: block;
        animation: fadeIn .35s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .error-input {
        border-color: #dc3545 !important;
        background: #fff5f6;
    }

    .error-message {
        color: #dc3545;
        font-size: .85rem;
        margin-top: .25rem;
        min-height: 18px;
    }

    .btn {
        border-radius: 8px;
        padding: .55rem 1rem;
    }

    label {
        font-weight: 600;
        color: #444;
    }
</style>

<div class="row align-items-center py-5">

    <div class="col-12">
        <div class="form-container">
            <form id="registroForm" class="needs-validation" novalidate method="POST" action="controladores/login.controlador.php">
                <input type="hidden" name="action" value="registro" />
                <h2>Registro de Nuevos Usuarios</h2>

                <!-- Barra de Progreso -->
                <div class="progreso-container" role="progressbar" aria-label="Progreso de registro">
                    <div class="progreso-item active" id="step1">1</div>
                    <div class="progreso-item" id="step2">2</div>
                </div>

                <!-- === PASO 1 === -->
                <div class="pagina active" id="pagina1">
                    <h5 class="mb-3">Datos de Persona</h5>

                    <div class="mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el Nombre" required>
                        <div class="error-message" id="error-nombre"></div>
                    </div>

                    <div class="mb-3">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese el Apellido" required>
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
                            <option value="3">Otro</option>
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
                        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Ingrese el nombre de usuario" required>
                        <div class="error-message" id="error-nombre_usuario"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su email" required>
                        <div class="error-message" id="error-email"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required minlength="6">
                        <div class="error-message" id="error-password"></div>
                    </div>

                    <input type="hidden" name="perfil_id_perfil" value="3"> <!-- Por defecto Paciente -->

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="mostrarPaso(1)">Anterior</button>
                        <button type="submit" class="btn btn-success">Registrarse</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulario de Registro en pagina de lista de usuarios -->
<!-- Formulario de Registro en pagina de lista de usuarios -->
<!-- Formulario de Registro en pagina de lista de usuarios -->
<!-- Formulario de Registro en pagina de lista de usuarios -->
<?php if (($_GET['page'] == 'lista_usuario') & (isset($is_admin))): ?>
    <div>
        <div class="mb-3 mt-3">
            <label for="nombre_usuario" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" placeholder="Ingrese el Nombre" name="nombre" required>
        </div>

        <div class="mb-3 mt-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" placeholder="Ingrese el Apellido" name="apellido" required>
        </div>

        <div class="mb-3 mt-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>

        <div class="mb-3 mt-3">
            <label for="sexo" class="form-label">Sexo</label>
            <select class="form-select" id="sexo" name="sexo" required>
                <option value="1">Masculino</option>
                <option value="2">Femenino</option>
                <option value="3">Otro</option>
            </select>
        </div>

        <div class="mb-3 mt-3">
            <label for="nombre_usuario" class="form-label">Nombre Usuario</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Ingrese el nombre del usuario" required>
        </div>

        <div class="mb-3 mt-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
        </div>

        <div class="form-check mb-3">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember"> Recordar Contraseña
            </label>
        </div>

        <div class="mb-3 mt-3">
            <label for="perfil" class="form-label">Perfil</label>
            <select class="form-select" id="perfil" name="perfil_id_perfil" onchange="toggleDoctorFields()">
                <option value="1">Administrador</option>
                <option value="2">Doctor</option>
                <option value="3" selected>Paciente</option>
            </select>
        </div>

        <!-- Campos extra solo si selecciona Doctor -->
        <div id="doctorFields" style="display: none;">
            <div class="mb-3 mt-3">
                <label for="matricula" class="form-label">Número de Matrícula Profesional</label>
                <input type="text" class="form-control" id="matricula" name="numero_matricula_profesional" placeholder="Ingrese la matrícula">
            </div>

            <div class="mb-3 mt-3">
                <label for="salario" class="form-label">Salario</label>
                <input type="number" class="form-control" id="salario" name="salario" placeholder="Ingrese el salario" step="0.01">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    <?php else: ?>
        <input type="hidden" name="perfil_id_perfil" value="3">
    <?php endif; ?>
    </div>
    </form>
    </div>
    </div>



    <?php if ($is_admin): ?>
        <script>
            function toggleDoctorFields() {
                var perfil = document.getElementById("perfil").value;
                var camposDoctor = document.getElementById("doctorFields");
                camposDoctor.style.display = (perfil == "2") ? "block" : "none";
            }

            // Ejecutar al cargar si ya está seleccionado Doctor
            window.onload = toggleDoctorFields;
        </script>
    <?php endif; ?>

    <script src="assets/js/validaciones/form_multipasos.js"></script>