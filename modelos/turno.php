<?php
require_once 'conexion.php';

class TurnoHistorial
{
    private $id_turno_historial;
    private $turno_id_turnos;
    private $usuario_id_usuario;
    private $accion;
    private $detalle;
    private $fecha_registro;
}

class Turno
{
    private $id_turnos;
    private $minutos_turnos;
    private $fecha_hora;
    private $disponible;
    private $agenda_id_agenda;
    private $con_obra_social;
    private $obra_social_id_obra_social;

    public function guardarTurno()
    {
        $con = new Conexion();
        $agenda = intval($this->agenda_id_agenda);
        $minutos = intval($this->minutos_turnos);
        $disponible = intval($this->disponible);
        $fecha_hora = trim($this->fecha_hora);

        if ($agenda <= 0 || $minutos <= 0 || $fecha_hora === '') {
            return false;
        }

        // Evitar duplicar un turno en la misma agenda y horario
        $existente = $this->existeTurnoEnAgenda($agenda, $fecha_hora);

        if ($existente) {
            // Devolvemos el ID existente.
            // Esto es especialmente útil para "crear y asignar".
            return $existente;
        }

        $query = "INSERT INTO turno
              (minutos_turnos, fecha_hora, disponible, agenda_id_agenda)
              VALUES ($minutos, '$fecha_hora', $disponible, $agenda)";

        return $con->insertar($query);
    }

    // GENERAR TURNOS
    public function generarTurnosParaAgenda($id_agenda, $fechaDesde, $fechaHasta, $horaDesde, $horaHasta, $minutos)
    {
        $this->agenda_id_agenda = intval($id_agenda);
        $minutos = intval($minutos);

        if ($minutos <= 0) {
            return false;
        }

        $fechaActual = new DateTime($fechaDesde);
        $fechaFinal = new DateTime($fechaHasta);

        while ($fechaActual <= $fechaFinal) {

            $fecha = $fechaActual->format("Y-m-d");

            $horaActual = strtotime("$fecha $horaDesde");
            $horaFin = strtotime("$fecha $horaHasta");

            while ($horaActual < $horaFin) {

                $this->minutos_turnos = $minutos;
                $this->fecha_hora = date("Y-m-d H:i:s", $horaActual);
                $this->disponible = 1;

                $this->guardarTurno();

                $horaActual = strtotime("+$minutos minutes", $horaActual);
            }

            $fechaActual->modify("+1 day");
        }

        return true;
    }

    public function eliminarPorAgenda($agenda_id_agenda)
    {
        $con = new Conexion();
        $agenda_id_agenda = intval($agenda_id_agenda);
        $sql = "DELETE FROM turno
            WHERE agenda_id_agenda = $agenda_id_agenda";
        return $con->eliminar($sql);
    }

        public function obtenerTurnosPorDoctor($id_doctor)
    {
        $con = new Conexion();
        $id_doctor = intval($id_doctor);

        $sql = "
            SELECT
                at.id_agenda_turno,
                t.id_turnos AS turno_id_turnos,
                t.fecha_hora,
                t.minutos_turnos,
                e.tipo_estado,
                CONCAT(per.nombre, ' ', per.apellido) AS paciente_nombre
            FROM agenda_turno at
            INNER JOIN turno t ON at.turno_id_turnos = t.id_turnos
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN paciente p ON at.paciente_id_paciente = p.id_paciente
            INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            INNER JOIN estados e ON at.estados_id_estados = e.id_estados
            WHERE a.doctor_id_doctor = $id_doctor
            ORDER BY t.fecha_hora ASC
        ";

        return $con->consultarArray($sql);
    }

    public function obtenerTurnosPorAgenda($idAgenda)
    {
        $con = new Conexion();

        $idAgenda = intval($idAgenda);

        $sql = "SELECT
                t.id_turnos,
                t.minutos_turnos,
                t.fecha_hora,
                t.disponible,
                t.agenda_id_agenda,
                CASE
                    WHEN at.id_agenda_turno IS NULL THEN 0
                    ELSE 1
                END AS asignado
            FROM turno t
            LEFT JOIN agenda_turno at
                ON at.turno_id_turnos = t.id_turnos
            WHERE t.agenda_id_agenda = $idAgenda
            ORDER BY t.fecha_hora ASC";

        return $con->consultarArray($sql);
    }

