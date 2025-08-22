<?php
require_once('modelos/usuarios.php');
require_once('controladores/plantilla_controlador.php');

//indexo es la pagina principal e index es el login
// Cargar la plantilla para el inicio de sesión
$plantillaControlador = new PlantillaControlador();
$plantillaControlador->traer_plantilla();


?>