<?php
//trae las obras_sociales que son del paciente y que son compatibles con el doctor
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/doctor_obra_social.php";
require_once "../../modelos/paciente_obra_social.php";

$doctor_id = intval($_GET["doctor_id"] ?? 0);
$paciente_id = intval($_GET["paciente_id"] ?? 0);

if ($doctor_id <= 0 || $paciente_id <= 0) {
    echo json_encode([
        "success" => false,
        "error" => "Parámetros inválidos.",
        "data" => []
    ]);
    exit();
}

$doctorOS = new Doctor_Obra_Social();
$pacienteOS = new Paciente_Obra_Social();

$obrasDoctor = $doctorOS->consultarArrayPorDoctor($doctor_id);
$obrasPaciente = $pacienteOS->consultarArrayPorPaciente($paciente_id);

// Intersección: solo las obras sociales que doctor y paciente
// tienen en común.
$idsPaciente = array_column($obrasPaciente, 'id_obra_social');

$compatibles = array_values(array_filter(
    $obrasDoctor,
    function ($os) use ($idsPaciente) {
        return in_array($os['id_obra_social'], $idsPaciente);
    }
));

echo json_encode([
    "success" => true,
    "data" => $compatibles
]);