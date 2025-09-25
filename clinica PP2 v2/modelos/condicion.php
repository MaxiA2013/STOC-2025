<?php
require_once "conexion.php";

class Condicion{
    private int $id_condicion;
    private string $nombre_condicion;
    private string $detalle;


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

    public function getDetalle(): string
    {
        return $this->detalle;
    }

    public function setDetalle(string $detalle): self
    {
        $this->detalle = $detalle;

        return $this;
    }

    
    public function guardarCondicion(){
        $conn = new Conexion();
        $query = "INSERT INTO condicion (nombre_condicion, detalle) VALUES ('{$this->nombre_condicion}','{$this->detalle}')";
        $id = $conn->insertar($query);
        $this->setIdCondicion($id);
    }

    public function eliminarCondicion(){
        $conn = new Conexion();
        $query = "DELETE FROM condicion WHERE id_condicion={$this->id_condicion}";
        $conn->eliminar($query);
    }

    public function actualizarCondicion(){
        $conn = new Conexion();
        $query = "UPDATE condicion SET nombre_condicion='{$this->nombre_condicion}', detalle='{$this->detalle}' WHERE id_condicion={$this->id_condicion}";
        $conn->actualizar($query);
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