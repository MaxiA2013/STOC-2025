<?php

require_once "conexion.php";

class Pais
{
    private int $id_pais;
    private string $nombre_pais;

    public function guardarPais()
    {
        $conn = new Conexion();
        $nombre_pais = $this->nombre_pais;
        $query = "INSERT INTO pais (nombre_pais) VALUES ('$nombre_pais')";
        $id = $conn->insertar($query);

        if ($id) {
            $this->setId_pais($id);
        }
        return $id;
    }

    /**
     * Actualizar un país existente
     */
    public function actualizarPais()
    {
        $conn = new Conexion();
        $id = $this->id_pais;
        $nombre_pais = $this->nombre_pais;
        $query = "UPDATE pais SET nombre_pais = '$nombre_pais' WHERE id_pais = $id";
        return $conn->actualizar($query);
    }

    /**
     * Eliminar un país
     */
    public function eliminarPais()
    {
        $conn = new Conexion();
        $id = (int) $this->id_pais;
        $query = "DELETE FROM pais WHERE id_pais = $id";
        return $conn->eliminar($query);
    }

    /**
     * Consultar todos los países
     */
    public function consultarVariosPaises()
    {
        $conn = new Conexion();
        $query = "SELECT * FROM pais ORDER BY nombre_pais ASC";
        return $conn->consultar($query);
    }

    /**
     * Consultar un país por ID
     */
    public function consultarPais($id)
    {
        $conn = new Conexion();
        $id = (int) $id;
        $query = "SELECT * FROM pais WHERE id_pais = $id";
        return $conn->consultar($query);
    }

    /**
     * Obtener un país como array asociativo
     */
    public function obtenerPorId($id)
    {
        $conn = new Conexion();
        $id = (int) $id;
        $query = "SELECT * FROM pais WHERE id_pais = $id LIMIT 1";
        $resultado = $conn->consultarArray($query);
        return !empty($resultado) ? $resultado[0] : null;
    }

    public function getId_pais()
    {
        return $this->id_pais;
    }

    public function setId_pais($id_pais)
    {
        $this->id_pais = (int) $id_pais;
        return $this;
    }


    public function getNombre_pais()
    {
        return $this->nombre_pais;
    }

    public function setNombre_pais($nombre_pais)
    {
        $this->nombre_pais = $nombre_pais;

        return $this;
    }
}