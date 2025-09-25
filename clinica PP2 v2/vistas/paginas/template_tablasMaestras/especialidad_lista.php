<?php
include_once "modelos/especialidad.php";

$especialidad = new Especialidad();
$lista_especialidad = $especialidad->consultarVariasEspecialidades();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Especialidad</h2>
            <p>Ingresa una nueva Especialidad al sistema</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form method="post" action="controladores/especialidad_controlador.php">
                    <div class="mb-3">
                        <input type="hidden" name="action" value="insertar">
                        <label for="nombre_especialidad" class="form-label">Especialidad</label>
                        <input type="text" class="form-control" id="nombre_especialidad" placeholder="Ingrese la especialidad " name="nombre_especialidad">
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
                        <th scope="col">Especialidad</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_especialidad as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_especialidad'] ?></td>
                            <td><?php echo $row['nombre_especialidad'] ?></td>
                            <td>
                                <form action="controladores/tablas/tabla_controlador.php" method="post">
                                    <input type="hidden" name="id_especialidad" value="<?php echo $row['id_especialidad'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_especialidad'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modal<?php echo $row['id_especialidad'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id_especialidad'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id_especialidad'] ?>">Modificar Especialidad</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="controladores/tablas/tabla_controlador.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizacion">
                                                    <input type="hidden" name="id_especialidad" value="<?php echo $row['id_especialidad'] ?>">

                                                    <div class="mb-3">
                                                        <label for="nombre_especialidad<?php echo $row['id_especialidad'] ?>" class="form-label">Especialidad</label>
                                                        <input type="text" class="form-control" id="nombre_especialidad<?php echo $row['id_especialidad'] ?>" name="nombre_especialidad" value="<?php echo $row['nombre_especialidad'] ?>">
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