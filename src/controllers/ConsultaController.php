<?php
//
require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/MotivoConsulta.php");
require_once(CODE_ROOT . "/models/Acompanamiento.php");

require_once(CODE_ROOT . "/models/TratamientoFarmacologico.php");
require_once(CODE_ROOT . "/Dao/TratamientoFarmacologicoDAO.php");
require_once(CODE_ROOT . "/Dao/MotivoConsultaDAO.php");
require_once(CODE_ROOT . "/Dao/AcompaniamientosDAO.php");

use controllers\Controller;

class ConsultaController extends Controller {

    public function createView(){

        $motivosDao = new MotivoConsultaDAO();
        $motivos = $motivosDao->getAll();

        $acompanamientosDao = new AcompaniamientosDAO();
        $acompanamientos = $acompanamientosDao->getAll();

        $tratamientoFarmacologicoDAO =  new TratamientoFarmacologicoDAO();
        $tratamientos_farmacologicos = $tratamientoFarmacologicoDAO->getAll();


        $parameters = [
            'motivos' => $motivos,
            'acompanamientos' => $acompanamientos,
            'tratamientos_farmacologicos' => $tratamientos_farmacologicos,
        ];

        return $this->twig_render("modules/consultas/formConsulta.html", $parameters);

    }
}



