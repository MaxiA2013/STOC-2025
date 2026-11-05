<?php
// En este archivo encontramos las funciones de la clase ObraSocialPrestacion.
require_once('conexion.php');

class ObraSocialPrestacion
{
    public $obra_social_id;
    public $prestacion_id;
    public $codigo;
    public $precio;
    public $estado;
    public $visible;

    // Guarda una nueva cobertura (Alta)
    public function guardarCobertura()
    {
        $conexion = new Conexion();
        $query = "INSERT INTO clinica.obra_social_prestacion (`obra_social_id_obra-social`, prestacion_id_prestacion, codigo, precio, estado, visible) 
                  VALUES ('$this->obra_social_id', '$this->prestacion_id', '$this->codigo', '$this->precio', '$this->estado', 1)";
        return $conexion->insertar($query);
    }

    // Actualiza una cobertura existente (Modificación)
    public function actualizarCobertura()
    {
        $conexion = new Conexion();
        $query = "UPDATE clinica.obra_social_prestacion 
                  SET codigo = '$this->codigo', precio = '$this->precio', estado = '$this->estado' 
                  WHERE `obra_social_id_obra-social` = '$this->obra_social_id' AND prestacion_id_prestacion = '$this->prestacion_id'";
        return $conexion->actualizar($query);
    }

    public function eliminarLogico()
    {
        $conexion = new Conexion();
        // Corregido: Al eliminar se cambia a 0 (Oculto)
        $query = "UPDATE clinica.obra_social_prestacion SET visible = 0 
                  WHERE `obra_social_id_obra-social` = '$this->obra_social_id' AND prestacion_id_prestacion = '$this->prestacion_id'";
        return $conexion->actualizar($query);
    }

    // Función para listar las Obras Sociales en los select de los formularios
    public function listar_obras_sociales()
    {
        $conexion = new Conexion();
        $query = "SELECT id_obra_social, nombre_obra_social FROM clinica.obra_social ORDER BY nombre_obra_social ASC";
        return $conexion->consultar($query);
    }

    // Función para listar las Prestaciones en los select de los formularios
    public function listar_prestaciones()
    {
        $conexion = new Conexion();
        $query = "SELECT id_prestacion, nombre_prestacion FROM clinica.prestacion ORDER BY nombre_prestacion ASC";
        return $conexion->consultar($query);
    }

    // BUSCADOR PRINCIPAL CON LIMIT Y PAGINADO
    public function coberturas_buscador($columnas, $campo, $sLimit)
    {
        $conexion = new Conexion();
        // Corregido: El buscador consulta los registros que están en 1 (Visibles)
        $query = "SELECT " . implode(",", $columnas) . "
                  FROM clinica.obra_social_prestacion osp
                  JOIN clinica.obra_social os ON osp.`obra_social_id_obra-social` = os.id_obra_social
                  JOIN clinica.prestacion p ON osp.prestacion_id_prestacion = p.id_prestacion
                  WHERE osp.visible = 1";

        if (!empty($campo)) {
            $query .= " AND (osp.codigo LIKE '%$campo%'
                        OR os.nombre_obra_social LIKE '%$campo%'
                        OR p.nombre_prestacion LIKE '%$campo%')";
        }
        $query .= " $sLimit";
        return $conexion->consultar($query);
    }

    // TOTAL DE REGISTROS CON FILTRO WHERE (Para calcular el paginado)
    public function coberturas_filtradosWhere($campo)
    {
        $conexion = new Conexion();
        // Corregido: Cuenta los registros que están en 1 (Visibles)
        $query = "SELECT COUNT(*) AS total
                  FROM clinica.obra_social_prestacion osp
                  JOIN clinica.obra_social os ON osp.`obra_social_id_obra-social` = os.id_obra_social
                  JOIN clinica.prestacion p ON osp.prestacion_id_prestacion = p.id_prestacion
                  WHERE osp.visible = 1";

        if (!empty($campo)) {
            $query .= " AND (osp.codigo LIKE '%$campo%'
                        OR os.nombre_obra_social LIKE '%$campo%'
                        OR p.nombre_prestacion LIKE '%$campo%')";
        }
        $resultado = $conexion->consultar($query);
        $row = $resultado->fetch_assoc();
        return $row['total'];
    }

    // TOTAL GENERAL SIN FILTROS (Para el contador global)
    public function cantidad_coberturas()
    {
        $conexion = new Conexion();
        // Corregido: Cuenta el total general de registros en 1 (Visibles)
        $query = "SELECT COUNT(*) AS total FROM clinica.obra_social_prestacion WHERE visible = 1";
        $resultado = $conexion->consultar($query);
        $fila = $resultado->fetch_assoc();
        return $fila['total'];
    }
}
