<?php

require_once ('conexion.php');

class Modulos{
    private $id_modulos;
    private $nombre;

    public function __construct($id_modulos,$nombre) {
        $this->id_modulos = $id_modulos;
        $this->nombre = $nombre;
    }

    public function traer_modulos_por_perfil($id_perfil){
        $conexion = new Conexion();
        $query = "SELECT modulos.* 
            FROM clinica.modulos 
            INNER JOIN clinica.perfiles_modulos 
            ON perfiles_modulos.modulos_id_modulos = modulos.id_modulos 
            WHERE perfiles_modulos.perfil_id_perfil=;".$id_perfil;
        return $conexion->consultar($query);
    }

}

?>