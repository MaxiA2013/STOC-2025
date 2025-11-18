<?php
require_once "conexion.php";

class Sintomas{

    private int $id_sintomas;
    private string $nombre_sintomas;
    private string $descripcion;

    public function getIdSintomas(): int
    {
        return $this->id_sintomas;
    }

    public function setIdSintomas($id_sintomas): self
    {
        $this->id_sintomas = $id_sintomas;

        return $this;
    }

    public function getNombreSintomas(): string
    {
        return $this->nombre_sintomas;
    }

    public function setNombreSintomas($nombre_sintomas): self
    {
        $this->nombre_sintomas = $nombre_sintomas;

        return $this;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function guardarSintoma(){
        $con = new Conexion();
        $query = "INSERT INTO sintomas (nombre_sintomas, descripcion) VALUES ('{$this->nombre_sintomas}', '{$this->descripcion}')";
        $id = $con->insertar($query);
        $this->id_sintomas = $id;
    }

    public function actualizarSintoma(){
        $con = new Conexion();
        $query = "UPDATE sintomas SET nombre_sintomas='{$this->nombre_sintomas}', descripcion='{$this->descripcion}' WHERE id_sintomas={$this->id_sintomas}";
        $con->actualizar($query);
    }

    public function eliminarSintoma(){
        $con = new Conexion();
        $query = "DELETE FROM sintomas WHERE id_sintomas={$this->id_sintomas}";
        $con->eliminar($query);
    }

    public function consultarSintoma($id){
        $con = new Conexion();
        $query = "SELECT * FROM sintomas WHERE id_sintomas={$id}";
        $datos = $con->consultar($query);
    }

    public function consultarVariosSintomas(){
        $con = new Conexion();
        $query = "SELECT * FROM sintomas";
        $datos = $con->consultar($query);
        return $datos;
    }

    public function existeSintoma(){
        $con = new Conexion();
        $query = "SELECT nombre_sintomas FROM sintomas WHERE nombre_sintomas = '$this->nombre_sintomas'";
        return $con->consultar($query);
    }

}