<?php /*
require_once "../modelos/documento.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $Doc = new Documento();
            $Doc->setNombreSintomas($_POST['tipo_documento']);
            $Doc->setDescripcion($_POST['descripcion']);
            $Doc->guardarDocumento();
            header('Location: ../index.php?page=sintomas_lista');
            break;
        case 'eliminacion':
            $Doc = new Documento();
            $Doc->setIdDocumento($_POST['id_sintomas']);
            $Doc->eliminarDocumento();
            header('Location: ../index.php?page=sintomas_lista');
            break;
        case 'actualizacion':
            $Doc = new Documento();
            $Doc->setIdDocumento($_POST['id_sintomas']);
            $Doc->setNombreSintomas($_POST['tipo_documento']);
            $Doc->setDescripcion($_POST['descripcion']);
            $Doc->actualizarDocumento();
            header('Location: ../index.php?page=sintomas_lista');
            break;
    }
}
