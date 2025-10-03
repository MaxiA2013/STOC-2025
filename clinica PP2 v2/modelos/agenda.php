<?php
require_once "conexion.php";

class Agenda {
    private $id_agenda;
    private $fecha_agenda;
    private $hora_desde;
    private $hora_hasta;
    private $estados_id_estados;
    private $doctor_id_doctor;
    private $conexion;

    public function __construct($id_agenda = null, $fecha_agenda = null, $hora_desde = null, $hora_hasta = null, $estados_id_estados = null, $doctor_id_doctor = null) {
        $this->id_agenda = $id_agenda;
        $this->fecha_agenda = $fecha_agenda;
        $this->hora_desde = $hora_desde;
        $this->hora_hasta = $hora_hasta;
        $this->estados_id_estados = $estados_id_estados;
        $this->doctor_id_doctor = $doctor_id_doctor;
        $this->conexion = new Conexion();
    }

    public function guardar() {
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
        return $this->conexion->insertar($sql);
    }

    public function modificar($id_agenda) {
        $sql = "UPDATE agenda SET 
                    fecha_agenda = '$this->fecha_agenda',
                    hora_desde = '$this->hora_desde',
                    hora_hasta = '$this->hora_hasta',
                    estados_id_estados = $this->estados_id_estados,
                    doctor_id_doctor = $this->doctor_id_doctor
                WHERE id_agenda = $id_agenda";
        return $this->conexion->actualizar($sql);
    }

    public function cambiarEstado($id_agenda, $nuevo_estado_id) {
        $sql = "UPDATE agenda SET estados_id_estados = $nuevo_estado_id WHERE id_agenda = $id_agenda";
        return $this->conexion->actualizar($sql);
    }

public function obtenerPorId($id_agenda) {
    $sql = "SELECT * FROM agenda WHERE id_agenda = $id_agenda";
    $resultado = $this->conexion->consultarArray($sql); // ✅ usa el nuevo método
    return $resultado[0] ?? null;
}


    public function obtenerAgendas() {
        return $this->conexion->consultar("SELECT * FROM agenda");
    }

    public function obtenerDoctores() {
        $sql = "SELECT d.id_doctor, p.nombre AS nombre_persona, u.nombre_usuario
                FROM doctor d
                INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
                INNER JOIN persona p ON u.persona_id_persona = p.id_persona";
        return $this->conexion->consultar($sql);
    }

    public function obtenerEstados() {
        return $this->conexion->consultar("SELECT id_estados, tipo_estado FROM estados");
    }

    public function mapearDoctoresPorId() {
        $doctores = $this->obtenerDoctores();
        $mapa = [];
        foreach ($doctores as $doc) {
            $mapa[$doc['id_doctor']] = $doc['nombre_persona'];
        }
        return $mapa;
    }

    public function mapearEstadosPorId() {
        $estados = $this->obtenerEstados();
        $mapa = [];
        foreach ($estados as $estado) {
            $mapa[$estado['id_estados']] = $estado['tipo_estado'];
        }
        return $mapa;
    }
}
