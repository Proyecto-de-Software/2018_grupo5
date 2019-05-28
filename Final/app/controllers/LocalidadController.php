<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class LocalidadController extends Controller {

    private $partidoDao;
    private $localidadesDao;

    function __construct() {
        parent::__construct();
        $this->partidoDao = new PartidoDao();
        $this->localidadesDao = new LocalidadDAO();
    }

    function obtenerPorPartido($id_partido) {

        $partido_a_buscar = $this->partidoDao->getById($id_partido[1]);
        if($partido_a_buscar == null) {
            return $this->jsonResponse(['error'=>true, 'msg'=>'Not Found']);
        }
        $localidades = $this->localidadesDao->findByPartido($partido_a_buscar);
        $data = [];
        foreach ($localidades as $localidad) {
            $localidadJson = [
                'id_localidad' => $localidad->getId(),
                'nombre' => $localidad->getNombre(),
            ];
            $data[] = $localidadJson;
        }
        return $this->jsonResponse($data);
    }
}

