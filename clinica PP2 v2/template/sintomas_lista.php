<?php
require_once "../modulos/sintomas.php"; 

$con = new Sintomas();
$lista = $con->consultarVariosSintomas();  // Llamamos al método que consulta los síntomas
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Sintomas</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="mt-4 p-5 bg-primary text-white rounded">
        <h1>Bienvenido al Modulo Sintomas</h1>
        <p>Gestioná los síntomas del sistema agregando, eliminando o modificando.</p>
    </div>

    <div class="container-fluid">

        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $sintomas) { ?>
                                <tr>
                                    <td><?php echo $sintomas['id_sintomas'] ?></td>
                                    <td><?php echo $sintomas['nombre_sintomas'] ?></td>
                                    <td><?php echo $sintomas['descripcion'] ?></td>
                                    <td>
                                        <form class="needs-validation" action="../controladores/sintomas_controlador.php" method="post">
                                            <input type="hidden" name="id_sintomas" value="<?= $sintomas['id_sintomas'] ?>"> <!-- Toma el id del síntoma -->
                                            <input type="hidden" name="accion" value="baja"> <!-- Acción para eliminar -->
                                            <button type="submit" class="btn btn-danger">Borrar</button> <!-- Botón para eliminar -->
                                        </form>
                                    </td>
                                    <td>
                                        <!-- Botón para abrir el modal y modificar -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modificarModal" 
                                            data-id="<?php echo $sintomas['id_sintomas']; ?>" 
                                            data-nombre="<?php echo $sintomas['nombre_sintomas']; ?>" 
                                            data-descripcion="<?php echo $sintomas['descripcion']; ?>">
                                            Modificar
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="col">
                    <form class="needs-validation" action="../controladores/sintomas_controlador.php" method="post">
                        <div class="mb-3 mt-3">
                            <label for="nombre_sintomas" class="form-label">Nombre del Síntoma:</label>
                            <input type="text" class="form-control" id="nombre_sintomas" placeholder="Ingrese el síntoma" name="nombre_sintomas" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" id="descripcion" placeholder="Ingrese la descripción" name="descripcion" required>
                        </div>
                        <input type="hidden" name="accion" value="alta"> <!-- Acción para agregar un nuevo síntoma -->
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
                    <h5 class="modal-title" id="modificarModalLabel">Modificar Síntoma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModificar" action="../controladores/sintomas_controlador.php" method="post">
                        <input type="hidden" id="id_sintomas" name="id_sintomas"> <!-- Campo oculto para el ID del síntoma -->
                        <div class="mb-3">
                            <label for="nombre_sintomas" class="form-label">Nombre del Síntoma</label>
                            <input type="text" class="form-control" id="nombre_sintomas_modal" name="nombre_sintomas" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion_modal" name="descripcion" required>
                        </div>
                        <input type="hidden" name="accion" value="actualizacion"> <!-- Acción para actualizar -->
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
        // Cuando se abre el modal, cargamos los datos en los campos
        const modificarModal = document.getElementById('modificarModal');
        modificarModal.addEventListener('show.bs.modal', event => {
            // Botón que disparó el modal
            const button = event.relatedTarget;
            
            // Extraemos la información de los atributos data-
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const descripcion = button.getAttribute('data-descripcion');

            // Cargamos los valores en los inputs del modal
            document.getElementById('id_sintomas').value = id;
            document.getElementById('nombre_sintomas_modal').value = nombre;
            document.getElementById('descripcion_modal').value = descripcion;
        });
    </script>
</body>

</html>
