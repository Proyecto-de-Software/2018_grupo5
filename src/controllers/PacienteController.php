<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class PacienteController extends Controller {
	//aca habria un include del model pacientes

    static function index(){
        $instance = new PacienteController();

        $dnis = $instance->getModel('TipoDocumento')->findAll();

        $contexto = [
          'dnis'=> [
              '0'=>'dni',
              '1'=>'le',
          ]
        ];

        return $instance->twig_render("modules/pacientes/index.html", $contexto);
    }

}
