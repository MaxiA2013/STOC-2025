<?php
session_start();
require_once '../modelos/Doctor_Dias.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $diasSeleccionados = $_POST['dias'] ?? [];

    $dd = new Doctor_Dias();
    $id_doctor = $dd->obtenerIdDoctorPorUsuario($id_usuario);

    if ($id_doctor) {
        $dd->actualizarDiasDelDoctor($id_doctor, $diasSeleccionados);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        header("Location: ../vistas/paginas/mis_datos.php?error=no_doctor");
        exit;
    }
}
