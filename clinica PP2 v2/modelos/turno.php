<?php
require_once 'conexion.php';
require_once 'agenda.php';

class Turno{
    private $id_turnos;
    private $minutos_turnos;
    private $fecha_hora;
    private $disponible;
    private Agenda $agenda_id_agenda;

    

    /**
     * Get the value of id_turnos
     */ 
    public function getId_turnos()
    {
        return $this->id_turnos;
    }

    /**
     * Set the value of id_turnos
     *
     * @return  self
     */ 
    public function setId_turnos($id_turnos)
    {
        $this->id_turnos = $id_turnos;

        return $this;
    }

    /**
     * Get the value of minutos_turnos
     */ 
    public function getMinutos_turnos()
    {
        return $this->minutos_turnos;
    }

    /**
     * Set the value of minutos_turnos
     *
     * @return  self
     */ 
    public function setMinutos_turnos($minutos_turnos)
    {
        $this->minutos_turnos = $minutos_turnos;

        return $this;
    }

    /**
     * Get the value of fecha_hora
     */ 
    public function getFecha_hora()
    {
        return $this->fecha_hora;
    }

    /**
     * Set the value of fecha_hora
     *
     * @return  self
     */ 
    public function setFecha_hora($fecha_hora)
    {
        $this->fecha_hora = $fecha_hora;

        return $this;
    }

    /**
     * Get the value of disponible
     */ 
    public function getDisponible()
    {
        return $this->disponible;
    }

    /**
     * Set the value of disponible
     *
     * @return  self
     */ 
    public function setDisponible($disponible)
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Get the value of agenda_id_agenda
     */ 
    public function getAgenda_id_agenda()
    {
        return $this->agenda_id_agenda;
    }

    /**
     * Set the value of agenda_id_agenda
     *
     * @return  self
     */ 
    public function setAgenda_id_agenda($agenda_id_agenda)
    {
        $this->agenda_id_agenda = $agenda_id_agenda;

        return $this;
    }

    public function guardarTurno() {
        $con = new Conexion();
        $query = "INSERT INTO turno (minutos_turnos, fecha_hora, disponible, agenda_id_agenda) 
                  VALUES ({$this->minutos_turnos}, '{$this->fecha_hora}', {$this->disponible}, {$this->agenda_id_agenda})";
        $id = $con->insertar($query);
        $this->setId_turnos($id);
    }

    public function actualizarTurno() {
        $con = new Conexion();
        $query = "UPDATE turno 
                  SET minutos_turnos = {$this->minutos_turnos}, 
                      fecha_hora = '{$this->fecha_hora}', 
                      disponible = {$this->disponible}, 
                      agenda_id_agenda = {$this->agenda_id_agenda}
                  WHERE id_turnos = {$this->id_turnos}";
        $con->actualizar($query);
    }

    public function eliminarTurno() {
        $con = new Conexion();
        $query = "DELETE FROM turno WHERE id_turnos = {$this->id_turnos}";
        $con->eliminar($query);
    }

    public function consultarTurno($id) {
        $con = new Conexion();
        $query = "SELECT * FROM turno WHERE id_turnos = $id";
        return $con->consultar($query);
    }

    public function consultarVariosTurnos() {
        $con = new Conexion();
        $query = "SELECT * FROM turno";
        return $con->consultar($query);
    }

    // turnos por doctor en relación con agenda
    public function consultarTurnoDoctor($id_doctor) {
        $con = new Conexion();
        $query = "SELECT t.* 
                  FROM turno t 
                  INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
                  WHERE a.doctor_id_doctor = $id_doctor";
        return $con->consultar($query);
    }

    //trae resultados de turnos segun fecha_hora especifico
    public function consultarTurnoFecha($fecha_hora) {
        $con = new Conexion();
        $query = "SELECT * FROM turno WHERE DATE(fecha_hora) = '$fecha_hora'";
        return $con->consultar($query);
    }

    //trae resultados de turnos segun disponibilidad
    public function consultarTurnoDisponible($disponible = 1) {
        $con = new Conexion();
        $query = "SELECT * FROM turno WHERE disponible = $disponible";
        return $con->consultar($query);
    }

    //trae resultados de turnos segun el agenda_id_agenda especifico
    public function consultarTurnoAgenda($id_agenda) {
        $con = new Conexion();
        $query = "SELECT * FROM turno WHERE agenda_id_agenda = $id_agenda";
        return $con->consultar($query);
    }
}

?>