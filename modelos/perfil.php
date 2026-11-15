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

    // trae perfil de usuario (si lo necesitas)
    public function traer_perfil_por_usuario($id_usuario) {
        $conexion = new Conexion();
        $sql = "SELECT p.id_perfil, p.nombre_perfil 
                FROM usuario_has_perfil 
                JOIN perfil p ON perfil_id_perfil = p.id_perfil 
                WHERE usuario_id_usuario = $id_usuario";
        return $conexion->consultar($sql);
    }

    public function guardaPerfil(){
        $conexion = new Conexion();
        $query = "INSERT INTO clinica.perfil (nombre_perfil, descripcion) VALUES ('$this->nombre_perfil', '$this->descripcion')";
        // insertar() devuelve insert_id
        return $conexion->insertar($query);
    }

    public function actualizarPerfil(){
        $conexion = new Conexion();
        $query = "UPDATE clinica.perfil SET nombre_perfil = '$this->nombre_perfil', descripcion = '$this->descripcion' WHERE id_perfil = '$this->id_perfil'";
        return $conexion->actualizar($query);
    }

    public function eliminarPerfil(){
        $conexion = new Conexion();
        // borrar relaciones primero (para evitar FK o basura)
        $conexion->eliminar("DELETE FROM clinica.perfiles_modulos WHERE perfil_id_perfil = '$this->id_perfil'");
        // borrar perfil
        $query = "DELETE FROM clinica.perfil WHERE id_perfil = '$this->id_perfil'";
        return $conexion->eliminar($query);
    }

    // --- Métodos para manejar la tabla pivote perfiles_modulos

    public function asignarModulo($id_perfil, $id_modulo)
    {
        $conexion = new Conexion();
        $query = "INSERT INTO clinica.perfiles_modulos (perfil_id_perfil, modulos_id_modulos) VALUES ('$id_perfil', '$id_modulo')";
        return $conexion->insertar($query);
    }

    public function desasignarModulos($id_perfil)
    {
        $conexion = new Conexion();
        $query = "DELETE FROM clinica.perfiles_modulos WHERE perfil_id_perfil = '$id_perfil'";
        return $conexion->eliminar($query);
    }

    public function traer_modulos_ids_por_perfil($id_perfil)
    {
        $conexion = new Conexion();
        $query = "SELECT modulos_id_modulos as id_modulo FROM clinica.perfiles_modulos WHERE perfil_id_perfil = '$id_perfil'";
        $res = $conexion->consultar($query);
        $ids = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $ids[] = (int)$r['id_modulo'];
            }
        }
        return $ids;
    }

    // getters / setters
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
