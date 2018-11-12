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
        return $this->findBy(['eliminado' => 0]);
    }


    function getActiveUserById($id) {
        return $this->findOneBy([
            'id' => $id,
            'eliminado' => '0',
        ]);
    }

    function findUser($usernameOrEmail, $password) {

        $user = $this->findOneBy(
            [
                'email' => $usernameOrEmail,
                'password' => $password,
                'activo' => true,
                'eliminado' => 0,
            ]
        );

        // if the user didn't founded, search with the username now

        if(!isset($user)) {
            $user = $this->findOneBy(
                [
                    'username' => $usernameOrEmail,
                    'password' => $password,
                    'activo' => true,
                    'eliminado' => 0,
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
            return false;
        }

        /** @var Usuario $user */
        $user = $this->getById($user_id);
        return $user->hasPermission($permission_instance);
    }
}
