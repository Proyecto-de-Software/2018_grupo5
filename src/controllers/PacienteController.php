<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class PacienteController extends Controller {
	//aca habria un include del model pacientes
    static function index(){
        $instance = new PacienteController();
        return $instance->twig_render("modules/pacientes/index.html", []);
    }
    static function new(){
        $instance = new PacienteController();

        $parameters=array();
        $dnis = $instance->getModel('TipoDocumento')->findAll();
        foreach ($dnis as $key => $value) {
          array_push($parameters, $value->getNombre());
        }
        $parameters=array(
          'dnis' => $parameters,
        );


        return $instance->twig_render("modules/pacientes/crear.html", $parameters);
    }

}
