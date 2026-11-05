<?php
include_once "modelos/obra_social.php";

$obra_social = new Obra_Social();
$lista_obra_social = $obra_social->consultarVariasObrasSociales();
?>

<div class="container-fluid py-4 px-4">

    <!-- Encabezado -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">

                <h2 class="fw-semibold mb-0">
                    Gestión de Obras Sociales
                </h2>
            </div>

            <p class="text-muted mb-0">
                Administra las obras sociales disponibles en el sistema.
            </p>
        </div>

        <!-- Botón nueva obra social -->
        <button
            type="button"
            class="btn btn-primary d-flex align-items-center gap-2 px-3"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevaObraSocial"
            aria-controls="offcanvasNuevaObraSocial">

            <i class="fa-solid fa-plus"></i>
            Nueva obra social

        </button>

    </div>


    <!-- Contenedor principal -->
    <div class="card border-0 shadow-sm">

        <!-- Cabecera -->
        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Obras sociales registradas
                    </h5>

                    <small class="text-muted">
                        Consulta y administra las obras sociales disponibles.
                    </small>
                </div>

                <!-- Buscador -->
                <div class="input-group" style="max-width: 320px;">

                    <span class="input-group-text bg-white">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control"
                        id="buscarObraSocial"
                        placeholder="Buscar obra social..."
                        autocomplete="off">

                </div>

            </div>

        </div>


        <!-- Tabla -->
        <div class="card-body pt-0">

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                    id="tablaObrasSociales">

                    <thead class="table-light">

                        <tr>
                            <th scope="col" class="text-muted small">
                                ID
                            </th>

                            <th scope="col" class="text-muted small">
                                Obra social
                            </th>

                            <th scope="col" class="text-muted small">
                                Detalle
                            </th>

                            <th scope="col" class="text-muted small text-center">
                                Acciones
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($lista_obra_social)) { ?>

                            <?php foreach ($lista_obra_social as $row) { ?>

                                <tr class="fila-obra-social">

                                    <!-- ID -->
                                    <td class="fw-semibold text-muted">

                                        <?php
                                        echo htmlspecialchars($row['id_obra_social']);
                                        ?>

                                    </td>


                                    <!-- Obra social -->
                                    <td>

                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold">

                                                <?php
                                                echo htmlspecialchars($row['nombre_obra_social']);
                                                ?>

                                            </span>

                                        </div>

                                    </td>


                                    <!-- Detalle -->
                                    <td class="text-muted">

                                        <?php
                                        echo htmlspecialchars($row['detalle']);
                                        ?>

                                    </td>


                                    <!-- Acciones -->
                                    <td>

                                        <div class="d-flex justify-content-center gap-2">

                                            <!-- Editar -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal<?php echo $row['id_obra_social']; ?>"
                                                title="Editar obra social">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- Eliminar -->
                                            <form
                                                action="controladores/obra_social_controlador.php"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Está seguro de eliminar esta obra social?');">

                                                <input
                                                    type="hidden"
                                                    name="id_obra_social"
                                                    value="<?php echo htmlspecialchars($row['id_obra_social']); ?>">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="eliminacion">

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar obra social">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                <!-- Modal editar -->
                                <div
                                    class="modal fade"
                                    id="modal<?php echo $row['id_obra_social']; ?>"
                                    tabindex="-1"
                                    aria-labelledby="modalLabel<?php echo $row['id_obra_social']; ?>"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 shadow">

                                            <div class="modal-header">

                                                <div>

                                                    <h5
                                                        class="modal-title fw-semibold"
                                                        id="modalLabel<?php echo $row['id_obra_social']; ?>">

                                                        Modificar obra social

                                                    </h5>

                                                    <small class="text-muted">
                                                        Actualiza la información de la obra social.
                                                    </small>

                                                </div>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Cerrar">
                                                </button>

                                            </div>


                                            <form
                                                class="needs-validation"
                                                novalidate
                                                action="controladores/obra_social_controlador.php"
                                                method="post">

                                                <div class="modal-body">

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="actualizacion">

                                                    <input
                                                        type="hidden"
                                                        name="id_obra_social"
                                                        value="<?php echo htmlspecialchars($row['id_obra_social']); ?>">


                                                    <!-- Obra social -->
                                                    <div class="mb-3">

                                                        <label
                                                            for="nombre_obra_social<?php echo $row['id_obra_social']; ?>"
                                                            class="form-label fw-semibold">

                                                            Obra social

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="nombre_obra_social<?php echo $row['id_obra_social']; ?>"
                                                            name="nombre_obra_social"
                                                            value="<?php echo htmlspecialchars($row['nombre_obra_social']); ?>"
                                                            required>

                                                        <div class="invalid-feedback">
                                                            Campo Obra Social vacío.
                                                        </div>

                                                    </div>


                                                    <!-- Detalle -->
                                                    <div class="mb-3">

                                                        <label
                                                            for="detalle<?php echo $row['id_obra_social']; ?>"
                                                            class="form-label fw-semibold">

                                                            Detalle

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="detalle<?php echo $row['id_obra_social']; ?>"
                                                            name="detalle"
                                                            value="<?php echo htmlspecialchars($row['detalle']); ?>"
                                                            required>

                                                        <div class="invalid-feedback">
                                                            Campo Detalle vacío.
                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="modal-footer">

                                                    <button
                                                        type="button"
                                                        class="btn btn-light"
                                                        data-bs-dismiss="modal">

                                                        Cerrar

                                                    </button>

                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary d-flex align-items-center gap-2">

                                                        <i class="fa-solid fa-floppy-disk"></i>
                                                        Guardar cambios

                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4">

                                    <i class="fa-solid fa-hospital fs-4 mb-2 d-block"></i>

                                    No hay obras sociales registradas.

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>


            <!-- Sin resultados de búsqueda -->
            <div
                id="sinResultadosObraSocial"
                class="text-center text-muted py-4 d-none">

                <i class="fa-solid fa-magnifying-glass fs-4 mb-2 d-block"></i>

                No se encontraron obras sociales que coincidan con la búsqueda.

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     OFFCANVAS - NUEVA OBRA SOCIAL
========================================================= -->
<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevaObraSocial"
    aria-labelledby="offcanvasNuevaObraSocialLabel">

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevaObraSocialLabel">

                Nueva obra social

            </h5>

            <small class="text-muted">
                Registra una nueva obra social en el sistema.
            </small>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar">
        </button>

    </div>


    <div class="offcanvas-body">

        <form class="needs-validation" novalidate method="post" action="controladores/obra_social_controlador.php"> <input type="hidden" name="action" value="insertar"> <!-- OBRA SOCIAL -->
            
            <div class="mb-4"> 
                <label for="nombre_obra_social" class="form-label fw-semibold"> Obra Social </label> 
                <input type="text" class="form-control" id="nombre_obra_social" name="nombre_obra_social" placeholder="Ingrese el nombre de la obra social" required>
                <div class="invalid-feedback"> Ingrese el nombre de la obra social. </div>
            </div> 

            <!-- DETALLE -->
            <div class="mb-4"> 
                <label for="detalle" class="form-label fw-semibold"> Detalle </label> 
                <textarea class="form-control" id="detalle" name="detalle" rows="4" placeholder="Ingrese información adicional" required></textarea>
                <div class="invalid-feedback"> Ingrese un detalle para la obra social. </div>
            </div> 

            <!-- INFORMACIÓN -->
            <div class="alert alert-light border rounded-3">
                <div class="d-flex"> 
                    <i class="fa-solid fa-circle-info text-primary mt-1 me-2"></i> 
                    <small class="text-muted"> La obra social estará disponible para ser seleccionada en los formularios correspondientes del sistema. </small> 
                </div>

            </div> 

            <!-- BOTÓN -->
            <div class="d-grid mt-4">
                 <button type="submit" class="btn btn-primary"> 
                    <i class="fa-solid fa-floppy-disk me-2"></i> Registrar Obra Social 
                </button> 

            </div>
        </form>

    </div>

</div>

<script src="assets/js/validaciones/validaciones_controlador.js"></script>
<!-- =========================================================
     BUSCADOR
========================================================= -->

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const buscador = document.getElementById("buscarObraSocial");
        const filas = document.querySelectorAll(".fila-obra-social");
        const mensajeSinResultados = document.getElementById("sinResultadosObraSocial");

        if (!buscador) {
            return;
        }

        buscador.addEventListener("keyup", function() {

            const texto = this.value.toLowerCase().trim();

            let encontrados = 0;

            filas.forEach(function(fila) {

                const contenido = fila.textContent.toLowerCase();

                if (contenido.includes(texto)) {

                    fila.style.display = "";
                    encontrados++;

                } else {

                    fila.style.display = "none";

                }

            });

            if (mensajeSinResultados) {

                if (encontrados === 0 && texto !== "") {

                    mensajeSinResultados.classList.remove("d-none");

                } else {

                    mensajeSinResultados.classList.add("d-none");

                }

            }

        });

    });
</script>