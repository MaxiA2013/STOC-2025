<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/turno.php';

/**
 * Actualiza los estados de turnos vencidos.
 *
 * Los ids de estado NO están escritos a mano: se leen de la tabla
 * "estados" buscando cada uno por su nombre (sin importar mayúsculas
 * ni tildes).
 */
class ActualizadorTurnos
{
    private static $idsCache = null;

    /** "No asistió" -> "no_asistio" */
    private static function normalizar($texto)
    {
        $texto = mb_strtolower(trim((string) $texto), 'UTF-8');

        $texto = strtr($texto, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u'
        ]);

        return preg_replace('/\s+/', '_', $texto);
    }

    /**
     * Devuelve ['activo' => 1, 'pendiente' => 2, ...] o null si falta
     * algún estado necesario en la tabla "estados".
     */
    public static function obtenerIdsEstados()
    {
        if (self::$idsCache !== null) {
            return self::$idsCache;
        }

        $con = new Conexion();

        $filas = $con->consultarArray(
            "SELECT id_estados, tipo_estado FROM estados"
        );

        $ids = [];

        foreach ($filas as $fila) {
            $ids[self::normalizar($fila['tipo_estado'])] = intval($fila['id_estados']);
        }

        $necesarios = [
            'activo', 'pendiente', 'cancelado',
            'no_asistio', 'asistio', 'no_aprobado', 'vencido'
        ];

        foreach ($necesarios as $clave) {

            if (!isset($ids[$clave])) {

                error_log(
                    "ActualizadorTurnos: no existe en 'estados' el estado '$clave'."
                );

                return null;
            }
        }

        self::$idsCache = $ids;

        return $ids;
    }

    /**
     * Activo vencido    -> No asistió
     * Pendiente vencido -> Vencido
     * Un turno vence cuando TERMINA (inicio + duración).
     * Devuelve cuántos turnos cambió.
     */
    public static function marcarVencidos()
    {
        // Evita que dos ejecuciones simultáneas (web + tarea programada)
        // procesen los mismos turnos a la vez.
        $lock = fopen(
            sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'turnos_vencidos.lock',
            'c'
        );

        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            return 0;
        }

        try {

            $ids = self::obtenerIdsEstados();

            if ($ids === null) {
                return 0;
            }

            $activo    = $ids['activo'];
            $pendiente = $ids['pendiente'];

            $con = new Conexion();

            // Hora actual calculada en PHP (zona Argentina).
            $zona  = new DateTimeZone('America/Argentina/Buenos_Aires');
            $ahora = (new DateTime('now', $zona))->format('Y-m-d H:i:s');

            $vencidos = $con->consultarArray("
                SELECT
                    at.id_agenda_turno,
                    at.turno_id_turnos,
                    at.estados_id_estados
                FROM agenda_turno at
                INNER JOIN turno t
                    ON at.turno_id_turnos = t.id_turnos
                WHERE at.estados_id_estados IN ($activo, $pendiente)
                  AND DATE_ADD(t.fecha_hora, INTERVAL t.minutos_turnos MINUTE) < '$ahora'
            ");

            if (empty($vencidos)) {
                return 0;
            }

            $turnoModel = new Turno();

            $sistema = ['id' => null, 'nombre' => 'Sistema', 'perfil' => null];

            $cambiados = 0;

            foreach ($vencidos as $fila) {

                $idAgendaTurno = intval($fila['id_agenda_turno']);
                $idTurno       = intval($fila['turno_id_turnos']);
                $anterior      = intval($fila['estados_id_estados']);

                // Para usar "No aprobado" (12) en lugar de "Vencido" (13),
                // cambiá $ids['vencido'] por $ids['no_aprobado'].
                $nuevo = ($anterior === $activo)
                    ? $ids['no_asistio']
                    : $ids['vencido'];

                // "AND estados_id_estados = $anterior": si el estado cambió
                // mientras tanto, esta sentencia no lo pisa.
                $ok = $con->actualizar("
                    UPDATE agenda_turno
                    SET estados_id_estados = $nuevo
                    WHERE id_agenda_turno = $idAgendaTurno
                      AND estados_id_estados = $anterior
                ");

                if ($ok === false) {
                    continue;
                }

                $turnoModel->registrarHistorial(
                    $idTurno,
                    $sistema,
                    ($nuevo === $ids['no_asistio']) ? 'no_asistio' : 'vencido',
                    'estados_id_estados',
                    $anterior,
                    $nuevo,
                    'Cambio automático: el turno terminó sin ser atendido o aprobado.'
                );

                $cambiados++;
            }

            return $cambiados;

        } finally {

            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}