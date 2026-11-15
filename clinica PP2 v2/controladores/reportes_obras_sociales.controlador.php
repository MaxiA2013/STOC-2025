<?php
// controladores/reportes_obras_sociales.controlador.php
require_once __DIR__ . '/../modelos/conexion.php';

class ReportesObrasSocialesControlador {
    private $con;

    public function __construct() {
        $this->con = new Conexion();
    }

    // Diario
    public function diarioUso() {
        $sql = "SELECT DATE(u.fecha_alta) AS dia,
                       DAYNAME(u.fecha_alta) AS nombre_dia,
                       os.nombre_obra_social AS obra_social,
                       COUNT(*) AS total_uso
                FROM paciente_obra_social pos
                INNER JOIN paciente p ON pos.paciente_id_paciente = p.id_paciente
                INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
                INNER JOIN obra_social os ON pos.obra_social_id_obra_social = os.id_obra_social
                WHERE u.fecha_alta IS NOT NULL
                GROUP BY DATE(u.fecha_alta), os.id_obra_social
                ORDER BY dia ASC";
        return $this->con->consultar($sql);
    }

    // Semanal
    public function semanalUso() {
        $sql = "SELECT YEAR(u.fecha_alta) AS anio,
                       WEEK(u.fecha_alta, 1) AS semana,
                       os.nombre_obra_social AS obra_social,
                       COUNT(*) AS total_uso
                FROM paciente_obra_social pos
                INNER JOIN paciente p ON pos.paciente_id_paciente = p.id_paciente
                INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
                INNER JOIN obra_social os ON pos.obra_social_id_obra_social = os.id_obra_social
                WHERE u.fecha_alta IS NOT NULL
                GROUP BY anio, semana, os.id_obra_social
                ORDER BY anio DESC, semana ASC";
        return $this->con->consultar($sql);
    }

    // Mensual
    public function mensualUso() {
        $sql = "SELECT YEAR(u.fecha_alta) AS anio,
                       DATE_FORMAT(u.fecha_alta, '%M') AS mes_nombre,
                       os.nombre_obra_social AS obra_social,
                       COUNT(*) AS total_uso
                FROM paciente_obra_social pos
                INNER JOIN paciente p ON pos.paciente_id_paciente = p.id_paciente
                INNER JOIN usuario u ON p.usuario_id_usuario = u.id_usuario
                INNER JOIN obra_social os ON pos.obra_social_id_obra_social = os.id_obra_social
                WHERE u.fecha_alta IS NOT NULL
                GROUP BY anio, MONTH(u.fecha_alta), os.id_obra_social
                ORDER BY anio DESC, MONTH(u.fecha_alta) DESC";
        return $this->con->consultar($sql);
    }
}
