<?php
require_once 'conexion.php';

/**
 * Signos vitales de una ficha médica.
 *
 * Están en una tabla aparte porque no todas las especialidades los
 * necesitan (por ejemplo, dermatología). Solo existe una fila cuando
 * el doctor registra al menos un valor; si todos quedan vacíos, la fila
 * se elimina para no guardar registros vacíos.
 */
class SignosVitales
{
    private $id_signos_vitales;
    private $presion_sistolica;
    private $presion_diastolica;
    private $frecuencia_cardiaca;
    private $temperatura;
    private $saturacion_oxigeno;
    private $ficha_medica_id_ficha_medica;

    // ==========================================
    // AUXILIARES
    // ==========================================

    private function vacio($valor)
    {
        return $valor === null || trim((string) $valor) === '';
    }

    // Entero: vacío => NULL
    private function enteroSql($valor)
    {
        return $this->vacio($valor) ? 'NULL' : intval($valor);
    }

    // Decimal con 1 decimal: vacío => NULL (acepta coma o punto)
    private function decimalSql($valor)
    {
        if ($this->vacio($valor)) {
            return 'NULL';
        }

        $valor = str_replace(',', '.', trim((string) $valor));

        return number_format((float) $valor, 1, '.', '');
    }

    // ¿No hay ningún valor cargado?
    public function estaVacio()
    {
        return $this->vacio($this->presion_sistolica)
            && $this->vacio($this->presion_diastolica)
            && $this->vacio($this->frecuencia_cardiaca)
            && $this->vacio($this->temperatura)
            && $this->vacio($this->saturacion_oxigeno);
    }

    // ==========================================
    // CONSULTAS
    // ==========================================

    // Obtener los signos vitales de una ficha (o null si no se registraron)
    public function obtenerPorFicha($id_ficha_medica)
    {
        $con = new Conexion();
        $id_ficha_medica = intval($id_ficha_medica);

        $sql = "SELECT * FROM signos_vitales
                WHERE ficha_medica_id_ficha_medica = $id_ficha_medica
                LIMIT 1";

        $res = $con->consultarArray($sql);
        return $res[0] ?? null;
    }

    // Eliminar los signos vitales de una ficha
    public function eliminarPorFicha($id_ficha_medica)
    {
        $con = new Conexion();
        $id_ficha_medica = intval($id_ficha_medica);

        $sql = "DELETE FROM signos_vitales
                WHERE ficha_medica_id_ficha_medica = $id_ficha_medica";

        return $con->eliminar($sql);
    }

    // ==========================================
    // GUARDAR
    // ==========================================

    /* Guarda los signos vitales de la ficha:
     * - Si no hay ningún valor, elimina la fila existente (si la había).
     * - Si ya había fila, la actualiza.
     * - Si no, la crea.
     * Devuelve true si salió bien, false si falló. */
    public function guardarOActualizar()
    {
        $con = new Conexion();

        $ficha = intval($this->ficha_medica_id_ficha_medica);

        if ($ficha <= 0) {
            return false;
        }

        if ($this->estaVacio()) {
            $this->eliminarPorFicha($ficha);
            return true;
        }

        $sistolica  = $this->enteroSql($this->presion_sistolica);
        $diastolica = $this->enteroSql($this->presion_diastolica);
        $frecuencia = $this->enteroSql($this->frecuencia_cardiaca);
        $temperatura = $this->decimalSql($this->temperatura);
        $saturacion = $this->enteroSql($this->saturacion_oxigeno);

        $existente = $this->obtenerPorFicha($ficha);

        if ($existente) {

            $sql = "UPDATE signos_vitales SET
                        presion_sistolica = $sistolica,
                        presion_diastolica = $diastolica,
                        frecuencia_cardiaca = $frecuencia,
                        temperatura = $temperatura,
                        saturacion_oxigeno = $saturacion
                    WHERE ficha_medica_id_ficha_medica = $ficha";

            return $con->actualizar($sql) !== false;
        }

        $sql = "INSERT INTO signos_vitales
                    (presion_sistolica, presion_diastolica, frecuencia_cardiaca,
                     temperatura, saturacion_oxigeno, ficha_medica_id_ficha_medica)
                VALUES
                    ($sistolica, $diastolica, $frecuencia,
                     $temperatura, $saturacion, $ficha)";

        return $con->insertar($sql) !== false;
    }

    // ==========================================
    // GETTERS Y SETTERS
    // ==========================================

    public function getId_signos_vitales()
    {
        return $this->id_signos_vitales;
    }

    public function setId_signos_vitales($id_signos_vitales)
    {
        $this->id_signos_vitales = $id_signos_vitales;

        return $this;
    }

    public function getPresion_sistolica()
    {
        return $this->presion_sistolica;
    }

    public function setPresion_sistolica($presion_sistolica)
    {
        $this->presion_sistolica = $presion_sistolica;

        return $this;
    }

    public function getPresion_diastolica()
    {
        return $this->presion_diastolica;
    }

    public function setPresion_diastolica($presion_diastolica)
    {
        $this->presion_diastolica = $presion_diastolica;

        return $this;
    }

    public function getFrecuencia_cardiaca()
    {
        return $this->frecuencia_cardiaca;
    }

    public function setFrecuencia_cardiaca($frecuencia_cardiaca)
    {
        $this->frecuencia_cardiaca = $frecuencia_cardiaca;

        return $this;
    }

    public function getTemperatura()
    {
        return $this->temperatura;
    }

    public function setTemperatura($temperatura)
    {
        $this->temperatura = $temperatura;

        return $this;
    }

    public function getSaturacion_oxigeno()
    {
        return $this->saturacion_oxigeno;
    }

    public function setSaturacion_oxigeno($saturacion_oxigeno)
    {
        $this->saturacion_oxigeno = $saturacion_oxigeno;

        return $this;
    }

    public function getFicha_medica_id_ficha_medica()
    {
        return $this->ficha_medica_id_ficha_medica;
    }

    public function setFicha_medica_id_ficha_medica($ficha_medica_id_ficha_medica)
    {
        $this->ficha_medica_id_ficha_medica = $ficha_medica_id_ficha_medica;

        return $this;
    }
}