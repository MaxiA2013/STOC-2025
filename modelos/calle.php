<?php

require_once "conexion.php";
require_once "barrio.php";

class Calle
{
    private int $id_calle;
    private string $nombre_calle;
    private string $calle_altura;
    private int $barrio_id_barrio;


    public function guardarCalle()
    {
        $conn = new Conexion();

        $query = "INSERT INTO calle (nombre_calle, calle_altura, barrio_id_barrio) VALUES ('$this->nombre_calle', '$this->calle_altura', $this->barrio_id_barrio)";
        $id = $conn->insertar($query);
        $this->setId_calle($id);
    }


    public function actualizarCalle()
    {
        $conn = new Conexion();
        $query = "UPDATE calle SET nombre_calle = '$this->nombre_calle', calle_altura = '$this->calle_altura', barrio_id_barrio = $this->barrio_id_barrio WHERE id_calle = $this->id_calle";
        $conn->actualizar($query);
    }


    public function eliminarCalle()
    {
        $conn = new Conexion();
        $query = "DELETE FROM calle WHERE id_calle = $this->id_calle";
        $conn->eliminar($query);
    }


    public function consultarVariasCalles()
    {
        $conn = new Conexion();
        $query = "SELECT * FROM calle";
        $datos = $conn->consultar($query);
        return $datos;
    }


    public function consultarCalle($id_calle)
    {
        $conn = new Conexion();
        $query = "SELECT * FROM calle WHERE id_calle = $id_calle";
        $datos = $conn->consultar($query);
        return $datos;
    }

    public function getId_calle()
    {
        return $this->id_calle;
    }

    public function setId_calle($id_calle)
    {
        $this->id_calle = $id_calle;
        return $this;
    }

    public function getNombre_calle()
    {
        return $this->nombre_calle;
    }

    public function setNombre_calle($nombre_calle)
    {
        $this->nombre_calle = $nombre_calle;

        return $this;
    }

    public function getCalle_altura()
    {
        return $this->calle_altura;
    }

    public function setCalle_altura($calle_altura)
    {
        $this->calle_altura = $calle_altura;

        return $this;
    }

    public function getBarrio_id_barrio()
    {
        return $this->barrio_id_barrio;
    }


    public function setBarrio_id_barrio($barrio_id_barrio)
    {
        $this->barrio_id_barrio = $barrio_id_barrio;

        return $this;
    }
}
?>

<script src="assets/js/validaciones/validaciones_controlador.js"></script>