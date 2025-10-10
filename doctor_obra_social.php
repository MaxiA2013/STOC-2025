<?php
require_once "conexion.php";

class Doctor_Obra_Social {

    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // ✅ Asignar obra social a un doctor
    public function asignar($id_doctor, $id_obra_social) {
        $sql = "INSERT INTO doctor_obra_social (doctor_id_doctor, obra_social_id_obra_social)
                VALUES ('$id_doctor', '$id_obra_social')";
        return $this->conexion->insertar($sql);
    }

    // ✅ Eliminar obra social de un doctor
    public function eliminar($id_doctor, $id_obra_social) {
        $sql = "DELETE FROM doctor_obra_social 
                WHERE doctor_id_doctor = '$id_doctor' 
                AND obra_social_id_obra_social = '$id_obra_social'";
        return $this->conexion->eliminar($sql);
    }

    // ✅ Consultar todas las obras sociales de un doctor
    public function consultarPorDoctor($id_doctor) {
        $sql = "SELECT os.id_obra_social, os.nombre_obra_social, os.detalle 
                FROM obra_social os
                INNER JOIN doctor_obra_social dos 
                ON os.id_obra_social = dos.obra_social_id_obra_social
                WHERE dos.doctor_id_doctor = '$id_doctor'";
        return $this->conexion->consultar($sql);
    }

    public function obtenerIdDoctorPorUsuario($id_usuario) {
        $sql = "SELECT id_doctor FROM doctor WHERE usuario_id_usuario = '$id_usuario'";
        $resultado = $this->conexion->consultar($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return $fila['id_doctor'];
        }

        return null;
    }

    public function consultarArrayPorDoctor($id_doctor) {
    $sql = "SELECT os.id_obra_social, os.nombre_obra_social, os.detalle 
            FROM obra_social os
            INNER JOIN doctor_obra_social dos 
            ON os.id_obra_social = dos.obra_social_id_obra_social
            WHERE dos.doctor_id_doctor = '$id_doctor'";
    return $this->conexion->consultarArray($sql);
}


}
?>
