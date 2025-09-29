<?php
require_once "conexion.php";

class Agenda {
    private $id_agenda;
    private $fecha_agenda;
    private $hora_desde;
    private $hora_hasta;
    private $estados_id_estados;
    private $doctor_id_doctor;

    public function __construct($id_agenda, $fecha_agenda, $hora_desde, $hora_hasta, $estados_id_estados, $doctor_id_doctor) {
        $this->id_agenda = $id_agenda;
        $this->fecha_agenda = $fecha_agenda;
        $this->hora_desde = $hora_desde;
        $this->hora_hasta = $hora_hasta;
        $this->estados_id_estados = $estados_id_estados;
        $this->doctor_id_doctor = $doctor_id_doctor;
    }

    public function guardar() {
        $conn = new Conexion();
        $sql = "INSERT INTO agenda (
                    fecha_agenda,
                    hora_desde,
                    hora_hasta,
                    estados_id_estados,
                    doctor_id_doctor
                ) VALUES (
                    '$this->fecha_agenda',
                    '$this->hora_desde',
                    '$this->hora_hasta',
                    $this->estados_id_estados,
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
