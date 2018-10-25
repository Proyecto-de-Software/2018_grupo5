<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class RolController extends Controller {

    function indexView(...$args) {
        $this->assertPermission();
        $roles = $this->getModel('Rol')->findAll();
        $context = [
            'roles'=> $roles,
        ];
        echo $this->twig_render('/modules/roles/index.html', $context);
    }

    function show($args){
        $this->assertPermission();
        $rol = $this->getModel('Rol')->findOneBy(array('id'=>$args['id']));
        $permisos = $this->getModel('Permiso')->findAll();

        $context = [
            'rol'=> $rol,
            'permisos_disponibles' => $permisos
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
        $permisos = $_POST['permisos-for-rol'];
        $rol_id = $_POST['id'];
        $permisos = $this->getModel("Permiso")->findBy(['id' => $permisos]);
        $rol = $this->getModel("Rol")->findOneBy(['id' => $rol_id]);

        if($rol !== null) {
            $rol->leaveOnlyThisPermissions($permisos);
            $this->entityManager()->merge($rol);
            $this->entityManager()->flush();
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
        $roles = $this->getModel("Rol")->findBy(['id' => $rol_id]);
        $response = [];
        foreach ($roles as $rol){
            $permisos = $rol->getPermiso();
            foreach ($permisos as $permiso){
                array_push($response, $permiso->getId());
            }
        }

        $data = array(
            'permisos' => $response //aut_login auth_logout loc_obtener_por_partido
        );
        return json_encode($data);

    }
}
