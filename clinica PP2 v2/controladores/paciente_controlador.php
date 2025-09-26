<?php
require_once "../../modelos/paciente.php";

if (isset($_GET['action']) && $_GET['action'] == 'eliminar') {
    $id_paciente = $_GET['id'];
    $conexion = new Conexion();
    $query = "DELETE FROM paciente WHERE id_paciente = $id_paciente";
    $conexion->eliminar($query);
    header('Location: ../../index.php?page=lista_paciente');
}
