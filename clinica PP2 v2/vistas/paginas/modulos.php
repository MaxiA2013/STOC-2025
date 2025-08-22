<?php
include_once "modelos/modulos.php";
include_once "modelos/tablas.php";

$modul = new Modulos("", "");
$lista_modulos = $modul->traer_modulos_con_tablas();

$tables = new tablas();
$lista_tablas = $tables->traerTablas();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Modulos</h2>
            <p>Ingresa un nuevo Modulo al sistema</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container">
                <form method="post" action="controladores/modulos/modulo_controlador.php">
                    <div class="mb-3">
                        <input type="hidden" name="action" value="insertar">
                        <label for="nombre_modulo" class="form-label">Modulo</label>
                        <input type="text" class="form-control" id="nombre_modulo" placeholder="Ingrese el Modulo " name="nombre_modulo">
                    </div>
                    <div>
                        <h4>Asignar Tablas:</h4>
                        <?php while ($row = $lista_tablas->fetch_assoc()): ?>
                            <div>
                                <input type="checkbox" name="tablas[]" value="<?= $row['id_tablas'] ?>">
                                <?= $row['nombre_tabla'] ?>
                            </div>
                        <?php endwhile; ?>
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
                        <th scope="col">Modulos</th>
                        <th scope="col">Tablas</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_modulos as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['id_modulos'] ?></td>
                            <td><?php echo $row['nombre'] ?></td>
                            <td><?php echo $row['tablas'] ?></td>
                            <td>
                                <form action="controladores/modulos/modulo_controlador.php" method="post">
                                    <input type="hidden" name="id_modulos" value="<?php echo $row['id_modulos'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Botón que abre el modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id_modulos'] ?>">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>

                                <!-- Modal dinámico -->
                                <div class="modal fade" id="modalModulo" tabindex="-1" aria-labelledby="modalModuloLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="POST" action="controladores/modulos/modulo_controlador.php">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalModuloLabel">Gestionar Módulo</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_modulos" id="id_modulos">
                                                    <input type="hidden" name="action" id="action">

                                                    <div class="mb-3">
                                                        <label for="nombre_modulo" class="form-label">Nombre del Módulo</label>
                                                        <input type="text" class="form-control" id="nombre_modulos" name="nombre_modulo" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Asignar Tablas</label><br>
                                                        <?php foreach ($lista_tablas as $tabla): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="tablas[]"
                                                                    value="<?php echo $tabla['id_tablas']; ?>"
                                                                    id="tabla_<?php echo $tabla['id_tablas']; ?>">
                                                                <label class="form-check-label" for="tabla_<?php echo $tabla['id_tablas']; ?>">
                                                                    <?php echo $tabla['nombre_tabla']; ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </form>
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

<script>
    function editarModulo(id, nombre, tablasAsignadas) {
        document.getElementById('id_modulos').value = id;
        document.getElementById('nombre_modulo').value = nombre;
        document.getElementById('action').value = 'actualizacion';

        // Reiniciar todos los checkboxes
        document.querySelectorAll("input[name='tablas[]']").forEach(cb => cb.checked = false);

        // Marcar solo los que pertenecen al módulo
        tablasAsignadas.forEach(id_tabla => {
            let checkbox = document.getElementById('tabla_' + id_tabla);
            if (checkbox) checkbox.checked = true;
        });

        new bootstrap.Modal(document.getElementById('modalModulo')).show();
    }

    function nuevoModulo() {
        document.getElementById('id_modulos').value = '';
        document.getElementById('nombre_modulo').value = '';
        document.getElementById('action').value = 'insertar';
        document.querySelectorAll("input[name='tablas[]']").forEach(cb => cb.checked = false);

        new bootstrap.Modal(document.getElementById('modalModulo')).show();
    }
</script>