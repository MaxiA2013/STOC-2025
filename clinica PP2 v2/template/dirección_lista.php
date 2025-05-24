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
    <title>Gestion de Metodo de Pago</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="mt-4 p-5 bg-primary text-white rounded">
        <h1>Bienvenido al Modulo de Metodos de Pago</h1>
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
                                    <td><?php echo $metodo_pago['nombre'] ?></td>
                                    <td>
                                        <!-- Formulario para borrar -->
                                        <form class="d-inline-block" action="../controladores/metodo_pago_controlador.php" method="post">
                                            <input type="hidden" name="id_metodo_pago" value="<?= $metodo_pago['id_metodo_pago'] ?>">
                                            <input type="hidden" name="accion" value="baja">
                                            <button type="submit" class="btn btn-danger">Borrar</button>
                                        </form>

                                        <!-- Botón para abrir el modal de modificación -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditar<?php echo $metodo_pago['id_metodo_pago'] ?>">
                                            Modificar
                                        </button>

                                        <!-- Modal de edición -->
                                        <div class="modal fade" id="modalEditar<?php echo $metodo_pago['id_metodo_pago'] ?>" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditarLabel">Modificar Método de Pago</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="../controladores/metodo_pago_controlador.php" method="post">
                                                            <div class="mb-3">
                                                                <label for="nombre_metodo_pago" class="form-label">Nombre del Método de Pago</label>
                                                                <input type="text" class="form-control" id="nombre_metodo_pago" name="nombre_metodo_pago" value="<?= $metodo_pago['nombre'] ?>" required>
                                                            </div>
                                                            <input type="hidden" name="id_metodo_pago" value="<?= $metodo_pago['id_metodo_pago'] ?>">
                                                            <input type="hidden" name="accion" value="actualizacion">
                                                            <button type="submit" class="btn btn-warning">Guardar cambios</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                            <label for="metodo_pago" class="form-label">Nombre del Metodo de Pago:</label>
                            <input type="text" class="form-control" id="metodo_pago" placeholder="Ingrese el nombre del método de pago" name="metodo_pago" required>
                        </div>
                        <input type="hidden" name="accion" value="alta">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>
