<?php 
require_once 'conexion.php';

class Mes{
    private $id_mes;
    private $nombre_mes;

    

    /**
     * Get the value of id_mes
     */ 
    public function getId_mes()
    {
        return $this->id_mes;
    }

    /**
     * Set the value of id_mes
     *
     * @return  self
     */ 
    public function setId_mes($id_mes)
    {
        $this->id_mes = $id_mes;

        return $this;
    }

    /**
     * Get the value of nombre_mes
     */ 
    public function getNombre_mes()
    {
        return $this->nombre_mes;
    }

    /**
     * Set the value of nombre_mes
     *
     * @return  self
     */ 
    public function setNombre_mes($nombre_mes)
    {
        $this->nombre_mes = $nombre_mes;

        return $this;
    }

    public function guardarMes() {
        $con = new Conexion();
        $query = "INSERT INTO mes (nombre_mes) VALUES('".$this->getNombre_mes()."')";
        $id = $con->insertar($query);
        $this->setId_mes($id);
    }

    public function actualizarMes(){
        $con = new Conexion();
        $query = "UPDATE mes SET nombre_mes = '". $this->getNombre_mes(). "' WHERE id_mes = ". $this->getId_mes();
        $con->actualizar($query);
    }

    public function eliminarMes(){
        $con = new Conexion();
        $query = "DELETE FROM mes WHERE id_mes = ". $this->getId_mes();
        $con->eliminar($query);
    }

    public function consultarVariosMes(){
        $con = new Conexion();
        $query = "SELECT * FROM mes";
        $datos = $con->consultar($query);
        return $datos;
    }

    public function consultarMes($id){
        $con = new Conexion();
        $query = "SELECT * FROM mes WHERE id_mes = ". $id;
        $datos = $con->consultar($query);
        return $datos;
    }

    public function existeMes(){
        $con = new Conexion();
        $query = "SELECT nombre_mes FROM mes WHERE nombre_mes LIKE '$this->nombre_mes'";
        return $con->consultar($query);
    }
}
?>