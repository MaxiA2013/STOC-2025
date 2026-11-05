<?php

require_once '../config/pusher.php';

try {

    $datos = [
        'mensaje' => '¡Pusher funciona correctamente!',
        'fecha' => date('Y-m-d H:i:s')
    ];

    $resultado = $pusher->trigger(
        'notificaciones',
        'nueva-notificacion',
        $datos
    );

    echo json_encode([
        'success' => true,
        'mensaje' => 'Evento enviado correctamente',
        'resultado' => $resultado
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'mensaje' => $e->getMessage()
    ]);
}