<?php
require_once "conexion.php";

class Paciente {
    private $id_paciente;
    private $usuario_id_usuario;

    public function __construct($id_paciente = '', $usuario_id_usuario = '') {
        $this->id_paciente = $id_paciente;
        $this->usuario_id_usuario = $usuario_id_usuario;
    }

    public function listarPacientes() {
        $conexion = new Conexion();
        $query = "SELECT p.id_paciente, per.nombre, per.apellido, u.nombre_usuario, u.email 
                  FROM paciente p
                  INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
                  INNER JOIN persona per ON u.persona_id_persona = per.id_persona";
        return $conexion->consultar($query);
    }

    public function obtenerPorId($id_paciente) {
        $conexion = new Conexion();
        $query = "SELECT p.id_paciente, u.id_usuario, u.nombre_usuario, u.email, per.nombre, per.apellido
                  FROM paciente p
                  INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
                  INNER JOIN persona per ON u.persona_id_persona = per.id_persona
                  WHERE p.id_paciente = $id_paciente";
        $resultado = $conexion->consultarArray($query);
        return $resultado[0] ?? null;
    }

    public function insertar() {
        $conexion = new Conexion();
        $query = "INSERT INTO paciente (usuario_id_usuario) VALUES ($this->usuario_id_usuario)";
        return $conexion->insertar($query);
    }

    public function modificar($id_paciente) {
        $conexion = new Conexion();
        $query = "UPDATE paciente SET usuario_id_usuario = $this->usuario_id_usuario WHERE id_paciente = $id_paciente";
        return $conexion->actualizar($query);
    }

    public function eliminar($id_paciente) {
        $conexion = new Conexion();
        $query = "DELETE FROM paciente WHERE id_paciente = $id_paciente";
        return $conexion->eliminar($query);
    }

    // Getters y setters
    public function getId_paciente() {
        return $this->id_paciente;
    }

    public function setId_paciente($id_paciente) {
        $this->id_paciente = $id_paciente;
        return $this;
    }

    public function getUsuario_id_usuario() {
        return $this->usuario_id_usuario;
    }

    public function setUsuario_id_usuario($usuario_id_usuario) {
        $this->usuario_id_usuario = $usuario_id_usuario;
        return $this;
    }
}