<?php
require_once "conexion.php";
require_once "provincia.php";
require_once("pais.php");

class Provincia{ 
    private int $id_provincia;
    private string $descripcion;
    private int $id_pais; #comprobar

    public function guardarProvincia(){
        $conn = new Conexion();
        $query = "INSERT INTO provincia ( descripcion, pais_id_pais ) VALUES ('$this->descripcion', $this->id_pais)";
        $id = $conn->insertar($query);
        $this->setId_provincia($id);
    }

    public function actualizarProvincia(){
        $conn = new Conexion();
        $query = "UPDATE provincia SET descripcion = '$this->descripcion' WHERE id_provincia = $this->id_provincia";
        $conn->actualizar($query);
    }

    public function eliminarProvincia(){
        $conn = new Conexion();
        $query = "DELETE FROM provincia WHERE id_provincia = $this->id_provincia";
        $conn->eliminar($query);
    }

    public function consultarVariasProvincias(){
        $conn = new Conexion();
        $query = "SELECT * FROM provincia";
        $datos = $conn->consultar($query);
        return $datos;
    }


    public function consultarProvincia($id_provincia){
        $conn = new Conexion();
        $query = "SELECT * FROM provincia WHERE id_provincia = $id_provincia";
        $datos = $conn->consultar($query);
        return $datos;
    }

    /**
     * Get the value of id_provincia
     */ 
    public function getId_provincia()
    {
        return $this->id_provincia;
    }

    /**
     * Set the value of id_provincia
     *
     * @return  self
     */ 
    public function setId_provincia($id_provincia)
    {
        $this->id_provincia = $id_provincia;

        return $this;
    }

    /**
     * Get the value of descripcion
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of id_pais
     */ 
    public function getId_pais()
    {
        return $this->id_pais;
    }

    /**
     * Set the value of id_pais
     *
     * @return  self
     */ 
    public function setId_pais($id_pais)
    {
        $this->id_pais = $id_pais;

        return $this;
    }
}
