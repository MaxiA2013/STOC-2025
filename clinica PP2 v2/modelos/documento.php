<?php

class Documento{
    private int $id_documento;
    private string $tipo_documento; #el nombre del documento (ej: pasaporte extranjero, cedula policial, etc)
    private string $detalle; #es una descripcion del documento


    public function getIdDocumento(): int
    {
        return $this->id_documento;
    }

    public function setIdDocumento(int $id_documento): self
    {
        $this->id_documento = $id_documento;

        return $this;
    }

    public function getTipoDocumento(): string
    {
        return $this->tipo_documento;
    }

    public function setTipoDocumento(string $tipo_documento): self
    {
        $this->tipo_documento = $tipo_documento;

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

    public function guardarDocumento(){
        $conn = new Conexion();
        $query = "INSERT INTO documento (tipo_documento_id, detalle) VALUES ($this->id_documento , '$this->detalle')";
        $id = $conn->insertar($query);
        $this->setIdDocumento($id);
    }

    public function modificarDocumento(){
        $conn = new Conexion();
        $query = "UPDATE documento SET tipo_documento_id = $this->id_documento, detalle = '$this->detalle' WHERE id = $this->id_documento";
        $conn->modificar($query);
    }

    public function eliminarDocumento(){
        $conn = new Conexion();
        $query = "DELETE FROM documento WHERE id = $this->id_documento";
        $conn->eliminar($query);
    }

    public function consultarDocumento(){
        $conn = new Conexion();
        $query = "SELECT * FROM documento WHERE id_documento = '".$this->getIdDocumento()."' ";
        $datos = $conn->consultar($query);
        return $datos; 
    }

    public function consultarVariosDocumento($id){
        $conn = new Conexion();
        $query = "SELECT * FROM documento WHERE id_documento = $id ";
        $datos = $conn->consultar($query);
        return $datos;
    }
}

#comprobar funcionamiento

?>