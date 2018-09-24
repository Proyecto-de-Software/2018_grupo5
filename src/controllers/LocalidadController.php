<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once (CODE_ROOT . "/models/Localidad.php");
require_once (CODE_ROOT . "/models/Partido.php");
require_once (CODE_ROOT . "/models/RegionSanitaria.php");

use controllers\Controller;

class LocalidadController extends Controller {

    
    static function obtenerPorPartido($id_partido){
        $instance = new LocalidadController();
        $partido_a_buscar = $instance->getModel('Partido')->findOneBy(array('id'=>$id_partido[1]));
        $localidades = $instance->getModel('Localidad')->findBy(array('partido'=>$partido_a_buscar));
        $arrayName = array();
        foreach ($localidades as $valor) {
            $newItem = array('id_localidad' => $valor->getId(), 'nombre' => $valor-> getNombre());
            array_push($arrayName, $newItem);
        }
        return json_encode($arrayName);
    }
}

