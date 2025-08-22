<?php

require_once('conexion.php');

class Modulos
{
    private $conn;
    private $id_modulos;
    private $nombre;

    public function __construct($id_modulos = '', $nombre = '')
    {
        $this->id_modulos = $id_modulos;
        $this->nombre = $nombre;
    }

    public function guardarModulo()
    {
        $conexion = new Conexion();
        $query = "INSERT INTO clinica.modulos (nombre) VALUES ('$this->nombre')";
        $conexion->insertar($query);
    }
/*
    public function eliminarModulo()
    {
        $conexion = new Conexion();
        $query = "DELETE FROM clinica.modulos WHERE id_modulos = '$this->id_modulos;'";
        return $conexion->eliminar($query);
    }

    public function actualizarModulo()
    {
        $conexion = new Conexion();
        $query = "UPDATE clinica.modulos SET nombre = '$this->nombre' WHERE id_modulos = '$this->id_modulos;'";
        return $conexion->actualizar($query);
    }*/

    public function traer_Modulos()
    {
        $conexion = new Conexion();
        $query = "SELECT * FROM clinica.modulos;";
        return $conexion->consultar($query);
    }

    public function traer_modulos_por_perfil($id_perfil)
    {
        $conexion = new Conexion();
        $query = "SELECT modulos.* 
            FROM clinica.modulos 
            INNER JOIN clinica.perfiles_modulos 
            ON perfiles_modulos.modulos_id_modulos = modulos.id_modulos 
            WHERE perfiles_modulos.perfil_id_perfil=" . $id_perfil;
        return $conexion->consultar($query);
    }

    public function traer_modulos_con_tablas()
    {
        $conexion = new Conexion();
        $query = "SELECT m.id_modulos, m.nombre, 
                    COALESCE(GROUP_CONCAT(t.nombre_tabla SEPARATOR ', '), 'Sin tablas') AS tablas
              FROM clinica.modulos m
              LEFT JOIN clinica.modulos_tablas mt ON m.id_modulos = mt.Modulos_id_modulos
              LEFT JOIN clinica.tablas t ON mt.Tablas_id_tablas = t.id_tablas
              GROUP BY m.id_modulos, m.nombre";
        return $conexion->consultar($query);
    }

   // Guardar módulo y devolver el ID usando mysqli_insert_id
    public function guardarModuloConRetorno() {
        $sql = "INSERT INTO modulos (nombre) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->nombre);
        $stmt->execute();
        return mysqli_insert_id($this->conn); // sin propiedad personalizada
    }

    public function actualizarModulo() {
        $sql = "UPDATE modulos SET nombre=? WHERE id_modulos=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $this->nombre, $this->id_modulos);
        $stmt->execute();
    }

    public function eliminarModulo() {
        $sql = "DELETE FROM modulos WHERE id_modulos=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->id_modulos);
        $stmt->execute();
    }

    // Relaciones módulo-tablas
    public function asignarTablaAModulo($idModulo, $idTabla) {
        $sql = "INSERT INTO modulos_tablas (modulos_id_modulos, Tablas_id_tablas) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $idModulo, $idTabla);
        $stmt->execute();
    }

    public function eliminarTablasDeModulo($idModulo) {
        $sql = "DELETE FROM modulos_tablas WHERE modulos_id_modulos=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idModulo);
        $stmt->execute();
    }

    public function traerTablasAsignadas($idModulo) {
        $sql = "SELECT Tablas_id_tablas FROM modulos_tablas WHERE modulos_id_modulos=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idModulo);
        $stmt->execute();
        $result = $stmt->get_result();
        $tablas = [];
        while ($row = $result->fetch_assoc()) {
            $tablas[] = $row['Tablas_id_tablas'];
        }
        return $tablas;
    }



    public function getIdModulos()
    {
        return $this->id_modulos;
    }

    public function setIdModulos($id_modulos)
    {
        $this->id_modulos = $id_modulos;

        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }
}
