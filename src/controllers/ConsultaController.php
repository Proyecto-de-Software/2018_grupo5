<?php
//
require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/MotivoConsulta.php");
require_once(CODE_ROOT . "/models/Acompanamiento.php");

require_once(CODE_ROOT . "/models/TratamientoFarmacologico.php");
require_once(CODE_ROOT . "/Dao/TratamientoFarmacologicoDAO.php");
require_once(CODE_ROOT . "/Dao/MotivoConsultaDAO.php");
require_once(CODE_ROOT . "/Dao/AcompaniamientoDAO.php");

use controllers\Controller;

class ConsultaController extends Controller {

    public function createView(){
        $motivosDao = MotivoConsultaDAO::getInstance();
        $acompanamientosDao = AcompaniamientoDAO::getInstance();
        $tratamientoFarmacologicoDAO =  TratamientoFarmacologicoDAO::getInstance();

        $parameters = [
            'motivos' => $motivosDao->getAll(),
            'acompanamientos' => $acompanamientosDao->getAll(),
            'tratamientos_farmacologicos' => $tratamientoFarmacologicoDAO->getAll(),
        ];

        return $this->twig_render("modules/consultas/formConsulta.html", $parameters);
    }
}

