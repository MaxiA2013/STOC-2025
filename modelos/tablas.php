<?php
require_once('conexion.php');

class Tablas
{
  private $id_tablas;
  private $nombre_tabla;

  public function __construct($id_tablas = '', $nombre_tabla = '')
  {
    $this->id_tablas = $id_tablas;
    $this->nombre_tabla = $nombre_tabla;
  }


  public function getIdTablas()
  {
    return $this->id_tablas;
  }

  public function setIdTablas($id_tablas)
  {
    $this->id_tablas = $id_tablas;

    return $this;
  }

  public function getNombreTabla()
  {
    return $this->nombre_tabla;
  }

  public function setNombreTabla($nombre_tabla)
  {
    $this->nombre_tabla = $nombre_tabla;

    return $this;
  }

  public function guardarTabla()
  {
    $conexion = new Conexion();
    $query = "INSERT INTO clinica.tablas (nombre_tabla) VALUES ('$this->nombre_tabla')";
    return $conexion->insertar($query);
  }

  public function eliminarTabla()
  {
    $conexion = new Conexion();
    $query = "DELETE FROM clinica.tablas WHERE id_tablas = '$this->id_tablas'";
    return $conexion->eliminar($query);
  }

  public function actualizarTabla()
  {
    $conexion = new Conexion();
    $query = "UPDATE clinica.tablas SET nombre_tabla = '$this->nombre_tabla' WHERE id_tablas = '$this->id_tablas'";
    return $conexion->actualizar($query);
  }

  public function traerTablas()
  {
    $conexion = new Conexion();
    $query = "SELECT * FROM clinica.tablas;";
    return $conexion->consultar($query);
  }

  public function traer_tablas_por_modulos_ALL()
  {
    $conexion = new Conexion();
    $query = 'SELECT m.id_modulos, m.nombre AS nombre_modulo, t.id_tablas, t.nombre_tabla, id_modulos_tablas
      FROM modulos m
      INNER JOIN modulos_tablas mt ON m.id_modulos = mt.modulos_id_modulos
      INNER JOIN tablas t ON t.id_tablas = mt.tablas_id_tablas;';
    return $conexion->consultar($query);
  }
}
