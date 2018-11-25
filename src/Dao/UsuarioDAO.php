<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/10/18
 * Time: 22:37
 */

class UsuarioDAO extends DAO {

    public $model = "Usuario";

    function getActiveUsers() {
        return $this->findBy([self::ELIMINADO => 0]);
    }

    function getActiveUserById($id) {
        return $this->findOneBy([
            'id' => $id,
            self::ELIMINADO  => '0',
        ]);
    }

    function login($usernameOrEmail, $password) {

        $user = $this->findOneBy(
            [
                'email' => $usernameOrEmail,
                'password' => $password,
                'activo' => true,
                self::ELIMINADO => 0,
            ]
        );

        if(!isset($user)) {
            $user = $this->findOneBy(
                [
                    'username' => $usernameOrEmail,
                    'password' => $password,
                    'activo' => true,
                    self::ELIMINADO => 0,
                ]
            );
        }
        return $user;

    }

    function userHasPermission($user_id, $permission_name) {

        /** @var \Permiso $permission_instance */
        $permisoDao = new PermisoDAO();
        $permission_instance = $permisoDao->getByName($permission_name);

        if(!isset($permission_instance)) {
            error_log("Permission $permission_name not found.");
            return true;
        }

        /** @var Usuario $user */
        $user = $this->getById($user_id);
        return $user->hasPermission($permission_instance);
    }
}
