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

    /**
     * @param $user Usuario
     * @return mixed
     */
    private function setUserData($user) {
        $this->assertInMaintenance();
        $this->assertPermission();

        $user->setFirstName($_POST['first_name']);
        $user->setLastName($_POST['last_name']);
        $user->setEmail($_POST['email']);
        $user->setUsername($_POST['username']);
        $user->setActivo(!is_null($_POST['user_state']));
        $user->setIsSuperuser(!is_null($_POST['superuser']));
        if (isset($_POST['password']))  $user->setPassword($_POST['password']);
        $roles = $_POST['rolesList'];
        $roles = $this->getModel("Rol")->findBy(array('id'=>$roles));
        $user->leaveOnlyThisRoles($roles);
        $permisos = $_POST['permissionList'];
        $permisos = $this->getModel("Permiso")->findBy(array('id'=>$permisos));
        $user->leaveOnlyThisPermissions($permisos);
        $user->setUpdatedAt(new DateTime('now'));
        return $user;
    }

    public function update() {
        $data['error'] = false;
        $data['msg'] = 'nada';
        $userId = $_POST['id']; //viene por input hidden
        $user = $this->getModel('Usuario')->findOneBy(['id' => $userId]);
        try {
            $this->entityManager()->merge($this->setUserData($user));
            $this->entityManager()->flush();
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

    function changePassword($data){
        $id = $data['id'];
        return $this->jsonResponse("cambio pass");
    }

}
