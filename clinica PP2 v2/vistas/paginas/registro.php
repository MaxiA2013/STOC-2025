<?php
$is_admin = isset($_SESSION['nombre_perfil']) && $_SESSION['nombre_perfil'] === 'administrador';
$page = $_GET['page'] ?? '';
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

    .progreso-item.active { background: #0d6efd; transform: scale(1.05); }
    .progreso-item.completed { background: #198754; }

    .pagina { display: none; }
    .pagina.active { display: block; animation: fadeIn .35s ease; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .error-input { border-color: #dc3545 !important; background: #fff5f6; }
    .error-message { color: #dc3545; font-size: .85rem; min-height: 18px; }
</style>

<div class="row align-items-center py-5">
    <div class="col-12">

        <!-- ====================================================================================== -->
        <!-- ========================== FORMULARIO MULTIPASOS (page=registro) ====================== -->
        <!-- ====================================================================================== -->
        <?php if ($page === "registro"): ?>
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
        <?php endif; ?>


        <!-- ====================================================================================== -->
        <!-- ================= FORMULARIO SIMPLE (page=lista_usuario && admin) ===================== -->
        <!-- ====================================================================================== -->
        <?php if ($page === "lista_usuario" && $is_admin): ?>
            <div class="form-container mt-4">
                <h2>Registrar Nuevo Usuario</h2>

                <form action="controladores/login.controlador.php" method="POST">
                    <input type="hidden" name="action" value="registro_simple">

                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label>Apellido</label>
                        <input type="text" class="form-control" name="apellido" required>
                    </div>

                    <div class="mb-3">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" class="form-control" name="fecha_nacimiento" required>
                    </div>

                    <div class="mb-3">
                        <label>Sexo</label>
                        <select class="form-select" name="sexo" required>
                            <option value="1">Masculino</option>
                            <option value="2">Femenino</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nombre de Usuario</label>
                        <input type="text" class="form-control" name="nombre_usuario" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label>Perfil</label>
                        <select class="form-select" id="perfil" name="perfil_id_perfil" onchange="toggleDoctorFields()">
                            <option value="1">Administrador</option>
                            <option value="2">Doctor</option>
                            <option value="3" selected>Paciente</option>
                        </select>
                    </div>

                    <!-- Campos extra si el perfil es Doctor -->
                    <div id="doctorFields" style="display:none;">
                        <div class="mb-3">
                            <label>Número de Matrícula Profesional</label>
                            <input type="text" class="form-control" name="numero_matricula_profesional">
                        </div>

                        <div class="mb-3">
                            <label>Precio Consulta</label>
                            <input type="number" class="form-control" name="precio_consulta" step="0.01">
                        </div>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Registrar</button>
                </form>
            </div>

            <script>
                function toggleDoctorFields() {
                    const perfil = document.getElementById("perfil").value;
                    document.getElementById("doctorFields").style.display = (perfil == "2") ? "block" : "none";
                }
                window.onload = toggleDoctorFields;
            </script>
        <?php endif; ?>

    </div>
</div>

<script src="assets/js/validaciones/form_multipasos.js"></script>
