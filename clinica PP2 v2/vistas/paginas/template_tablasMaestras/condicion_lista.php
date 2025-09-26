<?php
include_once "modelos/condicion.php";

$condicion = new Condicion();
$lista_condicion = $condicion->consultarVariasCondiciones();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Condiciones</h2>
            <p>Ingresa una nueva Condicion</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form method="post" action="controladores/condicion_controlador.php">
                    <div class="mb-3">
                        <input type="hidden" name="action" value="insertar">
                        <label for="nombre_condicion" class="form-label">Condicion</label>
                        <input type="text" class="form-control" id="nombre_condicion" placeholder="Ingrese la condicion" name="nombre_condicion">

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
                        <th scope="col">condicion</th>
                        <th scope="col">detalle</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_condicion as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_condicion'] ?></td>
                            <td><?php echo $row['nombre_condicion'] ?></td>
                            <td><?php echo $row['detalle'] ?></td>
                            <td>
                                <form action="controladores/condicion_controlador.php" method="post">
                                    <input type="hidden" name="id_condicion" value="<?php echo $row['id_condicion'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_condicion'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modal<?php echo $row['id_condicion'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id_condicion'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id_condicion'] ?>">Modificar condicion</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="controladores/condicion_controlador.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizacion">
                                                    <input type="hidden" name="id_condicions" value="<?php echo $row['id_condicion'] ?>">

                                                    <div class="mb-3">
                                                        <label for="nombre_condicion<?php echo $row['id_condicion'] ?>" class="form-label">condicion</label>
                                                        <input type="text" class="form-control" id="nombre_condicion<?php echo $row['id_condicion'] ?>" name="nombre_condicion" value="<?php echo $row['nombre_condicion'] ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="detalle<?php echo $row['id_condicion'] ?>" class="form-label">detalle</label>
                                                        <input type="text" class="form-control" id="detalle<?php echo $row['id_condicion'] ?>" name="detalle" value="<?php echo $row['detalle'] ?>">
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