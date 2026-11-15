<?php
/**
 * AVISO DIARIO DE CONFIRMACIÓN DE ASISTENCIA
 *
 * Se ejecuta por consola (Programador de tareas de Windows / cron),
 * por ejemplo una vez por hora durante el horario de atención.
 *
 *   - Crea la fila de "confirmacion" de cada turno ACTIVO de hoy que
 *     todavía no la tiene (y que aún no pasó).
 *   - Avisa al paciente (un aviso por turno) y al doctor (un único aviso
 *     resumen por doctor).
 *
 * Es idempotente: solo procesa los turnos sin fila de confirmación,
 * así que se puede ejecutar todas las veces que haga falta sin duplicar avisos.
 *
 * Ejemplo (XAMPP en Windows):
 *   C:\xampp\php\php.exe C:\xampp\htdocs\carpeta-limpia\clinica PP2 v2\controladores\cita\enviar_confirmaciones.php
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit("Este script solo se puede ejecutar desde la consola.");
}

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once __DIR__ . "/../../modelos/confirmacion.php";
require_once __DIR__ . "/../../modelos/notificacion.php";

// Páginas a las que llevan las notificaciones (ajustar al nombre real de cada página)
const URL_NOTIF_PACIENTE = 'index.php?page=miperfil_paciente';
const URL_NOTIF_DOCTOR   = 'index.php?page=historial_citas';

$confirmacion = new Confirmacion();
$notificacion = new Notificacion();

$turnos = $confirmacion->listarTurnosDelDiaSinConfirmacion();

$creadas = 0;
$resumenDoctores = [];

foreach ($turnos as $t) {
    $id_confirmacion = $confirmacion->crearParaAgendaTurno(
        $t['id_agenda_turno']
    );

    if (!$id_confirmacion) {
        echo "No se pudo crear la confirmación del turno " .
            $t['id_agenda_turno'] . PHP_EOL;
        continue;
    }

    $creadas++;

    $hora = date('H:i', strtotime($t['fecha_hora']));

    // Aviso al paciente
    $notificacion->crearYNotificar(
        $t['paciente_usuario_id'],
        'Confirmá tu asistencia',
        'Hoy a las ' . $hora . ' tenés turno con el Dr. ' .
            trim($t['doctor_apellido']) . '. Confirmá si vas a asistir.',
        'confirmacion_turno',
        URL_NOTIF_PACIENTE
    );

    // Se cuenta para el aviso resumen del doctor
    $idUsuarioDoctor = $t['doctor_usuario_id'];

    if (!isset($resumenDoctores[$idUsuarioDoctor])) {
        $resumenDoctores[$idUsuarioDoctor] = 0;
    }

    $resumenDoctores[$idUsuarioDoctor]++;
}

// Un único aviso por doctor
foreach ($resumenDoctores as $idUsuarioDoctor => $cantidad) {

    $mensaje = ($cantidad === 1)
        ? 'Tenés 1 turno para confirmar hoy.'
        : 'Tenés ' . $cantidad . ' turnos para confirmar hoy.';

    $notificacion->crearYNotificar(
        $idUsuarioDoctor,
        'Confirmá tu asistencia de hoy',
        $mensaje,
        'confirmacion_turno',
        URL_NOTIF_DOCTOR
    );
}

echo '[' . date('Y-m-d H:i:s') . '] Confirmaciones creadas: ' . $creadas .
    ' | Doctores avisados: ' . count($resumenDoctores) . PHP_EOL;