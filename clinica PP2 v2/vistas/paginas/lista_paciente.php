<?php
require_once "modelos/paciente.php";
$paciente = new Paciente();
$resultado = $paciente->listarPacientes();
?>

<div class="container mt-4">
    <h2>Lista de Pacientes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Paciente</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id_paciente']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['apellido']; ?></td>
                    <td><?php echo $row['nombre_usuario']; ?></td>
                    <td>
                        <a href="index.php?page=editar_paciente&id=<?php echo $row['id_paciente']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="controladores/paciente_controlador.php?action=eliminar&id=<?php echo $row['id_paciente']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('¿Seguro que deseas eliminar este paciente?');">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>