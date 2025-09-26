<?php
include_once "modelos/turno.php";

$turnoObj = new Turno();
$lista_turnos = $turnoObj->consultarVariosTurnos();
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Turnos</h2>
            <p>Agregar un nuevo turno</p>
        </div>
    </div>
    <div class="row">
        <!-- Formulario de alta -->
        <div class="col">
            <form method="post" action="controladores/turno_controlador.php">
                <input type="hidden" name="action" value="insertar">

                <div class="mb-3">
                    <label class="form-label">Minutos del Turno</label>
                    <input type="number" class="form-control" name="minutos_turnos" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha y Hora</label>
                    <input type="datetime-local" class="form-control" name="fecha_hora" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Disponible</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="disponible" value="0" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="disponible" value="1">
                        <label class="form-check-label">Sí</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">ID Agenda</label>
                    <input type="number" class="form-control" name="agenda_id_agenda" required>
                </div>

                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>

        <!-- Tabla -->
        <div class="col">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Minutos</th>
                        <th>Fecha y Hora</th>
                        <th>Disponible</th>
                        <th>ID Agenda</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lista_turnos as $row): ?>
                    <tr>
                        <td><?= $row['id_turnos'] ?></td>
                        <td><?= $row['minutos_turnos'] ?></td>
                        <td><?= $row['fecha_hora'] ?></td>
                        <td><?= $row['disponible'] ? 'Sí' : 'No' ?></td>
                        <td><?= $row['agenda_id_agenda'] ?></td>
                        <td>
                            <!-- Eliminar -->
                            <form action="controladores/turno_controlador.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_turnos" value="<?= $row['id_turnos'] ?>">
                                <input type="hidden" name="action" value="eliminacion">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                            <!-- Editar -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $row['id_turnos'] ?>">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal edición -->
                    <div class="modal fade" id="modal<?= $row['id_turnos'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="controladores/turno_controlador.php" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modificar Turno</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="actualizacion">
                                        <input type="hidden" name="id_turnos" value="<?= $row['id_turnos'] ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Minutos del Turno</label>
                                            <input type="number" class="form-control" name="minutos_turnos" value="<?= $row['minutos_turnos'] ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Fecha y Hora</label>
                                            <input type="datetime-local" class="form-control" name="fecha_hora" value="<?= date('Y-m-d\TH:i', strtotime($row['fecha_hora'])) ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Disponible</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="disponible" value="0" <?= $row['disponible'] == 0 ? 'checked' : '' ?>>
                                                <label class="form-check-label">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="disponible" value="1" <?= $row['disponible'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label">Sí</label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">ID Agenda</label>
                                            <input type="number" class="form-control" name="agenda_id_agenda" value="<?= $row['agenda_id_agenda'] ?>">
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
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
