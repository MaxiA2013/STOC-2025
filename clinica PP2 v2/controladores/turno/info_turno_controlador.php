<?php
require_once "modelos/turno.php";
//este archivo es el controlador para el paginado de los turnos de la pagina turno.php (despues le cambio el nombre... o no... nose)

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
