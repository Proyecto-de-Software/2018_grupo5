<?php
require_once("Controller.php");

use controllers\Controller;

class AuthenticationController extends Controller {

    public function login() {
        $usr = $_POST['username'];
        $psw = $_POST['password'];

        $userDao = new UsuarioDAO();
        $user = $userDao->login($usr, $psw);

        if(isset($user)) {
            $this->session->createAuthenticatedSession($user->getId(), []);
            if($this->userHasPermission("configuracion_index_view")) {
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
