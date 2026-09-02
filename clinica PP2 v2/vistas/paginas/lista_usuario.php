<?php
require_once __DIR__ . '/../../modelos/usuarios.php';
$u = new Usuario();

$lista_usuarios = $u->total_usuarios();
$total_registros = $u->cantidad_usuarios();

?>

<div class="container-fluid">

    <!-- Encabezado -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Gestión de Usuarios
        </h2>

        <p class="text-muted mb-0">
            Administración de cuentas, perfiles y accesos al sistema.
        </p>
    </div>

    <div class="row g-4">

        <!-- LISTADO -->
        <div class="col-12">

            <!-- BUSCADOR/FILTROS -->
            <div class="card border-0 shadow-sm rounded-4 mb-3">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                        <div>
                            <h5 class="mb-0 fw-semibold">
                                Usuarios Registrados
                            </h5>

                            <small class="text-muted">
                                Consulta y administración de usuarios del sistema.
                            </small>
                        </div>

                        <div class="d-flex gap-2">

                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasNuevoUsuario"
                                aria-controls="offcanvasNuevoUsuario">

                                <i class="fa-solid fa-user-plus me-2"></i>
                                Nuevo Usuario

                            </button>

                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#filtrosAvanzados">

                                <i class="fa-solid fa-sliders me-2"></i>
                                Filtros

                            </button>

                        </div>

                    </div>

                    <!-- Buscador -->
                    <div class="row g-3">
                        <div class="position-relative">
                            <input
                                type="text"
                                class="form-control pe-5"
                                name="campo"
                                id="campo"
                                placeholder="Buscar por nombre, apellido, usuario o email">

                            <button
                                type="button"
                                id="limpiarBusqueda"
                                class="btn btn-sm border-0 position-absolute top-50 end-0 translate-middle-y me-2 d-none text-secondar"
                                title="Limpiar búsqueda">

                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filtros Avanzados -->
                    <div class="collapse mt-4" id="filtrosAvanzados">

                        <hr>

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Perfil
                                </label>

                                <select class="form-select">
                                    <option selected>Todos</option>
                                    <option>Administrador</option>
                                    <option>Doctor</option>
                                    <option>Paciente</option>
                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Estado
                                </label>

                                <select class="form-select">
                                    <option selected>Todos</option>
                                    <option>Activo</option>
                                    <option>Inactivo</option>
                                    <option>Bloqueado</option>
                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Sexo
                                </label>

                                <select class="form-select">
                                    <option selected>Todos</option>
                                    <option>Masculino</option>
                                    <option>Femenino</option>
                                    <option>Otro</option>
                                </select>

                            </div>

                            <div class="col-12">

                                <div class="d-flex justify-content-end gap-2">

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary">
                                        Limpiar
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-primary">
                                        Aplicar Filtros
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div id="contenedorTablaUsuarios">
                <!-- TABLA -->
                <div class="card border-0 shadow-sm rounded-4" id="tablita">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-semibold mb-0">
                                    Listado General
                                </h6>

                                <small class="text-muted px-5">
                                    Total usuarios en sistema: <?php echo $total_registros; ?>
                                </small>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                <div class="row g-4">
                                    <div class="col-auto">
                                        <label for="campo" class="col-form-label">Mostrar:</label>
                                    </div>
                                    <div class="col-auto">
                                        <select name="num_registros" id="num_registros" class="form-select">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-outline-success btn-sm">

                                    <i class="fa-solid fa-file-excel me-1"></i>
                                    Exportar Excel
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-outline-danger btn-sm">

                                    <i class="fa-solid fa-file-pdf me-1"></i>
                                    Exportar PDF
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table table-hover align-middle">

                                <thead class="table-light">

                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Perfil</th>
                                        <th>Estado</th>
                                        <th>Última Conexión</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>

                                </thead>
                                <!-- TABLA QUE SE REESCRIBE -->
                                <tbody id="contenido">
                                </tbody>

                            </table>
                        </div>
                        <!-- ================================================= -->
                        <!-- MODAL MODIFICAR USUARIO -->
                        <!-- ================================================= -->

                        <div class="modal fade" id="modalModificarUsuario" tabindex="-1">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title">
                                            Modificar usuario
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>

                                    </div>

                                    <form id="formModificarUsuario">

                                        <div class="modal-body">

                                            <!-- ACCIÓN -->
                                            <input
                                                type="hidden"
                                                name="action"
                                                value="actualizacion">

                                            <!-- ID USUARIO -->
                                            <input
                                                type="hidden"
                                                id="editar_id_usuario"
                                                name="id_usuario">

                                            <!-- ID PERSONA -->
                                            <input
                                                type="hidden"
                                                id="editar_id_persona"
                                                name="id_persona">


                                            <!-- NOMBRE DE USUARIO -->

                                            <div class="mb-3">

                                                <label
                                                    for="editar_nombre_usuario"
                                                    class="form-label">

                                                    Nombre de usuario

                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="editar_nombre_usuario"
                                                    name="nombre_usuario"
                                                    required>

                                            </div>


                                            <!-- EMAIL -->

                                            <div class="mb-3">

                                                <label
                                                    for="editar_email"
                                                    class="form-label">

                                                    Email

                                                </label>

                                                <input
                                                    type="email"
                                                    class="form-control"
                                                    id="editar_email"
                                                    name="email"
                                                    required>

                                            </div>


                                            <!-- NOMBRE -->

                                            <div class="mb-3">

                                                <label
                                                    for="editar_nombre"
                                                    class="form-label">

                                                    Nombre

                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="editar_nombre"
                                                    name="nombre"
                                                    required>

                                            </div>


                                            <!-- APELLIDO -->

                                            <div class="mb-3">

                                                <label
                                                    for="editar_apellido"
                                                    class="form-label">

                                                    Apellido

                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="editar_apellido"
                                                    name="apellido"
                                                    required>

                                            </div>

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">

                                                Cancelar

                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-primary">

                                                Guardar cambios

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>



                        <!-- PAGINACIÓN BOOTSTRAP (LOS NUMERITOS DE ABAJO DEL LISTADO) -->
                        <div class="row">
                            <div class="col-6">
                                <label id="lbl-total"></label>
                            </div>
                            <div class="col-6" id="nav_paginacion">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- OFFCANVAS -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNuevoUsuario" aria-labelledby="offcanvasNuevoUsuarioLabel">
        <div class="offcanvas-header border-bottom">
            <div>
                <h5 class="offcanvas-title fw-semibold" id="offcanvasNuevoUsuarioLabel">
                    Nuevo Usuario
                </h5>

                <small class="text-muted">
                    Complete los datos para registrar una nueva cuenta.
                </small>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
            </button>
        </div>

        <div class="offcanvas-body">
            <form method="POST" action="controladores/usuarios/usuarios_controlador.php" id="formNuevoUsuario">
                <input
                    type="hidden"
                    name="action"
                    value="insertar">

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Apellido</label>
                    <input type="text" class="form-control" name="apellido" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Sexo</label>
                    <select class="form-select" name="sexo" required>
                        <option value="1">Masculino</option>
                        <option value="2">Femenino</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Nombre de Usuario</label>
                    <input type="text" class="form-control" name="nombre_usuario" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Contraseña</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Perfil</label>
                    <select class="form-select" id="perfil" name="perfil_id_perfil" onchange="toggleDoctorFields()">
                        <option value="1">Administrador</option>
                        <option value="2">Doctor</option>
                        <option value="3" selected>Paciente</option>
                    </select>
                </div>

                <!-- Campos extra si el perfil es Doctor -->
                <div id="doctorFields" style="display:none;">
                    <div class="col-12 col-md-6 col-lg-20">
                        <label>Número de Matrícula Profesional</label>
                        <input type="text" class="form-control" name="numero_matricula_profesional">
                    </div>

                    <div class="col-12 col-md-6 col-lg-20">
                        <label>Precio Consulta</label>
                        <input type="number" class="form-control" name="precio_consulta" step="0.01">
                    </div>
                </div>

                <div class="d-grid">
                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Guardar Usuario
                    </button>
                </div>
            </form>

        </div>

    </div>
    <script src="assets/js/validaciones/lista_usuario/usuarios_buscador.js"></script>
    <script src="assets/js/validaciones/usuarios.js"></script>
    <script src="assets/js/validaciones/lista_usuario/lista_usuarios.js"></script>
    <script>
        function toggleDoctorFields() {
            const perfil = document.getElementById("perfil").value;
            document.getElementById("doctorFields").style.display = (perfil == "2") ? "block" : "none";
        }
        window.onload = toggleDoctorFields;
    </script>