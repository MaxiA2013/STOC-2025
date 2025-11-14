<?php
require_once 'conexion.php';

class Doctor_Dias {
    public function obtenerIdDoctorPorUsuario($id_usuario) {
        $con = new Conexion();
        $query = "SELECT id_doctor FROM doctor WHERE usuario_id_usuario = $id_usuario";
        $resultado = $con->consultarArray($query);
        return $resultado[0]['id_doctor'] ?? null;
    }

    public function consultarDiasPorDoctor($id_doctor) {
        $con = new Conexion();
        $query = "SELECT d.descripcion FROM doctor_dias dd
                  JOIN dias d ON dd.dias_id_dias = d.id_dias
                  WHERE dd.doctor_id_doctor = $id_doctor";
        $resultado = $con->consultarArray($query);
        return array_column($resultado, 'descripcion');
    }

    public function actualizarDiasDelDoctor($id_doctor, $diasSeleccionados) {
        $con = new Conexion();

        // Eliminar días anteriores
        $queryDelete = "DELETE FROM doctor_dias WHERE doctor_id_doctor = $id_doctor";
        $con->eliminar($queryDelete);

        // Insertar nuevos días
        foreach ($diasSeleccionados as $dia) {
            $queryDiaId = "SELECT id_dias FROM dias WHERE descripcion = '$dia'";
            $resultado = $con->consultarArray($queryDiaId);
            if (!empty($resultado)) {
                $id_dia = $resultado[0]['id_dias'];
                $queryInsert = "INSERT INTO doctor_dias (doctor_id_doctor, dias_id_dias) VALUES ($id_doctor, $id_dia)";
                $con->insertar($queryInsert);
            }
        }
    }
}