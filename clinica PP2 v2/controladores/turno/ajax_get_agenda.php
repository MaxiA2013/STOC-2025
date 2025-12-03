<?php
require_once "../../modelos/agenda.php";

header("Content-Type: application/json; charset=UTF-8");

try {

    // Capturar doctor_id desde GET
    $doctorId = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;

    if ($doctorId === 0) {
        echo json_encode([
            "status" => "error",
            "message" => "No se recibió doctor_id válido."
        ]);
        exit;
    }

    // Recuperar agendas del doctor
    $agend = new Agenda();
    $agendas = $agend->listarAgendaXDoctor($doctorId);

    echo json_encode([
        "status" => "success",
        "data" => $agendas
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudo obtener la agenda.",
        "error" => $e->getMessage()
    ]);
}