    public function eliminarTurno($idTurno)
    {
        $con = new Conexion();

        $idTurno = intval($idTurno);

        $sql = "DELETE FROM turno
            WHERE id_turnos = $idTurno";

        return $con->eliminar($sql);
    }

    public function actualizarMinutos($idTurno, $minutos)
    {
        $con = new Conexion();

        $idTurno = intval($idTurno);
        $minutos = intval($minutos);

        $sql = "UPDATE turno
            SET minutos_turnos = $minutos
            WHERE id_turnos = $idTurno";

        return $con->actualizar($sql);
    }

    public function sincronizarTurnosAgenda(
        $idAgenda,
        $fechaDesde,
        $fechaHasta,
        $horaDesde,
        $horaHasta,
        $minutos
    ) {
        $idAgenda = intval($idAgenda);
        $minutos = intval($minutos);

        if ($minutos <= 0) {
            return [
                "success" => false,
                "error" => "La duración de los turnos debe ser mayor a 0."
            ];
        }

        /*
     * 1. GENERAR LA LISTA DE HORARIOS QUE DEBERÍAN EXISTIR
     */

        $turnosEsperados = [];

        $fechaActual = new DateTime($fechaDesde);
        $fechaFinal = new DateTime($fechaHasta);

        while ($fechaActual <= $fechaFinal) {

            $fecha = $fechaActual->format("Y-m-d");

            $horaActual = strtotime("$fecha $horaDesde");
            $horaFin = strtotime("$fecha $horaHasta");

            while ($horaActual < $horaFin) {

                $fechaHora = date("Y-m-d H:i:s", $horaActual);

                $turnosEsperados[$fechaHora] = true;

                $horaActual = strtotime("+$minutos minutes", $horaActual);
            }

            $fechaActual->modify("+1 day");
        }

        /*
     * =====================================================
     * 2. OBTENER TURNOS ACTUALES
     * =====================================================
     */

        $turnosActuales = $this->obtenerTurnosPorAgenda($idAgenda);

        $mapaActuales = [];

        foreach ($turnosActuales as $turno) {

            $fechaHora = $turno["fecha_hora"];

            $mapaActuales[$fechaHora] = $turno;
        }

        /*
     * =====================================================
     * 3. COMPROBAR SI HAY TURNOS ASIGNADOS
     *    QUE SE PERDERÍAN
     * =====================================================
     */

        $turnosAsignadosAEliminar = [];

        foreach ($mapaActuales as $fechaHora => $turno) {

            if (!isset($turnosEsperados[$fechaHora])) {

                if (intval($turno["asignado"]) === 1) {

                    $turnosAsignadosAEliminar[] = $turno;
                }
            }
        }

        /*
     * Si existe algún turno asignado que quedaría fuera
     * de la nueva configuración, NO modifica nada.
     */

        if (!empty($turnosAsignadosAEliminar)) {

            $fechas = [];

            foreach ($turnosAsignadosAEliminar as $turno) {
                $fechas[] = date(
                    "d/m/Y H:i",
                    strtotime($turno["fecha_hora"])
                );
            }

            return [
                "success" => false,
                "error" => "Hay turnos asignados que quedarían fuera de la nueva configuración.",
                "turnos_asignados" => $fechas
            ];
        }

        /*
     * 4. ELIMINAR TURNOS QUE YA NO CORRESPONDEN
     */

        foreach ($mapaActuales as $fechaHora => $turno) {

            if (!isset($turnosEsperados[$fechaHora])) {

                $this->eliminarTurno($turno["id_turnos"]);
            }
        }

        /*
     * 5. CREAR LOS TURNOS NUEVOS
     */

        foreach ($turnosEsperados as $fechaHora => $valor) {

            if (!isset($mapaActuales[$fechaHora])) {

                $this->agenda_id_agenda = $idAgenda;
                $this->minutos_turnos = $minutos;
                $this->fecha_hora = $fechaHora;
                $this->disponible = 1;

                $this->guardarTurno();
            }
        }

        /*
     *  6. ACTUALIZAR DURACIÓN DE LOS TURNOS EXISTENTES
     */

        foreach ($mapaActuales as $fechaHora => $turno) {

            if (isset($turnosEsperados[$fechaHora])) {

                if (intval($turno["minutos_turnos"]) !== $minutos) {

                    $this->actualizarMinutos(
                        $turno["id_turnos"],
                        $minutos
                    );
                }
            }
        }

        return [
            "success" => true
        ];
    }


