<?php

require_once('conexion.php');

class Permiso
{
    private $id_permiso;
    private $nombre_permiso;
    private $detalle;
    private $estado;

    public function __construct(
        $id_permiso = '',
        $nombre_permiso = '',
        $detalle = '',
        $estado = ''
    ) {
        $this->id_permiso = $id_permiso;
        $this->nombre_permiso = $nombre_permiso;
        $this->detalle = $detalle;
        $this->estado = $estado;
    }


    // =========================================================
    // CRUD DE PERMISOS
    // =========================================================

    public function traer_permisos()
    {
        $conexion = new Conexion();

        $query = "SELECT *
                  FROM clinica.permiso
                  ORDER BY id_permiso ASC";

        return $conexion->consultar($query);
    }


    public function traer_permiso($id_permiso)
    {
        $conexion = new Conexion();

        $query = "SELECT *
                  FROM clinica.permiso
                  WHERE id_permiso = '$id_permiso'";

        return $conexion->consultar($query);
    }


    public function guardarPermiso()
    {
        $conexion = new Conexion();

        $query = "INSERT INTO clinica.permiso
                    (nombre_permiso, detalle, estado)
                  VALUES
                    ('$this->nombre_permiso',
                     '$this->detalle',
                     '$this->estado')";

        return $conexion->insertar($query);
    }


    public function actualizarPermiso()
    {
        $conexion = new Conexion();

        $query = "UPDATE clinica.permiso
                  SET
                    nombre_permiso = '$this->nombre_permiso',
                    detalle = '$this->detalle',
                    estado = '$this->estado'
                  WHERE id_permiso = '$this->id_permiso'";

        return $conexion->actualizar($query);
    }


    public function eliminarPermiso()
    {
        $conexion = new Conexion();

        $query = "DELETE FROM clinica.permiso
                  WHERE id_permiso = '$this->id_permiso'";

        return $conexion->eliminar($query);
    }


    // =========================================================
    // ESTADO DEL PERMISO
    // =========================================================

    public function activarPermiso()
    {
        $conexion = new Conexion();

        $query = "UPDATE clinica.permiso
                  SET estado = 1
                  WHERE id_permiso = '$this->id_permiso'";

        return $conexion->actualizar($query);
    }


    public function desactivarPermiso()
    {
        $conexion = new Conexion();

        $query = "UPDATE clinica.permiso
                  SET estado = 0
                  WHERE id_permiso = '$this->id_permiso'";

        return $conexion->actualizar($query);
    }


    // =========================================================
    // CONSULTAS
    // =========================================================

    public function permisoExiste()
    {
        $conexion = new Conexion();

        $query = "SELECT id_permiso
                  FROM clinica.permiso
                  WHERE nombre_permiso = '$this->nombre_permiso'";

        return $conexion->consultar($query);
    }


    public function traer_permisos_activos()
    {
        $conexion = new Conexion();

        $query = "SELECT *
                  FROM clinica.permiso
                  WHERE estado = 1
                  ORDER BY id_permiso ASC";

        return $conexion->consultar($query);
    }


    // =========================================================
    // RELACION PERMISO - PERFIL
    // =========================================================

    public function asignarPerfil($id_permiso, $id_perfil)
    {
        $conexion = new Conexion();

        $query = "INSERT INTO clinica.permiso_perfiles
                    (permiso_id_permiso, perfil_id_perfil)
                  VALUES
                    ('$id_permiso', '$id_perfil')";

        return $conexion->insertar($query);
    }


    public function desasignarPerfil($id_permiso, $id_perfil)
    {
        $conexion = new Conexion();

        $query = "DELETE FROM clinica.permiso_perfiles
                  WHERE permiso_id_permiso = '$id_permiso'
                  AND perfil_id_perfil = '$id_perfil'";

        return $conexion->eliminar($query);
    }


    public function desasignarPerfiles($id_permiso)
    {
        $conexion = new Conexion();

        $query = "DELETE FROM clinica.permiso_perfiles
                  WHERE permiso_id_permiso = '$id_permiso'";

        return $conexion->eliminar($query);
    }


    public function traer_permisos_por_perfil($id_perfil)
    {
        $conexion = new Conexion();

        $query = "SELECT permiso.*
                  FROM clinica.permiso
                  INNER JOIN clinica.permiso_perfiles
                  ON permiso_perfiles.permiso_id_permiso = permiso.id_permiso
                  WHERE permiso_perfiles.perfil_id_perfil = '$id_perfil'
                  ORDER BY permiso.id_permiso ASC";

        return $conexion->consultar($query);
    }


    public function traer_permisos_ids_por_perfil($id_perfil)
    {
        $conexion = new Conexion();

        $query = "SELECT permiso_id_permiso
                  FROM clinica.permiso_perfiles
                  WHERE perfil_id_perfil = '$id_perfil'";

        $resultado = $conexion->consultar($query);

        $ids = [];

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $ids[] = (int)$fila['permiso_id_permiso'];
            }
        }

        return $ids;
    }


    // =========================================================
    // GETTERS Y SETTERS
    // =========================================================

    public function getId_permiso()
    {
        return $this->id_permiso;
    }


    public function setId_permiso($id_permiso)
    {
        $this->id_permiso = $id_permiso;

        return $this;
    }


    public function getNombre_permiso()
    {
        return $this->nombre_permiso;
    }


    public function setNombre_permiso($nombre_permiso)
    {
        $this->nombre_permiso = $nombre_permiso;

        return $this;
    }


    public function getDetalle()
    {
        return $this->detalle;
    }


    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }


    public function getEstado()
    {
        return $this->estado;
    }


    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }
}
?>