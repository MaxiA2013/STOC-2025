<?php
include_once "modelos/modulos.php";
include_once "modelos/tablas.php";

$modul = new Modulos("", "");
$lista_modulos = $modul->traer_modulos_con_tablas();

$tables = new tablas();
$lista_tablas_result = $tables->traerTablas();

// convertir a array para usarlo varias veces
$tablasArray = [];
if ($lista_tablas_result) {
    while ($r = $lista_tablas_result->fetch_assoc()) {
        $tablasArray[] = $r;
    }
}

?>
<!------------------------------------------  Registro    ----------------------------------------->
<!------------------------------------------  Registro    ----------------------------------------->
<!------------------------------------------  Registro    ----------------------------------------->

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
                        <?php foreach ($tablasArray as $row): ?>
                            <div>
                                <input type="checkbox" name="tablas[]" value="<?= $row['id_tablas'] ?>">
                                <?= $row['nombre_tabla'] ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>

<!----------------------------------------------  CRUD  -------------------------------------------->
<!----------------------------------------------  CRUD  -------------------------------------------->
<!----------------------------------------------  CRUD  -------------------------------------------->

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
                    // si $lista_modulos es mysqli_result
                    while ($row = $lista_modulos->fetch_assoc()) {
                        // obtener ids de tablas asignadas a este módulo
                        $tablas_ids = $modul->traer_tablas_ids_por_modulo($row['id_modulos']);
                    ?>
                        <tr>
                            <td><?= $row['id_modulos'] ?></td>
                            <td><?= $row['nombre'] ?></td>
                            <td><?= $row['tablas'] ?></td>
                            <td>
                                <form action="controladores/modulos/modulo_controlador.php" method="post">
                                    <input type="hidden" name="id_modulos" value="<?= $row['id_modulos'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- editar: ahora pasamos el array de ids con json_encode -->
                                <button type="button" onclick='editarModulo(<?= $row['id_modulos']; ?>, <?= json_encode($row['nombre']); ?>, <?= json_encode($tablas_ids); ?>)'>
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } // end while 
                    ?>

    <!------------------------------------ Modal único (fuera del foreach / while) -------------------------------->
    <!------------------------------------ Modal único (fuera del foreach / while) -------------------------------->
    <!------------------------------------ Modal único (fuera del foreach / while) -------------------------------->

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
                                            <label for="nombre_modulo_modal" class="form-label">Nombre del Módulo</label>
                                            <input type="text" class="form-control" id="nombre_modulo_modal" name="nombre_modulo" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Asignar Tablas</label><br>
                                            <?php foreach ($tablasArray as $tabla): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="tablas[]"
                                                        value="<?= $tabla['id_tablas']; ?>" id="tabla_<?= $tabla['id_tablas']; ?>">
                                                    <label class="form-check-label" for="tabla_<?= $tabla['id_tablas']; ?>">
                                                        <?= $tabla['nombre_tabla']; ?>
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

                </tbody>
            </table>
        </div>
    </div>
</div>

<!--------------------------- JAVASCRIPT PARA EL MODAL -------------------------->
<!--------------------------- JAVASCRIPT PARA EL MODAL -------------------------->
<!--------------------------- JAVASCRIPT PARA EL MODAL -------------------------->

<script>
    function editarModulo(id, nombre, tablasAsignadas) {
        document.getElementById('id_modulos').value = id;
        document.getElementById('nombre_modulo_modal').value = nombre;
        document.getElementById('action').value = 'actualizacion';

        // desmarcar todo
        document.querySelectorAll("input[name='tablas[]']").forEach(cb => cb.checked = false);

        // marcar las que pertenecen
        tablasAsignadas.forEach(id_tabla => {
            let checkbox = document.getElementById('tabla_' + id_tabla);
            if (checkbox) checkbox.checked = true;
        });

        new bootstrap.Modal(document.getElementById('modalModulo')).show();
    }
</script>