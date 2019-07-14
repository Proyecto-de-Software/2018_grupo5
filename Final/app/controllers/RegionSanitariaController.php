<?php
require_once("Controller.php");

use controllers\Controller;

class RegionSanitariaController extends Controller {

    private $regionSAnitariaDao;
    private $partidoDao;

    function __construct() {
        parent::__construct();
        $this->partidoDao = new PartidoDao();
        $this->regionSAnitariaDao = new RegionSanitariaDAO();
    }

    function obtenerPorPartido($id_partido) {

        $this->assertPermission();
        $partido_a_buscar = $this->partidoDao->getById($id_partido[1]);
        if($partido_a_buscar == null) {
            return $this->jsonResponse(['error'=>true, 'msg'=>'Not Found']);
        }
        $region_encontrada = $partido_a_buscar->getRegionSanitaria()->getNombre();
        $id_region_encontrada = $partido_a_buscar->getRegionSanitaria()->getId();
        $arrayRegion = ['id_region_sanitaria' => $id_region_encontrada, 'nombre' => $region_encontrada];
        return $this->jsonResponse($arrayRegion);
    }

}