    public function eliminarPorAgendaAsignados($agenda_id_agenda)
    {
        $con = new Conexion();
        $agenda_id_agenda = intval($agenda_id_agenda);

        // 1) Eliminar primero las asignaciones en agenda_turno para los turnos de esta agenda
        $sqlAgendaTurno = "
        DELETE at
        FROM agenda_turno at
        INNER JOIN turno t ON at.turno_id_turnos = t.id_turnos
        WHERE t.agenda_id_agenda = $agenda_id_agenda
    ";
        $con->eliminar($sqlAgendaTurno);

        // 2) Ahora sí, eliminar los turnos de esa agenda
        $sqlTurnos = "DELETE FROM turno WHERE agenda_id_agenda = $agenda_id_agenda";
        return $con->eliminar($sqlTurnos);
    }

    public function eliminarNoAsignadosPorAgenda($agenda_id_agenda)
    {
        $con = new Conexion();
        $agenda_id_agenda = intval($agenda_id_agenda);

        // Borra solo los turnos de esa agenda que NO tengan registro en agenda_turno
        $sql = "
        DELETE t
        FROM turno t
        LEFT JOIN agenda_turno at 
            ON at.turno_id_turnos = t.id_turnos
        WHERE t.agenda_id_agenda = $agenda_id_agenda
          AND at.id_agenda_turno IS NULL
    ";

        return $con->eliminar($sql);
    }

    public function consultarVariosTurnos()
    {
        $conexion = new Conexion();
        $query = "SELECT 
                t.id_turnos,
                t.minutos_turnos,
                t.fecha_hora,
                t.disponible,
                t.agenda_id_agenda AS agenda_id,
                a.fecha_desde AS fecha_agenda,
                d.id_doctor AS doctor_id,
                per.nombre AS nombre_doctor,
                per.apellido
            FROM turno t
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            ORDER BY t.fecha_hora ASC";

        return $conexion->consultar($query);
    }

    public function turnos_buscador($columnas, $campo, $sLimit)
{
    $con = new Conexion();

    $campo = addslashes(trim($campo));

    $where = "";

    if ($campo !== '') {
        $condiciones = [];

        foreach ($columnas as $columna) {
            $condiciones[] = "$columna LIKE '%$campo%'";
        }

        $where = "WHERE (" . implode(" OR ", $condiciones) . ")";
    }

    $query = "SELECT 
                t.id_turnos,
                t.minutos_turnos,
                t.fecha_hora,
                t.disponible,
                t.agenda_id_agenda AS agenda_id,
                a.fecha_desde AS fecha_agenda,
                d.id_doctor AS doctor_id,
                per.nombre AS nombre_doctor,
                per.apellido
            FROM turno t
            INNER JOIN agenda a 
                ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d 
                ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u 
                ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per 
                ON u.persona_id_persona = per.id_persona

            $where

            ORDER BY t.fecha_hora ASC

            $sLimit";

    return $con->consultar($query);
}


public function turnos_filtradosWhere($campo)
{
    $con = new Conexion();

    $campo = addslashes(trim($campo));

    $where = "";

    if ($campo !== '') {

        $where = "WHERE (
            t.id_turnos LIKE '%$campo%'
            OR t.minutos_turnos LIKE '%$campo%'
            OR t.fecha_hora LIKE '%$campo%'
            OR t.disponible LIKE '%$campo%'
            OR t.agenda_id_agenda LIKE '%$campo%'
            OR a.fecha_desde LIKE '%$campo%'
            OR d.id_doctor LIKE '%$campo%'
            OR per.nombre LIKE '%$campo%'
            OR per.apellido LIKE '%$campo%'
        )";
    }

    $query = "SELECT COUNT(*) AS total

            FROM turno t

            INNER JOIN agenda a
                ON t.agenda_id_agenda = a.id_agenda

            INNER JOIN doctor d
                ON a.doctor_id_doctor = d.id_doctor

            INNER JOIN usuario u
                ON d.usuario_id_usuario = u.id_usuario

            INNER JOIN persona per
                ON u.persona_id_persona = per.id_persona

            $where";

    $resultado = $con->consultar($query);

    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        return (int) $fila['total'];
    }

    return 0;
}


