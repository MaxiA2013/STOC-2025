<?php
require_once 'conexion.php';

class Turno
{
    private $id_turnos;
    private $minutos_turnos;
    private $fecha_hora;
    private $disponible;
    private $agenda_id_agenda;

<<<<<<< HEAD

    public function guardarTurno()
    {
        $con = new Conexion();
        $query = "INSERT INTO turno (minutos_turnos, fecha_hora, disponible, agenda_id_agenda) 
=======
    public function guardarTurno()
    {
        $con = new Conexion();
        $query = "INSERT INTO turno (minutos_turnos, fecha_hora, disponible, agenda_id_agenda)
>>>>>>> origin/mi-ramita
                  VALUES ($this->minutos_turnos, '$this->fecha_hora', $this->disponible, $this->agenda_id_agenda)";
        return $con->insertar($query);
    }

<<<<<<< HEAD
=======
    // GENERAR TURNOS
>>>>>>> origin/mi-ramita
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

<<<<<<< HEAD
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
=======
    public function eliminarPorAgenda($agenda_id_agenda)
    {
        $con = new Conexion();
        return $con->eliminar("DELETE FROM turno WHERE agenda_id_agenda = '$this->agenda_id_agenda'");
    }

    public function consultarVariosTurnos()
    {
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

    public function listarTurnoXAgenda($id_agenda)
    {
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

    public function actualizar()
    {
        $con = new Conexion();
        $query = "UPDATE turno SET
                    minutos_turnos = '$this->minutos_turnos',
                    fecha_hora = '$this->fecha_hora',
                    disponible = '$this->disponible',
                    agenda_id_agenda = '$this->agenda_id_agenda'
                WHERE id_turnos = '$this->id_turnos'";
        return $con->actualizar($query);
    }

    public function actualizarDisponible($id_turnos)
    {
        $con = new Conexion();
        $query = "UPDATE turno SET disponible = 0 WHERE id_turnos = $id_turnos";
        return $con->actualizar($query);
    }

    public function existeTurnoDisponible($id_turnos)
    {
        $con = new Conexion();
        $sql = "SELECT disponible FROM turno WHERE id_turnos = $id_turnos";
        return $con->consultar($sql);
    }

    public function listarTurnosDisponibles()
    {
        $con = new Conexion();
        $query = "SELECT 
                t.id_turnos,
                t.minutos_turnos,
                t.fecha_hora,
                t.disponible,
                t.agenda_id_agenda,
                d.id_doctor AS doctor_id,
                per.nombre AS nombre_doctor,
                per.apellido AS apellido_doctor,
                a.fecha_desde,
                a.hora_desde,
                a.hora_hasta
            FROM turno t
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            WHERE t.disponible = 1
            ORDER BY t.fecha_hora ASC";

        return $con->consultar($query);
    }

public function consultarTurnosDisponiblesPaginado($offset, $porPagina)
{
    $con = new Conexion();

    $query = "SELECT 
                t.id_turnos,
                t.fecha_hora,
                t.minutos_turnos,
                t.disponible,
                t.agenda_id_agenda,
                a.fecha_desde,
                d.id_doctor,
                per.nombre,
                per.apellido
            FROM turno t
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            WHERE t.disponible = 1
            ORDER BY t.fecha_hora ASC
            LIMIT $offset, $porPagina";

    $res = $con->consultar($query);

    // Convertimos el mysqli_result en array
    $datos = [];
    if ($res) {
        while ($fila = $res->fetch_assoc()) {
            $datos[] = $fila;
        }
    }
    return $datos;
}
    

public function contarTurnosDisponibles()
{

    $con = new Conexion();
    $query = "SELECT COUNT(*) AS total FROM turno WHERE disponible = 1";

    $res = $con->consultar($query);

    if ($res) {
        $fila = $res->fetch_assoc(); // Convertimos el resultado en un array asociativo
        return $fila['total'];       // Devolvemos el número
    }

    return 0; // si hay error

}


public function obtenerTurnoPorId($id_turno)
{
    $con = new Conexion();

    $query = "SELECT 
                t.id_turnos,
                t.fecha_hora,
                t.minutos_turnos,
                t.disponible,
                t.agenda_id_agenda,
                a.fecha_desde,
                d.id_doctor,
                per.nombre,
                per.apellido
            FROM turno t
            INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
            INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
            INNER JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
            INNER JOIN persona per ON u.persona_id_persona = per.id_persona
            WHERE t.id_turnos = $id_turno
            LIMIT 1";

    $res = $con->consultar($query); // retorna mysqli_result

    if ($res && $res->num_rows > 0) {
        return $res->fetch_assoc(); // ✅ convertir a array asociativo
    } else {
        return null;
    }
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
>>>>>>> origin/mi-ramita

        return $this;
    }
}
