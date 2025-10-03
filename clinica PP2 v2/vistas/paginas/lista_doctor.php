<?php
require_once __DIR__ . "/../../modelos/conexion.php";
require_once __DIR__ . "/../../modelos/doctor.php";

$doctor = new Doctor();
$doctores = $doctor->all_doctores();

// Obtener usuarios disponibles para asignar doctor
$conn = new Conexion();
$sqlUsuarios = "SELECT u.id_usuario, p.nombre, p.apellido 
                FROM usuario u
                JOIN persona p ON u.persona_id_persona = p.id_persona";
$usuarios = $conn->consultar($sqlUsuarios);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Doctores</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h2 class="mb-4">Registrar Doctor</h2>
        <p>Registra a un usuario ya existente como doctor</p>
        <form id="formDoctor" action="controladores/doctor_controlador.php" method="POST">
            <input type="hidden" name="action" value="guardar_doctor">

            <div class="mb-3">
                <label for="numero_matricula_profesional">Número Matrícula</label>
                <input type="text" id="numero_matricula_profesional" name="numero_matricula_profesional" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="precio_consulta">Precio de consulta</label>
                <input type="number" id="precio_consulta" name="precio_consulta" step="0.01" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="usuario_id_usuario">Usuario</label>
                <select id="usuario_id_usuario" name="usuario_id_usuario" class="form-control" required>
                    <option value="">Seleccione un usuario</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario['id_usuario'] ?>">
                            <?= $usuario['nombre'] . " " . $usuario['apellido'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Doctor</button>
        </form>


        <hr class="my-5">

        <h3>Doctores Registrados</h3>
        <div class="col">
            <button type="button" class="btn btn-success">
                <a href="controladores/generar_excel.php" style="text-decoration: none; color: white">Excel</a>
            </button>
            <button type="button" class="btn btn-danger">PDF</button>
        </div>
        <table class="table table-bordered table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Matrícula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Usuario</th>
                    <th>Precio de consulta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($doctores)) : ?>
                    <?php foreach ($doctores as $fila) : ?>
                        <tr>
                            <td><?= $fila['id_doctor'] ?></td>
                            <td><?= $fila['numero_matricula_profesional'] ?></td>
                            <td><?= $fila['nombre'] ?></td>
                            <td><?= $fila['apellido'] ?></td>
                            <td><?= $fila['nombre_usuario'] ?></td>
                            <td>$ <?= $fila['precio_consulta'] ?></td>
                            <td class="d-flex gap-2">

                                <!-- Botón eliminar -->
                                <form action="controladores/doctor_controlador.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="eliminar_doctor">
                                    <input type="hidden" name="id_doctor" value="<?= $fila['id_doctor'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>

                                <!-- Botón editar -->
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $fila['id_doctor'] ?>">
                                    Editar
                                </button>

                                <!-- Modal editar -->
                                <div class="modal fade" id="modalEditar<?= $fila['id_doctor'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $fila['id_doctor'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="controladores/doctor_controlador.php" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel<?= $fila['id_doctor'] ?>">Editar Doctor</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizar_doctor">
                                                    <input type="hidden" name="id_doctor" value="<?= $fila['id_doctor'] ?>">

                                                    <div class="mb-3">
                                                        <label for="numero_matricula_profesional<?= $fila['id_doctor'] ?>" class="form-label">Número Matrícula</label>
                                                        <input type="text" class="form-control" id="numero_matricula_profesional<?= $fila['id_doctor'] ?>" name="numero_matricula_profesional" value="<?= $fila['numero_matricula_profesional'] ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="precio_consulta<?= $fila['id_doctor'] ?>" class="form-label">Precio de consulta</label>
                                                        <input type="number" step="0.01" class="form-control" id="precio_consulta<?= $fila['id_doctor'] ?>" name="precio_consulta" value="<?= $fila['precio_consulta'] ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="usuario_id_usuario<?= $fila['id_doctor'] ?>" class="form-label">Usuario</label>
                                                        <select class="form-control" id="usuario_id_usuario<?= $fila['id_doctor'] ?>" name="usuario_id_usuario" required>
                                                            <?php foreach ($usuarios as $usuario): ?>
                                                                <option value="<?= $usuario['id_usuario'] ?>" <?= $usuario['id_usuario'] == $fila['usuario_id_usuario'] ? 'selected' : '' ?>>
                                                                    <?= $usuario['nombre'] . " " . $usuario['apellido'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fin modal editar -->

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay doctores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/validaciones/lista_doctor/lista_doctor.js"></script>
</body>

</html>