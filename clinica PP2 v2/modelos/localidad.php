<?php
require_once("conexion.php");

class Localidad{
    private int $id_localidad;
    private string $nombre_localidad;
    private int $id_provincia;


    public function guardarLocalidad(){
        $conn= new Conexion();
        $query = "INSERT INTO localidad (nombre_localidad, id_provincia ) VALUES ('$this->nombre_localidad', $this->id_provincia)";
        $id = $conn->insertar($query);
        $this->setId_localidad($id);
    }

    public function modificarLocalidad(){
        $conn= new Conexion();
        $query = "UPDATE localidad SET nombre_localidad = '$this->nombre_localidad' WHERE id_localidad = $this->id_localidad";
        $conn->actualizar($query);
    }

    public function eliminarLocalidad(){
        $conn= new Conexion();
        $query = "UPDATE localidad SET activo = 0 WHERE id_localidad = $this->id_localidad";
        $conn->actualizar($query);
    }

    public function consultarLocalidad($id){
        $conn= new Conexion();
        $query = "SELECT * FROM localidad WHERE id_localidad = $id";
        $datos = $conn->consultar($query);
        return $datos;
    }

    public function consultarVariasLocalidades(){
        $conn= new Conexion();
        $query = "SELECT * FROM localidad";
        $datos = $conn->consultar($query);
        return $datos;
    }


    public function getId_localidad()
    {
        return $this->id_localidad;
    }

    public function setId_localidad($id_localidad)
    {
        $this->id_localidad = $id_localidad;

        return $this;
    }

    public function getNombre_localidad()
    {
        return $this->nombre_localidad;
    }

    public function setNombre_localidad($nombre_localidad)
    {
        $this->nombre_localidad = $nombre_localidad;

        return $this;
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
}

?>