<?php

 class Persona{
    private $id_persona;
    private $nombre;
    private $apellido;
    private $sexo;
    private $fecha_nacimiento;

    public function __construct($id_persona = '', $nombre = '', $apellido = '', $sexo = '', $fecha_nacimiento = '') {
        $this->id_persona = $id_persona;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->sexo = $sexo;
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function guardar(){
        $conexion = new Conexion();
        $query = "INSERT INTO clinica.persona (nombre, apellido, sexo, fecha_nacimiento) VALUES ('$this->nombre', '$this->apellido', '$this->sexo', '$this->fecha_nacimiento')";
        return $conexion->insertar($query);
    }

    public function actualizar(){
        $conexion = new Conexion();
        $query = "UPDATE clinica.persona SET nombre = '$this->nombre', apellido = '$this->apellido', sexo = '$this->sexo', fecha_nacimiento = '$this->fecha_nacimiento' WHERE id_persona = '$this->id_persona'";
        return $conexion->actualizar($query);
    }

    public function eliminar(){
        $conexion = new Conexion();
        $query = "DELETE FROM clinica.persona WHERE id_persona = '$this->id_persona'";
        return $conexion->eliminar($query);
    }

    public function validar_persona(){
        $conexion = new Conexion();
        $query = "SELECT * FROM clinica.persona WHERE nombre = '$this->nombre' AND apellido = '$this->apellido' AND sexo = '$this->sexo' AND fecha_nacimiento = '$this->fecha_nacimiento'";
        return $conexion->consultar($query);
    }
    

    /**
     * Get the value of id_persona
     */ 
    public function getId_persona()
    {
        return $this->id_persona;
    }

    /**
     * Set the value of id_persona
     *
     * @return  self
     */ 
    public function setId_persona($id_persona)
    {
        $this->id_persona = $id_persona;

        return $this;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of apellido
     */ 
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set the value of apellido
     *
     * @return  self
     */ 
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get the value of sexo
     */ 
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set the value of sexo
     *
     * @return  self
     */ 
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get the value of fecha_nacimiento
     */ 
    public function getFecha_nacimiento()
    {
        return $this->fecha_nacimiento;
    }

    /**
     * Set the value of fecha_nacimiento
     *
     * @return  self
     */ 
    public function setFecha_nacimiento($fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;

        return $this;
    }
 }

?>