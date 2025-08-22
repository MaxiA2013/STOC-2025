<?php
require_once "../modelos/conexion.php";

class Obra_Social{
    private int $id_obra_social;
    private string $nombre_obra_social;
    private string $detalle;

    public function __construct( $nombre_obra_social ='' , $detalle =''){
        $this->nombre_obra_social = $nombre_obra_social;
        $this->detalle = $detalle;
    }


    public function getIdObraSocial(): int
    {
        return $this->id_obra_social;
    }

    public function setIdObraSocial(int $id_obra_social): self
    {
        $this->id_obra_social = $id_obra_social;

        return $this;
    }

    public function getNombreObraSocial(): string
    {
        return $this->nombre_obra_social;
    }

    public function setNombreObraSocial(string $nombre_obra_social): self
    {
        $this->nombre_obra_social = $nombre_obra_social;

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


    public function guardarObraSocial(){
        $con = new Conexion();
        $query = "INSERT INTO obra_social (nombre_obra_social,detalle) VALUES ('$this->nombre_obra_social','$this->detalle')";
        $id = $con->insertar($query);
        $this->setIdObraSocial($id);

    }

    public function actualizarObraSocial(){
        $con = new Conexion();
        $query = "UPDATE obra_social SET nombre_obra_social = '$this->nombre_obra_social', detalle = '$this->detalle' WHERE id_obra_social = $this->id_obra_social";
        $con->actualizar($query);
    }

    public function eliminarObraSocial(){
        $con = new Conexion();
        $query = "DELETE FROM obra_social WHERE id_obra_social = $this->id_obra_social";
        $con->eliminar($query);
    }

    public function consultarVariasObrasSociales(){
        $con = new Conexion();
        $query = "SELECT * FROM obra_social";
        return $con->consultar($query);
    }

    public function consultarObraSocial($id){
        $con = new Conexion();
        $query = "SELECT * FROM obra_social WHERE id_obra_social = $id";
        return $con->consultar($query);
    }
    
}