<?php
include_once "modelos/perfil.php";
$per = new Perfil("", "");
$lista_perfiles = $per->traer_perfiles();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Perfiles</h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form method="post" action="controladores/perfiles/perfiles_controlador.php">
                    <input type="hidden" name="action" value="insertar">
                    <div class="mb-3">
                        <label for="nombre_perfil" class="form-label">Perfil</label>
                        <input type="text" class="form-control" id="nombre_perfil" placeholder="Ingrese Perfil " name="nombre_perfil">
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <input type="text" class="form-control" id="descripcion" placeholder="Ingrese Descripcion " name="descripcion">
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
                        <th scope="col">Perfil</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_perfiles as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_perfil'] ?></td>
                            <td><?php echo $row['nombre_perfil'] ?></td>
                            <td><?php echo $row['descripcion'] ?></td>
                            <td>
                                <form action="controladores/perfiles/perfiles_controlador.php" method="post">
                                    <input type="hidden" name="id_perfil" value="<?php echo $row['id_perfil'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_perfil'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modal<?php echo $row['id_perfil'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id_perfil'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id_perfil'] ?>">Modificar Perfil</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="controladores/perfiles/perfiles_controlador.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizacion">
                                                    <input type="hidden" name="id_perfil" value="<?php echo $row['id_perfil'] ?>">

                                                    <div class="mb-3">
                                                        <label for="nombre_perfil<?php echo $row['id_perfil'] ?>" class="form-label">Perfil</label>
                                                        <input type="text" class="form-control" id="nombre_perfil<?php echo $row['id_perfil'] ?>" name="nombre_perfil" value="<?php echo $row['nombre_perfil'] ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="descripcion<?php echo $row['id_perfil'] ?>" class="form-label">Descripción</label>
                                                        <input type="text" class="form-control" id="descripcion<?php echo $row['id_perfil'] ?>" name="descripcion" value="<?php echo $row['descripcion'] ?>">
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