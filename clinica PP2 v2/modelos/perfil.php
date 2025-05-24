<?php

require_once 'conexion.php';

class Perfil{
    private $id_perfil;
    private $nombre_perfil;
    private $descripcion;

    public function __construct($id_perfil='', $nombre_perfil='', $descripcion='')
    {
        $this->id_perfil = $id_perfil;
        $this->nombre_perfil = $nombre_perfil;
        $this->descripcion = $descripcion;
    }

    public function traer_perfiles(){
        $conexion = new Conexion();
        $query = "SELECT * FROM clinica.perfil";
        return $conexion->consultar($query);
    }

    public function traer_perfil($id_perfil){
        $conexion = new Conexion();
        $query = "SELECT nombre_perfil, id_perfil FROM clinica.perfil WHERE id_perfil = $id_perfil";
        return $conexion->consultar($query);
    }


     //Método para traer perfil desde usuario_has_perfil
    public function traer_perfil_por_usuario($id_usuario) {
        $conexion = new Conexion();
        $sql = "SELECT p.id_perfil, p.nombre_perfil 
                FROM usuario_has_perfil uhp 
                JOIN perfil p ON uhp.perfil_id_perfil = p.id_perfil 
                WHERE uhp.usuario_id_usuario = $id_usuario";
        return $conexion->consultar($sql);
    }
    //Método para traer perfil desde usuario_has_perfil


    

    public function guardaPerfil(){
        $conexion = new Conexion();
        $query = "INSERT INTO clinica.perfil (nombre_perfil, descripcion) VALUES ('$this->nombre_perfil', '$this->descripcion')";
        return $conexion->insertar($query);
    }

    public function actualizarPerfil(){
        $conexion = new Conexion();
        $query = "UPDATE clinica.perfil SET nombre_perfil = '$this->nombre_perfil', descripcion = '$this->descripcion' WHERE id_perfil = '$this->id_perfil'";
        return $conexion->actualizar($query);
    }


    public function eliminarPerfil(){
        $conexion = new Conexion();
        $query = "DELETE FROM clinica.perfil WHERE id_perfil = '$this->id_perfil'";
        return $conexion->eliminar($query);
    }


    public function getNombre_perfil()
    {
        return $this->nombre_perfil;
    }

    public function setNombre_perfil($nombre_perfil)
    {
        $this->nombre_perfil = $nombre_perfil;

        return $this;
    }

    public function getId_perfil()
    {
        return $this->id_perfil;
    }

    public function setId_perfil($id_perfil)
    {
        $this->id_perfil = $id_perfil;

        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}

?>