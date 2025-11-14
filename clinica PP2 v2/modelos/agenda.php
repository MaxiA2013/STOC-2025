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


    public function obtenerAgenda() {
        return $this->conexion->consultar("SELECT * FROM agenda");
    }

    // devuelve todas las agendas como array asociativo
    public function obtenerAgendas() {
        $res = $this->conexion->consultar("SELECT * FROM agenda");
        $rows = [];
        if (is_object($res) && method_exists($res, 'fetch_assoc')) {
            while ($r = $res->fetch_assoc()) $rows[] = $r;
        } elseif (is_array($res)) $rows = $res;
        return $rows;
    }

    // devuelve doctores con nombre para poblar select
    public function obtenerDoctores() {
        $sql = "SELECT d.id_doctor, p.nombre AS nombre_persona, u.nombre_usuario
                FROM doctor d
                INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
                INNER JOIN persona p ON u.persona_id_persona = p.id_persona";
        $res = $this->conexion->consultar($sql);
        $rows = [];
        if (is_object($res) && method_exists($res, 'fetch_assoc')) {
            while ($r = $res->fetch_assoc()) $rows[] = $r;
        } elseif (is_array($res)) $rows = $res;
        return $rows;
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

    /**
     * Get the value of id_agenda
     */ 
    public function getId_agenda()
    {
        return $this->id_agenda;
    }

    /**
     * Set the value of id_agenda
     *
     * @return  self
     */ 
    public function setId_agenda($id_agenda)
    {
        $this->id_agenda = $id_agenda;

        return $this;
    }

    /**
     * Get the value of fecha_agenda
     */ 
    public function getFecha_agenda()
    {
        return $this->fecha_agenda;
    }

    /**
     * Set the value of fecha_agenda
     *
     * @return  self
     */ 
    public function setFecha_agenda($fecha_agenda)
    {
        $this->fecha_agenda = $fecha_agenda;

        return $this;
    }

    /**
     * Get the value of hora_desde
     */ 
    public function getHora_desde()
    {
        return $this->hora_desde;
    }

    /**
     * Set the value of hora_desde
     *
     * @return  self
     */ 
    public function setHora_desde($hora_desde)
    {
        $this->hora_desde = $hora_desde;

        return $this;
    }

    /**
     * Get the value of hora_hasta
     */ 
    public function getHora_hasta()
    {
        return $this->hora_hasta;
    }

    /**
     * Set the value of hora_hasta
     *
     * @return  self
     */ 
    public function setHora_hasta($hora_hasta)
    {
        $this->hora_hasta = $hora_hasta;

        return $this;
    }

    /**
     * Get the value of estados_id_estados
     */ 
    public function getEstados_id_estados()
    {
        return $this->estados_id_estados;
    }

    /**
     * Set the value of estados_id_estados
     *
     * @return  self
     */ 
    public function setEstados_id_estados($estados_id_estados)
    {
        $this->estados_id_estados = $estados_id_estados;

        return $this;
    }

    /**
     * Get the value of doctor_id_doctor
     */ 
    public function getDoctor_id_doctor()
    {
        return $this->doctor_id_doctor;
    }

    /**
     * Set the value of doctor_id_doctor
     *
     * @return  self
     */ 
    public function setDoctor_id_doctor($doctor_id_doctor)
    {
        $this->doctor_id_doctor = $doctor_id_doctor;

        return $this;
    }
}