<?php
require_once 'conexion.php';
require_once 'agenda.php';

class Turno
{
    private $id_turnos;
    private $minutos_turnos;
    private $fecha_hora;
    private $disponible;
    private int $agenda_id_agenda;



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

    public function guardarTurno()
    {
        $con = new Conexion();
        $query = "INSERT INTO turno (minutos_turnos, fecha_hora, disponible, agenda_id_agenda) 
                  VALUES ({$this->minutos_turnos}, '{$this->fecha_hora}', {$this->disponible}, {$this->agenda_id_agenda})";
        return $con->insertar($query);
    }

    public function actualizarTurno()
    {
        $con = new Conexion();
        $query = "UPDATE turno 
                  SET minutos_turnos = {$this->minutos_turnos}, 
                      fecha_hora = '{$this->fecha_hora}', 
                      disponible = {$this->disponible}, 
                      agenda_id_agenda = {$this->agenda_id_agenda}
                  WHERE id_turnos = {$this->id_turnos}";
        return $con->actualizar($query);
    }

    public function eliminarTurno()
    {
        $con = new Conexion();
        $query = "DELETE FROM turno WHERE id_turnos = {$this->id_turnos}";
        return $con->eliminar($query);
    }

    public function consultarTurno($id)
    {
        $con = new Conexion();
        $query = "SELECT * FROM turno WHERE id_turnos = $id";
        $res = $con->consultar($query);
        // normalizar a array
        $rows = [];
        if (is_object($res) && method_exists($res, 'fetch_assoc')) {
            while ($r = $res->fetch_assoc()) $rows[] = $r;
        } elseif (is_array($res)) $rows = $res;
        return $rows;
    }

    /**
     * Devuelve todos los turnos con datos de agenda y doctor.
     * Las claves que se retornan son:
     * id_turnos, minutos_turnos, fecha_hora, disponible,
     * agenda_id, fecha_agenda,
     * doctor_id, nombre_doctor, apellido
     */
    public function consultarVariosTurnos()
    {
        $con = new Conexion();
        $query = "SELECT 
                    t.id_turnos,
                    t.minutos_turnos,
                    t.fecha_hora,
                    t.disponible,
                    t.agenda_id_agenda AS agenda_id,
                    a.fecha_agenda,
                    d.id_doctor AS doctor_id,
                    p.nombre AS nombre_doctor,
                    p.apellido
                  FROM turno t
                  INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
                  INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
                  INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
                  INNER JOIN persona p ON u.persona_id_persona = p.id_persona
                  ORDER BY t.id_turnos DESC";
        $res = $con->consultar($query);

        $rows = [];
        if (is_object($res) && method_exists($res, 'fetch_assoc')) {
            while ($r = $res->fetch_assoc()) $rows[] = $r;
        } elseif (is_array($res)) {
            $rows = $res;
        }
        return $rows;
    }

    // obtener turnos por agenda (útil si hace falta)
    public function consultarTurnoAgenda($id_agenda)
    {
        $con = new Conexion();
        $query = "SELECT * FROM turno WHERE agenda_id_agenda = $id_agenda";
        $res = $con->consultar($query);
        $rows = [];
        if (is_object($res) && method_exists($res, 'fetch_assoc')) {
            while ($r = $res->fetch_assoc()) $rows[] = $r;
        } elseif (is_array($res)) $rows = $res;
        return $rows;
    }
}
