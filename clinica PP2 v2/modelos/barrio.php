<?php
require_once("conexion.php");
require_once("localidad.php");
require_once("calle.php");

class Barrio{
    private int $id_barrio;
    private string $descripcion;
    private int $id_localidad;


    public function guardarBarrio(){
        $conn = new Conexion();
        $query = "INSERT INTO barrio ( descripcion, localidad_id_localidad ) VALUES ('$this->descripcion', $this->id_localidad)";
        $id = $conn->insertar($query);
        $this->setId_barrio($id);
    }

    public function actualizarbarrio(){
        $conn = new Conexion();
        $query = "UPDATE Barrio SET descripcion = '$this->descripcion' WHERE id_barrio = $this->id_barrio";
        $conn->actualizar($query);
    }

    public function eliminarBarrio(){
        $conn = new Conexion();
        $query = "DELETE FROM barrio WHERE id_barrio = $this->id_barrio";
        $conn->eliminar($query);
    }

    public function consultarVariosBarrios(){
        $conn = new Conexion();
        $query = "SELECT * FROM barrio";
        $datos = $conn->consultar($query);
        return $datos;
    }


    public function consultarBarrio($id_barrio){
        $conn = new Conexion();
        $query = "SELECT * FROM barrio WHERE id_barrio = $id_barrio";
        $datos = $conn->consultar($query);
        return $datos;
    }

    /**
     * Get the value of id_barrio
     */ 
    public function getId_barrio()
    {
        return $this->id_barrio;
    }

    /**
     * Set the value of id_barrio
     *
     * @return  self
     */ 
    public function setId_barrio($id_barrio)
    {
        $this->id_barrio = $id_barrio;

        return $this;
    }

    /**
     * Get the value of descripción
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripción
     *
     * @return  self
     */ 
    public function setDescripcion($descripción)
    {
        $this->descripcion = $descripción;

        return $this;
    }
}

?>