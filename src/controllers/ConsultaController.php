<?php
//
require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/MotivoConsulta.php");
require_once(CODE_ROOT . "/models/Acompanamiento.php");
require_once(CODE_ROOT . "/models/TratamientoFarmacologico.php");

use controllers\Controller;

class ConsultaController extends Controller {

    public function createView(){
        $motivos = $this->getModel('MotivoConsulta')->findAll();
        $acompanamientos = $this->getModel('Acompanamiento')->findAll();
        $tratamientos_farmacologicos = $this->getModel('TratamientoFarmacologico')->findAll();
        $parameters = [
            'motivos' => $motivos,
            'acompanamientos' => $acompanamientos,
            'tratamientos_farmacologicos' => $tratamientos_farmacologicos,
        ];

        return $this->twig_render("modules/consultas/formConsulta.html", $parameters);

    }
}



