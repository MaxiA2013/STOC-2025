<?php
// En este archivo encontramos las funciones de base de datos para registrar antecedentes de salud (alergias, condiciones y grupo sanguíneo).
require_once('conexion.php');

class AntecedentesPacienteModelo
{
    public $id_paciente;
    public $id_grupo_sanguineo;
    public $alergias_seleccionadas = []; 
    public $condiciones_seleccionadas = []; 

    // 1. OBTENER EL GRUPO SANGUÍNEO ACTUAL DEL PACIENTE
    public function obtenerGrupoSanguineo()
    {
        $conexion = new Conexion();
        $query = "SELECT grupo_sanguineo_id FROM clinica.paciente_grupo_sanguineo WHERE paciente_id_paciente = '$this->id_paciente'";
        $resultado = $conexion->consultar($query);
        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            return $row['grupo_sanguineo_id'];
        }
        return null;
    }

    // 2. OBTENER LAS ALERGIAS QUE YA TIENE GUARDADAS EL PACIENTE
    public function obtenerAlergiasPaciente()
    {
        $conexion = new Conexion();
        $query = "SELECT alergias_id_alergias FROM clinica.paciente_has_alergias WHERE paciente_id_paciente = '$this->id_paciente'";
        $resultado = $conexion->consultar($query);
        $ids = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $ids[] = $row['alergias_id_alergias'];
            }
        }
        return $ids;
    }

    // 3. OBTENER LAS CONDICIONES QUE YA TIENE GUARDADAS EL PACIENTE
    public function obtenerCondicionesPaciente()
    {
        $conexion = new Conexion();
        $query = "SELECT condicion_id_condicion FROM clinica.paciente_has_condiciones WHERE paciente_id_paciente = '$this->id_paciente'";
        $resultado = $conexion->consultar($query);
        $ids = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $ids[] = $row['condicion_id_condicion'];
            }
        }
        return $ids;
    }

    // 4. LISTAR TODOS LOS GRUPOS SANGUÍNEOS
    public function listarGruposSanguineos()
    {
        $conexion = new Conexion();
        $query = "SELECT id_grupo_sanguineo, descripcion FROM clinica.grupo_sanguineo ORDER BY descripcion ASC";
        return $conexion->consultar($query);
    }

    // 5. LISTAR TODAS LAS ALERGIAS MAESTRAS
    public function listarAlergiasMaestras()
    {
        $conexion = new Conexion();
        $query = "SELECT id_alergias, nombre_alergia, descripcion FROM clinica.alergias ORDER BY nombre_alergia ASC";
        return $conexion->consultar($query);
    }

    // 6. LISTAR TODAS LAS CONDICIONES MAESTRAS
    public function listarCondicionesMaestras()
    {
        $conexion = new Conexion();
        $query = "SELECT id_condicion, nombre_condicion, detalle FROM clinica.condicion ORDER BY nombre_condicion ASC";
        return $conexion->consultar($query);
    }

    // 7. GUARDAR TODO EL HISTORIAL MÉDICO DEL PACIENTE
    public function guardarDatosSalud()
    {
        $conexion = new Conexion();

        // Guardar Grupo Sanguíneo
        $queryDeleteGrupo = "DELETE FROM clinica.paciente_grupo_sanguineo WHERE paciente_id_paciente = '$this->id_paciente'";
        $conexion->actualizar($queryDeleteGrupo);

        if (!empty($this->id_grupo_sanguineo)) {
            $queryInsertGrupo = "INSERT INTO clinica.paciente_grupo_sanguineo (paciente_id_paciente, grupo_sanguineo_id) 
                                     VALUES ('$this->id_paciente', '$this->id_grupo_sanguineo')";
            $conexion->insertar($queryInsertGrupo);
        }

        // Guardar Alergias
        $queryDeleteAlergias = "DELETE FROM clinica.paciente_has_alergias WHERE paciente_id_paciente = '$this->id_paciente'";
        $conexion->actualizar($queryDeleteAlergias);

        if (!empty($this->alergias_seleccionadas)) {
            foreach ($this->alergias_seleccionadas as $id_alergia) {
                $queryInsertAlergia = "INSERT INTO clinica.paciente_has_alergias (paciente_id_paciente, alergias_id_alergias) 
                                           VALUES ('$this->id_paciente', '$id_alergia')";
                // Usamos ejecutar consulta directa para inserción múltiple
                $conexion->consultar($queryInsertAlergia);
            }
        }

        // Guardar Condiciones
        $queryDeleteCondiciones = "DELETE FROM clinica.paciente_has_condiciones WHERE paciente_id_paciente = '$this->id_paciente'";
        $conexion->actualizar($queryDeleteCondiciones);

        if (!empty($this->condiciones_seleccionadas)) {
            foreach ($this->condiciones_seleccionadas as $id_cond) {
                $queryInsertCondicion = "INSERT INTO clinica.paciente_has_condiciones (paciente_id_paciente, condicion_id_condicion) 
                                             VALUES ('$this->id_paciente', '$id_cond')";
                $conexion->consultar($queryInsertCondicion);
            }
        }

        return true;
    }
}
