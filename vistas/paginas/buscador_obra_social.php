<?php
// Enlazamos al modelo que acabamos de terminar en los pasos anteriores
require_once __DIR__ . '../../../modelos/obraSocialPrestacion.php';
$osp = new ObraSocialPrestacion();
$total_registros = $osp->cantidad_coberturas();
?>

<div class="container-fluid">

    <!-- Encabezado de la Sección -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Comparador de Obras Sociales
        </h2>
        <p class="text-muted mb-0">
            Administración de coberturas, códigos de prestaciones, precios y estados.
        </p>
    </div>

    <div class="row g-4">

        <!-- SECCIÓN DE LISTADO Y BUSCADOR -->
        <div class="col-12">
            <!-- FILTROS Y BUSCADOR -->
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="mb-0 fw-semibold">
                                Coberturas Registradas
                            </h5>
                            <small class="text-muted">
                                Consulta y administración de aranceles por obra social.
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <!-- Botón que despliega el panel lateral para dar de alta -->
                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasNuevaCobertura"
                                aria-controls="offcanvasNuevaCobertura">
                                <i class="fa-solid fa-plus me-2"></i>
                                Nueva Cobertura
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

                    <!-- Input de Búsqueda Dinámica -->
                    <div class="row g-3">
                        <div class="position-relative">
                            <input
                                type="text"
                                class="form-control pe-5"
                                name="campo"
                                id="campo"
                                placeholder="Buscar por código, prestación médica u obra social">

                            <button
                                type="button"
                                id="limpiarBusqueda"
                                class="btn btn-sm border-0 position-absolute top-50 end-0 translate-middle-y me-2 d-none text-secondary"
                                title="Limpiar búsqueda">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filtros Desplegables -->
                    <div class="collapse mt-4" id="filtrosAvanzados">
                        <hr>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Estado</label>
                                <select class="form-select" id="filtro_estado">
                                    <option value="todos" selected>Todos</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="btnLimpiarFiltros">
                                        Limpiar
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btnAplicarFiltros">
                                        Aplicar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CONTENEDOR DE LA TABLA PRINCIPAL -->
            <div class="card border-0 shadow-sm rounded-4" id="tablita">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-semibold mb-0">Listado General</h6>
                            <small class="text-muted px-5">
                                Total registros: <span id="total_regs"><?php echo $total_registros; ?></span>
                            </small>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <div class="row g-4">
                                <div class="col-auto">
                                    <label for="num_registros" class="col-form-label">Mostrar:</label>
                                </div>
                                <div class="col-auto">
                                    <select name="num_registros" id="num_registros" class="form-select">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estructura de la Tabla -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Prestación Médica</th>
                                    <th>Obra Social</th>
                                    <th>Precio ($)</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <!-- El código JavaScript escribirá las filas dentro de este tbody -->
                            <tbody id="contenido"></tbody>
                        </table>
                    </div>

                    <!-- Indicadores Inferiores y Botones de Paginado -->
                    <div class="row mt-3">
                        <div class="col-6">
                            <label id="lbl-total"></label>
                        </div>
                        <div class="col-6" id="nav_paginacion"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- ================================================= -->
<!-- MODAL DE EDICIÓN / MODIFICAR COBERTURA -->
<!-- ================================================= -->
<div class="modal fade" id="modalModificarCobertura" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modificar Cobertura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formModificarCobertura">
                <div class="modal-body">
                    <!-- Parámetros Ocultos Requeridos por el Controlador -->
                    <input type="hidden" name="action" value="actualizacion">
                    <input type="hidden" id="editar_id_obra_social" name="id_obra_social">
                    <input type="hidden" id="editar_id_prestacion" name="id_prestacion">

                    <div class="mb-3">
                        <label class="form-label">Código Arancelario</label>
                        <input type="text" class="form-control" id="editar_codigo" name="codigo" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio Cobertura ($)</label>
                        <input type="number" class="form-control" id="editar_precio" name="precio" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" id="editar_estado" name="estado" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ================================================= -->
<!-- OFFCANVAS / FORMULARIO DE ALTA -->
<!-- ================================================= -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNuevaCobertura">
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title fw-semibold">Nueva Cobertura</h5>
            <small class="text-muted">Asigne códigos y precios a las prestaciones.</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form method="POST" action="controladores/obra_social_prestacion_controlador.php" id="formNuevaCobertura">
            <input type="hidden" name="action" value="insertar">

            <div class="mb-3">
                <label class="form-label">Obra Social</label>
                <select class="form-select" name="obra_social_id" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <?php 
                    $comboOS = $osp->listar_obras_sociales();
                    while($row = $comboOS->fetch_assoc()) {
                        echo '<option value="'.$row['id_obra_social'].'">'.$row['nombre_obra_social'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Prestación Médica</label>
                <select class="form-select" name="prestacion_id" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <?php 
                    $comboP = $osp->listar_prestaciones();
                    while($row = $comboP->fetch_assoc()) {
                        echo '<option value="'.$row['id_prestacion'].'">'.$row['nombre_prestacion'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Código Arancelario</label>
                <input type="text" class="form-control" name="codigo" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Precio ($)</label>
                <input type="number" class="form-control" name="precio" step="0.01" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado" required>
                    <option value="activo" selected>Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cobertura
                </button>
            </div>
        </form>
    </div>
</div>

<!-- <script src="assets/js/validaciones/lista_usuario/coberturas_buscador.js"></script> -->
<script src="assets/js/validaciones/lista_obra_social/coberturas_buscador.js"></script>
