<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class AuthenticationController extends Controller {

    public function login(){
        $usr = $_POST['username'];
        $psw = $_POST['password'];
        $user = $this->getModel('Usuario')->findOneBy(
            array(
                'email'=> $usr,
                'password'=> $psw,
                'activo'=>true
            )
        );

        // if the user didn't founded, search with the username now
        if (!isset($user)){
            $user = $this->getModel('Usuario')->findOneBy(
                array(
                    'username'=> $usr,
                    'password'=> $psw,
                    'activo'=>true
                )
            );
        }

        if (isset($user)) {
            $this->session->createAuthenticatedSession($user->getId(),[]);
            $this->redirect('/');
        } else {
             return $this->twig_render('/login.html',['error'=>true]);
        }
        error_log("algo raro paso en el login");
    }

   public function logout(){
        $this->session->destroyAuthenticatedSession();
        $this->redirect('/');
   }
}
