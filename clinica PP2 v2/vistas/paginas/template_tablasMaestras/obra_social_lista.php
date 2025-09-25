<?php
include_once "modelos/obra_social.php";

$obra_social = new Obra_Social();
$lista_obra_social = $obra_social->consultarVariasObrasSociales();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Obras Sociales</h2>
            <p>Ingresa una nueva Obra Social</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form method="post" action="controladores/obra_social_controlador.php">
                    <div class="mb-3">
                        <input type="hidden" name="action" value="insertar">
                        <label for="nombre_obra_social" class="form-label">obra_social</label>
                        <input type="text" class="form-control" id="nombre_obra_social" placeholder="Ingrese la obra social " name="nombre_obra_social">

                        <label for="detalle" class="form-label">Detalle</label>
                        <input type="text" class="form-control" id="detalle" placeholder="Ingrese el detalle " name="detalle">
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>

        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">obra_social</th>
                        <th scope="col">detalle</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_obra_social as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_obra_social'] ?></td>
                            <td><?php echo $row['nombre_obra_social'] ?></td>
                            <td><?php echo $row['detalle'] ?></td>
                            <td>
                                <form action="controladores/obra_social_controlador.php" method="post">
                                    <input type="hidden" name="id_obra_social" value="<?php echo $row['id_obra_social'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_obra_social'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modal<?php echo $row['id_obra_social'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id_obra_social'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id_obra_social'] ?>">Modificar obra_social</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="controladores/obra_socials/obra_social_controlador.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizacion">
                                                    <input type="hidden" name="id_obra_socials" value="<?php echo $row['id_obra_social'] ?>">

                                                    <div class="mb-3">
                                                        <label for="nombre_obra_social<?php echo $row['id_obra_social'] ?>" class="form-label">obra_social</label>
                                                        <input type="text" class="form-control" id="nombre_obra_social<?php echo $row['id_obra_social'] ?>" name="nombre_obra_social" value="<?php echo $row['nombre_obra_social'] ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="detalle<?php echo $row['id_obra_social'] ?>" class="form-label">detalle</label>
                                                        <input type="text" class="form-control" id="detalle<?php echo $row['id_obra_social'] ?>" name="detalle" value="<?php echo $row['detalle'] ?>">
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
                        </tr>';
                </tbody>
            </table>
        </div>
    </div>
</div>