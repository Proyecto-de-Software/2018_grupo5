<?php

/*
require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Paciente.php");
require_once(CODE_ROOT . "/models/Genero.php");
require_once(CODE_ROOT . "/models/RegionSanitaria.php");
require_once(CODE_ROOT . "/models/TipoDocumento.php");
require_once(CODE_ROOT . "/models/Localidad.php");
require_once(CODE_ROOT . "/models/Partido.php");
require_once(CODE_ROOT . "/models/ObraSocial.php");
*/
use controllers\Controller;

class ConsultaController extends Controller {

    public function createView(){
        
        return $this->twig_render("modules/consultas/formConsulta.html", []);

    }
}

