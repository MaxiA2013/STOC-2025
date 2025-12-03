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

    public function guardar()
    {
        $con = new Conexion();
        $sql = "INSERT INTO agenda (
                    fecha_desde,
                    fecha_hasta,
                    hora_desde,
                    hora_hasta,
                    estados_id_estados,
                    doctor_id_doctor
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

    public function eliminar($id_agenda)
    {
        $con = new Conexion();
        return $con->eliminar("DELETE FROM agenda WHERE id_agenda = $id_agenda");
    }

    public function obtenerPorId($id_agenda)
    {
        $con = new Conexion();
        $sql = "SELECT * FROM agenda WHERE id_agenda = $id_agenda";
        $resultado = $con->consultarArray($sql);
        return $resultado[0] ?? null;
    }

    public function listarAgendas()
    {
        $con = new Conexion();
        $sql = "SELECT 
                    a.id_agenda,
                    a.fecha_desde,
                    a.fecha_hasta,
                    a.hora_desde,
                    a.hora_hasta,
                    e.tipo_estado AS estado_nombre,
                    d.id_doctor,
                    p.nombre AS doctor_nombre,
                    p.apellido AS doctor_apellido
                FROM agenda a
                INNER JOIN estados e ON a.estados_id_estados = e.id_estados
                INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
                INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
                INNER JOIN persona p ON u.persona_id_persona = p.id_persona
                ORDER BY a.id_agenda DESC";

        return $con->consultarArray($sql);
    }

    // Getters y setters
    public function setFecha_desde($v) { $this->fecha_desde = $v; }
    public function setFecha_hasta($v) { $this->fecha_hasta = $v; }
    public function setHora_desde($v) { $this->hora_desde = $v; }
    public function setHora_hasta($v) { $this->hora_hasta = $v; }
    public function setEstados_id_estados($v) { $this->estados_id_estados = $v; }
    public function setDoctor_id_doctor($v) { $this->doctor_id_doctor = $v; }
}
