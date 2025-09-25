<?php
include_once "modelos/metodo_pago.php";

$metodo_pago = new Metodo_pago();
$lista_metodo_pago = $metodo_pago->consultarVariosMetodosPago();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Metodo de Pago</h2>
            <p>Ingresa un nuevo Metodo de Pago</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form method="post" action="controladores/metodo_pago_controlador.php">
                    <div class="mb-3">
                        <input type="hidden" name="action" value="insertar">
                        <label for="nombre_metodo" class="form-label">Metodo de Pago</label>
                        <input type="text" class="form-control" id="nombre_metodo" placeholder="Ingrese el metodo de pago " name="nombre_metodo">
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>

        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">metodo de pago</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_metodo_pago as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_metodo_pago'] ?></td>
                            <td><?php echo $row['nombre_metodo'] ?></td>
                            <td>
                                <form action="controladores/metodo_pago_controlador.php" method="post">
                                    <input type="hidden" name="id_metodo_pago" value="<?php echo $row['id_metodo_pago'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_metodo_pago'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modal<?php echo $row['id_metodo_pago'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id_metodo_pago'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id_metodo_pago'] ?>">Modificar Metodo de Pago</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="controladores/obra_socials/obra_social_controlador.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizacion">
                                                    <input type="hidden" name="id_metodo_pagos" value="<?php echo $row['id_metodo_pago'] ?>">

                                                    <div class="mb-3">
                                                        <label for="nombre_metodo<?php echo $row['id_metodo_pago'] ?>" class="form-label">Metodo de Pago</label>
                                                        <input type="text" class="form-control" id="nombre_metodo<?php echo $row['id_metodo_pago'] ?>" name="nombre_metodo" value="<?php echo $row['nombre_metodo'] ?>">
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
                            </td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>