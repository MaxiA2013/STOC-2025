<?php
include_once "modelos/perfil.php";
include_once "modelos/modulos.php";

$per = new Perfil();
$lista_perfiles = $per->traer_perfiles();

$mod = new Modulos();
$lista_modulos_result = $mod->traer_Modulos();

// convertir resultado de módulos a array para usarlo varias veces
$modulosArray = [];
if ($lista_modulos_result) {
    while ($r = $lista_modulos_result->fetch_assoc()) {
        $modulosArray[] = $r;
    }
}
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

                    <div>
                        <h4>Asignar Módulos:</h4>
                        <?php foreach ($modulosArray as $row): ?>
                            <div>
                                <input type="checkbox" name="modulos[]" value="<?= $row['id_modulos'] ?>">
                                <?= $row['nombre'] ?>
                            </div>
                        <?php endforeach; ?>
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
                    if ($lista_perfiles) {
                        while ($row = $lista_perfiles->fetch_assoc()) {
                            $modulos_ids = $per->traer_modulos_ids_por_perfil($row['id_perfil']);
                    ?>
                        <tr>
                            <td><?= $row['id_perfil'] ?></td>
                            <td><?= $row['nombre_perfil'] ?></td>
                            <td><?= $row['descripcion'] ?></td>
                            <td>
                                <form action="controladores/perfiles/perfiles_controlador.php" method="post">
                                    <input type="hidden" name="id_perfil" value="<?= $row['id_perfil'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </td>
                            <td>
                                <!-- Se agrega la descripción como tercer parámetro -->
                                <button type="button" onclick='editarPerfil(
                                    <?= $row['id_perfil']; ?>, 
                                    <?= json_encode($row['nombre_perfil']); ?>, 
                                    <?= json_encode($row['descripcion']); ?>, 
                                    <?= json_encode($modulos_ids); ?>)'>
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>
                            </td>
                        </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Modal -->
            <div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="modalPerfilLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="controladores/perfiles/perfiles_controlador.php">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalPerfilLabel">Gestionar Perfil</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_perfil" id="id_perfil">
                                <input type="hidden" name="action" id="action">

                                <div class="mb-3">
                                    <label for="nombre_perfil_modal" class="form-label">Perfil</label>
                                    <input type="text" class="form-control" id="nombre_perfil_modal" name="nombre_perfil" required>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion_modal" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" id="descripcion_modal" name="descripcion" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Asignar Módulos</label><br>
                                    <?php foreach ($modulosArray as $modulo): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="modulos[]"
                                                   value="<?= $modulo['id_modulos']; ?>" id="modulo_<?= $modulo['id_modulos']; ?>">
                                            <label class="form-check-label" for="modulo_<?= $modulo['id_modulos']; ?>">
                                                <?= $modulo['nombre']; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- fin modal -->
        </div>
    </div>
</div>

<script>
    // ahora la función recibe descripcion como parámetro
    function editarPerfil(id, nombre, descripcion, modulosAsignados) {
        document.getElementById('id_perfil').value = id;
        document.getElementById('nombre_perfil_modal').value = nombre;
        document.getElementById('descripcion_modal').value = descripcion; // se llena el campo
        document.getElementById('action').value = 'actualizacion';

        // desmarcar todo
        document.querySelectorAll("input[name='modulos[]']").forEach(cb => cb.checked = false);

        // marcar los asignados
        modulosAsignados.forEach(id_mod => {
            let checkbox = document.getElementById('modulo_' + id_mod);
            if (checkbox) checkbox.checked = true;
        });

        new bootstrap.Modal(document.getElementById('modalPerfil')).show();
    }

    function nuevoPerfil() {
        document.getElementById('id_perfil').value = '';
        document.getElementById('nombre_perfil_modal').value = '';
        document.getElementById('descripcion_modal').value = '';
        document.getElementById('action').value = 'insertar';
        document.querySelectorAll("input[name='modulos[]']").forEach(cb => cb.checked = false);
        new bootstrap.Modal(document.getElementById('modalPerfil')).show();
    }
</script>
