<?php
include_once "modelos/familiar.php";

$fam = new Familiar();
$lista_fam = $fam->consultarVariosFamiliar();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Familiar</h2>
            <p>Ingresa un nuevo tipo Familiar</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form method="post" action="controladores/familiar_controlador.php">
                    <div class="mb-3">
                        <input type="hidden" name="action" value="insertar">
                        <label for="relacion" class="form-label">Relación</label>
                        <input type="text" class="form-control" id="relacion" placeholder="Ingrese la relacion " name="relacion">

                        <label for="descripcion" class="form-label">descripcion</label>
                        <input type="text" class="form-control" id="descripcion" placeholder="Ingrese la Descripcion " name="descripcion">
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
                        <th scope="col">Relacion</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_fam as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_familiar'] ?></td>
                            <td><?php echo $row['relacion'] ?></td>
                            <td><?php echo $row['descripcion'] ?></td>
                            <td>
                                <form action="controladores/familiar_controlador.php" method="post">
                                    <input type="hidden" name="id_familiar" value="<?php echo $row['id_familiar'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_familiar'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modal<?php echo $row['id_familiar'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id_familiar'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id_familiar'] ?>">Modificar Relacion</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="controladores/sintomas_controlador.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizacion">
                                                    <input type="hidden" name="id_familiar" value="<?php echo $row['id_familiar'] ?>">

                                                    <div class="mb-3">
                                                        <label for="relacion<?php echo $row['id_familiar'] ?>" class="form-label">Relacion</label>
                                                        <input type="text" class="form-control" id="relacion<?php echo $row['id_familiar'] ?>" name="relacion" value="<?php echo $row['relacion'] ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="descripcion<?php echo $row['id_familiar'] ?>" class="form-label">Descripción</label>
                                                        <input type="text" class="form-control" id="descripcion<?php echo $row['id_familiar'] ?>" name="descripcion" value="<?php echo $row['descripcion'] ?>">
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