<?php
require_once "../modelos/antecedentes_paciente_modelo.php";
require_once "../modelos/conexion.php";

if (isset($_POST['action']) && $_POST['action'] === 'guardar_antecedentes') {

    $idPaciente = intval($_POST['id_paciente'] ?? 0);

    if ($idPaciente <= 0) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'ID de paciente inválido o sesión expirada.'
        ]);
        exit();
    }

    $antecedentes = new AntecedentesPacienteModelo();
    $antecedentes->id_paciente = $idPaciente;
    
    // Capturamos el grupo sanguíneo seleccionado
    $antecedentes->id_grupo_sanguineo = !empty($_POST['grupo_sanguineo_id']) ? intval($_POST['grupo_sanguineo_id']) : null;
    
    // Capturamos las alergias y condiciones (si no marcaron ninguna, pasamos un array vacío)
    $antecedentes->alergias_seleccionadas = isset($_POST['alergias']) ? $_POST['alergias'] : [];
    $antecedentes->condiciones_seleccionadas = isset($_POST['condiciones']) ? $_POST['condiciones'] : [];

    // Guardamos los datos en la base de datos
    $resultado = $antecedentes->guardarDatosSalud();

    if ($resultado) {
        echo json_encode([
            'success' => true,
            'mensaje' => '¡Sus antecedentes médicos se actualizaron con éxito!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Ocurrió un error al intentar guardar los datos.'
        ]);
    }
    exit();
}