public function turnos_filtradosSinWhere()
{
    $con = new Conexion();

    $query = "SELECT COUNT(*) AS total
              FROM turno";

    $resultado = $con->consultar($query);

    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        return (int) $fila['total'];
    }

    return 0;
}

    public function listarTurnoXAgenda($id_agenda)
    {
        $con = new Conexion();
        $query = "SELECT 
            t.id_turnos,
            t.minutos_turnos,
            t.fecha_hora,
            t.disponible,
            t.agenda_id_agenda
        FROM turno t
        WHERE t.agenda_id_agenda = $id_agenda
        ORDER BY t.fecha_hora ASC";
        return $con->consultar($query);
    }

    public function actualizar()
    {
        $con = new Conexion();

        $con_obra_social = intval($this->con_obra_social) === 1 ? 1 : 0;

        // Invariante: si no hay obra social, el id jamás se graba,
        // sin importar qué haya quedado seteado en el objeto.
        $obra_social_sql = ($con_obra_social === 1 && !empty($this->obra_social_id_obra_social))
            ? intval($this->obra_social_id_obra_social)
            : 'NULL';

        $query = "UPDATE turno SET
                    minutos_turnos = '$this->minutos_turnos',
                    fecha_hora = '$this->fecha_hora',
                    disponible = '$this->disponible',
                    agenda_id_agenda = '$this->agenda_id_agenda',
                    con_obra_social = $con_obra_social,
                    obra_social_id_obra_social = $obra_social_sql
                WHERE id_turnos = '$this->id_turnos'";
        return $con->actualizar($query);
    }

    public function actualizarDisponible($id_turnos)
    {
        $con = new Conexion();
        $query = "UPDATE turno SET disponible = 0 WHERE id_turnos = $id_turnos";
        return $con->actualizar($query);
    }

    public function existeTurnoDisponible($id_turnos)
    {
        $con = new Conexion();
        $sql = "SELECT disponible FROM turno WHERE id_turnos = $id_turnos";
        return $con->consultar($sql);
    }

    public function listarTurnosDisponibles()
    {
        $con = new Conexion();
        $query = "SELECT 
                t.id_turnos,
                t.minutos_turnos,
                t.fecha_hora,
                t.disponible,
                t.agenda_id_agenda,
                d.id_doctor AS doctor_id,
                per.nombre AS nombre_doctor,
                per.apellido AS apellido_doctor,
                a.fecha_desde,
                a.hora_desde,
                a.hora_hasta
            FROM turno t
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            WHERE t.disponible = 1
            ORDER BY t.fecha_hora ASC";

        return $con->consultar($query);
    }

    public function consultarTurnosDisponiblesPaginado($offset, $porPagina)
    {
        $con = new Conexion();

        $query = "SELECT 
                t.id_turnos,
                t.fecha_hora,
                t.minutos_turnos,
                t.disponible,
                t.agenda_id_agenda,
                a.fecha_desde,
                d.id_doctor,
                per.nombre,
                per.apellido
            FROM turno t
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            WHERE t.disponible = 1
            ORDER BY t.fecha_hora ASC
            LIMIT $offset, $porPagina";

        $res = $con->consultar($query);

        // Convertimos el mysqli_result en array
        $datos = [];
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $datos[] = $fila;
            }
        }
        return $datos;
    }
    public function contarTurnosDisponibles()
    {

        $con = new Conexion();
        $query = "SELECT COUNT(*) AS total FROM turno WHERE disponible = 1";

        $res = $con->consultar($query);

        if ($res) {
            $fila = $res->fetch_assoc(); // Convertimos el resultado en un array asociativo
            return $fila['total'];       // Devolvemos el número
        }

        return 0; // si hay error

    }
    public function obtenerTurnoPorId($id_turnos)
    {
        $con = new Conexion();

        $query = "SELECT 
                t.id_turnos,
                t.fecha_hora,
                t.minutos_turnos,
                t.disponible,
                t.agenda_id_agenda,
                a.fecha_desde,
                d.id_doctor,
                per.nombre,
                per.apellido
            FROM turno t
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            WHERE t.id_turnos = $id_turnos
            LIMIT 1";

        $res = $con->consultar($query); // retorna mysqli_result

        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc(); // convertir a array asociativo
        } else {
            return null;
        }
    }

    public function existeTurnoEnAgenda($agenda_id_agenda, $fecha_hora)
    {
        $con = new Conexion();

        $agenda_id_agenda = intval($agenda_id_agenda);
        $fecha_hora = trim($fecha_hora);

        if ($agenda_id_agenda <= 0 || $fecha_hora === '') {
            return false;
        }

        $sql = "SELECT id_turnos
            FROM turno
            WHERE agenda_id_agenda = $agenda_id_agenda
              AND fecha_hora = '$fecha_hora'
            LIMIT 1";

        $datos = $con->consultarArray($sql);

        return $datos[0]['id_turnos'] ?? false;
    }

    /**
 * Estadísticas generales del dashboard de un doctor.
 */
