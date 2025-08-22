<?php
$is_admin = isset($_SESSION['nombre_perfil']) && $_SESSION['nombre_perfil'] === 'Administrador';
?>

<div class="row">
    <div class="col" style="border: solid 1px; background-color: rgb(111, 111, 229);"></div>

    <div class="col-6">
        <form method="POST" action="controladores/login.controlador.php">
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
                <!-- Si es administrador, puede elegir el perfil -->
                <div class="mb-3 mt-3">
                    <label for="perfil" class="form-label">Perfil</label>
                    <select class="form-select" id="perfil" name="perfil_id_perfil">
                        <option value="1">Administrador</option>
                        <option value="2" selected>Paciente</option>
                        <option value="3">Doctor</option>
                    </select>
                </div>
            <?php else: ?>
                <!-- Si no es administrador, se asigna automáticamente como Paciente -->
                <input type="hidden" name="perfil_id_perfil" value="1">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
    </div>

    <div class="col" style="border: solid 1px; background-color: rgb(111, 111, 229);"></div>
</div>
