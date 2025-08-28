<?php

require_once('conexion.php');

class Modulos
{
    private $id_modulos;
    private $nombre;

    public function __construct($id_modulos = '', $nombre = '')
    {
        $this->id_modulos = $id_modulos;
        $this->nombre = $nombre;
    }
    public function eliminarModulo()
    {
        $conexion = new Conexion();
        // borrar relaciones primero
        $conexion->eliminar("DELETE FROM clinica.modulos_tablas WHERE modulos_id_modulos = '$this->id_modulos'");
        // borrar módulo
        $query = "DELETE FROM clinica.modulos WHERE id_modulos = '$this->id_modulos'";
        return $conexion->eliminar($query);
    }


    public function actualizarModulo()
    {
        $conexion = new Conexion();
        $query = "UPDATE clinica.modulos SET nombre = '$this->nombre' WHERE id_modulos = '$this->id_modulos'";
        return $conexion->actualizar($query);
    }

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

    //agregado
    public function guardarModulo()
    {
        $conexion = new Conexion();
        $query = "INSERT INTO clinica.modulos (nombre) VALUES ('$this->nombre')";
        // insertar() en Conexion ya devuelve insert_id
        $id = $conexion->insertar($query);
        $this->id_modulos = $id;
        return $id;
    }

    // --- asignar una tabla a un módulo (inserta en la tabla pivote)
    public function asignarTabla($id_modulo, $id_tabla)
    {
        $conexion = new Conexion();
        $query = "INSERT INTO clinica.modulos_tablas (Tablas_id_tablas, modulos_id_modulos) VALUES ('$id_tabla', '$id_modulo')";
        return $conexion->insertar($query);
    }

    // --- desasignar todas las tablas de un módulo (usar en actualización)
    public function desasignarTablas($id_modulo)
    {
        $conexion = new Conexion();
        $query = "DELETE FROM clinica.modulos_tablas WHERE modulos_id_modulos = '$id_modulo'";
        return $conexion->eliminar($query);
    }

    // --- traer ids de tablas asignadas a un módulo (devuelve array de ints)
    public function traer_tablas_ids_por_modulo($id_modulo)
    {
        $conexion = new Conexion();
        $query = "SELECT Tablas_id_tablas as id_tabla FROM clinica.modulos_tablas WHERE modulos_id_modulos = '$id_modulo'";
        $res = $conexion->consultar($query);
        $ids = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $ids[] = (int)$r['id_tabla'];
            }
        }
        return $ids;
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
