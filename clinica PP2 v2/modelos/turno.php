<?php
require_once 'conexion.php';

class Turno
{
    private $id_turnos;
    private $minutos_turnos;
    private $fecha_hora;
    private $disponible;
    private $agenda_id_agenda;

    public function guardarTurno()
    {
        $con = new Conexion();
        $query = "INSERT INTO turno (minutos_turnos, fecha_hora, disponible, agenda_id_agenda)
                  VALUES ($this->minutos_turnos, '$this->fecha_hora', $this->disponible, $this->agenda_id_agenda)";
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
     * de la nueva configuración, NO modificamos nada.
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
     * =====================================================
     * 4. ELIMINAR TURNOS QUE YA NO CORRESPONDEN
     * =====================================================
     */

    foreach ($mapaActuales as $fechaHora => $turno) {

        if (!isset($turnosEsperados[$fechaHora])) {

            $this->eliminarTurno($turno["id_turnos"]);
        }
    }

    /*
     * =====================================================
     * 5. CREAR LOS TURNOS NUEVOS
     * =====================================================
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
     * =====================================================
     * 6. ACTUALIZAR DURACIÓN DE LOS TURNOS EXISTENTES
     * =====================================================
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
        $query = "UPDATE turno SET
                    minutos_turnos = '$this->minutos_turnos',
                    fecha_hora = '$this->fecha_hora',
                    disponible = '$this->disponible',
                    agenda_id_agenda = '$this->agenda_id_agenda'
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
    public function obtenerTurnoPorId($id_turno)
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
            WHERE t.id_turnos = $id_turno
            LIMIT 1";

        $res = $con->consultar($query); // retorna mysqli_result

        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc(); // convertir a array asociativo
        } else {
            return null;
        }
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

    /**
     * Get the value of id_turnos
     */
    public function getId_turnos()
    {
        return $this->id_turnos;
    }

    /**
     * Set the value of id_turnos
     *
     * @return  self
     */
    public function setId_turnos($id_turnos)
    {
        $this->id_turnos = $id_turnos;

        return $this;
    }
}
