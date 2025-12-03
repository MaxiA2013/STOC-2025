<?php
// controladores/reportes_usuarios.controlador.php
require_once __DIR__ . '/../modelos/conexion.php';

class ReportesUsuariosControlador {
    private $con;

    public function __construct() {
        $this->con = new Conexion();
    }

    // Diario agrupado: nombre del día, fecha y total
    public function diarioAgrupado() {
        $sql = "SELECT DAYNAME(fecha_alta) AS nombre_dia,
                       DATE(fecha_alta) AS dia,
                       COUNT(*) AS total_usuarios
                FROM clinica.usuario
                WHERE fecha_alta IS NOT NULL
                GROUP BY nombre_dia, DATE(fecha_alta)
                ORDER BY dia ASC";
        return $this->con->consultar($sql);
    }

        // Semanal agrupado: conteo por día dentro del rango
    public function semanalAgrupado() {
        $con = new Conexion();
        $sql = "SELECT DATE(fecha_alta) AS dia,
                    COUNT(*) AS total_usuarios
                FROM clinica.usuario
                WHERE fecha_alta BETWEEN '2025-11-01 00:00:00' AND '2025-11-29 23:59:59'
                GROUP BY WEEK(fecha_alta, 1), DATE(fecha_alta)
                ORDER BY dia ASC";
        return $con->consultar($sql);
    }

        // Mensual agrupado: total de usuarios por mes
    public function mensualAgrupado() {
        $sql = "SELECT YEAR(fecha_alta) AS año,
                       DATE_FORMAT(fecha_alta, '%M') AS mes_nombre,
                       COUNT(*) AS total_usuarios
                FROM clinica.usuario
                WHERE fecha_alta IS NOT NULL
                GROUP BY YEAR(fecha_alta), MONTH(fecha_alta)
                ORDER BY año DESC, MONTH(fecha_alta) DESC";
        return $this->con->consultar($sql);
    }

}

?>