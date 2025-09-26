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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Registrar Doctor</h2>
    <form action="controladores/doctor_controlador.php" method="POST">
        <input type="hidden" name="action" value="guardar_doctor">

        <div class="mb-3">
            <label for="numero_matricula_profesional">Número Matrícula</label>
            <input type="text" name="numero_matricula_profesional" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="salario">Salario</label>
            <input type="number" name="salario" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="usuario_id_usuario">Usuario</label>
            <select name="usuario_id_usuario" class="form-control" required>
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
    <table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Matrícula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th>
                <th>Salario</th>
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
                        <td><?= $fila['salario'] ?></td>
                        <td>
                            <form action="../../controladores/doctor_controlador.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="eliminar_doctor">
                                <input type="hidden" name="id_doctor" value="<?= $fila['id_doctor'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
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

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
