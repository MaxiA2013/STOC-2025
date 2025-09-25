<?php
require_once 'conexion.php';

class Familiar{
    private $id_familiar;
    private $relacion;
    private $descripcion;

    

    /**
     * Get the value of id_familiar
     */ 
    public function getId_familiar()
    {
        return $this->id_familiar;
    }

    /**
     * Set the value of id_familiar
     *
     * @return  self
     */ 
    public function setId_familiar($id_familiar)
    {
        $this->id_familiar = $id_familiar;

        return $this;
    }

    /**
     * Get the value of relacion
     */ 
    public function getRelacion()
    {
        return $this->relacion;
    }

    /**
     * Set the value of relacion
     *
     * @return  self
     */ 
    public function setRelacion($relacion)
    {
        $this->relacion = $relacion;

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

    public function guardarFamiliar(){
        $con = new Conexion();
        $query = "INSERT INTO familiar (relacion , descripcion) VALUES ('{$this->relacion}', '{$this->descripcion}')";
        $id = $con->insertar($query);
        $this->setId_familiar($id);
    }

    public function actualizarFamiliar(){
        $con = new Conexion();
        $query = "UPDATE familiar SET relacion='{$this->relacion}', descripcion='{$this->descripcion}' WHERE id_familiar={$this->id_familiar}";
        $con->actualizar($query);
    }

    public function eliminarFamiliar(){
        $con = new Conexion();
        $query = "DELETE FROM familiar WHERE id_familiar={$this->id_familiar}";
        $con->eliminar($query);
    }

    public function consultarFamiliar($id){
        $con = new Conexion();
        $query = "SELECT * FROM familiar WHERE id_fmailiar={$id}";
        $datos = $con->consultar($query);
    }

    public function consultarVariosFamiliar(){
        $con = new Conexion();
        $query = "SELECT * FROM familiar";
        $datos = $con->consultar($query);
        return $datos;
    }
}

?>