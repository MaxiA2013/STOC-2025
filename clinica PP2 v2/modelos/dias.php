<?php
require_once 'conexion.php';

class Dias{
    private $id_dias;
    private $descripcion;


    /**
     * Get the value of id_dias
     */ 
    public function getId_dias()
    {
        return $this->id_dias;
    }

    /**
     * Set the value of id_dias
     *
     * @return  self
     */ 
    public function setId_dias($id_dias)
    {
        $this->id_dias = $id_dias;

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

    public function guardarDias(){
        $con = new Conexion();
        $query = "INSERT INTO dias (descripcion) VALUES('".$this->getDescripcion()."')";
        $id = $con->insertar($query);
        $this->setId_dias($id);
    }

    public function actualizarDias(){
        $con = new Conexion();
        $query = "UPDATE dias SET descripcion = '". $this->getDescripcion(). "' WHERE id_dias = ". $this->getId_dias();
        $con->actualizar($query);
    }

    public function eliminarDias(){
        $con = new Conexion();
        $query = "DELETE FROM especialidad WHERE id_especialidad = ". $this->getId_dias();
        $con->eliminar($query);
    }

    public function consultarVariosDias(){
        $con = new Conexion();
        $query = "SELECT * FROM dias";
        $datos = $con->consultar($query);
        return $datos;
    }

    public function consultarDias($id){
        $con = new Conexion();
        $query = "SELECT * FROM dias WHERE id_dias = ". $id;
        $datos = $con->consultar($query);
        return $datos;
    }
}

?>