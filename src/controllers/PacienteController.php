<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once (CODE_ROOT . "/models/Paciente.php");
use controllers\Controller;

class PacienteController extends Controller {

    static function index(){
        $instance = new PacienteController();
        $pacientes = $instance->getModel('Paciente')->findAll();
        $context['pacientes'] = $pacientes;
        return $instance->twig_render("modules/pacientes/index.html", $context);
    }
    static function new($params){
        $instance = new PacienteController();

        return $instance->twig_render("modules/pacientes/crear.html",[]);
    }

}
