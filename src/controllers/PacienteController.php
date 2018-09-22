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

        return $instance->twig_render("modules/pacientes/crear.html",[]);
    }

}
