<?php
require_once "../modelos/doctor.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action == "guardar_doctor") {
        $doctor = new Doctor(
            '',
            $_POST['numero_matricula_profesional'],
            $_POST['usuario_id_usuario'],
            $_POST['salario']
        );
        $doctor->guardar();
        header("Location: ../vistas/paginas/lista_doctor.php");
        exit();
    }

    if ($action == "actualizar_doctor") {
        $doctor = new Doctor(
            $_POST['id_doctor'],
            $_POST['numero_matricula_profesional'],
            $_POST['usuario_id_usuario'],
            $_POST['salario']
        );
        $doctor->actualizar();
        header("Location: ../vistas/paginas/lista_doctor.php");
        exit();
    }

    if ($action == "eliminar_doctor") {
        $doctor = new Doctor();
        $doctor->eliminar($_POST['id_doctor']);
        header("Location: ../vistas/paginas/lista_doctor.php");
        exit();
    }
}
