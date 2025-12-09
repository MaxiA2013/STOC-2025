<?php
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/cita.php";

$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

try {
    $cita = new Cita();

    switch ($accion) {

        case 'actualizar_asistencia':
            $id_cita = intval($_POST['id_cita'] ?? 0);
            $as_doc  = $_POST['asistencia_doctor'] ?? null;
            $as_pac  = $_POST['asistencia_paciente'] ?? null;

            if ($id_cita <= 0) {
                echo json_encode(["success" => false, "error" => "id_cita inválido"]);
                exit;
            }

            $res = $cita->actualizarAsistencia($id_cita, $as_doc, $as_pac);

            echo json_encode([
                "success" => $res !== false,
                "error"   => $res !== false ? null : "No se pudo actualizar la asistencia"
            ]);
        break;

        default:
            echo json_encode(["success" => false, "error" => "Acción no reconocida"]);
        break;
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
