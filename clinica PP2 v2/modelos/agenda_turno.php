<?php
require_once "conexion.php";

class AgendaTurno
{
    private $id_agenda_turno;
    private $paciente_id_paciente;
    private $turno_id_turnos;
    private $estados_id_estados;

    public function __construct($paciente_id_paciente = '', $turno_id_turnos = '', $estados_id_estados = "")
    {
        $this->paciente_id_paciente = $paciente_id_paciente;
        $this->turno_id_turnos = $turno_id_turnos;
        $this->estados_id_estados = $estados_id_estados;
    }

    // INSERTAR (asignar turno a paciente)
    public function insertar()
    {
        $conexion = new Conexion();
        $query = "INSERT INTO agenda_turno (paciente_id_paciente, turno_id_turnos, estados_id_estados)
                  VALUES ($this->paciente_id_paciente, $this->turno_id_turnos, $this->estados_id_estados)";
        return $conexion->insertar($query);
    }

    // MODIFICAR ESTADO DEL TURNO
    public function modificar()
    {
        $con = new Conexion();
        $query = "UPDATE agenda_turno 
                  SET paciente_id_paciente = $this->paciente_id_paciente,
                      turno_id_turnos = $this->turno_id_turnos,
                      estados_id_estados = $this->estados_id_estados
                  WHERE id_agenda_turno = $this->id_agenda_turno";
        return $con->actualizar($query);
    }

    // ELIMINAR REGISTRO
    public function eliminar($id_agenda_turno)
    {
        $conexion = new Conexion();
        return $conexion->eliminar("DELETE FROM agenda_turno WHERE id_agenda_turno = $id_agenda_turno");
    }

    // LISTAR TODOS LOS TURNOS ASIGNADOS
    public function listarTurnosAsignados()
    {
        $conexion = new Conexion();
        $query = "SELECT 
                at.id_agenda_turno,
                p.id_paciente,
                per.nombre AS paciente_nombre,
                per.apellido AS paciente_apellido,
                t.id_turnos AS turno_id_turnos,
                t.fecha_hora,
                t.minutos_turnos,
                e.id_estados AS estados_id_estados,
                e.tipo_estado
              FROM agenda_turno at
              INNER JOIN paciente p ON at.paciente_id_paciente = p.id_paciente
              INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
              INNER JOIN persona per ON u.persona_id_persona = per.id_persona
              INNER JOIN turno t ON at.turno_id_turnos = t.id_turnos
              INNER JOIN estados e ON at.estados_id_estados = e.id_estados";
        return $conexion->consultar($query);
    }

    //LISTAR TODOS LOS TURNOS ASIGNADOS CON DOCTOR INCLUIDO
    public function listar()
    {
        $conexion = new Conexion();
        $query = "SELECT 
                at.id_agenda_turno,
                p.id_paciente,
                per.nombre AS paciente_nombre,
                per.apellido AS paciente_apellido,
                t.id_turnos AS turno_id_turnos,
                t.fecha_hora,
                t.minutos_turnos,
                e.id_estados,
                e.tipo_estado,
                a.id_agenda AS agenda_id_agenda,
                CONCAT(a.id_agenda, ' - ', a.fecha_desde) AS agenda_desc,
                d.id_doctor AS doctor_id,
                u2.nombre_usuario AS doctor_usuario,
                per2.nombre AS doctor_nombre,
                per2.apellido AS doctor_apellido,
                at.paciente_id_paciente AS paciente_id_paciente,
                at.estados_id_estados AS estados_id_estados
              FROM agenda_turno at
              INNER JOIN paciente p ON at.paciente_id_paciente = p.id_paciente
              INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
              INNER JOIN persona per ON u.persona_id_persona = per.id_persona
              INNER JOIN turno t ON at.turno_id_turnos = t.id_turnos
              INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
              INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
              INNER JOIN usuario u2 ON d.usuario_id_usuario = u2.id_usuario
              INNER JOIN persona per2 ON u2.persona_id_persona = per2.id_persona
              INNER JOIN estados e ON at.estados_id_estados = e.id_estados
              ORDER BY t.fecha_hora ASC";
        return $conexion->consultar($query);
    }


