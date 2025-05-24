<?php
require_once("conexion.php");

class Calle{
    private int $id_calle;
    private string $nombre_calle;
    private string $calle_altura;


    public function guardarCalle(){
        $conn = new Conexion();
        $query = "INSERT INTO calle ( nombre_calle, calle_altura ) VALUES ('$this->nombre_calle', $this->calle_altura)";
        $id = $conn->insertar($query);
        $this->setId_calle($id);
    }

    public function actualizarCalle(){
        $conn = new Conexion();
        $query = "UPDATE calle SET nombre_calle = '$this->nombre_calle' WHERE id_calle = $this->id_calle";
        $conn->modificar($query);
    }

    public function eliminarCalle(){
        $conn = new Conexion();
        $query = "DELETE FROM calle WHERE id_calle = $this->id_calle";
        $conn->eliminar($query);
    }

    public function consultarVariasCalles(){
        $conn = new Conexion();
        $query = "SELECT * FROM calle";
        $datos = $conn->consultar($query);
        return $datos;
    }


    public function consultarCalle($id_calle){
        $conn = new Conexion();
        $query = "SELECT * FROM calle WHERE id_calle = $id_calle";
        $datos = $conn->consultar($query);
        return $datos;
    }

    /**
     * Get the value of id_calle
     */ 
    public function getId_calle()
    {
        return $this->id_calle;
    }

    /**
     * Set the value of id_calle
     *
     * @return  self
     */ 
    public function setId_calle($id_calle)
    {
        $this->id_calle = $id_calle;

        return $this;
    }

    /**
     * Get the value of nombre_calle
     */ 
    public function getNombre_calle()
    {
        return $this->nombre_calle;
    }

    /**
     * Set the value of nombre_calle
     *
     * @return  self
     */ 
    public function setNombre_calle($nombre_calle)
    {
        $this->nombre_calle = $nombre_calle;

        return $this;
    }

    /**
     * Get the value of calle_altura
     */ 
    public function getCalle_altura()
    {
        return $this->calle_altura;
    }

    /**
     * Set the value of calle_altura
     *
     * @return  self
     */ 
    public function setCalle_altura($calle_altura)
    {
        $this->calle_altura = $calle_altura;

        return $this;
    }
}

