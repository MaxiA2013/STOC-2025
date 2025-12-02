<?php
include_once "modelos/estados.php";

$stat = new Estado();
$lista_estados = $stat->consultarVariosEstados();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Estados</h2>
            <p>Ingresa un nuevo Estados</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form class="needs-validation" novalidate method="post" action="controladores/estados_controlador.php">
                    <div class="mb-3">
                        <input type="hidden" name="action" value="insertar">
                        <label for="tipo_estado" class="form-label">Estado</label>
                        <input type="text" class="form-control" id="tipo_estado" placeholder="Ingrese el estado " name="tipo_estado" required>
                        <div class="invalid-feedback">Campo de nombre de estado vacio</div>
                    </div>
                    <div>
                        <label for="descripcion" class="form-label">descripcion</label>
                        <input type="text" class="form-control" id="descripcion" placeholder="Ingrese la Descripcion " name="descripcion" required>
                        <div class="invalid-feedback">Campo descripcion vacio</div>
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
                        <th scope="col">Estados</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_estados as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_estados'] ?></td>
                            <td><?php echo $row['tipo_estado'] ?></td>
                            <td><?php echo $row['descripcion'] ?></td>
                            <td>
                                <form action="controladores/estados_controlador.php" method="post">
                                    <input type="hidden" name="id_estados" value="<?php echo $row['id_estados'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_estados'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modal<?php echo $row['id_estados'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id_estados'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id_estados'] ?>">Modificar Estado</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="needs-validation" novalidate action="controladores/estados_controlador.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizacion">
                                                    <input type="hidden" name="id_estados" value="<?php echo $row['id_estados'] ?>">

                                                    <div class="mb-3">
                                                        <label for="tipo_estado<?php echo $row['id_estados'] ?>" class="form-label">Estado</label>
                                                        <input type="text" class="form-control" id="tipo_estado<?php echo $row['id_estados'] ?>" name="tipo_estado" value="<?php echo $row['tipo_estado'] ?>" required>
                                                        <div class="invalid-feedback">Campo de nombre de estado vacio</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="descripcion<?php echo $row['id_estados'] ?>" class="form-label">Descripción</label>
                                                        <input type="text" class="form-control" id="descripcion<?php echo $row['id_estados'] ?>" name="descripcion" value="<?php echo $row['descripcion'] ?>" required>
                                                        <div class="invalid-feedback">Campo descripcion vacio</div>
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
<script src="assets/js/validaciones/validaciones_controlador.js"></script>