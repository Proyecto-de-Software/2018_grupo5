<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class AuthenticationController extends Controller {

    public static function login(){
        $instance =  new self();
        $usr = $_POST['username'];
        $psw =  $_POST['password'];

        $usr = "admin@admin";
        $psw = "sinhash";

        $user = $instance->getModel('Usuario')->findOneBy(
            array(
                'email'=> $usr,
                'password'=>$psw
            )
        );

        if (isset($user)) {
            $instance->session->createAuthenticatedSession($user->getId(),[]);
            return "OK";
        }
        return "No autenticado";

    }

   public static function logout(){
        $instance =  new self();
        $instance->session->destroyAuthenticatedSession();
    }


}