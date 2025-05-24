<?php
require_once("conexion.php");

class Metodo_Pago{
    private int $id_metodo_pago;
    private string $nombre_metodo;

    public function guardarMetodoPago(){
        $conn = new Conexion();
        $query = "INSERT INTO metodo_pago (nombre_metodo) VALUES ('$this->nombre_metodo')";
        $id = $conn->insertar($query);
        $this->setId_metodo_pago($id);
    }

    public function actualizarMetodoPago(){
        $conn = new Conexion();
        $query = "UPDATE metodo_pago SET nombre_metodo = '$this->nombre_metodo' WHERE id_metodo_pago = $this->id_metodo_pago";
        $conn->modificar($query);
    }

    public function eliminarMetodoPago(){
        $conn = new Conexion();
        $query = "DELETE FROM metodo_pago WHERE id_metodo_pago = $this->id_metodo_pago";
        $conn->eliminar($query);
    }

    public function consultarVariosMetodosPago(){
        $conn = new Conexion();
        $query = "SELECT * FROM metodo_pago";
        $result = $conn->consultar($query);
        return $result;
    }

    public function consultarMetodoPago($id){
        $conn = new Conexion();
        $query = "SELECT * FROM metodo_pago WHERE id_metodo_pago = $id";
        $result = $conn->consultar($query);
        return $result;
    }

    


    /**
     * Get the value of id_metodo_pago
     */ 
    public function getId_metodo_pago()
    {
        return $this->id_metodo_pago;
    }

    /**
     * Set the value of id_metodo_pago
     *
     * @return  self
     */ 
    public function setId_metodo_pago($id_metodo_pago)
    {
        $this->id_metodo_pago = $id_metodo_pago;

        return $this;
    }

    /**
     * Get the value of nombre_metodo
     */ 
    public function getNombre_metodo()
    {
        return $this->nombre_metodo;
    }

    /**
     * Set the value of nombre_metodo
     *
     * @return  self
     */ 
    public function setNombre_metodo($nombre_metodo)
    {
        $this->nombre_metodo = $nombre_metodo;

        return $this;
    }
}

?>