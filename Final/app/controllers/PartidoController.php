<?php
require_once("Controller.php");

use controllers\Controller;

class PartidoController extends Controller {

    private $partidoDao;

    function __construct() {
        parent::__construct();
        $this->partidoDao = new PartidoDao();
    }

    function verTodosLosPartidos() {
        $data = [];
        $partidos = $this->partidoDao->getAll();
        foreach ($partidos as $partido) {
            $data[] = [
                'id' => $partido->getId(),
                'nombre' => $partido->getNombre()
                ];
        }
        return $this->jsonResponse($data);
    }
}
