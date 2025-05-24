<?php
require_once("conexion.php");

class Estado{
    private int $id_estados;
    private string $tipo_estado;
    private string $descripcion;

    public function guardarEstado(){
        $conn = new Conexion();
        $query = "INSERT INTO estados (tipo_estado ,descripcion) VALUES ('$this->tipo_estado','$this->descripcion')";
        $id = $conn->insertar($query);
        $this->setId_estados($id);
    }

    public function actualizarEstado(){
        $conn = new Conexion();
        $query = "UPDATE estados SET tipo_estado = '$this->tipo_estado', descripcion = '$this->descripcion' WHERE id_estados = $this->id_estados";
        $conn->actualizar($query);
    }

    public function eliminarEstado(){
        $conn = new Conexion();
        $query = "DELETE FROM estados WHERE id_estados = $this->id_estados";
        $conn->eliminar($query);
    }

    public static function consultarVariosEstados(){
        $conn = new Conexion();
        $query = "SELECT * FROM estados";
        return $conn->consultar($query);
    }

    public static function consultarEstado($id){
        $conn = new Conexion();
        $query = "SELECT * FROM estados WHERE id_estados = $id";
        $datos = $conn->consultar($query);
        return $datos;
    }

    /**
     * Get the value of id_estados
     */ 
    public function getId_estados()
    {
        return $this->id_estados;
    }

    /**
     * Set the value of id_estados
     *
     * @return  self
     */ 
    public function setId_estados($id_estados)
    {
        $this->id_estados = $id_estados;

        return $this;
    }

    /**
     * Get the value of tipo_estado
     */ 
    public function getTipo_estado()
    {
        return $this->tipo_estado;
    }

    /**
     * Set the value of tipo_estado
     *
     * @return  self
     */ 
    public function setTipo_estado($tipo_estado)
    {
        $this->tipo_estado = $tipo_estado;

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
}

?>