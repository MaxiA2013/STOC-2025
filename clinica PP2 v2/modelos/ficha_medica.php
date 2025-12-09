<?php
require_once 'conexion.php';
require_once 'cita.php';

class FichaMedica{
    private $id_ficha_medica;
    private $altura;
    private $peso;
    private $medicacion_actual;
    private $observaciones;
    private $cita_id_cita;

    /**
     * Get the value of id_ficha_medica
     */ 
    public function getId_ficha_medica()
    {
        return $this->id_ficha_medica;
    }

    /**
     * Set the value of id_ficha_medica
     *
     * @return  self
     */ 
    public function setId_ficha_medica($id_ficha_medica)
    {
        $this->id_ficha_medica = $id_ficha_medica;

        return $this;
    }

    /**
     * Get the value of altura
     */ 
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * Set the value of altura
     *
     * @return  self
     */ 
    public function setAltura($altura)
    {
        $this->altura = $altura;

        return $this;
    }

    /**
     * Get the value of peso
     */ 
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set the value of peso
     *
     * @return  self
     */ 
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get the value of medicacion_actual
     */ 
    public function getMedicacion_actual()
    {
        return $this->medicacion_actual;
    }

    /**
     * Set the value of medicacion_actual
     *
     * @return  self
     */ 
    public function setMedicacion_actual($medicacion_actual)
    {
        $this->medicacion_actual = $medicacion_actual;

        return $this;
    }

    /**
     * Get the value of observaciones
     */ 
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set the value of observaciones
     *
     * @return  self
     */ 
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get the value of cita_id_cita
     */ 
    public function getCita_id_cita()
    {
        return $this->cita_id_cita;
    }

    /**
     * Set the value of cita_id_cita
     *
     * @return  self
     */ 
    public function setCita_id_cita($cita_id_cita)
    {
        $this->cita_id_cita = $cita_id_cita;

        return $this;
    }

    // Crear ficha médica
    public function crear($altura, $peso, $medicacion_actual, $observaciones, $id_cita)
    {
        $con = new Conexion();
        $id_cita = intval($id_cita);

        $sql = "INSERT INTO ficha_medica (altura, peso, medicacion_actual, observaciones, cita_id_cita)
                VALUES ('$altura', '$peso', '$medicacion_actual', '$observaciones', $id_cita)";

        return $con->insertar($sql);
    }

    // Obtener ficha médica por cita
    public function obtenerPorCita($id_cita)
    {
        $con = new Conexion();
        $id_cita = intval($id_cita);

        $sql = "SELECT * FROM ficha_medica WHERE cita_id_cita = $id_cita LIMIT 1";
        $res = $con->consultarArray($sql);

        return $res[0] ?? null;
    }

    // Actualizar ficha médica existente
    public function actualizar($id_ficha_medica, $altura, $peso, $medicacion_actual, $observaciones)
    {
        $con = new Conexion();
        $id_ficha_medica = intval($id_ficha_medica);

        $sql = "UPDATE ficha_medica SET
                    altura = '$altura',
                    peso = '$peso',
                    medicacion_actual = '$medicacion_actual',
                    observaciones = '$observaciones'
                WHERE id_ficha_medica = $id_ficha_medica";

        return $con->actualizar($sql);
    }
}

?>