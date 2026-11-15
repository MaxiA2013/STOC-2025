<?php

require_once 'conexion.php';
require_once 'agenda.php';
require_once 'turno.php';

class Confirmacion
{
    private $id_confirmacion;
    private $confirmacion_paciente; // TINYINT(1) NULL/0/1
    private $confirmacion_doctor;   // TINYINT(1) NULL/0/1
    private $agenda_turno_id_agenda_turno;

    /**
     * Get the value of id_confirmacion
     */
    public function getId_confirmacion()
    {
        return $this->id_confirmacion;
    }

    /**
     * Set the value of id_confirmacion
     *
     * @return  self
     */
    public function setId_confirmacion($id_confirmacion)
    {
        $this->id_confirmacion = $id_confirmacion;

        return $this;
    }

    /**
     * Get the value of confirmacion_paciente
     */
    public function getConfirmacion_paciente()
    {
        return $this->confirmacion_paciente;
    }

    /**
     * Set the value of confirmacion_paciente
     *
     * @return  self
     */
    public function setConfirmacion_paciente($confirmacion_paciente)
    {
        $this->confirmacion_paciente = $confirmacion_paciente;

        return $this;
    }

    /**
     * Get the value of confirmacion_doctor
     */
    public function getConfirmacion_doctor()
    {
        return $this->confirmacion_doctor;
    }

    /**
     * Set the value of confirmacion_doctor
     *
     * @return  self
     */
    public function setConfirmacion_doctor($confirmacion_doctor)
    {
        $this->confirmacion_doctor = $confirmacion_doctor;

        return $this;
    }

    /**
     * Get the value of agenda_turno_id_agenda_turno
     */
    public function getAgenda_turno_id_agenda_turno()
    {
        return $this->agenda_turno_id_agenda_turno;
    }

    /**
     * Set the value of agenda_turno_id_agenda_turno
     *
     * @return  self
     */
    public function setAgenda_turno_id_agenda_turno($agenda_turno_id_agenda_turno)
    {
        $this->agenda_turno_id_agenda_turno = $agenda_turno_id_agenda_turno;

        return $this;
    }

    // Crear registro de confirmación para un agenda_turno
    public function crearParaAgendaTurno($id_agenda_turno)
    {
        $con = new Conexion();
        $id_agenda_turno = intval($id_agenda_turno);

        $sql = "INSERT INTO confirmacion (confirmacion_paciente, confirmacion_doctor, agenda_turno_id_agenda_turno)
                VALUES (NULL, NULL, $id_agenda_turno)";
        return $con->insertar($sql);
    }

    // Actualizar confirmación (paciente o doctor)
    public function actualizarConfirmacion($id_confirmacion, $esPaciente, $valor)
    {
        $con = new Conexion();

        $id_confirmacion = intval($id_confirmacion);
        $valor = $valor ? 1 : 0;

        if ($esPaciente) {
            $sql = "UPDATE confirmacion 
                    SET confirmacion_paciente = $valor
                    WHERE id_confirmacion = $id_confirmacion";
        } else {
            $sql = "UPDATE confirmacion 
                    SET confirmacion_doctor = $valor
                    WHERE id_confirmacion = $id_confirmacion";
        }

        return $con->actualizar($sql);
    }

    // Obtener por id
    public function obtenerPorId($id_confirmacion)
    {
        $con = new Conexion();
        $id_confirmacion = intval($id_confirmacion);

        $sql = "SELECT * FROM confirmacion WHERE id_confirmacion = $id_confirmacion";
        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // Obtener por agenda_turno
    public function obtenerPorAgendaTurno($id_agenda_turno)
    {
        $con = new Conexion();
        $id_agenda_turno = intval($id_agenda_turno);

        $sql = "SELECT * FROM confirmacion 
                WHERE agenda_turno_id_agenda_turno = $id_agenda_turno
                LIMIT 1";
        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // ¿Ambos confirmaron?
    public function ambosConfirmaron($id_confirmacion)
    {
        $data = $this->obtenerPorId($id_confirmacion);
        if (!$data) return false;
        return (intval($data['confirmacion_paciente']) === 1
            && intval($data['confirmacion_doctor']) === 1);
    }

    // Lista confirmaciones pendientes para un DOCTOR
    public function listarPendientesDoctor($doctor_id)
    {
        $con = new Conexion();
        $doctor_id = intval($doctor_id);

        $sql = "SELECT
                c.id_confirmacion,
                c.confirmacion_paciente,
                c.confirmacion_doctor,
                at.id_agenda_turno,
                t.id_turnos,
                t.fecha_hora,
                p.id_paciente,
                per.nombre  AS paciente_nombre,
                per.apellido AS paciente_apellido
            FROM confirmacion c
            INNER JOIN agenda_turno at
                ON c.agenda_turno_id_agenda_turno = at.id_agenda_turno
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
            WHERE a.doctor_id_doctor = $doctor_id
              AND c.confirmacion_doctor IS NULL   -- pendiente para el doctor
            ORDER BY t.fecha_hora ASC";

        return $con->consultarArray($sql);
    }

    // Lista confirmaciones pendientes para un PACIENTE
    public function listarPendientesPaciente($paciente_id)
    {
        $con = new Conexion();
        $paciente_id = intval($paciente_id);

        $sql = "SELECT
                c.id_confirmacion,
                c.confirmacion_paciente,
                c.confirmacion_doctor,
                at.id_agenda_turno,
                t.id_turnos,
                t.fecha_hora,
                d.id_doctor,
                per2.nombre  AS doctor_nombre,
                per2.apellido AS doctor_apellido
            FROM confirmacion c
            INNER JOIN agenda_turno at
                ON c.agenda_turno_id_agenda_turno = at.id_agenda_turno
            INNER JOIN turno t
                ON at.turno_id_turnos = t.id_turnos
            INNER JOIN agenda a
                ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d
                ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u2
                ON d.usuario_id_usuario = u2.id_usuario
            INNER JOIN persona per2
                ON u2.persona_id_persona = per2.id_persona
            WHERE at.paciente_id_paciente = $paciente_id
              AND c.confirmacion_paciente IS NULL   -- pendiente para el paciente
            ORDER BY t.fecha_hora ASC";

        return $con->consultarArray($sql);
    }
}
