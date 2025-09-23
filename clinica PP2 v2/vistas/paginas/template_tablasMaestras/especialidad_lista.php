<?php
include_once "modelos/especialidad.php";

//HAY QUE ARREGLAR LA LISTA ESPECIALIDAD, LO ESTABA HACIENDO YO PERO COMO TE LO ASIGNE TE LO DEJO ASI COMO ESTÁ
//ESTOY USANDO LOS ARCHIVOS DE LA LISTA TABLAS.PHP DE LA CARPETA PAGINAS, EL MODELO Y SU RESPECTIVO CONTROLADOR
//HAY QUE CAMBIAR LAS FUNCTIONES Y METODOS DE LOS ARCHIVOS DE TABLAS POR LOS DEL ARCHIVO ESPECIALIDAD
$tabs = new Tablas();
$lista_tablas = $tabs->traerTablas();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Tablas</h2>
            <p>Ingresa una nueva Tabla al sistema</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form method="post" action="controladores/tablas/tabla_controlador.php">
                    <div class="mb-3">
                        <input type="hidden" name="action" value="insertar">
                        <label for="nombre_tabla" class="form-label">Tablas</label>
                        <input type="text" class="form-control" id="nombre_tabla" placeholder="Ingrese la Tabla " name="nombre_tabla">
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
                        <th scope="col">Tabla</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_tablas as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_tablas'] ?></td>
                            <td><?php echo $row['nombre_tabla'] ?></td>
                            <td>
                                <form action="controladores/tablas/tabla_controlador.php" method="post">
                                    <input type="hidden" name="id_tablas" value="<?php echo $row['id_tablas'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_tablas'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modal<?php echo $row['id_tablas'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id_tablas'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id_tablas'] ?>">Modificar Tabla</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="controladores/tablas/tabla_controlador.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizacion">
                                                    <input type="hidden" name="id_tablas" value="<?php echo $row['id_tablas'] ?>">

                                                    <div class="mb-3">
                                                        <label for="nombre_tabla<?php echo $row['id_tablas'] ?>" class="form-label">Tabla</label>
                                                        <input type="text" class="form-control" id="nombre_tabla<?php echo $row['id_tablas'] ?>" name="nombre_tabla" value="<?php echo $row['nombre_tabla'] ?>">
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