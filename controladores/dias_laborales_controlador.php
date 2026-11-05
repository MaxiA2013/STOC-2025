<?php
session_start();
require_once '../modelos/Doctor_Dias.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $diasSeleccionados = $_POST['dias'] ?? [];
    $franjas = $_POST['franjas'] ?? []; // franjas es un array asociativo: franjas['lunes'] = 2

    $dd = new Doctor_Dias();
    $id_doctor = $dd->obtenerIdDoctorPorUsuario($id_usuario);

    if ($id_doctor) {
        // Validación mínima: si seleccionó días pero no seleccionó franjas para ninguno, avisamos
        if (!empty($diasSeleccionados)) {
            $dd->actualizarDiasYFranjasDelDoctor($id_doctor, $diasSeleccionados, $franjas);
            // redirigimos con éxito
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=dias_actualizados");
            exit;
        } else {
            // No se seleccionaron días -> eliminamos todo (ya lo hace el método) y redirigimos con éxito
            $dd->actualizarDiasYFranjasDelDoctor($id_doctor, [], []);
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=dias_actualizados");
            exit;
        }
    } else {
        header("Location: ../vistas/paginas/mis_datos.php?error=no_doctor");
        exit;
    }
}
