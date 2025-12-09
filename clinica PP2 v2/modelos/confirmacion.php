<?php

require_once 'conexion.php';
require_once 'agenda.php';
require_once 'turno.php';

class Confirmacion{
    private $id_confirmacion;
    private $confirmacion_paciente; // TINYINT(1) NULL/0/1
    private $confirmacion_doctor;   // TINYINT(1) NULL/0/1
    private $agenda_turno_id_agenda_turno;

    /**
     * Get the value of id_confirmacion
     */ 
    public function getId_confirmacion()
    {
        return $this->id_confirmacion;
    }

    /**
     * Set the value of id_confirmacion
     *
     * @return  self
     */ 
    public function setId_confirmacion($id_confirmacion)
    {
        $this->id_confirmacion = $id_confirmacion;

        return $this;
    }

    /**
     * Get the value of confirmacion_paciente
     */ 
    public function getConfirmacion_paciente()
    {
        return $this->confirmacion_paciente;
    }

    /**
     * Set the value of confirmacion_paciente
     *
     * @return  self
     */ 
    public function setConfirmacion_paciente($confirmacion_paciente)
    {
        $this->confirmacion_paciente = $confirmacion_paciente;

        return $this;
    }

    /**
     * Get the value of confirmacion_doctor
     */ 
    public function getConfirmacion_doctor()
    {
        return $this->confirmacion_doctor;
    }

    /**
     * Set the value of confirmacion_doctor
     *
     * @return  self
     */ 
    public function setConfirmacion_doctor($confirmacion_doctor)
    {
        $this->confirmacion_doctor = $confirmacion_doctor;

        return $this;
    }

    /**
     * Get the value of agenda_turno_id_agenda_turno
     */ 
    public function getAgenda_turno_id_agenda_turno()
    {
        return $this->agenda_turno_id_agenda_turno;
    }

    /**
     * Set the value of agenda_turno_id_agenda_turno
     *
     * @return  self
     */ 
    public function setAgenda_turno_id_agenda_turno($agenda_turno_id_agenda_turno)
    {
        $this->agenda_turno_id_agenda_turno = $agenda_turno_id_agenda_turno;

        return $this;
    }

    // Crear registro de confirmación para un agenda_turno
    public function crearParaAgendaTurno($id_agenda_turno)
    {
        $con = new Conexion();
        $id_agenda_turno = intval($id_agenda_turno);

        $sql = "INSERT INTO confirmacion (confirmacion_paciente, confirmacion_doctor, agenda_turno_id_agenda_turno)
                VALUES (NULL, NULL, $id_agenda_turno)";
        return $con->insertar($sql);
    }

    // Actualizar confirmación (paciente o doctor)
    public function actualizarConfirmacion($id_confirmacion, $esPaciente, $valor)
    {
        $con = new Conexion();

        $id_confirmacion = intval($id_confirmacion);
        $valor = $valor ? 1 : 0;

        if ($esPaciente) {
            $sql = "UPDATE confirmacion 
                    SET confirmacion_paciente = $valor
                    WHERE id_confirmacion = $id_confirmacion";
        } else {
            $sql = "UPDATE confirmacion 
                    SET confirmacion_doctor = $valor
                    WHERE id_confirmacion = $id_confirmacion";
        }

        return $con->actualizar($sql);
    }

    // Obtener por id
    public function obtenerPorId($id_confirmacion)
    {
        $con = new Conexion();
        $id_confirmacion = intval($id_confirmacion);

        $sql = "SELECT * FROM confirmacion WHERE id_confirmacion = $id_confirmacion";
        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // Obtener por agenda_turno
    public function obtenerPorAgendaTurno($id_agenda_turno)
    {
        $con = new Conexion();
        $id_agenda_turno = intval($id_agenda_turno);

        $sql = "SELECT * FROM confirmacion 
                WHERE agenda_turno_id_agenda_turno = $id_agenda_turno
                LIMIT 1";
        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // ¿Ambos confirmaron?
    public function ambosConfirmaron($id_confirmacion)
    {
        $data = $this->obtenerPorId($id_confirmacion);
        if (!$data) return false;
        return (intval($data['confirmacion_paciente']) === 1
             && intval($data['confirmacion_doctor']) === 1);
    }
}

?>