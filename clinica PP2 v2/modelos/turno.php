<?php
require_once 'conexion.php';

class Turno
{
    private $id_turnos;
    private $minutos_turnos;
    private $fecha_hora;
    private $disponible;    
    private $agenda_id_agenda;

    // ------------------------------------------
    // GUARDAR TURNO
    // ------------------------------------------
    public function guardarTurno()
    {
        $con = new Conexion();
        $query = "INSERT INTO turno (minutos_turnos, fecha_hora, disponible, agenda_id_agenda)
                  VALUES ($this->minutos_turnos, '$this->fecha_hora', $this->disponible, $this->agenda_id_agenda)";
        return $con->insertar($query);
    }

    // ------------------------------------------
    // GENERAR TURNOS
    // ------------------------------------------
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

    // ------------------------------------------
    // ELIMINAR TURNOS DE UNA AGENDA
    // ------------------------------------------
    public function eliminarPorAgenda($id_agenda)
    {
        $con = new Conexion();
        return $con->eliminar("DELETE FROM turno WHERE agenda_id_agenda = $id_agenda");
    }

    // Setters
    public function setMinutos_turnos($v){ $this->minutos_turnos = $v; }
    public function setFecha_hora($v){ $this->fecha_hora = $v; }
    public function setDisponible($v){ $this->disponible = $v; }
    public function setAgenda_id_agenda($v){ $this->agenda_id_agenda = $v; }
}
