<?php
require_once "modelos/paciente.php";

$paciente = new Paciente();
$resultado = $paciente->listarPacientes();
?>

<div class="container-fluid">

    <!-- ENCABEZADO -->
    <div class="mb-4">

        <h2 class="fw-bold mb-1">
            Gestión de Pacientes
        </h2>

        <p class="text-muted mb-0">
            Administración y consulta de los pacientes registrados en el sistema.
        </p>

    </div>


    <!-- CONTENIDO PRINCIPAL -->
    <div class="row g-4">

        <div class="col-12">


            <!-- BUSCADOR Y FILTROS -->
            <div class="card border-0 shadow-sm rounded-4 mb-3">

                <div class="card-body">

                    <!-- ENCABEZADO DEL LISTADO -->
                    <div
                        class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                        <div>

                            <h5 class="mb-0 fw-semibold">
                                Pacientes Registrados
                            </h5>

                            <small class="text-muted">
                                Consulta y administración de pacientes del sistema.
                            </small>

                        </div>


                        <div class="d-flex gap-2">

                            <!-- NUEVO PACIENTE -->
                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasNuevoPaciente"
                                aria-controls="offcanvasNuevoPaciente">

                                <i class="fa-solid fa-user-plus me-2"></i>
                                Nuevo Paciente

                            </button>


                            <!-- FILTROS -->
                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#filtrosAvanzadosPaciente">

                                <i class="fa-solid fa-sliders me-2"></i>
                                Filtros

                            </button>

                        </div>

                    </div>


                    <!-- BUSCADOR PRINCIPAL -->
                    <form>

                        <div class="row g-3">

                            <div class="col-lg-9">

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Buscar por nombre, apellido o usuario">

                            </div>


                            <div class="col-lg-3 d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="fa-solid fa-magnifying-glass me-2"></i>
                                    Buscar

                                </button>

                            </div>

                        </div>

                    </form>


                    <!-- FILTROS AVANZADOS -->
                    <div
                        class="collapse mt-4"
                        id="filtrosAvanzadosPaciente">

                        <hr>

                        <div class="row g-3">


                            <!-- SEXO -->
                            <div class="col-md-4">

                                <label class="form-label">
                                    Sexo
                                </label>

                                <select class="form-select">

                                    <option selected>
                                        Todos
                                    </option>

                                    <option>
                                        Masculino
                                    </option>

                                    <option>
                                        Femenino
                                    </option>

                                    <option>
                                        Otro
                                    </option>

                                </select>

                            </div>


                            <!-- ESTADO -->
                            <div class="col-md-4">

                                <label class="form-label">
                                    Estado
                                </label>

                                <select class="form-select">

                                    <option selected>
                                        Todos
                                    </option>

                                    <option>
                                        Activo
                                    </option>

                                    <option>
                                        Inactivo
                                    </option>

                                </select>

                            </div>


                            <!-- USUARIO -->
                            <div class="col-md-4">

                                <label class="form-label">
                                    Usuario
                                </label>

                                <select class="form-select">

                                    <option selected>
                                        Todos
                                    </option>

                                    <option>
                                        Con usuario
                                    </option>

                                    <option>
                                        Sin usuario
                                    </option>

                                </select>

                            </div>


                            <!-- FECHA DESDE -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Desde
                                </label>

                                <input
                                    type="date"
                                    class="form-control">

                            </div>


                            <!-- FECHA HASTA -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Hasta
                                </label>

                                <input
                                    type="date"
                                    class="form-control">

                            </div>


                            <!-- BOTONES -->
                            <div class="col-12">

                                <div
                                    class="d-flex justify-content-end gap-2">

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary">

                                        <i class="fa-solid fa-rotate-left me-1"></i>
                                        Limpiar

                                    </button>


                                    <button
                                        type="button"
                                        class="btn btn-primary">

                                        <i class="fa-solid fa-filter me-1"></i>
                                        Aplicar Filtros

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- TABLA -->
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">


                    <!-- CABECERA DE TABLA -->
                    <div
                        class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h6 class="fw-semibold mb-0">
                                Listado General
                            </h6>

                            <small class="text-muted">

                                Pacientes registrados en el sistema.

                            </small>

                        </div>


                        <!-- EXPORTACIONES -->
                        <div class="d-flex gap-2">

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



                    <!-- TABLA RESPONSIVE -->
                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        ID Paciente
                                    </th>

                                    <th>
                                        Nombre
                                    </th>

                                    <th>
                                        Apellido
                                    </th>

                                    <th>
                                        Usuario
                                    </th>

                                    <th class="text-center">
                                        Acciones
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php while ($row = $resultado->fetch_assoc()) { ?>

                                    <tr>


                                        <!-- ID -->
                                        <td>

                                            <span class="fw-semibold">

                                                <?php
                                                echo $row['id_paciente'];
                                                ?>

                                            </span>

                                        </td>


                                        <!-- NOMBRE -->
                                        <td>

                                            <?php
                                            echo $row['nombre'];
                                            ?>

                                        </td>


                                        <!-- APELLIDO -->
                                        <td>

                                            <?php
                                            echo $row['apellido'];
                                            ?>

                                        </td>


                                        <!-- USUARIO -->
                                        <td>

                                            <span
                                                class="badge bg-info-subtle text-dark border">

                                                <?php
                                                echo $row['nombre_usuario'];
                                                ?>

                                            </span>

                                        </td>


                                        <!-- ACCIONES -->
                                        <td class="text-center">

                                            <div
                                                class="d-flex justify-content-center gap-2">


                                                <!-- EDITAR -->
                                                <a
                                                    href="index.php?page=editar_paciente&id=<?php echo $row['id_paciente']; ?>"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Editar">

                                                    <i class="fa-solid fa-pen"></i>

                                                </a>


                                                <!-- ELIMINAR -->
                                                <a
                                                    href="controladores/paciente_controlador.php?action=eliminar&id=<?php echo $row['id_paciente']; ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar"
                                                    onclick="return confirm('¿Seguro que deseas eliminar este paciente?');">

                                                    <i class="fa-solid fa-trash"></i>

                                                </a>


                                                <!-- VER -->
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Ver detalles">

                                                    <i class="fa-solid fa-eye"></i>

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>



            <!-- PAGINACIÓN VISUAL -->
            <nav class="mt-4">

                <ul class="pagination justify-content-center">

                    <li class="page-item disabled">

                        <a
                            class="page-link">

                            Anterior

                        </a>

                    </li>


                    <li class="page-item active">

                        <span class="page-link">
                            1
                        </span>

                    </li>


                    <li class="page-item">

                        <a
                            class="page-link"
                            href="#">

                            2

                        </a>

                    </li>


                    <li class="page-item">

                        <a
                            class="page-link"
                            href="#">

                            3

                        </a>

                    </li>


                    <li class="page-item">

                        <a
                            class="page-link"
                            href="#">

                            Siguiente

                        </a>

                    </li>

                </ul>

            </nav>


        </div>

    </div>

</div>



<!-- OFFCANVAS NUEVO PACIENTE -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoPaciente"
    aria-labelledby="offcanvasNuevoPacienteLabel">


    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevoPacienteLabel">

                Nuevo Paciente

            </h5>

            <small class="text-muted">

                Complete los datos para registrar un nuevo paciente.

            </small>

        </div>


        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas">
        </button>

    </div>



    <div class="offcanvas-body">

        <!--
            FORMULARIO DEL PACIENTE

            Actualmente se deja preparado visualmente.
            Aquí puedes colocar posteriormente
            los campos correspondientes al modelo Paciente.
        -->

        <form>

            <div class="mb-3">

                <label
                    for="nombre_paciente"
                    class="form-label">

                    Nombre

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre_paciente"
                    name="nombre"
                    placeholder="Ingrese el nombre">

            </div>


            <div class="mb-3">

                <label
                    for="apellido_paciente"
                    class="form-label">

                    Apellido

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="apellido_paciente"
                    name="apellido"
                    placeholder="Ingrese el apellido">

            </div>


            <div class="mb-3">

                <label
                    for="fecha_nacimiento_paciente"
                    class="form-label">

                    Fecha de Nacimiento

                </label>

                <input
                    type="date"
                    class="form-control"
                    id="fecha_nacimiento_paciente"
                    name="fecha_nacimiento">

            </div>


            <div class="mb-3">

                <label
                    for="sexo_paciente"
                    class="form-label">

                    Sexo

                </label>

                <select
                    class="form-select"
                    id="sexo_paciente"
                    name="sexo">

                    <option value="">
                        Seleccione una opción
                    </option>

                    <option value="1">
                        Masculino
                    </option>

                    <option value="2">
                        Femenino
                    </option>

                    <option value="3">
                        Otro
                    </option>

                </select>

            </div>


            <div class="d-grid mt-4">

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="fa-solid fa-floppy-disk me-2"></i>

                    Guardar Paciente

                </button>

            </div>

        </form>

    </div>

</div>