<!-- MODAL NUEVO USUARIO -->

<div
    class="modal fade"
    id="modalNewUser"
    tabindex="-1
    aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div>
                    <h5 class="modal-title">
                        Registrar Nuevo Usuario
                    </h5>

                    <small class="text-muted">
                        Complete los datos para crear el usuario.
                    </small>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>
            </div>


            <div
                class="modal-body"
                id="modalNewUserBody">

                <form
                    id="registroForm"
                    class="needs-validation"
                    novalidate
                    action="controladores/doctor_ajax_controlador.php"
                    method="POST">

                    <input
                        type="hidden"
                        name="action"
                        value="registrarCompleto">

                    <h5 class="fw-semibold mb-4">
                        Datos del Usuario
                    </h5>


                    <!-- PROGRESO -->

                    <div class="progreso-container mb-4">

                        <div
                            class="progreso-item active"
                            id="step1">
                            1
                        </div>


                        <div
                            class="progreso-item"
                            id="step2">

                            2

                        </div>

                    </div>

                    <!-- ================================================= -->
                    <!-- PASO 1 -->
                    <!-- ================================================= -->

                    <div
                        class="pagina active"
                        id="pagina1">

                        <h6 class="fw-semibold mb-3">
                            Datos de Persona
                        </h6>


                        <!-- Nombre -->

                        <div class="mb-3">

                            <label
                                for="nombre"
                                class="form-label">

                                Nombre

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nombre"
                                name="nombre"
                                required>

                            <div
                                class="error-message"
                                id="error-nombre">
                            </div>

                        </div>


                        <!-- Apellido -->

                        <div class="mb-3">

                            <label
                                for="apellido"
                                class="form-label">

                                Apellido

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="apellido"
                                name="apellido"
                                required>

                            <div
                                class="error-message"
                                id="error-apellido">
                            </div>

                        </div>


                        <!-- Fecha -->

                        <div class="mb-3">

                            <label
                                for="fecha_nacimiento"
                                class="form-label">

                                Fecha de Nacimiento

                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="fecha_nacimiento"
                                name="fecha_nacimiento"
                                required>

                            <div
                                class="error-message"
                                id="error-fecha_nacimiento">
                            </div>

                        </div>


                        <!-- Sexo -->

                        <div class="mb-4">

                            <label
                                for="sexo"
                                class="form-label">

                                Sexo

                            </label>

                            <select
                                class="form-select"
                                id="sexo"
                                name="sexo"
                                required>

                                <option value="">
                                    Seleccione...
                                </option>

                                <option value="1">
                                    Masculino
                                </option>

                                <option value="2">
                                    Femenino
                                </option>

                            </select>

                            <div
                                class="error-message"
                                id="error-sexo">
                            </div>

                        </div>


                        <button
                            type="button"
                            class="btn btn-primary w-100"
                            onclick="validarPaso(1)">

                            Continuar

                            <i class="fa-solid fa-arrow-right ms-2"></i>

                        </button>

                    </div>


                    <!-- ================================================= -->
                    <!-- PASO 2 -->
                    <!-- ================================================= -->

                    <div
                        class="pagina"
                        id="pagina2">


                        <h6 class="fw-semibold mb-3">
                            Datos de Usuario
                        </h6>


                        <!-- Usuario -->

                        <div class="mb-3">

                            <label
                                for="nombre_usuario"
                                class="form-label">

                                Nombre de Usuario

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nombre_usuario"
                                name="nombre_usuario"
                                required>

                            <div
                                class="error-message"
                                id="error-nombre_usuario">
                            </div>

                        </div>


                        <!-- Email -->

                        <div class="mb-3">

                            <label
                                for="email"
                                class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required>

                            <div
                                class="error-message"
                                id="error-email">
                            </div>

                        </div>


                        <!-- Password -->

                        <div class="mb-4">

                            <label
                                for="password"
                                class="form-label">

                                Contraseña

                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                                minlength="6">

                            <div
                                class="error-message"
                                id="error-password">
                            </div>

                        </div>


                        <input
                            type="hidden"
                            name="perfil_id_perfil"
                            value="2">


                        <div
                            class="d-flex justify-content-between">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="mostrarPaso(1)">

                                <i class="fa-solid fa-arrow-left me-2"></i>

                                Anterior

                            </button>


                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa-solid fa-user-plus me-2"></i>

                                Registrarse

                            </button>

                        </div>

                    </div>

                </form>

            </div>


            <div class="modal-footer">

                <button
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">

                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>