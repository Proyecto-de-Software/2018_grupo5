<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class AuthenticationController extends Controller {

    public static function login(){
        $instance =  new self();
        try {
            $usr = $_POST['username'];
            $psw = $_POST['password'];
        }catch (Exception $e){
            echo $e;
        }


        $user = $instance->getModel('Usuario')->findOneBy(
            array(
                'email'=> 'admin@admin',
                'password'=>'sinhash',
                'activo'=>true
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