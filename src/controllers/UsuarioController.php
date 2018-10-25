<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Usuario.php");
require_once(CODE_ROOT . "/models/Rol.php");
require_once(CODE_ROOT . "/models/Permiso.php");

use controllers\Controller;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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
        isset($_POST['user_state']) ? $_POST['user_state'] = 1 : $_POST['user_state'] = 0;
        $user = new Usuario();

        $this->setUserData($user);
        $user->setCreatedAt(new DateTime('now'));
        try {
            $this->entityManager()->persist($user);
            $this->entityManager()->flush();
            $response['error'] = false;
            $response['msg'] = "Usuario creado con exito. <a href='/modulo/usuarios/modificar/" . $user->getId() . "'>Ver usuario</a>";

        } catch (UniqueConstraintViolationException $e) {
            $response["msg"] = "El usuario ya existe!";
        } catch (Exception $e) {
            $response["msg"] = "Error al a crear el usuario" . $e->getMessage();
        }
        return $this->jsonResponse($response);
    }

    public function destroy($param) {

        $userId = $param['id'];
        $response = [
            'error' => true,
            'msg' => null,
        ];
        $this->assertPermission();
        try {


            /** @var Usuario $user */
            $user = $this->getModel('Usuario')->findOneBy(['id' => $userId]);

            if($this->user()->getId() == $param['id']) {
                $response['msg'] = 'No puedes eliminar tu propio usuario';
                return $this->jsonResponse(($response));
            };

            if($user->getIsSuperuser() && !$this->user()->getIsSuperuser()) {
                $response['msg'] = 'No puedes eliminar a un superUsuario';
                return $this->jsonResponse(($response));
            }

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

    public function updateView($param) {
        $this->assertPermission();

        $user = $this->getModel('Usuario')->findOneBy(
            [
                'id' => $param['id'],
                'eliminado' => '0',
            ]);

        $roles = $this->getModel('Rol')->findAll();
        $permissions = $this->getModel('Permiso')->findAll();
        $context = [
            "roles" => $roles,
            "permisos" => $permissions,
            "usuario" => $user,
        ];
        return $this->twig_render("modules/usuarios/formUsuario.html", $context);
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
        $user->setActivo(!($_POST['user_state'] === 0));

        /**
         * @doc: Solos el super usuario del sistema puede setear este valor
         */
        if($this->userIsSuperUser()) {
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
        $this->updateRoles($user, $roles);

        if(isset($_POST['permissionList'])) {
            $permisos = $_POST['permissionList'];
            $permisos = $this->getModel("Permiso")->findBy(['id' => $permisos]);
        } else {
            $permisos = [];
        }
        $this->updatePermisos($user, $permisos);

        $user->setUpdatedAt(new DateTime('now'));
        return $user;
    }

    public function updatePermisos(&$user, $permisos) {
        if($this->userHasPermissionForCurrentMethod()) {
            $user->leaveOnlyThisPermissions($permisos);
        }
    }

    public function updateRoles(&$user, $roles) {
        if($this->userHasPermissionForCurrentMethod()) {
            $user->leaveOnlyThisRoles($roles);
        }
    }

    public function update() {
        $this->assertPermission();

        $data = [
            'error' => true,
            'msg' => null,
        ];
        $_POST['user_state'] = isset($_POST['user_state']) ? 1 : 0;

        /** @var Usuario $user */
        $user = $this->getModel('Usuario')->findOneBy(['id' => $_POST['id']]);

        if($user && $user->getIsSuperuser() && !$this->user()->getIsSuperuser()) {
            // the current user couldn't modify a superUser, so keep forward without changes
            $data['msg'] = "No puedes modificar a un super usuario! ";
            return $this->jsonResponse($data);
        }
        try {
            $this->entityManager()->merge($this->setUserData($user));
            $this->entityManager()->flush();
            $data['msg'] = "Datos actualizados con exito";
            $data['error'] = false;
        } catch (Exception $e) {
            $data["msg"] = "Error al actualizar los datos del usuario" . $e->getMessage();
        }
        return $this->jsonResponse($data);
    }

    public function configuracionView() {

        $this->assertPermission();

        return $this->twig_render("/modules/usuarios/configuracion.html", []);
    }

    public function changeOwnPassword() {
        /**
         * este metodo es exclusivamente para el uso del usuario
         * solo cambia la clave al usuario autenticado
         * NO CHEQUEAR POR PERMISOS, la logica de este metodo no permite cambiar a otro usuario
         */
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
        /** Si es un cambio para su propio usuario, le damos pase libre */

        $response = [
            'error' => true,
            'msg' => null,
        ];
        try {
            $userId = $data['id'];

            $this->validateParams(['password'], true);

            if($this->user()->getId() != $userId && !$this->userHasPermissionForCurrentMethod()) {
                throw new Exception("No tiene permisos para realizar este cambio");
            }

            $user = $this->getModel('Usuario')->findOneBy(['id' => $userId]);

            if($user && $user->getIsSuperuser() && !$this->user()->getIsSuperuser()) {
                // the current user couldn't modify a superUser, so keep forward without changes
                $data['msg'] = "No puedes modificar a un super usuario! ";
                return $this->jsonResponse($data);
            }

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
