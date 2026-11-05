<?php
require_once __DIR__ . "/../../modelos/conexion.php";
require_once __DIR__ . "/../../modelos/doctor.php";

$doctor = new Doctor();
$doctores = $doctor->all_doctores();

// Obtener usuarios disponibles para asignar doctor
$users = new Conexion();

//$usuariosDisponibles = $users->consultar("SELECT * FROM doctor;");

$resUsuariosModal = $users->consultar("
    SELECT 
        u.id_usuario,
        p.nombre,
        p.apellido
    FROM usuario u
    JOIN persona p 
        ON u.persona_id_persona = p.id_persona
");

?>

<div class="container-fluid">

    <!-- ENCABEZADO -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Gestión de Doctores
        </h2>

        <p class="text-muted mb-0">
            Administración de profesionales, matrículas y datos de consulta.
        </p>
    </div>

    <!-- BUSCARDOR / FILTROS -->
    <div class="card border-0 shadow-sm rounded-4 mb-3">

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h5 class="fw-semibold mb-1">
                            Registrar Doctor
                        </h5>

                        <small class="text-muted">
                            Registra un usuario existente como doctor.
                        </small>
                    </div>
                </div>

                <div class="d-flex gap-2">

                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNuevoDoctor"
                        aria-controls="offcanvasNuevoDoctor">

                        <i class="fa-solid fa-user-plus me-2"></i>
                        Nuevo Doctor
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
            <div class="mb-4">
                <div class="row g-3">
                    <div class="position-relative">
                        <input
                            type="text"
                            class="form-control"
                            id="campo"
                            name="campo"
                            placeholder="Buscar por nombre, apellido, matrícula o usuario">

                        <button
                            type="button"
                            id="limpiarBusqueda"
                            class="btn btn-sm border-0 position-absolute top-50 end-0 translate-middle-y me-2 d-none text-secondary"
                            title="Limpiar búsqueda">

                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filtros Avanzados -->
            <div class="collapse mt-4" id="filtrosAvanzados">
                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Obra Social
                        </label>

                        <select class="form-select">
                            <option selected>Todos</option>
                            <option>Avalian</option>
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

    <!-- TABLA DE DOCTORES -->
    <div id="contenedorTablaDoctores">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <!-- ENCABEZADO DEL LISTADO -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h5 class="fw-semibold mb-1">
                            Doctores Registrados
                        </h5>

                        <small class="text-muted">
                            Consulta y administración de profesionales registrados.
                        </small>
                    </div>

                    <div class="d-flex gap-2 align-items-center">
                        <div class="row g-4 mb-3">
                            <div class="col-auto">
                                <label
                                    for="num_registros"
                                    class="col-form-label">

                                    Mostrar:
                                </label>
                            </div>

                            <div class="col-auto">
                                <select
                                    name="num_registros"
                                    id="num_registros"
                                    class="form-select">

                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <!-- EXPORTAR -->
                        <div class="d-flex gap-2">
                            <a
                                class="btn btn-outline-success btn-sm"
                                href="controladores/generar_excel.php"
                                role="button">

                                <i class="fa-solid fa-file-excel me-1"></i>
                                Exportar Excel
                            </a>

                            <button
                                type="button"
                                class="btn btn-outline-danger btn-sm">

                                <i class="fa-solid fa-file-pdf me-1"></i>
                                Exportar PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- TABLA -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Usuario</th>
                                <th>Precio de Consulta</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="contenidoDoc">
                        </tbody>
                    </table>

                    <!-- PAGINACION -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label id="lbl-total"></label>
                        </div>

                        <div
                            class="col-md-6"
                            id="nav_paginacion">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- OFFCANVAS -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNuevoDoctor" aria-labelledby="offcanvasNuevoDoctorLabel">
        <div class="offcanvas-header border-bottom">
            <div>
                <h5
                    class="offcanvas-title fw-semibold"
                    id="offcanvasNuevoDoctorLabel">

                    Nuevo Usuario
                </h5>

                <small class="text-muted">
                    Complete los datos para registrar una nueva cuenta.
                </small>
            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas">
            </button>
        </div>

        <div class="offcanvas-body">
            <form
                method="POST"
                action="controladores/doctores/doctores_controlador.php">
                <input
                    type="hidden"
                    name="action"
                    value="insertar">

                <!-- REGISTRO DE DOCTOR -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <form
                            id="formDoctor"
                            action="controladores/doctores/doctor_controlador.php"
                            method="POST">

                            <input type="hidden" name="action" value="guardar_doctor">
                            <div class="row g-3">
                                <!-- Matrícula -->
                                <div class="mb-3">
                                    <label
                                        for="numero_matricula_profesional"
                                        class="form-label">

                                        Número de Matrícula
                                    </label>

                                    <input
                                        type="text"
                                        id="numero_matricula_profesional"
                                        name="numero_matricula_profesional"
                                        class="form-control"
                                        placeholder="Ingrese la matrícula"
                                        required>
                                </div>

                                <!-- Precio -->
                                <div class="mb-3">
                                    <label
                                        for="precio_consulta"
                                        class="form-label">

                                        Precio de Consulta
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            $
                                        </span>

                                        <input
                                            type="number"
                                            id="precio_consulta"
                                            name="precio_consulta"
                                            step="0.01"
                                            class="form-control"
                                            placeholder="0.00"
                                            required>
                                    </div>
                                </div>

                                <!-- Usuario -->
                                <div class="mb-3">

                                    <label
                                        for="usuario_id_usuario"
                                        class="form-label">

                                        Usuario
                                    </label>

                                    <select
                                        id="usuario_id_usuario"
                                        name="usuario_id_usuario"
                                        class="form-control"
                                        required>

                                        <option value="">
                                            Seleccione un usuario
                                        </option>

                                        <option value="new_user">
                                            ¿Usuario no registrado?
                                        </option>

                                        <?php
                                        if (isset($usuariosDisponibles) && $usuariosDisponibles && $doctores->num_rows > 0) :

                                            while ($u = $usuariosDisponibles->fetch_assoc()) :

                                                $texto =
                                                    $u['nombre']
                                                    . ' '
                                                    . $u['apellido']
                                                    . ' ('
                                                    . $u['nombre_usuario']
                                                    . ')';

                                                $perfiles = trim($u['perfiles']);

                                                if (!empty($perfiles)) {
                                                    $texto .= ' - ' . $perfiles;
                                                }

                                        ?>

                                                <option
                                                    value="<?= $u['id_usuario'] ?>">
                                                    <?= htmlentities($texto) ?>
                                                </option>

                                        <?php
                                            endwhile;

                                        endif;
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <!-- Botón -->
                            <div class="mb-3">
                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="fa-solid fa-user-plus me-2"></i>
                                    Registrar Doctor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


        </div>

    </div>

    <?php
    require_once __DIR__ . '../../componentes/modal_multipasos_usuarios.php';
    ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sweetalert2@11.js"></script>
    <script src="assets/js/validaciones/lista_doctor/select&Ajax.js"></script>
    <script src="assets/js/validaciones/form_multipasos.js"></script>
    <script src="assets/js/validaciones/lista_doctor/doctores_buscador.js"></script>