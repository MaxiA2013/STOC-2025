<?php
require_once "conexion.php";
require_once "provincia.php";
require_once "pais.php";

class Provincia{ 
    private int $id_provincia;
    private string $nombre_provincia;
    private int $pais_id_pais; #comprobar

    public function guardarProvincia(){
        $conn = new Conexion();
        $query = "INSERT INTO provincia ( nombre_provincia, pais_id_pais ) VALUES ('$this->nombre_provincia', $this->pais_id_pais)";
        $id = $conn->insertar($query);
        $this->setId_provincia($id);
    }

    public function actualizarProvincia(){
        $conn = new Conexion();
        $query = "UPDATE provincia SET nombre_provincia = '$this->nombre_provincia' WHERE id_provincia = $this->id_provincia";
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

    public function getId_provincia()
    {
        return $this->id_provincia;
    }

    public function setId_provincia($id_provincia)
    {
        $this->id_provincia = $id_provincia;

        return $this;
    }

    public function getNombre_provincia()
    {
        return $this->nombre_provincia;
    }

    public function setNombre_provincia($nombre_provincia)
    {
        $this->nombre_provincia = $nombre_provincia;

        return $this;
    }

    public function getPaisId_pais()
    {
        return $this->pais_id_pais;
    }

    public function setPaisId_pais($pais_id_pais)
    {
        $this->pais_id_pais = $pais_id_pais;

        return $this;
    }
}
