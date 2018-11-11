<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class RegionSanitariaController extends Controller {

    
    static function obtenerPorPartido($id_partido){
        /** @todo FIX THIS */
        $instance = new RegionSanitariaController();
        $instance->assertPermission();
        $partido_a_buscar = $instance->getModel('Partido')->findOneBy(array('id'=>$id_partido[1]));
        $region_encontrada= $partido_a_buscar->getRegionSanitaria()->getNombre();
        $id_region_encontrada = $partido_a_buscar->getRegionSanitaria()->getId();
        $arrayRegion = array('id_region_sanitaria' => $id_region_encontrada, 'nombre' => $region_encontrada);
        return json_encode($arrayRegion);
    }
}
