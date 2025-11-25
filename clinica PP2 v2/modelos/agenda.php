<?php
require_once "conexion.php";

class Agenda
{
    private $id_agenda;
    private $fecha_desde;
    private $fecha_hasta;
    private $hora_desde;
    private $hora_hasta;
    private $estados_id_estados;
    private $doctor_id_doctor;

    public function __construct() {}

    // ===========================
    // INSERTAR
    // ===========================
    public function guardar()
    {
        $con = new Conexion();
        $sql = "INSERT INTO agenda (
                    fecha_desde, fecha_hasta, hora_desde, hora_hasta,
                    estados_id_estados, doctor_id_doctor
                ) VALUES (
                    '$this->fecha_desde',
                    '$this->fecha_hasta',
                    '$this->hora_desde',
                    '$this->hora_hasta',
                    $this->estados_id_estados,
                    $this->doctor_id_doctor
                )";
        return $con->insertar($sql);
    }

    // ===========================
    // EDITAR
    // ===========================
    public function modificar($id_agenda)
    {
        $con = new Conexion();
        $sql = "UPDATE agenda SET
                fecha_desde = '$this->fecha_desde',
                fecha_hasta = '$this->fecha_hasta',
                hora_desde = '$this->hora_desde',
                hora_hasta = '$this->hora_hasta',
                estados_id_estados = $this->estados_id_estados,
                doctor_id_doctor = $this->doctor_id_doctor
                WHERE id_agenda = $id_agenda";

        return $con->actualizar($sql);
    }

    // ===========================
    // ELIMINAR
    // ===========================
    public function eliminar($id_agenda)
    {
        $con = new Conexion();
        return $con->eliminar("DELETE FROM agenda WHERE id_agenda = $id_agenda");
    }

    public function obtenerPorId($id_agenda)
    {
        $con = new Conexion();
        $sql = "SELECT * FROM agenda WHERE id_agenda = $id_agenda";
        $result = $con->consultarArray($sql);
        return $result[0] ?? null;
    }

    // ===========================
    // LISTAR COMPLETO
    // ===========================
    public function listarAgendas()
    {
        $con = new Conexion();
        $sql = "SELECT 
            a.*, 
            e.tipo_estado AS estado_nombre,
            p.nombre AS doctor_nombre,
            p.apellido AS doctor_apellido,
            d.id_doctor,
            t.minutos_turnos
        FROM agenda a
        INNER JOIN estados e 
            ON a.estados_id_estados = e.id_estados
        INNER JOIN doctor d 
            ON a.doctor_id_doctor = d.id_doctor
        INNER JOIN usuario u 
            ON d.usuario_id_usuario = u.id_usuario
        INNER JOIN persona p 
            ON u.persona_id_persona = p.id_persona
        LEFT JOIN turno t
            ON t.agenda_id_agenda = a.id_agenda
            GROUP BY a.id_agenda;
        "; //el group by es lo que junta todo y lo muestra como agenda

        return $con->consultarArray($sql);
    }

    // ===========================
    // LISTAR FILTRADO
    // ===========================
    public function listarAgendasFiltradas($doctorId)
    {
        $con = new Conexion();
        $sql = "SELECT a.*, 
                CONCAT(p.nombre,' ',p.apellido) AS doctor_nombre,
                e.tipo_estado AS estado_nombre
        FROM agenda a
        INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
        INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
        INNER JOIN persona p ON u.persona_id_persona = p.id_persona
        INNER JOIN estados e ON a.estados_id_estados = e.id_estados";

        if ($doctorId !== "") {
            $sql .= " WHERE a.doctor_id_doctor = $doctorId";
        }

        return $con->consultarArray($sql);
    }

    // ===========================
    // VALIDACIÓN DE SUPERPOSICIÓN
    // ===========================
    public function existeSuperposicion($doctorId, $fechaDesde, $fechaHasta, $horaDesde, $horaHasta, $idAgenda = null)
    {
        $con = new Conexion();
        $sql = "SELECT COUNT(*) AS total
                FROM agenda
                WHERE doctor_id_doctor = '$doctorId'
                AND (
                    fecha_desde <= '$fechaHasta'
                    AND fecha_hasta >= '$fechaDesde'
                )
                AND (
                    hora_desde < '$horaHasta'
                    AND hora_hasta > '$horaDesde'
                )
        ";

        if ($idAgenda !== null) {
            $sql .= " AND id_agenda != '$idAgenda'";
        }

        $res = $con->consultar($sql);
        $fila = $res->fetch_assoc();

        return $fila["total"] > 0;
    }

    // Setters
    public function setFecha_desde($v)
    {
        $this->fecha_desde = $v;
    }
    public function setFecha_hasta($v)
    {
        $this->fecha_hasta = $v;
    }
    public function setHora_desde($v)
    {
        $this->hora_desde = $v;
    }
    public function setHora_hasta($v)
    {
        $this->hora_hasta = $v;
    }
    public function setEstados_id_estados($v)
    {
        $this->estados_id_estados = $v;
    }
    public function setDoctor_id_doctor($v)
    {
        $this->doctor_id_doctor = $v;
    }
}
