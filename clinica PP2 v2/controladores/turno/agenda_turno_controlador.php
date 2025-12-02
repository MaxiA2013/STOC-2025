<?php
// controladores/agenda_turno_controlador.php
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/turno.php";
require_once "../../modelos/agenda.php";
require_once "../../modelos/conexion.php";

$action = $_POST["action"] ?? $_GET["action"] ?? null;

try {

    // ------------------------
    // INSERTAR (ASIGNAR TURNO A PACIENTE)
    // ------------------------
    if ($action === "insertar") {

        $paciente_id = intval($_POST["paciente_id"] ?? 0);
        $turno_id = intval($_POST["turno_id"] ?? $_POST["turno_existente_id"] ?? 0);
        $estado_id = intval($_POST["estados_id_estados"] ?? 1); // por defecto 1 si aplica

        if ($paciente_id <= 0 || $turno_id <= 0) {
            echo json_encode(["success" => false, "error" => "Faltan parámetros (paciente o turno)."]);
            exit;
        }

        $con = new Conexion();

        // 1) verificar que turno existe y está disponible
        $t = $con->consultarArray("SELECT disponible FROM turno WHERE id_turnos = $turno_id");
        if (empty($t)) {
            echo json_encode(["success" => false, "error" => "Turno no existe."]);
            exit;
        }
        if (intval($t[0]['disponible']) !== 1) {
            echo json_encode(["success" => false, "error" => "Turno no disponible."]);
            exit;
        }

        // 2) insertar agenda_turno
        $sqlIns = "INSERT INTO agenda_turno (paciente_id_paciente, turno_id_turnos, estados_id_estados)
                   VALUES ($paciente_id, $turno_id, $estado_id)";
        $id = $con->insertar($sqlIns);

        if (!$id) {
            echo json_encode(["success" => false, "error" => "No se pudo asignar el turno."]);
            exit;
        }

        // 3) marcar turno como no disponible
        $con->actualizar("UPDATE turno SET disponible = 0 WHERE id_turnos = $turno_id");

        echo json_encode(["success" => true, "id_agenda_turno" => $id]);
        exit;
    }

    // ------------------------
    // ACTUALIZAR (modificar asignación)
    // ------------------------
    if ($action === "actualizacion" || $action === "actualizar") {

        $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? 0);
        $paciente_id = intval($_POST["paciente_id"] ?? 0);
        $turno_id_new = intval($_POST["turno_id"] ?? $_POST["turno_existente_id"] ?? 0);
        $estado_id = intval($_POST["estados_id_estados"] ?? 1);

        if ($id_agenda_turno <= 0 || $paciente_id <= 0 || $turno_id_new <= 0) {
            echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios."]);
            exit;
        }

        $con = new Conexion();

        // obtener la asignación actual
        $actual = $con->consultarArray("SELECT turno_id_turnos FROM agenda_turno WHERE id_agenda_turno = $id_agenda_turno");
        if (empty($actual)) {
            echo json_encode(["success" => false, "error" => "Asignación no encontrada."]);
            exit;
        }
        $turno_old = intval($actual[0]['turno_id_turnos']);

        // si cambió el turno: verificar disponibilidad del nuevo y actualizar disponibilidades
        if ($turno_old !== $turno_id_new) {
            $check = $con->consultarArray("SELECT disponible FROM turno WHERE id_turnos = $turno_id_new");
            if (empty($check)) {
                echo json_encode(["success" => false, "error" => "Nuevo turno no existe."]);
                exit;
            }
            if (intval($check[0]['disponible']) !== 1) {
                echo json_encode(["success" => false, "error" => "Nuevo turno no disponible."]);
                exit;
            }

            // marcar nuevo turno como ocupado (disponible=0) y liberar el viejo (disponible=1)
            $con->actualizar("UPDATE turno SET disponible = 1 WHERE id_turnos = $turno_old");
            $con->actualizar("UPDATE turno SET disponible = 0 WHERE id_turnos = $turno_id_new");
        }

        // actualizar registro agenda_turno
        $sqlUpd = "UPDATE agenda_turno SET
                    paciente_id_paciente = $paciente_id,
                    turno_id_turnos = $turno_id_new,
                    estados_id_estados = $estado_id
                   WHERE id_agenda_turno = $id_agenda_turno";

        $res = $con->actualizar($sqlUpd);
        if ($res !== false) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "No se pudo actualizar la asignación."]);
        }
        exit;
    }

    // ------------------------
    // ELIMINAR (desasignar)
    // ------------------------
    if ($action === "eliminar" || $action === "eliminacion") {

        $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? $_POST["id"] ?? 0);
        if ($id_agenda_turno <= 0) {
            echo json_encode(["success" => false, "error" => "ID inválido."]);
            exit;
        }

        $con = new Conexion();
        // obtener turno relacionado
        $row = $con->consultarArray("SELECT turno_id_turnos FROM agenda_turno WHERE id_agenda_turno = $id_agenda_turno");
        if (empty($row)) {
            echo json_encode(["success" => false, "error" => "Registro no encontrado."]);
            exit;
        }
        $turno_id = intval($row[0]['turno_id_turnos']);

        // eliminar registro
        $del = $con->eliminar("DELETE FROM agenda_turno WHERE id_agenda_turno = $id_agenda_turno");
        if (!$del) {
            echo json_encode(["success" => false, "error" => "No se pudo eliminar la asignación."]);
            exit;
        }

        // liberar turno (marcar disponible)
        $con->actualizar("UPDATE turno SET disponible = 1 WHERE id_turnos = $turno_id");

        echo json_encode(["success" => true]);
        exit;
    }

    // acción desconocida
    echo json_encode(["success" => false, "error" => "Acción desconocida o no proporcionada."]);

} catch (Exception $ex) {
    echo json_encode(["success" => false, "error" => $ex->getMessage()]);
}
?>