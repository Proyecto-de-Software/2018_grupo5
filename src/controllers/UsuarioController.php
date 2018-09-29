<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Usuario.php");
require_once(CODE_ROOT . "/models/Rol.php");
require_once(CODE_ROOT . "/models/Permiso.php");

use controllers\Controller;

class UsuarioController extends Controller {
    //aca habria un include del model usuario

    static function index() {
        $instance = new UsuarioController();
        $context['usuarios'] = [];
        return $instance->twig_render("modules/usuarios/index.html", $context);
    }

    static function searchView() {
        $instance = new UsuarioController();
        return $instance->twig_render("modules/usuarios/buscar.html", []);
    }

    static function search() {
        $instance = new UsuarioController();
        /*
        Forma muy rara de hacer un or en una consulta usando doctrine, esto lo debe hacer el modelo directamente, buscar otra forma mejor
        */
        if(!isset($_POST['user_state'])) $_POST['user_state'] = 0;
        $query = "select u from Usuario u where (u.username like '%" . $_POST['username'] . "%' AND u.activo = '" . $_POST['user_state'] . "')";
        $q = $instance->entityManager()->createQuery($query);
        $usuarios = $q->getResult();


        $context['usuarios'] = $usuarios;
        return $instance->twig_render("modules/usuarios/index.html", $context);
    }

    static function ver($param) {
        $instance = new UsuarioController();
        $usuarioId = $param['id'];
        $user = $instance->getModel('Usuario')->findOneBy(['id' => $usuarioId]);
        $data = [
            'usuario' => $user,
        ];
        return $instance->twig_render("modules/usuarios/user.html", $data);
    }

    function createView() {
        $roles = $this->getModel('Rol')->findAll();
        $permissions = $this->getModel('Permiso')->findAll();
        $context = [
            "roles" => $roles,
            "permisos" => $permissions,
        ];
        return $this->twig_render('modules/usuarios/formUsuario.html', $context);
    }

    public function create() {
        $data['error'] = false;
        $data['msg'] = 'nada';

        $user = new Usuario();
        $this->setUserData($user);
        $user->setCreatedAt(new DateTime('now'));
        try {
            $this->entityManager()->persist($user);
            $this->entityManager()->flush();
            $data['error'] = false;
            $data['msg'] = "usuario agregado con exito";

        } catch (Exception $e) {
            $data = [
                "msg" => "No se pudo agregar al susuario" . $e->getMessage(),
                "error" => true,
            ];
        }
        return $this->jsonResponse($data);
    }

    static function delete($param) {

        $instance = new UsuarioController();
        $userId = $param['id'];
        $data = [];
        if($instance->userHasPermission('usuario_destroy')) {
            try {
                $user = $instance->getModel('Usuario')->findOneBy(['id' => $userId]);
                //$user->setDelete(1);
                $instance->entityManager()->flush();
                $data['msg'] = "usuario eliminado con exito";
                $data['error'] = false;
            } catch (Exception $e) {
                $data['msg'] = $e->getMessage();
                $data['error'] = true;
            }
        } else {
            $data['error'] = true;
            $data['msg'] = "Not enough permission or not logged";

        }
        (SETTINGS['debug']) ? var_dump("msg:" . $data['msg']) : header('Location: /modulo/usuarios');
    }

    static function update_view($param) {
        $usuarioId = $param['id'];
        $instance = new UsuarioController();
        $user = $instance->getModel('Usuario')->findOneBy(['id' => $usuarioId]);
        $roles = $instance->getModel('Rol')->findAll();
        $permissions = $instance->getModel('Permiso')->findAll();
        $context = [
            "roles" => $roles,
            "permisos" => $permissions,
            "usuario" => $user,
        ];
        return $instance->twig_render("modules/usuarios/formUsuario.html", $context);
    }

    private function setUserData($user) {

        $this->assertInMaintenance();
        $this->assertPermission();

        $user->setFirstName($_POST['first_name']);
        $user->setLastName($_POST['last_name']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setUsername($_POST['username']);
        (!!is_null($_POST['user_state'])) ? $user->setActivo((false)) : $user->setActivo((true));
        (!!is_null($_POST['superuser'])) ? $user->setIsSuperuser(false) : $user->setActivo(true);
        $roles = $_POST['roles'];
        if(isset($roles)) {
            foreach ($roles as $role) {
                $rol = ($this->getModel('Rol')->findOneBy(['id'=>$role]));
                $user->addRol($rol);
            }
        }
        $permisos = $_POST['permisos'];
        if(isset($permisos)) {
            foreach ($permisos as $permiso) {
                $perm = ($this->getModel('Permiso')->find($permiso));
                $user->addPermiso($perm);
            }
        }
        $user->setUpdatedAt(new DateTime('now'));
        return $user;


    }

    public function update() {
        $data['error'] = false;
        $data['msg'] = 'nada';
        $userId = $_POST['id']; //viene por input hidden
        $instance = new UsuarioController();
        $user = $instance->getModel('Usuario')->findOneBy(['id' => $userId]);
        try {
            $instance->entityManager()->merge($instance->setUserData($user));
            $instance->entityManager()->flush();
            $data['msg'] = "usuario actualizado ok";
        } catch (Exception $e) {
            $data = [
                "msg" => "Error al actualizar los datos del usuario" . $e->getMessage(),
                "error" => true,
            ];
        }
        return $this->jsonResponse($data);
    }



static function changePassword_view($param) {
    $instance = new UsuarioController();
    $user = $instance->getModel('Usuario')->findOneBy(['id' => $param['id']]);
    $context['usuario'] = $user;
    return $instance->twig_render("modules/usuarios/cambiarClave.html", $context);
}

static function changePassword($param) {
    $data = [];
    $old_password = $_POST['old_pwd'];
    $new_password = $_POST['new_pwd'];
    $usuarioId = $param['id'];
    $instance = new UsuarioController();
    if($instance->userHasPermission('usuario_update')) {
        $user = $instance->getModel('Usuario')->findOneBy(['id' => $usuarioId]);
        if($user->getPassword() == $old_password) {
            $user->setPassword($new_password);
            try {
                $instance->entityManager()->persist($user);
                $instance->entityManager()->flush();
                $data['msg'] = "clave actualizada ok";
            } catch (Exception $e) {
                $data['msg'] = $e->getMessage();
            }
        } else {
            $data['msg'] = "The old password didn't match";
        }
    } else {
        $data['error'] = true;
        $data['msg'] = "Not enough permission or not logged";
    }
    (DEBUG) ? var_dump("msg:" . $data['msg']) : header('Location: /modulo/usuarios');
}


}
