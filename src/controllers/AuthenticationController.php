<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class AuthenticationController extends Controller {

    public function login(){
        /**@api
         * code : null = not set
         * code : 0 = auth OK
         * code : 1 = username doesn't exists
         * code : 2 = password fail
         */

        $response = [
            'code' => null,
            'msg' => null,
        ];

        try {
            $usr = $_POST['username'];
            $psw = $_POST['password'];
        }catch (Exception $e){
            $a = $e;
        }

        $user = $this->getModel('Usuario')->findOneBy(
            array(
                'email'=> $usr,
                'password'=> $psw,
                'activo'=>true
            )
        );

        if (isset($user)) {
            $this->session->createAuthenticatedSession($user->getId(),[]);
            $this->redirect('/');

        } else {
             return $this->twig_render('/login.html',['error'=>true]);
        }

        return $this->jsonResponse($response);
    }

   public function logout(){
        $this->session->destroyAuthenticatedSession();
        $this->redirect('/');
   }


}