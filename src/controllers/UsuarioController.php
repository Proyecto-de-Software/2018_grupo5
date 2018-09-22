<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once (CODE_ROOT . "/models/Usuario.php");
use controllers\Controller;

class UsuarioController extends Controller {
	//aca habria un include del model usuario

    static function index(){
        $instance = new UsuarioController();
        return $instance->twig_render("modules/usuarios/index.html", []);
    }

    static function new(){

        //prueba de agregar usuario
        $instance = new UsuarioController();
        $user = new Usuario();
        $user->setFirstName($_POST['first_name']);
        $user->setLastName($_POST['last_name']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setUsername($_POST['username']);
        $instance->entityManager()->persist($user);
        $instance->entityManager()->flush();





    }
}
