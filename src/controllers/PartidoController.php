<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

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
            $data[] = ['id' => $partido->getId()];
        }
        return $this->jsonResponse($data);
    }
}
