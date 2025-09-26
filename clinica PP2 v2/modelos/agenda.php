<?php
require_once "conexion.php";

class Agenda {
    private $id_agenda;
    private $fecha_desde;
    private $fecha_hasta; 
    private $hora_desde;
    private $hora_hasta;
    private $minutos_turnos;
    private $dias_id_dias;
    private $doctor_id_doctor;

    public function __construct($id_agenda, $fecha_desde, $fecha_hasta, $hora_desde, $hora_hasta, $minutos_turnos, $dias_id_dias, $doctor_id_doctor) {
        $this->id_agenda = $id_agenda;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
        $this->hora_desde = $hora_desde;
        $this->hora_hasta = $hora_hasta;
        $this->minutos_turnos = $minutos_turnos;
        $this->dias_id_dias = $dias_id_dias;
        $this->doctor_id_doctor = $doctor_id_doctor;
    }

    public function guardar() {
        $conn = new Conexion();
        $sql = "INSERT INTO agenda (
                    fecha_desde,
                    fecha_hasta,
                    hora_desde,
                    hora_hasta,
                    minutos_turnos,
                    dias_id_dias,
                    doctor_id_doctor
                ) VALUES (
                    '$this->fecha_desde',
                    '$this->fecha_hasta',
                    '$this->hora_desde',
                    '$this->hora_hasta',
                    $this->minutos_turnos,
                    $this->dias_id_dias,
                    $this->doctor_id_doctor
                )";
        return $conn->insertar($sql);
    }

    public function all_agendas() {
        $conn = new Conexion();
        $sql = "SELECT * FROM agenda";
        return $conn->consultar($sql);
    }
}
?>