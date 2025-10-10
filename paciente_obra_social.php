<?php
require_once "conexion.php";

class Paciente_Obra_Social {

    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // ✅ Asignar obra social a un paciente
    public function asignar($id_paciente, $id_obra_social) {
        $sql = "INSERT INTO paciente_obra_social (paciente_id_paciente, obra_social_id_obra_social)
                VALUES ('$id_paciente', '$id_obra_social')";
        return $this->conexion->insertar($sql);
    }

    // ✅ Eliminar obra social de un paciente
    public function eliminar($id_paciente, $id_obra_social) {
        $sql = "DELETE FROM paciente_obra_social 
                WHERE paciente_id_paciente = '$id_paciente' 
                AND obra_social_id_obra_social = '$id_obra_social'";
        return $this->conexion->eliminar($sql);
    }

    // ✅ Consultar todas las obras sociales de un paciente
    public function consultarPorPaciente($id_paciente) {
        $sql = "SELECT os.id_obra_social, os.nombre_obra_social, os.detalle 
                FROM obra_social os
                INNER JOIN paciente_obra_social pos 
                ON os.id_obra_social = pos.obra_social_id_obra_social
                WHERE pos.paciente_id_paciente = '$id_paciente'";
        return $this->conexion->consultar($sql);
    }

    public function consultarArrayPorPaciente($id_paciente) {
    $sql = "SELECT os.id_obra_social, os.nombre_obra_social, os.detalle 
            FROM obra_social os
            INNER JOIN paciente_obra_social pos 
            ON os.id_obra_social = pos.obra_social_id_obra_social
            WHERE pos.paciente_id_paciente = '$id_paciente'";
    return $this->conexion->consultarArray($sql);
}


}
?>