public function obtenerEstadisticasDoctor($idDoctor)
{
    $con = new Conexion();

    $idDoctor = intval($idDoctor);

    if ($idDoctor <= 0) {
        return [];
    }

    $sql = "SELECT

                COUNT(DISTINCT CASE
                    WHEN DATE(t.fecha_hora) = CURDATE()
                    THEN t.id_turnos
                END) AS turnos_hoy,

                COUNT(DISTINCT CASE
                    WHEN DATE(t.fecha_hora) = CURDATE()
                    AND t.disponible = 1
                    THEN t.id_turnos
                END) AS turnos_disponibles_hoy,

                COUNT(DISTINCT CASE
                    WHEN DATE(t.fecha_hora) = CURDATE()
                    AND at.id_agenda_turno IS NOT NULL
                    THEN t.id_turnos
                END) AS turnos_asignados_hoy,

                COUNT(DISTINCT CASE
                    WHEN DATE(t.fecha_hora) = CURDATE()
                    AND at.id_agenda_turno IS NOT NULL
                    AND LOWER(e.tipo_estado) LIKE '%atend%'
                    THEN t.id_turnos
                END) AS turnos_atendidos_hoy,

                COUNT(DISTINCT CASE
                    WHEN t.fecha_hora >= NOW()
                    AND at.id_agenda_turno IS NOT NULL
                    THEN t.id_turnos
                END) AS proximos_turnos,

                COUNT(DISTINCT CASE
                    WHEN at.id_agenda_turno IS NOT NULL
                    THEN at.paciente_id_paciente
                END) AS pacientes_totales

            FROM turno t

            INNER JOIN agenda a
                ON t.agenda_id_agenda = a.id_agenda

            LEFT JOIN agenda_turno at
                ON at.turno_id_turnos = t.id_turnos

            LEFT JOIN estados e
                ON at.estados_id_estados = e.id_estados

            WHERE a.doctor_id_doctor = $idDoctor
              AND a.estados_id_estados <> 3";

    $resultado = $con->consultarArray($sql);

    return $resultado[0] ?? [];
}

/**
 * Obtener próximos turnos asignados del doctor.
 */
public function obtenerProximosTurnosDoctor($idDoctor, $limite = 8)
{
    $con = new Conexion();

    $idDoctor = intval($idDoctor);
    $limite = intval($limite);

    if ($idDoctor <= 0) {
        return [];
    }

    if ($limite <= 0) {
        $limite = 8;
    }

    $sql = "SELECT
                at.id_agenda_turno,
                t.id_turnos,
                t.fecha_hora,
                t.minutos_turnos,

                at.estados_id_estados,
                e.tipo_estado,

                p.id_paciente,
                per.nombre AS paciente_nombre,
                per.apellido AS paciente_apellido,

                a.id_agenda,
                a.fecha_desde,
                a.fecha_hasta,
                a.hora_desde,
                a.hora_hasta

            FROM agenda_turno at

            INNER JOIN turno t
                ON at.turno_id_turnos = t.id_turnos

            INNER JOIN agenda a
                ON t.agenda_id_agenda = a.id_agenda

            INNER JOIN paciente p
                ON at.paciente_id_paciente = p.id_paciente

            INNER JOIN usuario u
                ON p.usuario_id_usuario = u.id_usuario

            INNER JOIN persona per
                ON u.persona_id_persona = per.id_persona

            INNER JOIN estados e
                ON at.estados_id_estados = e.id_estados

            WHERE a.doctor_id_doctor = $idDoctor
              AND a.estados_id_estados <> 3
              AND t.fecha_hora >= NOW()

            ORDER BY t.fecha_hora ASC

            LIMIT $limite";

    return $con->consultarArray($sql);
}