    // OBTENER UN REGISTRO POR SU ID
    public function obtenerPorId($id_agenda_turno)
    {
        $conexion = new Conexion();
        $query = "SELECT * FROM agenda_turno WHERE id_agenda_turno = $id_agenda_turno";
        $datos = $conexion->consultarArray($query);
        return $datos[0] ?? null;
    }

    // OBTENER TURNOS DE UN PACIENTE
    public function obtenerPorPaciente($id_paciente)
    {
        $conexion = new Conexion();
        $query = "SELECT 
                    at.*, 
                    t.fecha_hora, 
                    t.minutos_turnos,
                    e.tipo_estado
                  FROM agenda_turno at
                  INNER JOIN turno t ON at.turno_id_turnos = t.id_turnos
                  INNER JOIN estados e ON at.estados_id_estados = e.id_estados
                  WHERE paciente_id_paciente = $id_paciente";
        return $conexion->consultar($query);
    }

    // OBTENER EL ESTADO DE UN TURNO ESPECÍFICO
    public function obtenerEstadoTurno($id_turno)
    {
        $conexion = new Conexion();
        $query = "SELECT at.estados_id_estados
                  FROM agenda_turno at
                  WHERE turno_id_turnos = $id_turno";
        $datos = $conexion->consultarArray($query);
        return $datos[0]['estados_id_estados'] ?? null;
    }

    public function obtenerTurnosPorPaciente($id_paciente)
{
    $con = new Conexion();
    $id_paciente = intval($id_paciente);

    $sql = "
        SELECT 
            at.id_agenda_turno,
            t.id_turnos,
            t.fecha_hora,
            t.minutos_turnos,
            CONCAT(p2.nombre, ' ', p2.apellido) AS doctor_nombre
        FROM agenda_turno at
        INNER JOIN turno t ON at.turno_id_turnos = t.id_turnos
        INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
        INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
        INNER JOIN usuario u2 ON d.usuario_id_usuario = u2.id_usuario
        INNER JOIN persona p2 ON u2.persona_id_persona = p2.id_persona
        WHERE at.paciente_id_paciente = $id_paciente
        ORDER BY t.fecha_hora ASC
    ";

    return $con->consultarArray($sql);
}

public function listarTurnosPorAgenda($idAgenda)
{
    $con = new Conexion();

    $idAgenda = intval($idAgenda);

    $sql = "
        SELECT
            at.id_agenda_turno,
            at.paciente_id_paciente,
            at.turno_id_turnos,
            t.fecha_hora,
            t.minutos_turnos,
            t.disponible
        FROM agenda_turno at
        INNER JOIN turno t
            ON at.turno_id_turnos = t.id_turnos
        WHERE t.agenda_id_agenda = $idAgenda
        ORDER BY t.fecha_hora ASC
    ";

    return $con->consultarArray($sql);
}



    public function getId_agenda_turno()
    {
        return $this->id_agenda_turno;
    }
    public function setId_agenda_turno($v)
    {
        $this->id_agenda_turno = $v;
        return $this;
    }

    public function getPaciente_id_paciente()
    {
        return $this->paciente_id_paciente;
    }
    public function setPaciente_id_paciente($v)
    {
        $this->paciente_id_paciente = $v;
        return $this;
    }

    public function getTurno_id_turnos()
    {
        return $this->turno_id_turnos;
    }
    public function setTurno_id_turnos($v)
    {
        $this->turno_id_turnos = $v;
        return $this;
    }

    public function getEstados_id_estados()
    {
        return $this->estados_id_estados;
    }
    public function setEstados_id_estados($v)
    {
        $this->estados_id_estados = $v;
        return $this;
    }
}
