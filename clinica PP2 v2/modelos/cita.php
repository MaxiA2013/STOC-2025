<?php
require_once 'conexion.php';

class Cita
{
    private $id_cita;
    private $razon;
    private $asistencia_doctor;      // TINYINT(1) NULL/0/1
    private $asistencia_paciente;    // TINYINT(1) NULL/0/1
    private $agenda_turno_id_agenda_turno;

    // ==========================================
    // CREAR / OBTENER
    // ==========================================

    // Crear la cita de un agenda_turno (hay una sola por turno asignado)
    public function crearParaAgendaTurno($id_agenda_turno)
    {
        $con = new Conexion();
        $id_agenda_turno = intval($id_agenda_turno);

        if ($id_agenda_turno <= 0) {
            return false;
        }

        $sql = "INSERT INTO cita (asistencia_doctor, asistencia_paciente, agenda_turno_id_agenda_turno)
                VALUES (NULL, NULL, $id_agenda_turno)";

        return $con->insertar($sql);
    }

    // Obtener por id
    public function obtenerPorId($id_cita)
    {
        $con = new Conexion();
        $id_cita = intval($id_cita);

        $sql = "SELECT * FROM cita WHERE id_cita = $id_cita LIMIT 1";
        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // Obtener por agenda_turno
    public function obtenerPorAgendaTurno($id_agenda_turno)
    {
        $con = new Conexion();
        $id_agenda_turno = intval($id_agenda_turno);

        $sql = "SELECT * FROM cita
                WHERE agenda_turno_id_agenda_turno = $id_agenda_turno
                LIMIT 1";
        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    /**
     * Devuelve la cita del agenda_turno y, si todavía no existe, la crea.
     * Si otro proceso la creó en el medio, el UNIQUE evita el duplicado
     * y simplemente se vuelve a leer.
     */
    public function obtenerOCrearPorAgendaTurno($id_agenda_turno)
    {
        $cita = $this->obtenerPorAgendaTurno($id_agenda_turno);

        if ($cita) {
            return $cita;
        }

        $this->crearParaAgendaTurno($id_agenda_turno);

        return $this->obtenerPorAgendaTurno($id_agenda_turno);
    }

    /**
     * Datos necesarios para validar permisos y notificar:
     * quién es el paciente, quién es el doctor, cuándo es el turno
     * y en qué estado está la asignación.
     */
    public function obtenerContextoAgendaTurno($id_agenda_turno)
    {
        $con = new Conexion();
        $id_agenda_turno = intval($id_agenda_turno);

        $sql = "SELECT
                    at.id_agenda_turno,
                    at.paciente_id_paciente,
                    at.estados_id_estados,

                    t.id_turnos,
                    t.fecha_hora,
                    t.minutos_turnos,

                    a.doctor_id_doctor,

                    p.usuario_id_usuario AS paciente_usuario_id,
                    perp.nombre AS paciente_nombre,
                    perp.apellido AS paciente_apellido,

                    d.usuario_id_usuario AS doctor_usuario_id,
                    perd.nombre AS doctor_nombre,
                    perd.apellido AS doctor_apellido

                FROM agenda_turno at

                INNER JOIN turno t
                    ON at.turno_id_turnos = t.id_turnos

                INNER JOIN agenda a
                    ON t.agenda_id_agenda = a.id_agenda

                INNER JOIN paciente p
                    ON at.paciente_id_paciente = p.id_paciente

                INNER JOIN usuario up
                    ON p.usuario_id_usuario = up.id_usuario

                INNER JOIN persona perp
                    ON up.persona_id_persona = perp.id_persona

                INNER JOIN doctor d
                    ON a.doctor_id_doctor = d.id_doctor

                INNER JOIN usuario ud
                    ON d.usuario_id_usuario = ud.id_usuario

                INNER JOIN persona perd
                    ON ud.persona_id_persona = perd.id_persona

                WHERE at.id_agenda_turno = $id_agenda_turno
                LIMIT 1";

        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // ==========================================
    // ASISTENCIA
    // ==========================================

    /**
     * Registra la asistencia de una de las dos partes.
     *
     * $esPaciente = true  -> asistencia del paciente
     * $esPaciente = false -> asistencia del doctor
     * $valor              -> 1 presente / 0 ausente
     * $porDoctor          -> solo aplica a la asistencia del paciente:
     *                        true si la marcó el doctor en su nombre.
     */
    public function registrarAsistencia($id_cita, $esPaciente, $valor, $porDoctor = false)
    {
        $con = new Conexion();

        $id_cita = intval($id_cita);
        $valor = $valor ? 1 : 0;

        if ($esPaciente) {

            $porDoctorSql = $porDoctor ? 1 : 0;

            $sql = "UPDATE cita
                    SET asistencia_paciente = $valor,
                        fecha_asistencia_paciente = NOW(),
                        asistencia_paciente_por_doctor = $porDoctorSql
                    WHERE id_cita = $id_cita";
        } else {

            $sql = "UPDATE cita
                    SET asistencia_doctor = $valor,
                        fecha_asistencia_doctor = NOW()
                    WHERE id_cita = $id_cita";
        }

        return $con->actualizar($sql);
    }

    // ¿Ambos estuvieron presentes? (habilita la ficha médica)
    public function ambosAsistieron($id_cita)
    {
        $data = $this->obtenerPorId($id_cita);

        if (!$data) {
            return false;
        }

        return ($data['asistencia_paciente'] !== null
            && $data['asistencia_doctor'] !== null
            && intval($data['asistencia_paciente']) === 1
            && intval($data['asistencia_doctor']) === 1);
    }

    // Marca el momento en que se cerró la cita
    public function marcarFinalizada($id_cita)
    {
        $con = new Conexion();
        $id_cita = intval($id_cita);

        $sql = "UPDATE cita
                SET fecha_finalizacion = NOW()
                WHERE id_cita = $id_cita";

        return $con->actualizar($sql);
    }

    // Motivo de la cita
    public function actualizarRazon($id_cita, $razon)
    {
        $con = new Conexion();

        $id_cita = intval($id_cita);
        $razon = addslashes(trim((string) $razon));

        $sql = "UPDATE cita
                SET razon = '$razon'
                WHERE id_cita = $id_cita";

        return $con->actualizar($sql);
    }

    // ==========================================
    // LISTADOS
    // ==========================================

    /**
     * Citas del doctor para la vista "Historial de citas":
     * turnos asignados desde 30 días atrás, con paciente, cobertura,
     * confirmación y asistencia.
     *
     * Para sumar DNI y teléfono del paciente agregar al SELECT
     * (con los nombres reales de las columnas de persona):
     *     perp.dni AS paciente_dni,
     *     perp.telefono AS paciente_telefono,
     */
    public function listarCitasDoctor($id_doctor)
    {
        $con = new Conexion();
        $id_doctor = intval($id_doctor);

        if ($id_doctor <= 0) {
            return [];
        }

        $sql = "SELECT
                    at.id_agenda_turno,
                    at.estados_id_estados,
                    e.tipo_estado,

                    t.id_turnos,
                    t.fecha_hora,
                    t.minutos_turnos,
                    t.con_obra_social,
                    os.nombre_obra_social,

                    p.id_paciente,
                    perp.nombre AS paciente_nombre,
                    perp.apellido AS paciente_apellido,

                    c.id_confirmacion,
                    c.confirmacion_paciente,
                    c.confirmacion_doctor,

                    ci.id_cita,
                    ci.razon,
                    ci.asistencia_paciente,
                    ci.asistencia_doctor,
                    ci.asistencia_paciente_por_doctor

                FROM agenda_turno at

                INNER JOIN turno t
                    ON at.turno_id_turnos = t.id_turnos

                INNER JOIN agenda a
                    ON t.agenda_id_agenda = a.id_agenda

                INNER JOIN paciente p
                    ON at.paciente_id_paciente = p.id_paciente

                INNER JOIN usuario up
                    ON p.usuario_id_usuario = up.id_usuario

                INNER JOIN persona perp
                    ON up.persona_id_persona = perp.id_persona

                INNER JOIN estados e
                    ON at.estados_id_estados = e.id_estados

                LEFT JOIN obra_social os
                    ON t.obra_social_id_obra_social = os.id_obra_social

                LEFT JOIN confirmacion c
                    ON c.agenda_turno_id_agenda_turno = at.id_agenda_turno

                LEFT JOIN cita ci
                    ON ci.agenda_turno_id_agenda_turno = at.id_agenda_turno

                WHERE a.doctor_id_doctor = $id_doctor
                  AND a.estados_id_estados <> 3
                  AND t.fecha_hora >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)

                ORDER BY t.fecha_hora ASC";

        return $con->consultarArray($sql);
    }

    // ==========================================
    // GETTERS Y SETTERS
    // ==========================================

    public function getId_cita()
    {
        return $this->id_cita;
    }

    public function setId_cita($id_cita)
    {
        $this->id_cita = $id_cita;

        return $this;
    }

    public function getRazon()
    {
        return $this->razon;
    }

    public function setRazon($razon)
    {
        $this->razon = $razon;

        return $this;
    }

    public function getAsistencia_doctor()
    {
        return $this->asistencia_doctor;
    }

    public function setAsistencia_doctor($asistencia_doctor)
    {
        $this->asistencia_doctor = $asistencia_doctor;

        return $this;
    }

    public function getAsistencia_paciente()
    {
        return $this->asistencia_paciente;
    }

    public function setAsistencia_paciente($asistencia_paciente)
    {
        $this->asistencia_paciente = $asistencia_paciente;

        return $this;
    }

    public function getAgenda_turno_id_agenda_turno()
    {
        return $this->agenda_turno_id_agenda_turno;
    }

    public function setAgenda_turno_id_agenda_turno($agenda_turno_id_agenda_turno)
    {
        $this->agenda_turno_id_agenda_turno = $agenda_turno_id_agenda_turno;

        return $this;
    }
}