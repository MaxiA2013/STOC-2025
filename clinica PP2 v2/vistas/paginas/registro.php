<?php
$is_admin = isset($_SESSION['nombre_perfil']) && $_SESSION['nombre_perfil'] === 'Administrador';
?>

<div class="row">
    <div class="col" style="border: solid 1px; background-color: rgb(111, 111, 229);"></div>

    <div class="col-6">
        <form method="POST" action="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/clinica PP2 v2/controladores/login.controlador.php'; ?>">

            <input type="hidden" name="action" value="registro" />

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

            <?php if ($is_admin): ?> 
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
                        <label for="precio_consulta" class="form-label">Precio de Consulta</label>
                        <input type="number" class="form-control" id="precio_consulta" name="precio_consulta" placeholder="Ingrese el precio_consulta" step="0.01">
                    </div>
                </div>
            <?php else: ?>
                <input type="hidden" name="perfil_id_perfil" value="3">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
    </div>

    <div class="col" style="border: solid 1px; background-color: rgb(111, 111, 229);"></div>
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
