<?php
require_once "conexion.php";

class Doctor {
    private $id_doctor;
    private $numero_matricula_profesional;
    private $usuario_id_usuario;
    private $precio_consulta;

    public function __construct($id_doctor = '', $numero_matricula_profesional = '', $usuario_id_usuario = '', $precio_consulta = '') {
        $this->id_doctor = $id_doctor;
        $this->numero_matricula_profesional = $numero_matricula_profesional;
        $this->usuario_id_usuario = $usuario_id_usuario;
        $this->precio_consulta = $precio_consulta;
    }

    public function guardar() {
        $conexion = new Conexion();
        $query = "INSERT INTO doctor (numero_matricula_profesional, usuario_id_usuario, precio_consulta) 
                  VALUES ('$this->numero_matricula_profesional', '$this->usuario_id_usuario', '$this->precio_consulta')";
        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $query = "UPDATE doctor 
                  SET numero_matricula_profesional = '$this->numero_matricula_profesional', 
                      usuario_id_usuario = '$this->usuario_id_usuario', 
                      precio_consulta = '$this->precio_consulta'
                  WHERE id_doctor = '$this->id_doctor'";
        return $conexion->actualizar($query);
    }

    public function eliminar($id) {
        $conexion = new Conexion();
        $query = "DELETE FROM doctor WHERE id_doctor = '$id'";
        return $conexion->eliminar($query);
    }

    public function all_doctores() {
    $conexion = new Conexion();
    $query = "SELECT d.id_doctor, d.numero_matricula_profesional, d.precio_consulta,
                     d.usuario_id_usuario,  -- 🔹 agregado
                     u.nombre_usuario, p.nombre, p.apellido
              FROM doctor d
              JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
              JOIN persona p ON u.persona_id_persona = p.id_persona";
    return $conexion->consultar($query);
}


    /**
     * Get the value of id_doctor
     */ 
    public function getId_doctor()
    {
        return $this->id_doctor;
    }

    /**
     * Set the value of id_doctor
     *
     * @return  self
     */ 
    public function setId_doctor($id_doctor)
    {
        $this->id_doctor = $id_doctor;

        return $this;
    }

    /**
     * Get the value of numero_matricula_profesional
     */ 
    public function getNumero_matricula_profesional()
    {
        return $this->numero_matricula_profesional;
    }

    /**
     * Set the value of numero_matricula_profesional
     *
     * @return  self
     */ 
    public function setNumero_matricula_profesional($numero_matricula_profesional)
    {
        $this->numero_matricula_profesional = $numero_matricula_profesional;

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

    /**
     * Get the value of precio_consulta
     */ 
    public function getPrecio_Consulta()
    {
        return $this->precio_consulta;
    }

    /**
     * Set the value of Precio_consulta
     *
     * @return  self
     */ 
    public function setPrecio_Consulta($precio_consulta)
    {
        $this->precio_consulta = $precio_consulta;

        return $this;
    }
}