<?php
require_once "../modelos/paciente.php";

// Eliminar paciente
if (isset($_GET['action']) && $_GET['action'] === 'eliminar') {
    $id_paciente = $_GET['id'];
    $paciente = new Paciente();
    $paciente->eliminar($id_paciente);
    header('Location: ../index.php?page=lista_paciente&message=Paciente eliminado&status=success');
    exit;
}

// Modificar solo nombre y apellido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'modificar') {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];

    $conexion = new Conexion();

    // Actualizar persona vinculada al usuario
    $query_persona = "UPDATE persona SET nombre = '$nombre', apellido = '$apellido' 
                      WHERE id_persona = (SELECT persona_id_persona FROM usuario WHERE id_usuario = $id_usuario)";
    $conexion->actualizar($query_persona);

    header('Location: ../index.php?page=lista_paciente&message=Paciente actualizado&status=success');
    exit;
}