/**
 * Obtener los turnos del día actual de un doctor.
 */
public function obtenerTurnosHoyDoctor($idDoctor)
{
    $con = new Conexion();

    $idDoctor = intval($idDoctor);

    $sql = "SELECT
                at.id_agenda_turno,
                t.id_turnos,
                t.fecha_hora,
                t.minutos_turnos,
                t.disponible,

                at.estados_id_estados,
                e.tipo_estado,

                p.id_paciente,
                per.nombre AS paciente_nombre,
                per.apellido AS paciente_apellido,

                a.id_agenda

            FROM turno t

            INNER JOIN agenda a
                ON t.agenda_id_agenda = a.id_agenda

            LEFT JOIN agenda_turno at
                ON at.turno_id_turnos = t.id_turnos

            LEFT JOIN paciente p
                ON at.paciente_id_paciente = p.id_paciente

            LEFT JOIN usuario u
                ON p.usuario_id_usuario = u.id_usuario

            LEFT JOIN persona per
                ON u.persona_id_persona = per.id_persona

            LEFT JOIN estados e
                ON at.estados_id_estados = e.id_estados

            WHERE a.doctor_id_doctor = $idDoctor
              AND a.estados_id_estados <> 3
              AND DATE(t.fecha_hora) = CURDATE()

            ORDER BY t.fecha_hora ASC";

    return $con->consultarArray($sql);
}

/**
 * Cantidad de turnos por día durante los últimos 7 días.
 */
public function obtenerActividadUltimos7Dias($idDoctor)
{
    $con = new Conexion();

    $idDoctor = intval($idDoctor);

    $sql = "SELECT
                DATE(t.fecha_hora) AS fecha,
                COUNT(t.id_turnos) AS total_turnos,
                SUM(
                    CASE
                        WHEN t.disponible = 1 THEN 1
                        ELSE 0
                    END
                ) AS disponibles,
                COUNT(at.id_agenda_turno) AS asignados

            FROM turno t

            INNER JOIN agenda a
                ON t.agenda_id_agenda = a.id_agenda

            LEFT JOIN agenda_turno at
                ON at.turno_id_turnos = t.id_turnos

            WHERE a.doctor_id_doctor = $idDoctor
              AND a.estados_id_estados <> 3
              AND DATE(t.fecha_hora)
                    BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                    AND CURDATE()

            GROUP BY DATE(t.fecha_hora)

            ORDER BY fecha ASC";

    return $con->consultarArray($sql);
}

/**
 * Distribución de turnos por estado.
 */
public function obtenerTurnosPorEstadoDoctor($idDoctor)
{
    $con = new Conexion();

    $idDoctor = intval($idDoctor);

    $sql = "SELECT
                e.tipo_estado AS estado,
                COUNT(at.id_agenda_turno) AS cantidad

            FROM agenda_turno at

            INNER JOIN turno t
                ON at.turno_id_turnos = t.id_turnos

            INNER JOIN agenda a
                ON t.agenda_id_agenda = a.id_agenda

            INNER JOIN estados e
                ON at.estados_id_estados = e.id_estados

            WHERE a.doctor_id_doctor = $idDoctor
              AND a.estados_id_estados <> 3

            GROUP BY e.id_estados, e.tipo_estado

            ORDER BY cantidad DESC";

    return $con->consultarArray($sql);
}

/**
 * Pacientes atendidos/asignados al doctor.
 */
