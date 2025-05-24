<?php
require_once("conexion.php");

class Localidad{
    private int $id_localidad;
    private string $descripcion;
    private int $id_provincia;


    public function guardarLocalidad(){
        $conn= new Conexion();
        $query = "INSERT INTO localidad (descripcion, id_provincia ) VALUES ('$this->descripcion', $this->id_provincia)";
        $id = $conn->insertar($query);
        $this->setId_localidad($id);
    }

    public function modificarLocalidad(){
        $conn= new Conexion();
        $query = "UPDATE localidad SET descripcion = '$this->descripcion' WHERE id_localidad = $this->id_localidad";
        $conn->modificar($query);
    }

    public function eliminarLocalidad(){
        $conn= new Conexion();
        $query = "UPDATE localidad SET activo = 0 WHERE id_localidad = $this->id_localidad";
        $conn->modificar($query);
    }

    public function consultarLocalidad($id){
        $conn= new Conexion();
        $query = "SELECT * FROM localidad WHERE id_localidad = $id";
        $datos = $conn->consultar($query);
        return $datos;
    }

    public function consultarVariasLocalidades(){
        $conn= new Conexion();
        $query = "SELECT * FROM localidad WHERE activo = 1";
        $datos = $conn->consultar($query);
        return $datos;
    }



    /**
     * Get the value of id_localidad
     */ 
    public function getId_localidad()
    {
        return $this->id_localidad;
    }

    /**
     * Set the value of id_localidad
     *
     * @return  self
     */ 
    public function setId_localidad($id_localidad)
    {
        $this->id_localidad = $id_localidad;

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

    /**
     * Get the value of id_provincia
     */ 
    public function getId_provincia()
    {
        return $this->id_provincia;
    }

    /**
     * Set the value of id_provincia
     *
     * @return  self
     */ 
    public function setId_provincia($id_provincia)
    {
        $this->id_provincia = $id_provincia;

        return $this;
    }
}

?>