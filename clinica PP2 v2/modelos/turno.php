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

    // GENERAR TURNOS
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

    public function eliminarPorAgenda($agenda_id_agenda)
    {
        $con = new Conexion();
        return $con->eliminar("DELETE FROM turno WHERE agenda_id_agenda = '$this->agenda_id_agenda'");
    }

    public function consultarVariosTurnos(){
        $conexion = new Conexion();
        $query = "SELECT 
                t.id_turnos,
                t.minutos_turnos,
                t.fecha_hora,
                t.disponible,
                t.agenda_id_agenda AS agenda_id,
                a.fecha_desde AS fecha_agenda,
                d.id_doctor AS doctor_id,
                per.nombre AS nombre_doctor,
                per.apellido
            FROM turno t
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            ORDER BY t.fecha_hora ASC";

        return $conexion->consultar($query);
    }

    public function listarTurnoXAgenda($id_agenda){
        $con = new Conexion();
        $query = "SELECT 
            t.id_turnos,
            t.minutos_turnos,
            t.fecha_hora,
            t.disponible,
            t.agenda_id_agenda
        FROM turno t
        WHERE t.agenda_id_agenda = $id_agenda
        ORDER BY t.fecha_hora ASC";
        return $con->consultar($query);
    }

    public function actualizar(){
        $con = new Conexion();
        $query = "UPDATE turno SET
                    minutos_turnos = '$this->minutos_turnos',
                    fecha_hora = '$this->fecha_hora',
                    disponible = '$this->disponible',
                    agenda_id_agenda = '$this->agenda_id_agenda'
                WHERE id_turnos = '$this->id_turnos'";
        return $con->actualizar($query);
    }

    public function actualizarDisponible($id_turno)
    {
        $con = new Conexion();
        $query ="UPDATE turno SET disponible = 0 WHERE id_turnos = '$this->id_turnos'";
        return $con->actualizar($query);
    }

    public function existeTurnoDisponible($id_turnos){
      $con = new Conexion();
      $sql = "SELECT disponible FROM turno WHERE id_turnos = '$this->id_turnos'";
      return $con-> consultar($sql);
    }

    // Setters
    public function setMinutos_turnos($v)
    {
        $this->minutos_turnos = $v;
    }
    public function setFecha_hora($v)
    {
        $this->fecha_hora = $v;
    }
    public function setDisponible($v)
    {
        $this->disponible = $v;
    }
    public function setAgenda_id_agenda($v)
    {
        $this->agenda_id_agenda = $v;
    }
}

?>
