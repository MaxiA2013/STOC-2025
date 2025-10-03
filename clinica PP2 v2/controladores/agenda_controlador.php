<?php
require_once "../modelos/agenda.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    if ($action == "guardar_agenda") {
        $agenda = new Agenda(
            null,
            $_POST["fecha_agenda"],
            $_POST["hora_desde"],
            $_POST["hora_hasta"],
            $_POST["estados_id_estados"],
            $_POST["doctor_id_doctor"]
        );
        $resultado = $agenda->guardar();
    }

    if ($action == "modificar_agenda") {
        $agenda = new Agenda(
            null,
            $_POST["fecha_agenda"],
            $_POST["hora_desde"],
            $_POST["hora_hasta"],
            $_POST["estados_id_estados"],
            $_POST["doctor_id_doctor"]
        );
        $resultado = $agenda->modificar($_POST["id_agenda"]);
    }

    if ($action == "eliminar_agenda") {
        $agenda = new Agenda();
        $resultado = $agenda->cambiarEstado($_POST["id_agenda"], $_POST["estado_inactivo_id"]);
    }

    $mensaje = $resultado ? "Operación realizada correctamente" : "Error en la operación";
    $status = $resultado ? "success" : "danger";
    header("Location: ../index.php?page=lista_agenda&message=$mensaje&status=$status");
    exit();
}
