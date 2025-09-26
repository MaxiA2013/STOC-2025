<?php
require_once "../modelos/documento.php";

if (isset($_POST['action'])) {
    $accion = $_POST['action'];

    switch ($accion) {
        case 'insertar':
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
