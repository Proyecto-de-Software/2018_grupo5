<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Usuario.php");
require_once(CODE_ROOT . "/models/Rol.php");
require_once(CODE_ROOT . "/models/Permiso.php");

use controllers\Controller;

class UsuarioController extends Controller {
    //aca habria un include del model usuario

    function index() {
        $this->assertPermission();
        $instance = new UsuarioController();
        $users = $instance->getModel('Usuario')->findBy(['eliminado' => 0]);
        $context['usuarios'] = $users;
        return $instance->twig_render("modules/usuarios/index.html", $context);
    }

    function createView() {
        $this->assertPermission();

        $roles = $this->getModel('Rol')->findAll();
        $permissions = $this->getModel('Permiso')->findAll();
        $context = [
            "roles" => $roles,
            "permisos" => $permissions,
        ];
        return $this->twig_render('modules/usuarios/formUsuario.html', $context);
    }

    public function create() {
        $this->assertPermission();

        $response = [
            'error' => true,
            'msg' => null,
        ];

        $user = new Usuario();
        $this->setUserData($user);
        $user->setCreatedAt(new DateTime('now'));
        try {
            $this->entityManager()->persist($user);
            $this->entityManager()->flush();
            $response['error'] = false;
            $response['msg'] = "Usuario creado con exito";

        } catch (Exception $e) {
            $response = [
                "msg" => "Error al a crear el usuario" . $e->getMessage(),
            ];
        }
        return $this->jsonResponse($response);
    }

    public function delete($param) {

        $userId = $param['id'];
        $response = [
            'error' => true,
            'msg' => null,
        ];
        $this->assertPermission();
        try {
            $user = $this->getModel('Usuario')->findOneBy(['id' => $userId]);
            if ($this->user()->getId() == $param['id']) {
                $response['msg'] = 'No puedes eliminar tu propio usuario';
                return $this->jsonResponse(($response));
            };
            $user->setEliminado('1');
            $this->entityManager()->persist($user);
            $this->entityManager()->flush();
            $response['msg'] = "usuario eliminado con exito";
            $response['error'] = false;
        } catch (Exception $e) {
            $response['msg'] = "Error al eliminar el usuario" . $e->getMessage();
        }
        return $this->jsonResponse($response);
    }

    static function update_view($param) {
        $usuarioId = $param['id'];
        $instance = new UsuarioController();
        $instance->assertPermission();

        $user = $instance->getModel('Usuario')->findOneBy(['id' => $usuarioId]);
        if ( $user==null || $user->getEliminado() == '1')   $user=null;
        $roles = $instance->getModel('Rol')->findAll();
        $permissions = $instance->getModel('Permiso')->findAll();
        $context = [
            "roles" => $roles,
            "permisos" => $permissions,
            "usuario" => $user,
        ];
        return $instance->twig_render("modules/usuarios/formUsuario.html", $context);
    }

    /**
     * @param $user Usuario
     * @return mixed
     */
    private function setUserData($user) {

        $user->setFirstName($_POST['first_name']);
        $user->setLastName($_POST['last_name']);
        $user->setEmail($_POST['email']);
        $user->setUsername($_POST['username']);
        $user->setActivo(!is_null($_POST['user_state']));

        /**
         * @doc: Solos el super usuario del sistema puede setear este valor
         */
        if($this->userIsSuperUser()){
            @$valueSuperUser = !is_null($_POST['superuser']);
        } else {
            $valueSuperUser = false;
        }
        @$user->setIsSuperuser($valueSuperUser);

        if(isset($_POST['password'])) $user->setPassword($_POST['password']);

        if(isset($_POST['rolesList'])) {
            $roles = $_POST['rolesList'];
            $roles = $this->getModel("Rol")->findBy(['id' => $roles]);
        } else {
            $roles = [];
        }
        $user->leaveOnlyThisRoles($roles);

        if(isset($_POST['permissionList'])) {
            $permisos = $_POST['permissionList'];
            $permisos = $this->getModel("Permiso")->findBy(['id' => $permisos]);
        } else {
            $permisos = [];
        }
        $user->leaveOnlyThisPermissions($permisos);

        $user->setUpdatedAt(new DateTime('now'));
        return $user;
    }

    public function update() {
        $this->assertPermission();

        $data['error'] = false;
        $data['msg'] = 'nada';
        $userId = $_POST['id']; //viene por input hidden
        $user = $this->getModel('Usuario')->findOneBy(['id' => $userId]);
        try {
            $this->entityManager()->merge($this->setUserData($user));
            $this->entityManager()->flush();
            $data['msg'] = "Datos actualizados con exito";
        } catch (Exception $e) {
            $data = [
                "msg" => "Error al actualizar los datos del usuario" . $e->getMessage(),
                "error" => true,
            ];
        }
        return $this->jsonResponse($data);
    }

    public function configuracionView() {
        $this->assertPermission();

        return $this->twig_render("/modules/usuarios/configuracion.html", []);
    }

    public function changeOwnPassword() {
        // este metodo es exclusivamente para el uso del usuarioo
        // solo cambia la clave al usuario autenticado
        $this->assertPermission();
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

    public function changePassword($data) {
        $this->assertInMaintenance();
        $this->assertPermission();
        $response = [
            'error' => true,
            'msg' => null,
        ];
        try {
            if(!$this->user()->getIsSuperuser()) {
                throw new Exception("Solo los usuarios administradores pueden realizar estos cambios");
            }
            $this->validateParams(['password'], true);
            $userId = $data['id'];
            $user = $this->getModel('Usuario')->findOneBy(['id' => $userId]);
            $user->setPassword($_POST['password']);
            $this->entityManager()->persist($user);
            $this->entityManager()->flush();
            $response['error'] = false;
            $response['msg'] = "clave actualizada";
        } catch (Exception $e) {
            $response['msg'] = 'error al cambiar la clave: ' . $e->getMessage();
        }
        return $this->jsonResponse($response);
    }


}
