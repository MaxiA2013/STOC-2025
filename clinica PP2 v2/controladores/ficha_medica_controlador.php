<?php
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/ficha_medica.php";

$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

try {

    $ficha = new FichaMedica();

    switch ($accion) {

        case 'guardar':
            $id_cita          = intval($_POST['cita_id_cita'] ?? 0);
            $altura           = $_POST['altura'] ?? '';
            $peso             = $_POST['peso'] ?? '';
            $medicacion       = $_POST['medicacion_actual'] ?? '';
            $observaciones    = $_POST['observaciones'] ?? '';

            if ($id_cita <= 0) {
                echo json_encode(["success" => false, "error" => "Cita inválida"]);
                exit;
            }

            // ¿ya existe ficha?
            $existente = $ficha->obtenerPorCita($id_cita);
            if ($existente) {
                $res = $ficha->actualizar(
                    $existente['id_ficha_medica'],
                    $altura,
                    $peso,
                    $medicacion,
                    $observaciones
                );
                $id_ficha = $existente['id_ficha_medica'];
            } else {
                $id_ficha = $ficha->crear($altura, $peso, $medicacion, $observaciones, $id_cita);
                $res = $id_ficha ? true : false;
            }

            echo json_encode([
                "success" => $res !== false,
                "id_ficha_medica" => $id_ficha ?? null
            ]);
        break;

        default:
            echo json_encode(["success" => false, "error" => "Acción no reconocida"]);
        break;
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
