<?php
require_once "conexion.php";

class Condicion{
    private int $id_condicion;
    private string $nombre_condicion;
    private string $detalles;


    public function getIdCondicion(): int
    {
        return $this->id_condicion;
    }

    public function setIdCondicion(int $id_condicion): self
    {
        $this->id_condicion = $id_condicion;

        return $this;
    }

    public function getNombreCondicion(): string
    {
        return $this->nombre_condicion;
    }

    public function setNombreCondicion(string $nombre_condicion): self
    {
        $this->nombre_condicion = $nombre_condicion;

        return $this;
    }

    public function getDetalles(): string
    {
        return $this->detalles;
    }

    public function setDetalles(string $detalles): self
    {
        $this->detalles = $detalles;

        return $this;
    }

    
    public function guardarCondicion(){
        $conn = new Conexion();
        $query = "INSERT INTO condicin (nombre_condicion, detalle) VALUES ('{$this->nombre_condicion}','{$this->detalles}')";
        $id = $conn->insertar($query);
        $this->setIdCondicion($id);
    }

    public function eliminarCondicion(){
        $conn = new Conexion();
        $query = "DELETE FROM condicion WHERE id_condicion={$this->id_condicion}";
        $conn->eliminar($query);
    }

    public function modificarCondicion(){
        $conn = new Conexion();
        $query = "UPDATE condicion SET nombre_condicion='{$this->nombre_condicion}', detalle='{$this->detalles}' WHERE id_condicion={$this->id_condicion}";
        $conn->modificar($query);
    }

    public function consultarVariasCondiciones(){
        $conn = new Conexion();
        $query = "SELECT * FROM condicion";
        $result = $conn->consultar($query);
        return $result;
    }

    public function consultarCondicion($id){
        $conn = new Conexion();
        $query = "SELECT * FROM condicion WHERE id_condicion = ".$id;
        $result = $conn->consultar($query);
        return $result;
    }



}