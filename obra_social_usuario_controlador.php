<?php
session_start();
require_once "../modelos/paciente_obra_social.php";
require_once "../modelos/doctor_obra_social.php";

$perfil = strtolower($_POST['perfil']);
$id_usuario = $_POST['id_usuario'];
$obrasSeleccionadas = $_POST['obras'] ?? [];

if ($perfil === "paciente") {
    $po = new Paciente_Obra_Social();
    $anteriores = $po->consultarArrayPorPaciente($id_usuario);
    $ids_anteriores = array_map(fn($obra) => $obra['id_obra_social'], $anteriores);

    // Agregar nuevas obras sociales
    foreach ($obrasSeleccionadas as $id_obra_social) {
        if (!in_array($id_obra_social, $ids_anteriores)) {
            $po->asignar($id_usuario, $id_obra_social);
        }
    }

    // Eliminar las que fueron desmarcadas
    foreach ($ids_anteriores as $id_obra_social) {
        if (!in_array($id_obra_social, $obrasSeleccionadas)) {
            $po->eliminar($id_usuario, $id_obra_social);
        }
    }

} elseif ($perfil === "doctor") {
    $do = new Doctor_Obra_Social();
    $id_doctor = $do->obtenerIdDoctorPorUsuario($id_usuario);
    $anteriores = $do->consultarArrayPorDoctor($id_doctor);
    $ids_anteriores = array_map(fn($obra) => $obra['id_obra_social'], $anteriores);

    // Agregar nuevas obras sociales
    foreach ($obrasSeleccionadas as $id_obra_social) {
        if (!in_array($id_obra_social, $ids_anteriores)) {
            $do->asignar($id_doctor, $id_obra_social);
        }
    }

    // Eliminar las que fueron desmarcadas
    foreach ($ids_anteriores as $id_obra_social) {
        if (!in_array($id_obra_social, $obrasSeleccionadas)) {
            $do->eliminar($id_doctor, $id_obra_social);
        }
    }
}

// Esta redirección devuelve al usuario a la página desde donde envió el formulario.
// Es útil cuando el proyecto tiene carpetas con espacios o caracteres especiales,
// ya que evita errores de "URL no encontrada" al usar rutas relativas.
// $_SERVER['HTTP_REFERER'] contiene la URL anterior automáticamente.
header("Location: " . $_SERVER['HTTP_REFERER']);// Redirigir de nuevo a Mis Datos con mensaje de éxito

exit();
