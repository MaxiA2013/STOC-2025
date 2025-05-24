<?php
require_once("conexion.php");

class Especialidad{
    public int $id_especialidad;
    public string $nombre_especialidad;

    public function guardarEspecialidad(){
        $con = new Conexion();
        $query = "INSERT INTO especialidad (nombre_especialidad) VALUES('".$this->getNombreEspecialidad()."')";
        $id = $con->insertar($query);
        $this->setIdEspecialidad($id);
    }

    public function actualizarEspecialidad(){
        $con = new Conexion();
        $query = "UPDATE especialidad SET nombre_especialidad = '". $this->getNombreEspecialidad(). "' WHERE id_especialidad = ". $this->getIdEspecialidad();
        $con->actualizar($query);
    }

    public function eliminarEspecialidad(){
        $con = new Conexion();
        $query = "DELETE FROM especialidad WHERE id_especialidad = ". $this->getIdEspecialidad();
        $con->eliminar($query);
    }

    public function consultarVariasEspecialidades(){
        $con = new Conexion();
        $query = "SELECT * FROM especialidad";
        $datos = $con->consultar($query);
        return $datos;
    }

    public function consultarEspecialidad($id){
        $con = new Conexion();
        $query = "SELECT * FROM especialidad WHERE id_especialidad = ". $id;
        $datos = $con->consultar($query);
        return $datos;
    }

    public function getIdEspecialidad(): int
    {
        return $this->id_especialidad;
    }

    public function setIdEspecialidad(int $id_especialidad): self
    {
        $this->id_especialidad = $id_especialidad;

        return $this;
    }

    public function getNombreEspecialidad(): string
    {
        return $this->nombre_especialidad;
    }

    public function setNombreEspecialidad(string $nombre_especialidad): self
    {
        $this->nombre_especialidad = $nombre_especialidad;

        return $this;
    }
}

#comprobar su funcionamiento

?>