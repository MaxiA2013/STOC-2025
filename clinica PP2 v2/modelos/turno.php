<?php
require_once 'conexion.php';

class Turno
{
    private $id_turnos;
    private $minutos_turnos;
    private $fecha_hora;
    private $disponible;
    private $agenda_id_agenda;


    public function guardarTurno()
    {
        $con = new Conexion();
        $query = "INSERT INTO turno (minutos_turnos, fecha_hora, disponible, agenda_id_agenda) 
                  VALUES ($this->minutos_turnos, '$this->fecha_hora', $this->disponible, $this->agenda_id_agenda)";
        return $con->insertar($query);
    }

    public function generarTurnosParaAgenda($id_agenda, $fecha, $inicio, $fin, $minutos)
    {
        $this->agenda_id_agenda = $id_agenda;

        $horaActual = strtotime("$fecha $inicio");
        $horaFin = strtotime("$fecha $fin");

        while ($horaActual < $horaFin) {

            $this->minutos_turnos = $minutos;
            $this->fecha_hora = date("Y-m-d H:i:s", $horaActual);
            $this->disponible = 1;

            $this->guardarTurno();

            $horaActual = strtotime("+$minutos minutes", $horaActual);
        }
    }

    public function eliminarPorAgenda($id_agenda)
    {
        $con = new Conexion();
        return $con->eliminar("DELETE FROM turno WHERE agenda_id_agenda = $id_agenda");
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
}
