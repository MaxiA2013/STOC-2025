<div class="row align-items-center g-lg-5 py-5">

    <div class="col-md-10 mx-auto col-lg-5" style="background-color: rgb(79, 100, 139); margin-top: 5%;">
        <form id="id_form" method="POST" action="controladores/login.controlador.php">
            <input type="hidden" name="action" value="login" />
            <div class="mb-3 mt-3">
                <label for="nombre_usuario" class="form-label">Nombre Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" placeholder="Ingrese el Nombre del Usuario" name="nombre_usuario">
                <p id="id_usuario_parrafo" style="color: red; display:none;">Usuario Requerido</p>
            </div>
            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Ingresar Correo Electrónico" name="email">
                <p id="id_email_parrafo" style="color: red; display:none;">Email Requerido</p>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" placeholder="Ingresar Contraseña" name="password">
                <p id="id_contraseña_parrafo" style="color: red; display:none;">Contraseña Requerido</p>
            </div>
            <div class="form-check mb-3" id="id_remember">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="remember"> Recordarme
                </label>
            </div>
            <div class="mb-3">
                <p style="color: white;">¿No tenés una cuenta?
                    <a href="vistas/paginas/registro.php" style="color: yellow; text-decoration: underline;">Registrate</a>
                </p>
            </div>

            <div class="d-grid gap-2">
                <button onclick="validate()" type="submit" class="btn btn-dark" >Entrar</button>
            </div>
        </form>
    </div>

    <div class="col col-lg-7 text-center text-lg-start">
        <h1 class="diplay-4 fw-bold 1h-1 text-body-emphasis mb-3">¡Agendá tu salud en un clic!</h1>
        <p class="col-lg-10 fs-4">Olvidate de las filas y los llamados eternos. 
            Iniciá sesión y reservá tu turno médico en segundos. 
            ¡Tu bienestar merece esta comodidad!</p>
    </div>

</div>

<script src="assets/js/validaciones/login.js"></script>