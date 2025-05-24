<?php
require_once("../modulos/metodo_pago.php");

$con = new Metodo_Pago();
$lista = $con->consultarVariosMetodosPago();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Método de Pago</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="mt-4 p-5 bg-primary text-white rounded">
        <h1>Bienvenido al Módulo de Métodos de Pago</h1>
        <p>Gestioná los métodos de pago del sistema agregando, eliminando o modificando.</p>
    </div>
    <a href="../index.php">Regresar al Inicio</a> <!-- Link para la página -->

    <div class="container-fluid">
        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <!-- Tabla de Métodos de Pago -->
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $metodo_pago) { ?>
                                <tr>
                                    <td><?php echo $metodo_pago['id_metodo_pago'] ?></td>
                                    <td><?php echo $metodo_pago['nombre_metodo'] ?></td>
                                    <td>
                                        <!-- Formulario para borrar -->
                                        <form class="d-inline-block" action="../controladores/metodo_pago_controlador.php" method="post">
                                            <input type="hidden" name="id_metodo_pago" value="<?= $metodo_pago['id_metodo_pago'] ?>">
                                            <input type="hidden" name="accion" value="baja">
                                            <button type="submit" class="btn btn-danger">Borrar</button>
                                        </form>
                                    </td>
                                    <td>
                                        <!-- Botón para abrir el modal de modificación -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modificarModal" 
                                            data-id="<?php echo $metodo_pago['id_metodo_pago']; ?>" 
                                            data-nombre="<?php echo $metodo_pago['nombre_metodo']; ?>">
                                            Modificar
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Formulario para agregar un nuevo método de pago -->
                <div class="col">
                    <form class="needs-validation" action="../controladores/metodo_pago_controlador.php" method="post">
                        <div class="mb-3 mt-3">
                            <label for="metodo_pago" class="form-label">Nombre del Método de Pago:</label>
                            <input type="text" class="form-control" id="metodo_pago" placeholder="Ingrese el nombre del método de pago" name="metodo_pago" required>
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
                    <h5 class="modal-title" id="modificarModalLabel">Modificar Método de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModificar" action="../controladores/metodo_pago_controlador.php" method="post">
                        <input type="hidden" id="id_metodo_pago" name="id_metodo_pago"> <!-- Campo oculto para el ID del método -->
                        <div class="mb-3">
                            <label for="nombre_metodo_pago" class="form-label">Nombre del Método de Pago</label>
                            <input type="text" class="form-control" id="nombre_metodo_pago" name="nombre_metodo_pago" required>
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
            document.getElementById('id_metodo_pago').value = id;
            document.getElementById('nombre_metodo_pago').value = nombre;
        });
    </script>
</body>

</html>
