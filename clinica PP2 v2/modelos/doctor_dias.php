<?php
require_once 'conexion.php';

class Doctor_Dias {

    public function obtenerIdDoctorPorUsuario($id_usuario) {
        $con = new Conexion();
        $query = "SELECT id_doctor FROM doctor WHERE usuario_id_usuario = $id_usuario";
        $resultado = $con->consultarArray($query);

        // Si NO existe doctor → devolver null
        return $resultado[0]['id_doctor'] ?? null;
    }

    /**
     * Retorna un array con las descripciones de los días para compatibilidad
     * Ej: ['lunes','martes']
     */
    public function consultarDiasPorDoctor($id_doctor) {

        // PREVENCIÓN DE ERROR
        if ($id_doctor === null || $id_doctor === "") {
            return []; // evitamos SQL inválido
        }

        $con = new Conexion();
        $query = "SELECT d.descripcion 
                  FROM doctor_dias dd
                  JOIN dias d ON dd.dias_id_dias = d.id_dias
                  WHERE dd.doctor_id_doctor = $id_doctor";

        $resultado = $con->consultarArray($query);

        return array_column($resultado, 'descripcion');
    }

    /**
     * Nuevo: devuelve un array asociativo dia_descripcion => franja_id
     * Ej: ['lunes' => 2, 'martes' => 1]
     */
    public function consultarDiasYFranjasPorDoctor($id_doctor) {
        if ($id_doctor === null || $id_doctor === "") {
            return [];
        }

        $con = new Conexion();
        $query = "SELECT d.descripcion, dd.franja_horaria_id_franja 
                  FROM doctor_dias dd
                  JOIN dias d ON dd.dias_id_dias = d.id_dias
                  WHERE dd.doctor_id_doctor = $id_doctor";

        $resultado = $con->consultarArray($query);

        $map = [];
        foreach ($resultado as $row) {
            $map[$row['descripcion']] = $row['franja_horaria_id_franja'];
        }

        return $map;
    }

    /**
     * Nuevo: Actualiza los días del doctor incluyendo la franja horaria seleccionada.
     * $diasSeleccionados: array de descripciones de días (ej: ['lunes','martes'])
     * $franjasAssoc: array asociativo dia => id_franja (ej: ['lunes'=>2, 'martes'=>1])
     */
    public function actualizarDiasYFranjasDelDoctor($id_doctor, $diasSeleccionados, $franjasAssoc) {
        if ($id_doctor === null) {
            return false; // prevención
        }

        $con = new Conexion();

        // Eliminar días anteriores (reemplazo total)
        $queryDelete = "DELETE FROM doctor_dias WHERE doctor_id_doctor = $id_doctor";
        $con->eliminar($queryDelete);

        // Insertar nuevos días con su franja
        foreach ($diasSeleccionados as $dia) {
            // obtener id_dias por descripción
            $queryDiaId = "SELECT id_dias FROM dias WHERE descripcion = '$dia'";
            $resultado = $con->consultarArray($queryDiaId);

            if (!empty($resultado)) {
                $id_dia = $resultado[0]['id_dias'];

                // obtener franja enviada para este dia (si existe)
                $franja_id = isset($franjasAssoc[$dia]) ? intval($franjasAssoc[$dia]) : 0;

                // Si no hay franja válida, saltamos esa inserción (evitamos FK inválida)
                if ($franja_id > 0) {
                    $queryInsert = "INSERT INTO doctor_dias (doctor_id_doctor, dias_id_dias, franja_horaria_id_franja) 
                                    VALUES ($id_doctor, $id_dia, $franja_id)";
                    $con->insertar($queryInsert);
                }
                // si no tenía franja válida, no insertamos (podés cambiar la lógica si preferís insertar con una franja por defecto)
            }
        }
        return true;
    }
}
?>
