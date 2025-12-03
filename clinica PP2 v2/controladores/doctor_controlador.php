<?php
require_once "../modelos/doctor.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action == "guardar_doctor") {
        $doctor = new Doctor(
            '',
            $_POST['numero_matricula_profesional'],
            $_POST['usuario_id_usuario'],
            $_POST['precio_consulta']
        );
        $doctor->guardar();
        header("Location: ../index.php?page=lista_doctor");
        exit();
    }

    if ($action == "actualizar_doctor") {
        $doctor = new Doctor(
            $_POST['id_doctor'],
            $_POST['numero_matricula_profesional'],
            $_POST['usuario_id_usuario'],
            $_POST['precio_consulta']
        );
        $doctor->actualizar();
        header("Location: ../index.php?page=lista_doctor");
        exit();
    }

    if ($action == "eliminar_doctor") {
        $doctor = new Doctor();
        $doctor->eliminar($_POST['id_doctor']); // ahora hace eliminación en cascada desde el modelo
        header("Location: ../index.php?page=lista_doctor");
        exit();
    }
}