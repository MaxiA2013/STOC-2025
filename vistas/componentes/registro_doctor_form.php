<!-- Modal multipasos -->
    <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="modalNuevoUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="../controladores/login.controlador.php">
                    <input type="hidden" name="action" value="registro">
                    <input type="hidden" name="perfil_id_perfil" value="2"><!-- 🔒 Forzado a Doctor -->

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNuevoUsuarioLabel">Registrar nuevo Doctor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Paso 1 -->
                        <div class="wizard-step active" id="step1">
                            <h5>Datos Personales</h5>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="fecha_nacimiento" required>
                            </div>
                            <div class="mb-3">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select class="form-select" name="sexo" required>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                    <option value="3">Otro</option>
                                </select>
                            </div>
                        </div>

                        <!-- Paso 2 -->
                        <div class="wizard-step" id="step2">
                            <h5>Datos de Usuario</h5>
                            <div class="mb-3">
                                <label for="nombre_usuario" class="form-label">Nombre Usuario</label>
                                <input type="text" class="form-control" name="nombre_usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <!-- Paso 3 -->
                        <div class="wizard-step" id="step3">
                            <h5>Datos Profesionales</h5>
                            <div class="mb-3">
                                <label for="numero_matricula_profesional" class="form-label">Número de Matrícula Profesional</label>
                                <input type="text" class="form-control" name="numero_matricula_profesional" required>
                            </div>
                            <div class="mb-3">
                                <label for="salario" class="form-label">Salario</label>
                                <input type="number" step="0.01" class="form-control" name="salario">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="prevBtn" class="btn btn-secondary">Anterior</button>
                        <button type="button" id="nextBtn" class="btn btn-primary">Siguiente</button>
                        <button type="submit" id="submitBtn" class="btn btn-success d-none">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
         // Wizard multipasos
        let currentStep = 1;
        const totalSteps = 3;

        document.getElementById("prevBtn").addEventListener("click", () => {
            if (currentStep > 1) {
                document.getElementById("step" + currentStep).classList.remove("active");
                currentStep--;
                document.getElementById("step" + currentStep).classList.add("active");
                toggleButtons();
            }
        });

        document.getElementById("nextBtn").addEventListener("click", () => {
            if (currentStep < totalSteps) {
                document.getElementById("step" + currentStep).classList.remove("active");
                currentStep++;
                document.getElementById("step" + currentStep).classList.add("active");
                toggleButtons();
            }
        });

        function toggleButtons() {
            document.getElementById("prevBtn").style.display = (currentStep === 1) ? "none" : "inline-block";
            document.getElementById("nextBtn").style.display = (currentStep === totalSteps) ? "none" : "inline-block";
            document.getElementById("submitBtn").classList.toggle("d-none", currentStep !== totalSteps);
        }

        toggleButtons();
    </script>