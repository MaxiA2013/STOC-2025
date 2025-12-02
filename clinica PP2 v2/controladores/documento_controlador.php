<?php
require_once "../modelos/documento.php";

if (isset($_POST['action'])) {
    $accion = $_POST['action'];

    switch ($accion) {
        case 'insertar':
            //Validaciones previas antes de la inserción

            //Validación para que no esté vacio
            if (empty($_POST['tipo_documento'])) {
                header('Location: ../index.php?page=documento_lista&message=El documento es obligatorio&status=danger');
                exit();
            }

            //validacion de que no esté duplicado
            $docTemp= new Documento();
            $docTemp->setTipoDocumento($_POST['tipo_documento']);
            $existeDoc = $docTemp->existeDocumento();
            if ($existeDoc->num_rows > 0) {
                header('Location: ../index.php?page=documento_lista&message=El documento ya existe&status=danger');
                exit();
            }

            if (empty($_POST['descripcion'])) {
                header('Location ../index.php?page=documento_lista&message=Complete el campo&status=danger');
                exit();
            }
            
            //Validaciones previas antes de la inserción
            //Guarda valores
            $Doc = new Documento();
            $Doc->setTipoDocumento($_POST['tipo_documento']);
            $Doc->setDescripcion($_POST['descripcion']);
            $Doc->setObligatorio($_POST['obligatorio'] ?? 0); // si no viene, queda en 0
            $Doc->guardarDocumento();
            header('Location: ../index.php?page=documento_lista');
            break;

        case 'eliminacion':
            $Doc = new Documento();
            $Doc->setIdDocumento($_POST['id_documento']);
            $Doc->eliminarDocumento();
            header('Location: ../index.php?page=documento_lista');
            break;

        case 'actualizacion':
            if ((empty($_POST['tipo_documento'])) or (empty($_POST['descripcion']))){
                header('Location: ../index.php?page=documento_lista&message=No deje campos vacios&status=danger');
                exit();
            }
            $Doc = new Documento();
            $Doc->setIdDocumento($_POST['id_documento']);
            $Doc->setTipoDocumento($_POST['tipo_documento']);
            $Doc->setDescripcion($_POST['descripcion']);
            $Doc->setObligatorio($_POST['obligatorio'] ?? 0);
            $Doc->actualizarDocumento();
            header('Location: ../index.php?page=documento_lista');
            break;
    }
}
?>
