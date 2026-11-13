<?php
require_once 'conexion.php';

class FichaMedica
{
    private $id_ficha_medica;
    private $altura;
    private $peso;
    private $medicacion_actual;
    private $observaciones;
    private $diagnostico;
    private $indicaciones;
    private $tratamiento;
    private $proximo_control;   // DATE (Y-m-d) o vacío
    private $cita_id_cita;

    // ==========================================
    // AUXILIARES (arman el valor SQL de cada campo)
    // ==========================================

    // Texto: vacío => NULL, si no => 'texto' escapado
    private function textoSql($valor)
    {
        $valor = trim((string) $valor);

        if ($valor === '') {
            return 'NULL';
        }

        return "'" . addslashes($valor) . "'";
    }

    // Fecha Y-m-d válida => 'fecha', cualquier otra cosa => NULL
    private function fechaSql($valor)
    {
        $valor = trim((string) $valor);

        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $m)) {
            return 'NULL';
        }

        if (!checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
            return 'NULL';
        }

        return "'" . $valor . "'";
    }

    // ==========================================
    // CONSULTAS
    // ==========================================

    // Obtener la ficha de una cita
    public function obtenerPorCita($id_cita)
    {
        $con = new Conexion();
        $id_cita = intval($id_cita);

        $sql = "SELECT * FROM ficha_medica
                WHERE cita_id_cita = $id_cita
                LIMIT 1";

        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // Obtener por id
    public function obtenerPorId($id_ficha_medica)
    {
        $con = new Conexion();
        $id_ficha_medica = intval($id_ficha_medica);

        $sql = "SELECT * FROM ficha_medica
                WHERE id_ficha_medica = $id_ficha_medica
                LIMIT 1";

        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // ==========================================
    // GUARDAR
    // ==========================================

    // Insertar una ficha nueva (devuelve el id)
    public function guardar()
    {
        $con = new Conexion();

        $cita = intval($this->cita_id_cita);

        if ($cita <= 0) {
            return false;
        }

        $altura        = $this->textoSql($this->altura);
        $peso          = $this->textoSql($this->peso);
        $medicacion    = $this->textoSql($this->medicacion_actual);
        $observaciones = $this->textoSql($this->observaciones);
        $diagnostico   = $this->textoSql($this->diagnostico);
        $indicaciones  = $this->textoSql($this->indicaciones);
        $tratamiento   = $this->textoSql($this->tratamiento);
        $proximo       = $this->fechaSql($this->proximo_control);

        $sql = "INSERT INTO ficha_medica
                    (altura, peso, medicacion_actual, observaciones,
                     diagnostico, indicaciones, tratamiento, proximo_control,
                     cita_id_cita)
                VALUES
                    ($altura, $peso, $medicacion, $observaciones,
                     $diagnostico, $indicaciones, $tratamiento, $proximo,
                     $cita)";

        return $con->insertar($sql);
    }

    /* Actualizar una ficha existente.
     * fecha_modificacion se actualiza sola en la base de datos
     * (ON UPDATE CURRENT_TIMESTAMP) solo si algún dato cambió. */
    public function actualizar()
    {
        $con = new Conexion();

        $id = intval($this->id_ficha_medica);

        if ($id <= 0) {
            return false;
        }

        $altura        = $this->textoSql($this->altura);
        $peso          = $this->textoSql($this->peso);
        $medicacion    = $this->textoSql($this->medicacion_actual);
        $observaciones = $this->textoSql($this->observaciones);
        $diagnostico   = $this->textoSql($this->diagnostico);
        $indicaciones  = $this->textoSql($this->indicaciones);
        $tratamiento   = $this->textoSql($this->tratamiento);
        $proximo       = $this->fechaSql($this->proximo_control);

        $sql = "UPDATE ficha_medica SET
                    altura = $altura,
                    peso = $peso,
                    medicacion_actual = $medicacion,
                    observaciones = $observaciones,
                    diagnostico = $diagnostico,
                    indicaciones = $indicaciones,
                    tratamiento = $tratamiento,
                    proximo_control = $proximo
                WHERE id_ficha_medica = $id";

        return $con->actualizar($sql);
    }

    /* Guarda la ficha de la cita: la crea si no existe o la actualiza.
     * Devuelve el id de la ficha, o false si falló. */
    public function guardarOActualizar()
    {
        $existente = $this->obtenerPorCita($this->cita_id_cita);

        if ($existente) {

            $this->id_ficha_medica = $existente['id_ficha_medica'];

            $resultado = $this->actualizar();

            return ($resultado === false)
                ? false
                : intval($existente['id_ficha_medica']);
        }

        return $this->guardar();
    }

        // Fichas anteriores de un paciente con un doctor (excluye la cita actual)
    public function listarAnterioresPaciente($id_paciente, $id_doctor, $id_cita_actual, $limite = 10)
    {
        $con = new Conexion();

        $id_paciente = intval($id_paciente);
        $id_doctor = intval($id_doctor);
        $id_cita_actual = intval($id_cita_actual);
        $limite = intval($limite);

        if ($id_paciente <= 0 || $id_doctor <= 0) {
            return [];
        }

        if ($limite <= 0) {
            $limite = 10;
        }

        $sql = "SELECT
                    fm.id_ficha_medica,
                    t.fecha_hora,
                    ci.razon,
                    fm.diagnostico,
                    fm.indicaciones,
                    fm.tratamiento,
                    fm.observaciones

                FROM ficha_medica fm

                INNER JOIN cita ci
                    ON fm.cita_id_cita = ci.id_cita

                INNER JOIN agenda_turno at
                    ON ci.agenda_turno_id_agenda_turno = at.id_agenda_turno

                INNER JOIN turno t
                    ON at.turno_id_turnos = t.id_turnos

                INNER JOIN agenda a
                    ON t.agenda_id_agenda = a.id_agenda

                WHERE at.paciente_id_paciente = $id_paciente
                  AND a.doctor_id_doctor = $id_doctor
                  AND ci.id_cita <> $id_cita_actual

                ORDER BY t.fecha_hora DESC

                LIMIT $limite";

        return $con->consultarArray($sql);
    }

    // ==========================================
    // GETTERS Y SETTERS
    // ==========================================

    public function getId_ficha_medica()
    {
        return $this->id_ficha_medica;
    }

    public function setId_ficha_medica($id_ficha_medica)
    {
        $this->id_ficha_medica = $id_ficha_medica;

        return $this;
    }

    public function getAltura()
    {
        return $this->altura;
    }

    public function setAltura($altura)
    {
        $this->altura = $altura;

        return $this;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    public function getMedicacion_actual()
    {
        return $this->medicacion_actual;
    }

    public function setMedicacion_actual($medicacion_actual)
    {
        $this->medicacion_actual = $medicacion_actual;

        return $this;
    }

    public function getObservaciones()
    {
        return $this->observaciones;
    }

    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    public function getIndicaciones()
    {
        return $this->indicaciones;
    }

    public function setIndicaciones($indicaciones)
    {
        $this->indicaciones = $indicaciones;

        return $this;
    }

    public function getTratamiento()
    {
        return $this->tratamiento;
    }

    public function setTratamiento($tratamiento)
    {
        $this->tratamiento = $tratamiento;

        return $this;
    }

    public function getProximo_control()
    {
        return $this->proximo_control;
    }

    public function setProximo_control($proximo_control)
    {
        $this->proximo_control = $proximo_control;

        return $this;
    }

    public function getCita_id_cita()
    {
        return $this->cita_id_cita;
    }

    public function setCita_id_cita($cita_id_cita)
    {
        $this->cita_id_cita = $cita_id_cita;

        return $this;
    }
}