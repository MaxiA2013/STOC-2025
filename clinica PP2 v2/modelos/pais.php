<?php
require_once "conexion.php";

class Pais{
    private int $id_pais;
    private string $descripcion;


    public function guardarPais(){
        $conn = new Conexion();
        $query = "INSERT INTO pais (decripcion) VALUES ('$this->descripcion')";
        $id = $conn->insertar($query);
        $this->setId_pais($id);
    }

    public function actualizarPais(){
        $conn = new Conexion();
        $query = "UPDATE pais SET descripcion='$this->descripcion' WHERE id_pais=$this->id_pais";
        $conn->modificar($query);
    }

    public function eliminarPais(){
        $conn = new Conexion();
        $query = "DELETE FROM pais WHERE id_pais=$this->id_pais";
        $conn->eliminar($query);
    }

    public function consultarVariosPaises(){
        $conn = new Conexion();
        $query = "SELECT * FROM pais";
        $resultado = $conn->consultar($query);
        return $resultado;
    }

    public function consultarPais($id){
        $conn = new Conexion();
        $query = "SELECT * FROM pais WHERE id_pais=$id";
        $resultado = $conn->consultar($query);
        return $resultado;
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
}