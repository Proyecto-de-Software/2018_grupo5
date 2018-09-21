<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class UsuarioController extends Controller {
	//aca habria un include del model usuario

    static function index(){
        $instance = new UsuarioController();
        return $instance->twig_render("modules/usuarios/index.html", []);
    }

}
