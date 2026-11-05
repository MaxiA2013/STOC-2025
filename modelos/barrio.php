<?php
require_once("conexion.php");
require_once("localidad.php");
require_once("calle.php");

class Barrio{
    private int $id_barrio;
    private string $nombre_barrio;
    private int $id_localidad;


    public function guardarBarrio(){
        $conn = new Conexion();
        $query = "INSERT INTO barrio ( nombre_barrio, localidad_id_localidad ) VALUES ('$this->nombre_barrio', $this->id_localidad)";
        $id = $conn->insertar($query);
        $this->setId_barrio($id);
    }

    public function actualizarbarrio(){
        $conn = new Conexion();
        $query = "UPDATE Barrio SET nombre_barrio = '$this->nombre_barrio' WHERE id_barrio = $this->id_barrio";
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


    public function getId_barrio()
    {
        return $this->id_barrio;
    }


    public function setId_barrio($id_barrio)
    {
        $this->id_barrio = $id_barrio;

        return $this;
    }


    public function getNombre_barrio()
    {
        return $this->nombre_barrio;
    }

    public function setNombre_barrio($nombre_barrio)
    {
        $this->nombre_barrio = $nombre_barrio;

        return $this;
    }
}

?>