public function obtenerPacientesDoctor($idDoctor)
{
    $con = new Conexion();

    $idDoctor = intval($idDoctor);

    $sql = "SELECT
                p.id_paciente,
                per.nombre,
                per.apellido,
                COUNT(at.id_agenda_turno) AS cantidad_turnos,
                MAX(t.fecha_hora) AS ultima_consulta

            FROM agenda_turno at

            INNER JOIN turno t
                ON at.turno_id_turnos = t.id_turnos

            INNER JOIN agenda a
                ON t.agenda_id_agenda = a.id_agenda

            INNER JOIN paciente p
                ON at.paciente_id_paciente = p.id_paciente

            INNER JOIN usuario u
                ON p.usuario_id_usuario = u.id_usuario

            INNER JOIN persona per
                ON u.persona_id_persona = per.id_persona

            WHERE a.doctor_id_doctor = $idDoctor

            GROUP BY
                p.id_paciente,
                per.nombre,
                per.apellido

            ORDER BY ultima_consulta DESC";

    return $con->consultarArray($sql);
}

    public function obtenerPorUsuario($id_usuario)
    {
        $conexion = new Conexion();
        $id_usuario = intval($id_usuario);

        $query = "SELECT
                d.id_doctor,
                u.id_usuario,
                per.nombre,
                per.apellido
            FROM doctor d
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            WHERE u.id_usuario = $id_usuario
            LIMIT 1";

        $resultado = $conexion->consultarArray($query);
        return $resultado[0] ?? null;
    }

    

    // HISTORIAL DE CAMBIOS
        /*Registra un evento en el historial de un turno qué ze cambió específicamente,
        * de qué valor a qué valor, quién lo hizo y con qué perfil*/
        public function registrarHistorial(
            $id_turno,
            array $usuario,
            $accion,
            $campo_modificado = null,
            $valor_anterior = null,
            $valor_nuevo = null,
            $detalle = null
        ) {
            $con = new Conexion();

            $id_turno = intval($id_turno);

            $usuario_id = !empty($usuario['id'])
                ? intval($usuario['id'])
                : null;

            $usuario_id_sql = $usuario_id ? $usuario_id : 'NULL';

            $usuario_nombre = addslashes(
                trim($usuario['nombre'] ?? 'Sistema')
            );

            $usuario_perfil = isset($usuario['perfil']) && $usuario['perfil'] !== ''
                ? "'" . addslashes(trim($usuario['perfil'])) . "'"
                : 'NULL';

            $accion = addslashes(trim($accion));

            $campo_sql = $campo_modificado !== null
                ? "'" . addslashes(trim($campo_modificado)) . "'"
                : 'NULL';

            $valor_anterior_sql = $valor_anterior !== null
                ? "'" . addslashes(trim((string) $valor_anterior)) . "'"
                : 'NULL';

            $valor_nuevo_sql = $valor_nuevo !== null
                ? "'" . addslashes(trim((string) $valor_nuevo)) . "'"
                : 'NULL';

            $detalle_sql = $detalle !== null
                ? "'" . addslashes(trim($detalle)) . "'"
                : 'NULL';

            $query = "INSERT INTO turno_historial
                (turno_id_turnos, usuario_id_usuario, usuario_nombre, usuario_perfil,
                accion, campo_modificado, valor_anterior, valor_nuevo, detalle)
            VALUES
                ($id_turno, $usuario_id_sql, '$usuario_nombre', $usuario_perfil,
                '$accion', $campo_sql, $valor_anterior_sql, $valor_nuevo_sql, $detalle_sql)";

            return $con->insertar($query);
        }

        /**
        * Devuelve los últimos $limite cambios de un turno.
        * No necesita JOIN: nombre y perfil quedaron congelados
        * al momento del cambio.
        */
        public function obtenerHistorial($id_turno, $limite = 4)
        {
            $con = new Conexion();

            $id_turno = intval($id_turno);
            $limite = intval($limite);

            $query = "SELECT
                id_turno_historial,
                usuario_id_usuario,
                usuario_nombre,
                usuario_perfil,
                accion,
                campo_modificado,
                valor_anterior,
                valor_nuevo,
                detalle,
                fecha_registro
            FROM turno_historial
            WHERE turno_id_turnos = $id_turno
            ORDER BY fecha_registro DESC
            LIMIT $limite";

            return $con->consultarArray($query);
        }

    // Setters
    public function setMinutos_turnos($v)
    {
        $this->minutos_turnos = $v;
    }
    public function setFecha_hora($v)
    {
        $this->fecha_hora = $v;
    }
    public function setDisponible($v)
    {
        $this->disponible = $v;
    }
    public function setAgenda_id_agenda($v)
    {
        $this->agenda_id_agenda = $v;
    }

    public function getId_turnos()
    {
        return $this->id_turnos;
    }

    public function setId_turnos($id_turnos)
    {
        $this->id_turnos = $id_turnos;

        return $this;
    }

    public function setCon_obra_social($v)
    {
        $this->con_obra_social = $v;
    }

    public function getCon_obra_social()
    {
        return $this->con_obra_social;
    }

    public function setObra_social_id_obra_social($v)
    {
        $this->obra_social_id_obra_social = $v;
    }

    public function getObra_social_id_obra_social()
    {
        return $this->obra_social_id_obra_social;
    }
}
