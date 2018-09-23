<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once (CODE_ROOT . "/models/Usuario.php");
use controllers\Controller;

class UsuarioController extends Controller {
	//aca habria un include del model usuario

    static function index(){
        $instance = new UsuarioController();
        $usuarios = $instance->getModel('Usuario')->findAll();
        $context['usuarios'] = $usuarios;
        var_dump($instance->userHasPermission('usuario_crear'));
        return $instance->twig_render("modules/usuarios/index.html", $context);
    }

    static function ver(){
        $instance = new UsuarioController();
        echo "vista de un usuario";
    }

    static function new(){
        //prueba de agregar usuario
        $instance = new UsuarioController();
        if ($instance->userHasPermission('usuario_new')) {
            $user = new Usuario();
            $user->setFirstName($_POST['first_name']);
            $user->setLastName($_POST['last_name']);
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);
            $user->setUsername($_POST['username']);
            $user->setUsername($_POST['activo']);
            $instance->entityManager()->persist($user);
            $instance->entityManager()->flush();
        }
    }
}


