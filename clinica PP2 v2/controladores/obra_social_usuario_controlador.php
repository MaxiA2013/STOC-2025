<?php
require_once "../modelos/paciente_obra_social.php";
require_once "../modelos/doctor_obra_social.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $perfil = $_POST['perfil'];
    $id_usuario = $_POST['id_usuario'];
    $obrasSeleccionadas = $_POST['obras'] ?? [];

    if ($perfil === "Paciente") {
        $po = new Paciente_Obra_Social();

        // 🔹 Primero eliminamos las anteriores
        $anteriores = $po->consultarPorPaciente($id_usuario);
        foreach ($anteriores as $ant) {
            $po->eliminar($id_usuario, $ant['id_obra_social']);
        }

        // 🔹 Insertamos las nuevas
        foreach ($obrasSeleccionadas as $id_obra) {
            $po->asignar($id_usuario, $id_obra);
        }

    } elseif ($perfil === "Doctor") {
        $do = new Doctor_Obra_Social();

        // 🔹 Obtener el ID real del doctor
        $id_doctor = $do->obtenerIdDoctorPorUsuario($id_usuario);

        if ($id_doctor) {
            // 🔹 Primero eliminamos las anteriores
            $anteriores = $do->consultarPorDoctor($id_doctor);
            foreach ($anteriores as $ant) {
                $do->eliminar($id_doctor, $ant['id_obra_social']);
            }

            // 🔹 Insertamos las nuevas
            foreach ($obrasSeleccionadas as $id_obra) {
                $do->asignar($id_doctor, $id_obra);
            }
        } else {
            header("Location: ../index.php?page=mi_perfil&error=no_doctor");
            exit();
        }
    }

    header("Location: ../index.php?page=mi_perfil&success=obras_actualizadas");
    exit();
}
