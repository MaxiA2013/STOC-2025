<?php
include_once "modelos/documento.php";

$docu = new Documento();
$lista_documentos = $docu->consultarVariosDocumento();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Documentos</h2>
            <p>Ingresa un nuevo tipo de Documento</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <form class="needs-validation" novalidate method="post" action="controladores/documento_controlador.php">
                <input type="hidden" name="action" value="insertar">

                <div class="mb-3">
                    <label for="tipo_documento" class="form-label">Documento</label>
                    <input type="text" class="form-control" id="tipo_documento" name="tipo_documento" required>
                    <div class="invalid-feedback">Campo Documento vacío</div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                    <div class="invalid-feedback">Campo Descripción vacío</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Obligatorio</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="obligatorio" id="obligatorio_no" value="0" checked>
                        <label class="form-check-label" for="obligatorio_no">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="obligatorio" id="obligatorio_si" value="1">
                        <label class="form-check-label" for="obligatorio_si">Sí</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>

        <div class="col">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Documento</th>
                        <th>Descripción</th>
                        <th>Obligatorio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lista_documentos as $row): ?>
                    <tr>
                        <td><?= $row['id_documento'] ?></td>
                        <td><?= $row['tipo_documento'] ?></td>
                        <td><?= $row['descripcion'] ?></td>
                        <td><?= $row['obligatorio'] ? 'Sí' : 'No' ?></td>
                        <td>
                            <!-- Eliminar -->
                            <form action="controladores/documento_controlador.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_documento" value="<?= $row['id_documento'] ?>">
                                <input type="hidden" name="action" value="eliminacion">
                                <button type="submit">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                            <!-- Editar -->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?= $row['id_documento'] ?>">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modal<?= $row['id_documento'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="controladores/documento_controlador.php" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modificar Documento</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="actualizacion">
                                        <input type="hidden" name="id_documento" value="<?= $row['id_documento'] ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Documento</label>
                                            <input type="text" class="form-control" name="tipo_documento" value="<?= $row['tipo_documento'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Descripción</label>
                                            <input type="text" class="form-control" name="descripcion" value="<?= $row['descripcion'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Obligatorio</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="obligatorio" value="0" <?= $row['obligatorio'] == 0 ? 'checked' : '' ?>>
                                                <label class="form-check-label">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="obligatorio" value="1" <?= $row['obligatorio'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label">Sí</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-success">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="assets/js/validaciones/validaciones_controlador.js"></script>