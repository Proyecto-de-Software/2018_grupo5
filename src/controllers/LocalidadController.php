<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class LocalidadController extends Controller {

    function obtenerPorPartido($id_partido) {
        /** @todo FIX THIS */
        $instance = new LocalidadController();
        $partidoDao = new PartidoDao();
        $partido_a_buscar = $partidoDao->getById($id_partido[1]);
        $localidadesDao = new LocalidadDao();
        $localidades = $localidadesDao->findByPartido($partido_a_buscar);
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

