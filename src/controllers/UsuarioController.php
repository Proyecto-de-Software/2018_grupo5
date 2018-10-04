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

    function searchView() {
        return $this->twig_render("modules/usuarios/buscar.html", []);
    }

    function search() {
        if(!isset($_POST['user_state'])) $_POST['user_state'] = 0;
        $qb = $this->entityManager()->createQueryBuilder();
        $qb->select('u')
            ->from('Usuario', 'u')
            ->where($qb->expr()->orX(
                $qb->expr()->like('u.username', '?1'),
                $qb->expr()->eq('u.activo', '?2')
            ),
            $qb->expr()->andX(
                    $qb->expr()->eq('u.eliminado', '?3')
            )


            );
        $qb->setParameters([1 => $_POST['username'], 2 => $_POST['user_state'], 3 => 0]);
        $query = $qb->getQuery();
        $context['usuarios'] = $query->getResult();

        return $this->twig_render("modules/usuarios/index.html", $context);
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

        $response = [
            'error'=>true,
            'msg' => null
        ];

        $user = new Usuario();
        $this->setUserData($user);
        $user->setCreatedAt(new DateTime('now'));
        try {
            $this->entityManager()->persist($user);
            $this->entityManager()->flush();
            $response['error'] = false;
            $response['msg'] = "usuario agregado con exito";

        } catch (Exception $e) {
            $response = [
                "msg" => "No se pudo agregar al susuario" . $e->getMessage(),
                "error" => true,
            ];
        }
        return $this->jsonResponse($response);
    }

    public function delete($param) {


        $userId = $param['id'];
        $response = [];
        if($this->userHasPermission('usuario_delete')) {
            try {
                $user = $this->getModel('Usuario')->findOneBy(['id' => $userId]);
                $user->setEliminado('1');
                $this->entityManager()->flush();
                $response['msg'] = "usuario eliminado con exito";
                $response['error'] = false;
            } catch (Exception $e) {
                $response['msg'] = $e->getMessage();
                $response['error'] = true;
            }
        } else {
            $response['error'] = true;
            $response['msg'] = "Not enough permission or not logged";

        }
        return $this->jsonResponse($response);
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
        $user->setActivo(!is_null($_POST['user_state']));
        $user->setIsSuperuser(!is_null($_POST['superuser']));

        //buscar una forma mas bonita de implementar
        $roles = $_POST['roles'];
        $roles_actuales = ($user->getRol()->map(function($entity) { return $entity->getId();}))->toArray();
        $int_array_roles = array_map(function($value) { return (int)$value; },$roles);
        $roles_a_remover = array_diff($roles_actuales,$int_array_roles);

        if(isset($roles)) {
            foreach ($roles_a_remover as $rol_a_remover){
                $r = ($this->getModel("Rol")->find($rol_a_remover));
                $user->removeRol($r);
            }
            foreach ($roles as $role) {
                $rol = ($this->getModel('Rol')->findOneBy(['id' => $role]));
                if(!$user->hasRol($rol)) {
                    $user->addRol($rol);

                }
            }
        }
        $permisos = $_POST['permisos'];
        $permisos_actuales = ($user->getPermiso()->map(function($entity) { return $entity->getId();}))->toArray();
        $intArrayPermisos = array_map(function($value) { return (int)$value; },$permisos);
        $permisos_a_remover = array_diff($permisos_actuales,$intArrayPermisos);

        if(isset($permisos)) {
            foreach ($permisos_a_remover as $permiso_a_remover){
                $p = ($this->getModel("Permiso")->find($permiso_a_remover));
                $user->removePermiso($p);
            }
            foreach ($permisos as $permiso) {
                $perm = ($this->getModel('Permiso')->find($permiso));
                if(!$user->hasPermiso($perm)) {
                    $user->addPermiso($perm);
                }
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

    public function configuracionView() {
        return $this->twig_render("/modules/usuarios/configuracion.html", []);
    }

    public function changeOwnPassword() {
        // este metodo es exclusivamente para el uso del usuarioo
        // solo cambia la clave al usuario autenticado
        $data = [];
        $password_new = $_POST['password_new'];
        $password_old = $_POST['password_old'];
        try {
            $user = $this->user();

            // Check if the actual password match
            if($user->getPassword() != $password_old) {
                return $this->twig_render(
                    "/modules/usuarios/configuracion.html",
                    [
                        'error' => true,
                        'msg' => "Constraseña actual incorrecta",
                    ]
                );
            }

            $user->setPassword($password_new);
            $this->entityManager()->persist($user);
            $this->entityManager()->flush();
        } catch (Exception $e) {
            error_log('exploto mal' . $e);
        }
        return $this->redirect('/auth/logout');
    }
}
