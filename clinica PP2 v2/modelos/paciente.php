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

    /**
     * Get the value of id_paciente
     */ 
    public function getId_paciente()
    {
        return $this->id_paciente;
    }

    /**
     * Set the value of id_paciente
     *
     * @return  self
     */ 
    public function setId_paciente($id_paciente)
    {
        $this->id_paciente = $id_paciente;

        return $this;
    }

    /**
     * Get the value of usuario_id_usuario
     */ 
    public function getUsuario_id_usuario()
    {
        return $this->usuario_id_usuario;
    }

    /**
     * Set the value of usuario_id_usuario
     *
     * @return  self
     */ 
    public function setUsuario_id_usuario($usuario_id_usuario)
    {
        $this->usuario_id_usuario = $usuario_id_usuario;

        return $this;
    }
}
