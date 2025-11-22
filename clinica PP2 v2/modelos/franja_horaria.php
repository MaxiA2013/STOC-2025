<?php
require_once 'conexion.php';

class Franja
{
    private $id_franja;
    private $tipo_franja;
    private $inicio_franja;
    private $fin_franja;

    public function getId_franja()
    {
        return $this->id_franja;
    }
    public function setId_franja($id_franja)
    {
        $this->id_franja = $id_franja;
        return $this;
    }

    public function getTipo_franja()
    {
        return $this->tipo_franja;
    }
    public function setTipo_franja($tipo_franja)
    {
        $this->tipo_franja = $tipo_franja;
        return $this;
    }

    public function getInicio_franja()
    {
        return $this->inicio_franja;
    }
    public function setInicio_franja($inicio_franja)
    {
        $this->inicio_franja = $inicio_franja;
        return $this;
    }

    public function getFin_franja()
    {
        return $this->fin_franja;
    }
    public function setFin_franja($fin_franja)
    {
        $this->fin_franja = $fin_franja;
        return $this;
    }

   public function guardarFranja() {
        $con = new Conexion();
        $query = "INSERT INTO franja_horaria (tipo_franja, inicio_franja, fin_franja)
                  VALUES ('{$this->tipo_franja}', '{$this->inicio_franja}', '{$this->fin_franja}')";
        return $con->insertar($query);
    }

    public function actualizarFranja() {
        $con = new Conexion();
        $query = "UPDATE franja_horaria 
                  SET tipo_franja = '{$this->tipo_franja}',
                      inicio_franja = '{$this->inicio_franja}',
                      fin_franja = '{$this->fin_franja}'
                  WHERE id_franja = {$this->id_franja}";
        return $con->actualizar($query);
    }

    public function eliminarFranja() {
        $con = new Conexion();
        $query = "DELETE FROM franja_horaria WHERE id_franja = {$this->id_franja}";
        return $con->eliminar($query);
    }

    public function consultarVariasFranjas() {
        $con = new Conexion();
        return $con->consultar("SELECT * FROM franja_horaria");
    }

    public function existeFranja() {
        $con = new Conexion();
        $query = "SELECT id_franja FROM franja_horaria 
                  WHERE tipo_franja = '{$this->tipo_franja}'";
        return $con->consultar($query);
    }
}
