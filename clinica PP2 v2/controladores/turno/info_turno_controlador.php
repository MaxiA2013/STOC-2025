<?php
require_once "modelos/turno.php";

class TurnoControlador {

    public function listarTurnos() {

        // --- PAGINADO ---
        $porPagina = 20; // turnos por página
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $offset = ($pagina - 1) * $porPagina;

        $turno = new Turno();

        // Consulta con LIMIT
        $turnos = $turno->consultarTurnosDisponiblesPaginado($offset, $porPagina);

        // Cantidad total para paginado
        $totalTurnos = $turno->contarTurnosDisponibles();
        $totalPaginas = ceil($totalTurnos / $porPagina);

        require_once "vistas/paginas/turnos.php";
    }
}
