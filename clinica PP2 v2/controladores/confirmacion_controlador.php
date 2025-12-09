<?php
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/confirmacion.php";
require_once "../../modelos/cita.php";

$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

try {

    $confModel = new Confirmacion();
    $citaModel = new Cita();

    switch ($accion) {

        // Crear confirmación para un agenda_turno (cuando se asigna turno)
        case 'crear_para_agenda_turno':
            $id_agenda_turno = intval($_POST['id_agenda_turno'] ?? 0);
            if ($id_agenda_turno <= 0) {
                echo json_encode(["success" => false, "error" => "id_agenda_turno inválido"]);
                exit;
            }
            $id_conf = $confModel->crearParaAgendaTurno($id_agenda_turno);
            echo json_encode(["success" => true, "id_confirmacion" => $id_conf]);
        break;

        // Registrar respuesta de confirmación (paciente o doctor)
        case 'responder':
            $id_confirmacion = intval($_POST['id_confirmacion'] ?? 0);
            $tipo            = $_POST['tipo'] ?? ''; // 'paciente' o 'doctor'
            $respuesta       = intval($_POST['respuesta'] ?? 0); // 1 = sí, 0 = no

            if ($id_confirmacion <= 0 || !in_array($tipo, ['paciente', 'doctor'])) {
                echo json_encode(["success" => false, "error" => "Parámetros inválidos"]);
                exit;
            }

            $esPaciente = ($tipo === 'paciente');

            $confModel->actualizarConfirmacion($id_confirmacion, $esPaciente, $respuesta);

            // Si alguno responde "no", simplemente devolvemos sin crear cita
            if ($respuesta !== 1) {
                echo json_encode(["success" => true, "cita_creada" => false]);
                exit;
            }

            // Verificar si ambos confirmaron
            if ($confModel->ambosConfirmaron($id_confirmacion)) {

                // ¿Ya existe una cita para esta confirmación?
                $citaExistente = $citaModel->obtenerPorConfirmacion($id_confirmacion);

                if (!$citaExistente) {
                    // Crear cita simple (razón opcional)
                    $id_cita = $citaModel->crearDesdeConfirmacion($id_confirmacion, "Cita generada automáticamente.");
                    echo json_encode([
                        "success" => true,
                        "cita_creada" => true,
                        "id_cita" => $id_cita
                    ]);
                } else {
                    echo json_encode([
                        "success" => true,
                        "cita_creada" => false,
                        "id_cita" => $citaExistente['id_cita']
                    ]);
                }

            } else {
                echo json_encode(["success" => true, "cita_creada" => false]);
            }

        break;

        default:
            echo json_encode(["success" => false, "error" => "Acción no reconocida"]);
        break;
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
