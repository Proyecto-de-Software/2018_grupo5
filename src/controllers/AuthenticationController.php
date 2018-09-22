<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class AuthenticationController extends Controller {

    public static function login(){
        $instance =  new self();
        $usr = $_GET['username'];
        $psw =  $_GET['password'];


        $repo = $instance->getModel('Usuario');
        var_dump($repo->findAll());
        $instance->session->createAuthenticatedSession('2',[]);
    }

   public static function logout(){
        $instance =  new self();
        $instance->session->destroyAuthenticatedSession();
    }


}