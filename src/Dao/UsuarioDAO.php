<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/10/18
 * Time: 22:37
 */

require_once(CODE_ROOT . "/Dao/DAO.php");

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

    function findUser($usernameOrEmail, $password){

        $user = $this->getModel()->findOneBy(
            [
                'email' => $usernameOrEmail,
                'password' => $password,
                'activo' => true,
                'eliminado' => 0,
            ]
        );

        // if the user didn't founded, search with the username now

        if(!isset($user)) {
            $user = $this->getModel()->findOneBy(
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

}
