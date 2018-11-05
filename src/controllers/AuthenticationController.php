<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
require_once (CODE_ROOT ."/Dao/UsuarioDAO.php");

use controllers\Controller;

class AuthenticationController extends Controller {

    public function login() {
        $usr = $_POST['username'];
        $psw = $_POST['password'];

        $userDao = UsuarioDAO::getInstance();
        $user = $userDao->findUser($usr, $psw);

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
