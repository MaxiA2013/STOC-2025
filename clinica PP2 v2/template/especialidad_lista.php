<?php
require_once("../modelos/especialidad.php");

$con = new Especialidad();
$lista = $con->consultarVariasEspecialidades();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Especialidades</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="mt-4 p-5 bg-primary text-white rounded">
        <h1>Bienvenido al Módulo de Especialidades</h1>
        <p>Gestioná las especialidades del sistema agregando, eliminando o modificando.</p>
    </div>
    <a href="../index.php">Regresar al Inicio</a> <!-- Link para la página -->

    <div class="container-fluid">
        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <!-- Tabla de Especialidades -->
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nombre</th>
                            </tr>
                        </thead>

                    

                        <tbody>
                            <?php foreach ($lista as $especialidad) { ?>
                                <tr>
                                    <td><?php echo $especialidad['id_especialidad'] ?></td>
                                    <td><?php echo $especialidad['nombre_especialidad'] ?></td>
                                    <td>
                                        <form class="needs-validation" action="../controladores/especialidad_controlador.php" method="post">
                                            <input type="hidden" name="id_especialidad" value="<?= $especialidad['id_especialidad'] ?>">
                                            <input type="hidden" name="accion" value="baja">
                                            <button type="submit" class="btn btn-danger">Borrar</button>
                                        </form>
                                    </td>

                                    <td>
                                        <!-- Botón para abrir el modal de modificación -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modificarModal" 
                                            data-id="<?php echo $especialidad['id_especialidad']; ?>" 
                                            data-nombre="<?php echo $especialidad['nombre_especialidad']; ?>">
                                            Modificar
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Formulario para agregar una nueva especialidad -->
                <div class="col">
                    <form class="needs-validation" action="../controladores/especialidad_controlador.php" method="post">
                        <div class="mb-3 mt-3">
                            <label for="especialidad" class="form-label">Nombre de Especialidad:</label>
                            <input type="text" class="form-control" id="especialidad" placeholder="Ingrese el nombre de la especialidad" name="especialidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_especialidad" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" id="descripcion_especialidad" placeholder="Ingrese la descripción" name="descripcion_especialidad" required>
                        </div>
                        <input type="hidden" name="accion" value="alta">
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
                    <h5 class="modal-title" id="modificarModalLabel">Modificar Especialidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModificar" action="../controladores/especialidad_controlador.php" method="post">
                        <input type="hidden" id="id_especialidad" name="id_especialidad"> <!-- Campo oculto para el ID de la especialidad -->
                        <div class="mb-3">
                            <label for="nombre_especialidad" class="form-label">Nombre de la Especialidad</label>
                            <input type="text" class="form-control" id="nombre_especialidad" name="nombre_especialidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="detalle" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="detalle" name="detalle" required>
                        </div>
                        <input type="hidden" name="accion" value="actualizacion">
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
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');

            // Cargamos los valores en los inputs del modal
            document.getElementById('id_especialidad').value = id;
            document.getElementById('nombre_especialidad').value = nombre;
        });
    </script>
    
</body>

</html>
