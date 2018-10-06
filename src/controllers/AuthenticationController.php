<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class AuthenticationController extends Controller {

    public function login() {
        $usr = $_POST['username'];
        $psw = $_POST['password'];
        $user = $this->getModel('Usuario')->findOneBy(
            [
                'email' => $usr,
                'password' => $psw,
                'activo' => true,
                'eliminado' => 0,
            ]
        );

        // if the user didn't founded, search with the username now
        if(!isset($user)) {
            $user = $this->getModel('Usuario')->findOneBy(
                [
                    'username' => $usr,
                    'password' => $psw,
                    'activo' => true,
                    'eliminado' => 0,
                ]
            );
        }

        if(isset($user)) {
            $this->session->createAuthenticatedSession($user->getId(), []);
            if($user->getIsSuperuser()) {
                $this->redirect('/modulo/configuracion');
            }
            $this->redirect('/');

        } else {
            return $this->twig_render('/login.html', ['error' => true]);
        }
        error_log("algo raro paso en el login");
    }

    public function logout() {
        $this->session->destroyAuthenticatedSession();
        $this->redirect('/');
    }
}
