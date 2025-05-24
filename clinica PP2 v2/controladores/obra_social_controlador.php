<?php
require_once "../modelos/obra_social.php";

/*class Validar {

    function validarCamposVacios() {  //valida campos vacios
        if (isset($_POST['obra']) || isset($_POST['descripcion_obra'])) { // si hay algo
            echo "<script>alert('Registrado con éxito');</script>";
            header("Location: ../template/obra_social_lista.php");
            exit(); // exit() después de header() para detener la ejecución del script
        } 
        else { // si no hay nada
            echo "<script>alert('Complete los campos vacíos');</script>";
            header("Location: ../template/obra_social_lista.php");
            exit();
        }
    }
} */

if (isset($_POST["registro"])) {
    if (!empty($_POST['nombre_obra_social']) && !empty($_POST['detalle'])) {
        $nombre_obra_social = $_POST['nombre_obra_social'];
        $detalle = $_POST['detalle'];



        $obraSocial = new Obra_Social("", "");
        $obraSocial->setNombreObraSocial($nombre_obra_social);
        $obraSocial->setDetalle($detalle);
        $obraSocial->guardarObraSocial();
    } else {
        echo "Por favor, rellena los campos obligatorios (nombre y apellido).";
    };
    
};

if (isset($_POST['eliminar'])) {
    $id = $_POST['id_obra_social'];

    $obraSocial = new Obra_Social("", "");
    $obraSocial->setIdObraSocial($id);

    $obraSocial->eliminarObraSocial();

    if ($obraSocial) {  //mensajes de alertaS
        echo '<script> 
                alert("Obra Social eliminada exitosamente de la tabla.");
                window.location.href="http://localhost/Es-este-Bianca-main/clinica%20PP2%20v2/template/obra_social_lista.php";
              </script>';
    } else {
        echo '<script>
                alert("Hubo un error al eliminar la Obra Social de la tabla.");
                window.location.href="http://localhost/Es-este-Bianca-main/clinica%20PP2%20v2/template/obra_social_lista.php";
              </script>';
    };
};

if (isset($_POST["modificar"])) {
    // Validación de campos no vacios
    if (!empty($_POST["nombre_obra_social"]) && !empty($_POST["detalle"])) {
        $nombre_obra_social = $_POST["nombre_obra_social"];
        $detalle = $_POST["detalle"];
        $obraSocial = new Obra_Social();
        $obraSocial->setNombreObraSocial($nombre_obra_social);
        $obraSocial->setDetalle($detalle);

        $obraSpcial->actualizarObraSocial();

        echo '<script
        >alert("Obra Social modificado exitosamente"); 
        window.location = "http://localhost/Es-este-Bianca-main/clinica%20PP2%20v2/template/obra_social_lista.php";
        </script>';
    } else {
        echo "<div class = 'alert alert-warning'>campo vacio</div>";
    };
};
