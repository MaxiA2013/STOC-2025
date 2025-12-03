<?php
// controladores/reportes_pacientes.controlador.php
require_once __DIR__ . '/../modelos/conexion.php';

class ReportesPacientesControlador {
    private $con;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function diarioPorGenero() {
        $sql = "SELECT DAYNAME(u.fecha_alta) AS nombre_dia,
                       DATE(u.fecha_alta) AS dia,
                       CASE per.sexo
                         WHEN 1 THEN 'Masculino'
                         WHEN 2 THEN 'Femenino'
                         ELSE 'Otro'
                       END AS sexo,
                       COUNT(*) AS total_pacientes
                FROM paciente p
                INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
                INNER JOIN persona per ON u.persona_id_persona = per.id_persona
                WHERE u.fecha_alta IS NOT NULL
                GROUP BY nombre_dia, DATE(u.fecha_alta), per.sexo
                ORDER BY dia ASC";
        return $this->con->consultar($sql);
    }

    public function semanalPorGenero() {
        $sql = "SELECT DATE(u.fecha_alta) AS dia,
                       CASE per.sexo
                         WHEN 1 THEN 'Masculino'
                         WHEN 2 THEN 'Femenino'
                         ELSE 'Otro'
                       END AS sexo,
                       COUNT(*) AS total_pacientes
                FROM paciente p
                INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
                INNER JOIN persona per ON u.persona_id_persona = per.id_persona
                WHERE u.fecha_alta IS NOT NULL
                GROUP BY WEEK(u.fecha_alta, 1), DATE(u.fecha_alta), per.sexo
                ORDER BY dia ASC";
        return $this->con->consultar($sql);
    }

    public function mensualPorGenero() {
        $sql = "SELECT YEAR(u.fecha_alta) AS año,
                       DATE_FORMAT(u.fecha_alta, '%M') AS mes_nombre,
                       CASE per.sexo
                         WHEN 1 THEN 'Masculino'
                         WHEN 2 THEN 'Femenino'
                         ELSE 'Otro'
                       END AS sexo,
                       COUNT(*) AS total_pacientes
                FROM paciente p
                INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
                INNER JOIN persona per ON u.persona_id_persona = per.id_persona
                WHERE u.fecha_alta IS NOT NULL
                GROUP BY YEAR(u.fecha_alta), MONTH(u.fecha_alta), per.sexo
                ORDER BY año DESC, MONTH(u.fecha_alta) DESC";
        return $this->con->consultar($sql);
    }
}
