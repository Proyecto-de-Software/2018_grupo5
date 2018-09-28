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

    static function new() {
        $instance = new UsuarioController();
        $roles = $instance->getModel('Rol')->findAll();
        $permissions = $instance->getModel('Permiso')->findAll();
        $context = [
            "roles" => $roles,
            "permisos" => $permissions,
        ];
        return $instance->twig_render('modules/usuarios/crear.html', $context);
    }

    public function create() {
        $data['error'] = false;
        $data['msg'] = 'nada';
        $this->assertInMaintenance();
        $this->assertPermission();
        $user = new Usuario();
        $user->setFirstName($_POST['first_name']);
        $user->setLastName($_POST['last_name']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setUsername($_POST['username']);
        (!!is_null($_POST['user_state'])) ? $user->setActivo((false)) : $user->setActivo((true));
        (!!is_null($_POST['superuser'])) ? $user->setIsSuperuser(false) : $user->setActivo(true);
        $user->setUpdatedAt(new DateTime('now'));
        $user->setCreatedAt(new DateTime('now'));

        $roles = $_POST['roles'];

        if(isset($roles)) {
            foreach ($roles as $role) {
                $rol = ($this->getModel('Rol')->find($role));
                $user->addRol($rol);
            }
        }
        $permisos = $_POST['permisos'];
        $data['permisos'] = $permisos;
        foreach ($permisos as $permiso) {
                $perm = ($this->getModel('Permiso')->find($permiso));
                $user->addPermiso($perm);
        }

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
        $usuarioId = $param['id'];
        $data = [];
        if($instance->userHasPermission('usuario_destroy')) {
            try {
                $user = $instance->getModel('Usuario')->findOneBy(['id' => $usuarioId]);
                //$instance->entityManager()->remove($user);
                //setear como inactivo ?
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
        return $instance->twig_render("modules/usuarios/modificar.html", $context);
    }

    static function update($param) {
        $data = [];
        $usuarioId = $param['id'];
        $instance = new UsuarioController();
        if($instance->userHasPermission('usuario_update')) {
            $user = $instance->getModel('Usuario')->findOneBy(['id' => $usuarioId]);
            $user->setFirstName($_POST['first_name']);
            $user->setLastName($_POST['last_name']);
            $user->setEmail($_POST['email']);
            $user->setUsername($_POST['username']);
            $user->setActivo($_POST['user_state']);
            $user->setUpdatedAt(new DateTime('now'));
            $roles = $_POST['roles'];
            if(isset($roles)) {
                foreach ($roles as $role) {
                    $rol = ($instance->getModel('Rol')->find($role));
                    $user->addRol($rol);
                }
            }
            $permissions = $_POST['permisos'];
            if(isset($permissions)) {
                foreach ($permissions as $permission) {
                    $perm = ($instance->getModel('Permiso')->find($permission));
                    $user->addPermiso($perm);
                }
            }
            try {
                $instance->entityManager()->persist($user);
                $instance->entityManager()->flush();
                $data['msg'] = "usuario actualizado ok";
            } catch (Exception $e) {
                $data['msg'] = $e->getMessage();
            }

        } else {
            $data['error'] = true;
            $data['msg'] = "Not enough permission or not logged";
        }
        (DEBUG) ? var_dump("msg:" . $data['msg']) : header('Location: /modulo/usuarios');
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
