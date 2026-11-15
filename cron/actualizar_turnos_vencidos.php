<?php
// Solo se ejecuta desde línea de comandos (tarea programada).
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Acceso denegado.');
}

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once __DIR__ . '/../modelos/actualizador_turnos.php';

try {

    $cantidad = ActualizadorTurnos::marcarVencidos();

    $linea = '[' . date('Y-m-d H:i:s') . "] Turnos actualizados: $cantidad\n";

} catch (Throwable $e) {

    $linea = '[' . date('Y-m-d H:i:s') . '] ERROR: ' . $e->getMessage() . "\n";
}

file_put_contents(__DIR__ . '/actualizar_turnos_vencidos.log', $linea, FILE_APPEND);

echo $linea;