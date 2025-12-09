<?php
require_once 'conexion.php';
require_once 'confirmación.php';

class Cita{
    private $id_cita;
    private $razon;
    private $asistencia_doctor;
    private $asistencia_paciente;
    private $confirmacion_id_confirmacion;


    /**
     * Get the value of id_cita
     */ 
    public function getId_cita()
    {
        return $this->id_cita;
    }

    /**
     * Set the value of id_cita
     *
     * @return  self
     */ 
    public function setId_cita($id_cita)
    {
        $this->id_cita = $id_cita;

        return $this;
    }

    /**
     * Get the value of razon
     */ 
    public function getRazon()
    {
        return $this->razon;
    }

    /**
     * Set the value of razon
     *
     * @return  self
     */ 
    public function setRazon($razon)
    {
        $this->razon = $razon;

        return $this;
    }

    /**
     * Get the value of asistencia_doctor
     */ 
    public function getAsistencia_doctor()
    {
        return $this->asistencia_doctor;
    }

    /**
     * Set the value of asistencia_doctor
     *
     * @return  self
     */ 
    public function setAsistencia_doctor($asistencia_doctor)
    {
        $this->asistencia_doctor = $asistencia_doctor;

        return $this;
    }

    /**
     * Get the value of asistencia_paciente
     */ 
    public function getAsistencia_paciente()
    {
        return $this->asistencia_paciente;
    }

    /**
     * Set the value of asistencia_paciente
     *
     * @return  self
     */ 
    public function setAsistencia_paciente($asistencia_paciente)
    {
        $this->asistencia_paciente = $asistencia_paciente;

        return $this;
    }

    /**
     * Get the value of confirmacion_id_confirmacion
     */ 
    public function getConfirmacion_id_confirmacion()
    {
        return $this->confirmacion_id_confirmacion;
    }

    /**
     * Set the value of confirmacion_id_confirmacion
     *
     * @return  self
     */ 
    public function setConfirmacion_id_confirmacion($confirmacion_id_confirmacion)
    {
        $this->confirmacion_id_confirmacion = $confirmacion_id_confirmacion;

        return $this;
    }

    // Crear cita a partir de una confirmación
    public function crearDesdeConfirmacion($id_confirmacion, $razon = "")
    {
        $con = new Conexion();
        $id_confirmacion = intval($id_confirmacion);
        $sql = "INSERT INTO cita (razon, asistencia_doctor, asistencia_paciente, confirmacion_id_confirmacion)
                VALUES ('$razon', NULL, NULL, $id_confirmacion)";
        return $con->insertar($sql);
    }

    public function obtenerPorId($id_cita)
    {
        $con = new Conexion();
        $id_cita = intval($id_cita);
        $sql = "SELECT * FROM cita WHERE id_cita = $id_cita";
        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // Obtener cita por confirmación (1:1)
    public function obtenerPorConfirmacion($id_confirmacion)
    {
        $con = new Conexion();
        $id_confirmacion = intval($id_confirmacion);

        $sql = "SELECT * FROM cita 
                WHERE confirmacion_id_confirmacion = $id_confirmacion
                LIMIT 1";
        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // Actualizar asistencia
    public function actualizarAsistencia($id_cita, $asistencia_doctor, $asistencia_paciente)
    {
        $con = new Conexion();
        $id_cita = intval($id_cita);

        $ad = is_null($asistencia_doctor) ? "NULL" : (intval($asistencia_doctor) ? 1 : 0);
        $ap = is_null($asistencia_paciente) ? "NULL" : (intval($asistencia_paciente) ? 1 : 0);

        $sql = "UPDATE cita SET 
                    asistencia_doctor = $ad,
                    asistencia_paciente = $ap
                WHERE id_cita = $id_cita";

        return $con->actualizar($sql);
    }
}

?>