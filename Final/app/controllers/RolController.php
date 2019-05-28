<?php
require_once("Controller.php");

use controllers\Controller;

class RolController extends Controller {
    private $rolDao;
    private $permisoDao;

    function __construct() {
        parent::__construct();
        $this->rolDao = new RolDAO();
        $this->permisoDao = new PermisoDAO();
    }

    function indexView(...$args) {
        $this->assertPermission();
        $roles = $this->rolDao->getAll();
        $context = [
            'roles'=> $roles,
        ];
        echo $this->twig_render('/modules/roles/index.html', $context);
    }

    function show($args){
        $this->assertPermission();
        $rol = $this->rolDao->getById($args['id']);

        $context = [
            'rol'=> $rol,
            'permisos_disponibles' => $this->permisoDao->getAll(),
        ];
        echo $this->twig_render('/modules/roles/show.html', $context);
    }

    function update() {
        $this->assertPermission();
        $response = [
            'error' => true,
            'msg' => null,
            'code' => null
        ];

        $permisos = $this->permisoDao->findByMultipleId($_POST['permisos-for-rol']);
        $rol =  $this->rolDao->getById( $_POST['id']);
        if($rol !== null) {
            $rol->leaveOnlyThisPermissions($permisos);
            $this->rolDao->update($rol);
            $response['code'] = 0;
            $response['error'] = false;
            $response['msg'] = 'Modificado correctamente';
        } else {
            $response['code'] = 1;
            $response['msg'] = 'El rol no existe';
        }
        return $this->jsonResponse($response);
    }

    function getPermissionsForRole($param) {

        $rol_id = $_POST['rolesList'] ?? null ;

        $data = [];
        $roles = $this->rolDao->findByMultipleId($rol_id);
        foreach ($roles as $rol){
            foreach ($rol->getPermiso() as $permiso){
                $data[] =  $permiso->getId();
            }
        }

        $response = [
            'permisos' => $data
        ];

        return $this->jsonResponse($response);

    }
}
