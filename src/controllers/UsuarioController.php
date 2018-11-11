<?php


require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UsuarioController extends Controller {

    /** @var RolDAO $rolDao */
    private $rolDao;
    /** @var PermisoDAO $permisoDao */
    private $permisoDao;
    /** @var UsuarioDAO $usuarioDao */
    private $usuarioDao;

    function __construct() {
        parent::__construct();
        $this->rolDao = new RolDAO();
        $this->permisoDao = new PermisoDAO();
        $this->usuarioDao = new UsuarioDAO();
    }

    function index() {
        $this->assertPermission();
        $context['usuarios'] = $this->usuarioDao->getActiveUsers();
        return $this->twig_render("modules/usuarios/index.html", $context);
    }

    function createView() {
        $this->assertPermission();
        $context = [
            "roles" => $this->rolDao->getAll(),
            "permisos" => $this->permisoDao->getAll(),
        ];
        return $this->twig_render('modules/usuarios/formUsuario.html', $context);
    }

    public function create() {
        $this->assertPermission();

        $response = [
            'error' => true,
            'msg' => null,
        ];

        $_POST['user_state'] = isset($_POST['user_state']) ? 1 : 0;

        $user = new Usuario();
        $this->setUserDataFromRequest($user);
        $user->setCreatedAt(new DateTime('now'));
        try {
            $this->usuarioDao->persist($user);
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
            $user = $this->usuarioDao->getById($userId);

            if($this->user()->getId() == $param['id']) {
                $response['msg'] = 'No puedes eliminar tu propio usuario';
                return $this->jsonResponse(($response));
            };

            if($user->getIsSuperuser() && !$this->user()->getIsSuperuser()) {
                $response['msg'] = 'No puedes eliminar a un superUsuario';
                return $this->jsonResponse(($response));
            }

            $user->setEliminado('1');
            $this->usuarioDao->persist($user);

            $response['msg'] = "usuario eliminado con exito";
            $response['error'] = false;
        } catch (Exception $e) {
            $response['msg'] = "Error al eliminar el usuario" . $e->getMessage();
        }
        return $this->jsonResponse($response);
    }

    public function updateView($param) {
        $this->assertPermission();

        $context = [
            "roles" => $this->rolDao->getAll(),
            "permisos" => $this->permisoDao->getAll(),
            "usuario" => $this->usuarioDao->getActiveUserById($param['id']),
        ];

        return $this->twig_render("modules/usuarios/formUsuario.html", $context);
    }

    /**
     * @param $user Usuario
     * @return mixed
     */
    private function setUserDataFromRequest($user) {

        $user->setFirstName($_POST['first_name']);
        $user->setLastName($_POST['last_name']);
        $user->setEmail($_POST['email']);
        $user->setUsername($_POST['username']);
        $user->setActivo(!($_POST['user_state'] === 0));


        /**
         * @doc: Solos el super usuario del sistema puede setear este valor,
         *        un usuario root, no puede autosacarse el privilegio de root
         */

        if ($this->user()->getId() != $user->getId()) {
            if($this->userIsSuperUser()) {
                @$valueSuperUser = !is_null($_POST['superuser']);
            } else {
                $valueSuperUser = false;
            }
            @$user->setIsSuperuser($valueSuperUser);
        }


        if(isset($_POST['password'])) {
            $user->setPassword($_POST['password']);
        }

        if(isset($_POST['rolesList'])) {
            $roles = $_POST['rolesList'];
            $roles = $this->rolDao->findByMultipleId($roles);
        } else {
            $roles = [];
        }
        $user->leaveOnlyThisRoles($roles);

        if(isset($_POST['permissionList'])) {
            $permissions = $this->permisoDao->findByMultipleId($_POST['permissionList']);
        } else {
            $permissions = [];
        }
        $user->leaveOnlyThisPermissions($permissions);
        $user->setUpdatedAt(new DateTime('now'));
        return $user;
    }


    public function update() {
        $this->assertPermission();

        $data = [
            'error' => true,
            'msg' => null,
        ];
        $_POST['user_state'] = isset($_POST['user_state']) ? 1 : 0;

        /** @var Usuario $user */

        $user = $this->usuarioDao->getById($_POST['id']);

        if($user && $user->getIsSuperuser() && !$this->user()->getIsSuperuser()) {
            // the current user couldn't modify a superUser, so keep forward without changes
            $data['msg'] = "No puedes modificar a un super usuario! ";
            return $this->jsonResponse($data);
        }
        try {
            $user = $this->setUserDataFromRequest($user);
            $this->usuarioDao->update($user);
            $data['msg'] = "Datos actualizados con exito";
            $data['error'] = false;
        } catch (Exception $e) {
            $data["msg"] = "Usuario o mail en uso.";
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
            $this->usuarioDao->persist($user);
        } catch (Exception $e) {
            error_log('exploto mal' . $e);
        }
        $this->redirect('/auth/logout');
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

            $user = $this->usuarioDao->getById($userId);

            if($user && $user->getIsSuperuser() && !$this->user()->getIsSuperuser()) {
                // the current user couldn't modify a superUser, so keep forward without changes
                $data['msg'] = "No puedes modificar a un super usuario! ";
                return $this->jsonResponse($data);
            }

            $user->setPassword($_POST['password']);
            $this->usuarioDao->persist($user);
            $response['error'] = false;
            $response['msg'] = "clave actualizada";
        } catch (Exception $e) {
            $response['msg'] = 'error al cambiar la clave: ' . $e->getMessage();
        }
        return $this->jsonResponse($response);
    }


}
