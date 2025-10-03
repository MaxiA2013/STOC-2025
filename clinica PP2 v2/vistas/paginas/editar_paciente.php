<?php
require_once "modelos/paciente.php";

if (isset($_GET['id'])) {
    $id_paciente = $_GET['id'];
    $paciente = new Paciente();
    $datos = $paciente->obtenerPorId($id_paciente);
} else {
    echo "<div class='alert alert-danger'>No se recibió el ID del paciente.</div>";
    exit;
}
?>

<div class="container mt-4">
    <h2>Editar Paciente</h2>
    <form action="controladores/paciente_controlador.php" method="POST">
        <input type="hidden" name="id_paciente" value="<?= $datos['id_paciente'] ?>">
        <input type="hidden" name="id_usuario" value="<?= $datos['id_usuario'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $datos['nombre'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" value="<?= $datos['apellido'] ?>" required>
        </div>

        <button type="submit" name="action" value="modificar" class="btn btn-primary">Guardar Cambios</button>
        <a href="index.php?page=lista_paciente" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
