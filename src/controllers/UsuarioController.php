<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once (CODE_ROOT . "/models/Usuario.php");
require_once (CODE_ROOT . "/models/Rol.php");
require_once (CODE_ROOT . "/models/Permiso.php");
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

    static function ver($param){
        // muestra a un usuario en particular.
        $instance = new UsuarioController();
        $usuarioId = $param['id'];
        $user = $instance->getModel('Usuario')->findOneBy(array('id'=>$usuarioId));
        $user->getEmail();
        $data  = [
            'usuario' => $user
        ];
        return $instance->twig_render("modules/usuarios/user.html", $data);
    }

    static function new() {
        $instance = new UsuarioController();
        $roles = $instance->getModel('Rol')->findAll();
        $permissions = $instance->getModel('Permiso')->findAll();
        $context = array(
            "roles" => $roles,
            "permisos" => $permissions
        );
        return $instance->twig_render('modules/usuarios/crear.html', $context);


    }

    static function create() {

        $instance = new UsuarioController();
        if ($instance->userHasPermission('usuario_new')) {
            $user = new Usuario();
            $user->setFirstName($_POST['first_name']);
            $user->setLastName($_POST['last_name']);
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);
            $user->setUsername($_POST['username']);
            $user->setActivo($_POST['user_state']);
            $user->setIsSuperuser(false);
            $user->setUpdatedAt(new DateTime('now'));
            $user->setCreatedAt(new DateTime('now'));
            //$user->addPermiso('instancais de los permisos');
            //$user->addRol(  instancias de los roles);
            try {
                $instance->entityManager()->persist($user);
                $instance->entityManager()->flush();
            } catch (Exception $e) {
                $error = array(
                    "msg"=>$e->getMessage(),
                );
                return ($instance->jsonResponse($error));
            }
            header('Location: /modulo/usuarios');
        } else {
            $data['error'] = true;
            $data['msg'] = "Not enough permission or not logged";
            return $instance->jsonResponse($data);
        }
    }
}


