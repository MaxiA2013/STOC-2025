<?php
require_once "../modulos/documento.php";

$con = new Documento();
$lista = $con->consultarVariosDocumento("");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Documento</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="mt-4 p-5 bg-primary text-white rounded">
        <h1>Bienvenido al Módulo de Documentos</h1>
        <p>Gestioná los documentos del sistema agregando, eliminando o modificando.</p>
    </div>
    <a href="../index.php">Regresar al Inicio</a> <!-- Link para la página principal -->

    <div class="container-fluid">
        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <!-- Tabla de Documentos -->
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Tipo de Documento</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $documento) { ?>
                                <tr>
                                    <td><?php echo $documento['id_documento'] ?></td>
                                    <td><?php echo $documento['tipo_documento'] ?></td>
                                    <td><?php echo $documento['descripcion'] ?></td>
                                    <td>
                                        <form class="needs-validation" action="../controladores/documento_controlador.php" method="post">
                                            <input type="hidden" name="id_documento" value="<?= $documento['id_documento'] ?>"> <!-- toma el id del documento -->
                                            <input type="hidden" name="accion" value="baja"> <!-- acción para borrar -->
                                            <button type="submit" class="btn btn-danger">Borrar</button> <!-- botón para eliminar -->
                                        </form>
                                    </td>
                                    <td>
                                        <!-- Botón para abrir el modal de modificación -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modificarModal" 
                                            data-id="<?php echo $documento['id_documento']; ?>" 
                                            data-tipo="<?php echo $documento['tipo_documento']; ?>" 
                                            data-descripcion="<?php echo $documento['descripcion']; ?>">
                                            Modificar
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="col">
                    <!-- Formulario para agregar un nuevo documento -->
                    <form class="needs-validation" action="../controladores/documento_controlador.php" method="post">
                        <div class="mb-3 mt-3">
                            <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                            <input type="text" class="form-control" id="tipo_documento" placeholder="Ingrese Tipo de Documento" name="tipo_documento" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_documento" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" id="descripcion_documento" placeholder="Ingrese Descripción" name="descripcion_documento" required>
                        </div>
                        <input type="hidden" name="accion" value="alta"> <!-- Acción para crear un nuevo documento -->
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para modificar -->
    <div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarModalLabel">Modificar Documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModificar" action="../controladores/documento_controlador.php" method="post">
                        <input type="hidden" id="id_documento" name="id_documento"> <!-- Campo oculto para el ID del documento -->
                        <div class="mb-3">
                            <label for="tipo_documento_mod" class="form-label">Tipo de Documento</label>
                            <input type="text" class="form-control" id="tipo_documento_mod" name="tipo_documento" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_mod" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion_mod" name="descripcion" required>
                        </div>
                        <input type="hidden" name="accion" value="actualizacion"> <!-- Acción para actualizar un documento -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" form="formModificar">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cuando se abre el modal de modificación, cargamos los datos en los campos
        const modificarModal = document.getElementById('modificarModal');
        modificarModal.addEventListener('show.bs.modal', event => {
            // Botón que disparó el modal
            const button = event.relatedTarget;
            
            // Extraemos la información de los atributos data-
            const id = button.getAttribute('data-id');
            const tipo = button.getAttribute('data-tipo');
            const descripcion = button.getAttribute('data-descripcion');

            // Cargamos los valores en los inputs del modal
            document.getElementById('id_documento').value = id;
            document.getElementById('tipo_documento_mod').value = tipo;
            document.getElementById('descripcion_mod').value = descripcion;
        });
    </script>
</body>

</html>
