<?php
// controladores/ajax_get_agenda.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../modelos/agenda.php';

if (!isset($_GET['doctor_id'])) {
    echo json_encode(['error' => 'Falta parámetro doctor_id']);
    exit;
}

$doctor_id = intval($_GET['doctor_id']);

try {
    $agendaObj = new Agenda();
    $agendas = $agendaObj->obtenerAgendas();

    $result = [];
    foreach ($agendas as $a) {
        if ((int)$a['doctor_id_doctor'] === $doctor_id) {
            $hora_desde = isset($a['hora_desde']) ? substr($a['hora_desde'], 0, 5) : '';
            $hora_hasta = isset($a['hora_hasta']) ? substr($a['hora_hasta'], 0, 5) : '';

            $result[] = [
                'id_agenda'    => $a['id_agenda'],
                'fecha_agenda' => $a['fecha_agenda'],
                'hora_desde'   => $hora_desde,
                'hora_hasta'   => $hora_hasta
            ];
        }
    }

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
